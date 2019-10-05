<?php
    session_start();
    require_once("./includes/connection.php");

// For creating logs of each action.
function logAttendance($info){
    global $conn;
    $query = "INSERT INTO log VALUES(null,'".date('d/m/Y')."','".date('h:i:s A')."','".$_SESSION['Username']."','".addslashes($info)."','".$_SESSION['department']."')";
    $conn->query($query);
}

// To find the section of a student from his roll number 
function getSection($rollNumber, $department) {
    global $conn;
    $query = "SELECT sec FROM departments WHERE initials='" . $department . "'";
    $sections = explode(",", $conn->query($query)->fetch_array()[0]);
    if(intval(substr($rollNumber, -2)) <= 50 && intval(substr($rollNumber, -2)) >= 1) {
        return $sections[0];
    } else {
        return $sections[1];
    }
}

// For verification of login
if (isset($_POST['loginSubmit'])) {
    $query = "SELECT * FROM `teacher_list` WHERE `teacher_username`='" . $_POST['Username'] . "' AND `password`='" . $_POST['Password'] . "'";
    $result = $conn->query($query);
    $data = $result->fetch_array();
    if ($result->num_rows > 0) {
        $_SESSION['Username'] = $data['teacher_username'];
        $_SESSION['userActive'] = true;
        $_SESSION['userType'] = $data['designation'];
        $_SESSION['department'] = $data['department'];
        echo "dashboard.php";
    }
    else {
        $query = "SELECT * FROM `roll_series` WHERE `roll_no`='" . $_POST['Username']. "' AND `password`='" . $_POST['Password'] . "'";
        $result = $conn->query($query);
        if(is_object($result) && $result->num_rows > 0) {
            $_SESSION['Username'] = $_POST['Username'];
            $_SESSION['userActive'] = true;
            $_SESSION['userType'] = "Student";
            echo "student-class.php";
        } else {
            echo "false";
        }
    }
}

// To add the subject in 'subjects' table
if(isset($_POST['addSubject'])) {
    $query = "INSERT INTO subjects VALUES(null, '".$_POST['subjectName']."', '".$_POST['subjectCode']."', '".$_POST['subjectInitials']."', '".$_POST['teacherCode']."', '".$_POST['semester']."', '".$_POST['subjectType']."', '".$_POST['department']."', '')";
    if($conn->query($query)) {
        echo "true";
        logAttendance("'".$_POST['subjectInitials']."' subject added! With Subject Code ".$_POST['subjectCode']);
    }
}

// To fetch the list of teachers in <select> list
if(isset($_POST['fetchTeachers'])) {
    if(intval($_POST['semester']) <= 2)
        $query = "SELECT * FROM teacher_list WHERE department='".$_POST['department']."' OR department='as'";
    else
        $query = "SELECT * FROM teacher_list WHERE department='".$_POST['department']."'";
    $result = $conn->query($query);
    while($data = $result->fetch_array()) {
    echo "<option value='" . $data[1] . "' data-initials='" . $data[3] . "'> " . $data[2] . " </option>";
    }
}

// To upload the attendance of a lecture
if(isset($_POST['sendAttendance'])) {
    $lectures = explode(", ", $_POST['lectures']);
    if($lectures[count($lectures) - 1] == 7) // if lecture is 7th then add 8th lecture also
        $lectures[count($lectures)] = 8;
    $date = date("Y-m-d");
    $sectionFirst = $_POST['sectionFirst']; // first section attendance
    $sectionSecond = $_POST['sectionSecond']; // second section attendance
    $sections = explode(",", $_POST['sections']); 
    $fullClass = $sectionFirst . "," . $sectionSecond; // combine the attendance of both sections
    
    // To increment the total lectures of subject
    foreach($lectures as $key => $value) {
        if($value != "") {
            $subjectCode = ($value < 8) ? $_POST['subjectCode'] : 9999; // if lecture is 8th then subject is SCA (9999 code)
            $query = "SELECT total_lectures FROM subjects WHERE subject_code='" . $subjectCode . "' AND department='" . $_POST['department'] . "' AND semester='" . $_POST['semester'] . "'";
            $subjectLectures = (object)(json_decode($conn->query($query)->fetch_array()[0])); // fetch total lectures of the subject
            foreach($sections as $section) { // increment the total lectures of each section(s)
                if(isset($subjectLectures->$section)) // if an entry exists of current section, then increment it
                    $subjectLectures->$section++;
                else $subjectLectures->$section = 1; // else set total lectures of current section as 1
            }
            $subjectLectures = addslashes(json_encode($subjectLectures));
            $query = "UPDATE subjects SET total_lectures='" . $subjectLectures . "' WHERE subject_code='" . $subjectCode . "' AND department='" . $_POST['department'] . "' AND semester='" . $_POST['semester'] . "'";
            $conn->query($query); // after incrementing, update the subject's total lectures
        }
    }

    // To increment the lecture of each present student
    $query = "SELECT roll_no FROM roll_series WHERE department='" . $_POST['department'] . "' AND semester='" . $_POST['semester'] . "' AND status='1' ORDER BY roll_no"; 
    $result = $conn->query($query); // fetch the list of all students in class
    while($row = $result->fetch_array()) { // iterate all the roll numbers
        $student = $row[0];
        $sec = getSection($student, $_POST['department']);
        if($student != "") {
            $query = "SELECT attendance FROM roll_series WHERE roll_no='" . $student . "'";
            $attendance = (object)(json_decode($conn->query($query)->fetch_array()[0])); // fetch the attendance of current roll number 
            if(array_search($student, explode(",", $fullClass)) !== false) { // if the roll number was present in class
                if($lectures[count($lectures) - 1] == 8)
                        $countOfLectures = count($lectures) - 1;
                    else
                        $countOfLectures = count($lectures);
                if(!isset($attendance->{$_POST['subjectCode']})) // if entry of current subject doesn't exist,
                    $attendance->{$_POST['subjectCode']} = 0; // then start it from 0
                if(strlen($_POST['lectures']) == 1) // increment the lectures count of subject by 1 or by number of lectures taken
                    $attendance->{$_POST['subjectCode']}++;
                else
                    $attendance->{$_POST['subjectCode']} += $countOfLectures;
                if($lectures[count($lectures) - 1] == 8) { // if lecture is 8th then also update the entry of SCA subject
                    if(isset($attendance->{'9999'})) { // 9999 is SCA subject code
                        $attendance->{'9999'}++;
                    } else {
                        $attendance->{'9999'} = 1;
                    }
                }
                $lectureCount = 0; // initially lecture count is 0
                foreach($attendance as $key => $value) { // iterate each subject to sum the lectures of each count
                    if($key != "lectureCount" && $key != "totalLectures")
                    $lectureCount += intval($value); // counting the total lectures attended
                }
                $attendance->lectureCount = $lectureCount;
            } else { // means the student was absent in class
                if(!isset($attendance->lectureCount)) // if lecture count entry doesn't exist then,
                    $attendance->lectureCount = 0; // start it from 0
                if($lectures[count($lectures) - 1] == 8) { // if lecture is 8th then and SCA subject entry doesn't exist
                    if(!isset($attendance->{'9999'})) { // then set it to 0
                        $attendance->{'9999'} = 0;
                    }
                }
            }
            if((strlen($_POST['sections']) != 1) || ((strlen($_POST['sections']) == 1) && ($sec == $_POST['sections']))) {
                // echo $sec . "\n" . $_POST['sections'] . "\n\n\n";
                if(isset($attendance->totalLectures)) { // if total lectures entry exists in student attendance then increment it,
                    if(strlen($_POST['lectures']) == 1) // by 1 or by the number of lectures taken
                        $attendance->totalLectures++;
                    else
                        $attendance->totalLectures += count($lectures);
                }
                else { // else start total lectures from 1 or by number of lectures taken
                    if(strlen($_POST['lectures']) == 1)
                        $attendance->totalLectures = 1;
                    else
                        $attendance->totalLectures = count($lectures);
                }
            }
            $attendance = addslashes(json_encode($attendance));
            $query = "UPDATE roll_series SET attendance='" . $attendance . "' WHERE roll_no='" . $student . "'";
            $conn->query($query); // finally update the attendance of the student (here, only count is updated)
        }
    }

    // Here the attendance is actually filled :=

    // For a single section (means Practical Lab)
   if(count($sections) === 1) {
        $data = ($sectionFirst != "") ? $sectionFirst : $sectionSecond; 
        $query = "SELECT * FROM ".$_POST['department']." WHERE date='".$date."' AND section='".$_POST['sections']."' AND semester='".$_POST['semester']."'";

        // To check if an entry already exists
        if($conn->query($query)->num_rows === 0) { // means no entry exists for current date
            $query = "INSERT INTO ".$_POST['department']." VALUES(null, '$date','".$_POST['sections']."', ";
            for($i = 1; $i <= 8; $i++) {
                if(count($lectures) === 1) {
                    if($i == $lectures[0]) {
                        $query .= "'".$data."', ";
                    } else {
                        $query .= "'NA', ";
                    }
                } else {
                    if($i >= $lectures[0] && $i <= $lectures[count($lectures) - 1]) {
                        $query .= "'".$data."', ";
                    } else {
                        $query .= "'NA', ";
                    }
                }
            }
            $query .= "'".$_POST['semester']."')";
        } else { // means an entry already exists, So update the entry
            $query = "UPDATE ".$_POST['department']." SET ";
            foreach($lectures as $value) {
                $query .= "lecture_" . $value . "='" . $data . "', ";
            }
            $query = substr($query, 0, -2) . " WHERE date='" . $date . "' AND section='".$_POST['sections']."' AND semester='".$_POST['semester']."'";
        }
    $conn->query($query);
    logAttendance("Attendance Entered of lecture:".$_POST['lectures']." of semester:".$_POST['semester']."", $_POST['department']);
    }
    
    // For both sections (means lecture)
    else {
        $query = "SELECT * FROM ".$_POST['department']." WHERE date='".$date."' AND semester='".$_POST['semester']."'";
        if($conn->query($query)->num_rows === 0) { // means no entry exists for current date
            
            // For 1st Section
            $query = "INSERT INTO ".$_POST['department']." VALUES(null, '$date','".$sections[0]."', ";
            for($i = 1; $i <= 8; $i++) {
                if($i == $lectures[0]) {
                    $query .= "'".$sectionFirst."', ";
                } else {
                    $query .= "'NA', ";
                }
            }
            $query .= "'".$_POST['semester']."')";
            $conn->query($query);

            // For 2nd Section
            $query = "INSERT INTO ".$_POST['department']." VALUES(null, '$date','".$sections[1]."', ";
            for($i = 1; $i <= 8; $i++) {
                if($i == $lectures[0]) {
                    $query .= "'".$sectionSecond."', ";
                } else {
                    $query .= "'NA', ";
                }
            }
            $query .= "'".$_POST['semester']."')";
            $conn->query($query);
            logAttendance("Attendance Entered of lecture:".$_POST['lectures']." of semester:".$_POST['semester']."", $_POST['department']);


        } else if ($conn->query($query)->num_rows === 1){
            $sec = $conn->query($query)->fetch_array()['section'];
            $secondSection = ($sec == $sections[0]) ? $sections[1] : $sections[0];
            $query = "INSERT INTO ".$_POST['department']." VALUES(null, '$date','".$secondSection."', ";
            for($i = 1; $i <= 8; $i++) {
                if($i == $lectures[0]) {
                    $data = ($sec != $sections[0]) ? $sectionFirst : $sectionSecond;
                    $query .= "'".$data."', ";
                } else {
                    $query .= "'NA', ";
                }
            }
            $query .= "'".$_POST['semester']."')";
            $conn->query($query);
            $query = "UPDATE ".$_POST['department']." SET ";
            foreach($lectures as $value) {
                $data = ($sec == $sections[0]) ? $sectionFirst : $sectionSecond;
                $query .= "lecture_" . $value . "='" . $data . "', ";
            }
            $query = substr($query, 0, -2) . " WHERE date='" . $date . "' AND section='".$sec."' AND semester='".$_POST['semester']."'";
            $conn->query($query);
            logAttendance("Attendance Entered of lecture:".$_POST['lectures']." of semester:".$_POST['semester']."", $_POST['department']);

        } else { // means an entry already exists, So update the entry

            // For 1st Section
            $query = "UPDATE ".$_POST['department']." SET ";
            foreach($lectures as $value) {
                $query .= "lecture_" . $value . "='" . $sectionFirst . "', ";
            }
            $query = substr($query, 0, -2) . " WHERE date='" . $date . "' AND section='".$sections[0]."' AND semester='".$_POST['semester']."'";
            $conn->query($query);

            // For 2nd Section
            $query = "UPDATE ".$_POST['department']." SET ";
            foreach($lectures as $value) {
                $query .= "lecture_" . $value . "='" . $sectionSecond . "', ";
            }
            $query = substr($query, 0, -2) . " WHERE date='" . $date . "' AND section='".$sections[1]."' AND semester='".$_POST['semester']."'";
            $conn->query($query);
            logAttendance("Attendance Entered of lecture:".$_POST['lectures']." of semester:".$_POST['semester']."", $_POST['department']);
        }
    }
}

// To fetch the roll series of a department
if(isset($_POST['fetchRollSeries'])) {
    $query = "SELECT * FROM roll_series WHERE semester='" . $_POST['semester'] . "' AND department='". $_POST['department'] ."'";
    $result = $conn->query($query);
    $rollNumbers = array();
    $counter = 0;
    while($data = $result->fetch_array()) {
        $rollNumbers[$counter] = array("rollNumber" => $data['roll_no'], "status" => $data['status']);
        $counter++;
    }
    echo json_encode($rollNumbers);
}

// To detain the students
if(isset($_POST['detainStudent'])) {
    foreach($_POST as $key => $value) {
        if($key !== "detainStudent") {
        $query = "UPDATE roll_series SET status=0 WHERE roll_no='" . $value . "'";
        $conn->query($query);
        logAttendance("Student Detained ".$value);
        }
    }
}

// To retain the students
if(isset($_POST['retainStudent'])) {
    foreach($_POST as $key => $value) {
        if($key !== "retainStudent") {
        $query = "UPDATE roll_series SET status=1 WHERE roll_no='" . $value . "'";
        $conn->query($query);
        logAttendance("Student Retained ".$value);
        }
    }
}

// To show the attendance of a specific date 
if(isset($_POST['showAttendance'])) {
    $rollSeries = array();
    $query = "SELECT sec from departments WHERE initials='" . $_POST['department'] . "'";
    $sections = $conn->query($query)->fetch_array()[0];
    $sections = explode(',', $sections);
    $query = "SELECT * FROM roll_series WHERE status=1 AND department='" . $_POST['department'] . "'";
    $result = $conn->query($query);
    while($row = $result->fetch_array()) {
        if(!isset($rollSeries[$row['semester']])) {
            $rollSeries[$row['semester']] = array();
            $rollSeries[$row['semester']][$sections[0]] = array();
            $rollSeries[$row['semester']][$sections[1]] = array();
        }
        if(substr($row['roll_no'], -2) <= 50)
            $rollSeries[$row['semester']][$sections[0]][$row['roll_no']] = $row['status'];
        else
            $rollSeries[$row['semester']][$sections[1]][$row['roll_no']] = $row['status'];
    }
    $attendance = array();
    $query = "SELECT * FROM " . $_POST['department'] . " WHERE date='" . $_POST['date'] . "'";
    $result = $conn->query($query);
    while($row = $result->fetch_array()) {
        if(!isset($attendance[$row['semester']]))
            $attendance[$row['semester']] = array();
        foreach($rollSeries[$row['semester']][$row['section']] as $key => $value) {
            if(!isset($attendance[$row['semester']][$key]))
                $attendance[$row['semester']][$key] = array();
            $attendance[$row['semester']][$key]['status'] = $value;
            for($i = 1; $i <= 8; $i++) {
                if($row['lecture_'.$i] == "NA")
                    $attendance[$row['semester']][$key][$i] = null;
                else {
                    $wasPresent = (array_search($key, explode(',', $row['lecture_'.$i])) !== FALSE) ? 1 : 0;
                    $attendance[$row['semester']][$key][$i] = $wasPresent;
                }
            }
        }
    }
    echo json_encode($attendance);
}

// To show the timetable in 'timetable.php'
if(isset($_POST['showTimetable'])) {
    $query = "SELECT * FROM subjects WHERE semester='" . $_POST['semester'] . "' AND department='" . $_POST['department'] . "'";
    $result = $conn->query($query);
    $counter = 0;
    $subjects = array();
    while($row = $result->fetch_array(MYSQLI_NUM)) {
        $subjects[$counter] = $row;
        $counter++;
    }
    $timetable = array();
    $query = "SELECT * FROM " . $_POST['department'] . "_timetable WHERE semester='" . $_POST['semester'] . "' AND section='" . $_POST['section'] . "'";
    $result = $conn->query($query);
    $counter = 1;
    while($row = $result->fetch_array()) {
        $timetable[$counter] = array();
        for($i = 1; $i <= 8; $i++) {
            for($j = 0; $j < count($subjects); $j++) {
                if(strcmp(($subjects[$j][2]), explode(":", $row['lecture_' . $i])[0]) == 0) {
                    break;
                }
            }
            $teachers = array();
            foreach(explode(",", explode(":", $row['lecture_' . $i])[1]) as $teacher) {
                $qry = "SELECT * FROM teacher_list WHERE teacher_code='" . $teacher . "'";
                array_push($teachers, $conn->query($qry)->fetch_array()[3]);
            }
            $timetable[$counter][$i] = array(
                "subject" => $subjects[$j][3],
                "subjectCode" => $subjects[$j][2],
                "teachers" => $teachers,
                "teachersCode" => $teacher,
                "type" => $subjects[$j][6]);
        }
        $counter++;
    }
    echo json_encode($timetable);
}

// To get the list of subjects in a department by semester
if(isset($_POST['fetchSubjects'])) {
    $query = "SELECT * FROM subjects WHERE department='" . $_POST['department'] . "' AND semester='" . $_POST['semester'] . "' ORDER BY lecture_type,subject_name";
    $result = $conn->query($query);
    $theorySubjects = array();
    $practicalSubjects = array();
    while($row = $result->fetch_array()) {
        if($row[6] == "Theory") {
            array_push($theorySubjects, array(
                "code" => $row[2],
                "teacherCode" => $row[4],
                "initials" => $row[3],
                "name" => $row[1]
            ));
        } else {
            array_push($practicalSubjects, array(
                "code" => $row[2],
                "teacherCode" => $row[4],
                "initials" => $row[3],
                "name" => $row[1]
            ));
        }
    }
    echo "<optgroup label='Practical'>";
    foreach($practicalSubjects as $row) {
        echo "<option value='" . $row['code'] . "' subject-type='Practical' teacher-code='". $row['teacherCode'] ."'>" . $row['initials'] . "</option>";
    }
    echo "</optgroup> <optgroup label='Theory'>";
    foreach($theorySubjects as $row) {
        echo "<option value='" . $row['code'] . "' subject-type='Theory' teacher-code='". $row['teacherCode'] ."'>" . $row['initials'] . "</option>";
    }
    echo "</optgroup>";
}

// To fetch the lectures of a specific teacher by semester 
if(isset($_POST['fetchSubj'])) {
    $query = "SELECT * FROM subjects WHERE department='" . $_POST['department'] . "' AND semester='" . $_POST['semester'] . "' AND teacher_code='".$_POST['teacher']."' ORDER BY lecture_type,subject_name";
    $result = $conn->query($query);
    $theorySubjects = array();
    $practicalSubjects = array();
    while($row = $result->fetch_array()) {
        if($row[6] == "Theory") {
            array_push($theorySubjects, array(
                "code" => $row[2],
                "teacherCode" => $row[4],
                "initials" => $row[3],
                "name" => $row[1]
            ));
        }
    }
    foreach($theorySubjects as $row) {
        echo "<option subject-type='Theory' teacher-code='". $row['teacherCode'] ."'>" . $row['initials'] . "</option>";
    }
}

// To update the entry in the timeteble
if(isset($_POST['updateEntry'])) {
    $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday');
    if($_POST['subjectType'] == 'Theory'){
    $query = "UPDATE " . $_POST['department'] . "_timetable SET lecture_" . $_POST['lecture']. "='" . $_POST['data'] . "' WHERE day='" . $days[$_POST['day'] - 1] . "' AND semester='" . $_POST['semester'] . "'";
    logAttendance("Timetable Update:".$_POST['subjectInitialForOld']." Set to ".$_POST['subjectInitialForNew']." on lecture:".$_POST['lecture']." of ".$days[$_POST['day'] - 1]." of semester:".$_POST['semester']);
}
    else{
    $query = "UPDATE " . $_POST['department'] . "_timetable SET lecture_" . $_POST['lecture']. "='" . $_POST['data'] . "' WHERE day='" . $days[$_POST['day'] - 1] . "' AND semester='" . $_POST['semester'] . "' AND section='" . $_POST['section'] . "'";
    logAttendance("Timetable Update:".$_POST['subjectInitialForOld']." Set to ".$_POST['subjectInitialForNew']." on lecture:".$_POST['lecture']." of ".$days[$_POST['day'] - 1]." of semester:".$_POST['semester']);
}
    $conn->query($query);
}
// For changing the password of an account
if (isset($_POST['passwordChangeSubmit'])) {
    $query = "SELECT password FROM `teacher_list` WHERE `teacher_username`='" . $_SESSION['Username']."'";
    $result = $conn->query($query);
    $oldpass="";
    while($row = $result->fetch_array()) {
        $oldpass=$row[0];
    }
    if($oldpass==$_POST['currentPassword']){
        $query = "UPDATE `teacher_list` SET password='".$_POST['newPassword']."' WHERE `teacher_username`='" . $_SESSION['Username']."'";
        $result = $conn->query($query);
        logAttendance("Password Changed by ".$_SESSION['Username']);
        if($result){
        echo "true";
        }
    }
    else{
        echo "false";
    }
}

// To add a new teacher in 'teachers_list' table
if(isset($_POST['addTeacher'])){
    $teacherName = $_POST['teacherName'];
    $teacherCode = str_replace(' ', '', strtolower(substr($teacherName,0,5)).rand(1000,9999));
    $teacherInitial = $_POST['teacherInitials'];
    $teacherUsername = $_POST['teacherUsername'];
    $teacherPassword = $_POST['teacherPassword'];
    $teacherDesignation = $_POST['teacherDesignation'];
    $teacherDepartment = $_POST['teacherDepartment'];
    $query= "INSERT INTO teacher_list VALUES('','$teacherCode','$teacherName','$teacherInitial','$teacherUsername','$teacherPassword','$teacherDesignation','$teacherDepartment')";
    $ins= mysqli_query($conn,$query);
    logAttendance("New Teacher Added Named:".$teacherName);
    if($ins){
        echo 'true';
    }
    else{
        echo "false";
    }
}

// To show the teacher timetable
if(isset($_POST['showLecturerTimetable'])) {
    $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday');
    $query = "SELECT * FROM subjects WHERE department='" . $_POST['department'] . "'";
    $result = $conn->query($query);
    $counter = 0;
    $subjects = array();
    while($row = $result->fetch_array(MYSQLI_NUM)) {
        $subjects[$counter] = $row;
        $counter++;
    }
    $timetable = array();
    $theQuery = "SELECT sec FROM departments WHERE initials='" . $_POST['department'] . "'";
    $sections = $conn->query($theQuery)->fetch_array()[0];
    $query = "SELECT * FROM ".$_POST['department']."_timetable";
    $result = $conn->query($query);
    while($row = $result->fetch_array()) {
        $dayNumber = array_search($row['day'], $days) + 1;
        if(!isset($timetable[$dayNumber]))
            $timetable[$dayNumber] = array();
        for($i = 1; $i <= 8; $i++) {
            if(array_search($_POST['teacherCode'], explode(',', explode(":", $row['lecture_' . $i])[1])) === false) {
                continue;
            } else {
                for($j = 0; $j < count($subjects); $j++) {
                    if($subjects[$j][2] == explode(":", $row['lecture_' . $i])[0]) {
                        break;
                    }
                }
                $timetable[$dayNumber][$i] = array(
                    "subject" => $subjects[$j][3],
                    "type" => $subjects[$j][6],
                    "semester" => $row['semester'],
                    "section" => $sections
                );
                if($subjects[$j][6] == "Practical")
                    $timetable[$dayNumber][$i]['section'] = $row['section'];
            }
        }
        $counter++;
    }
    echo json_encode($timetable);
}

// To show the lectures of each subject with percentage
if(isset($_POST['showLecturesCount'])) {
    $query = "SELECT sec FROM departments WHERE initials='" . $_POST['department'] . "'";
    $sections = explode(",", $conn->query($query)->fetch_array()[0]);
    if(intval(substr($_POST['rollNumber'], -2)) <= 50 && intval(substr($_POST['rollNumber'], -2)) >= 1) {
        $section = $sections[0];
    } else {
        $section = $sections[1];
    }
    $query = "SELECT * FROM subjects WHERE department='" . $_POST['department'] . "' AND semester='" . $_POST['semester'] . "' ORDER BY lecture_type, subject_name";
    $result = $conn->query($query);
    $subjects = array();
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
        array_push($subjects, $row);
    }
    $query = "SELECT attendance FROM roll_series WHERE roll_no='" . $_POST['rollNumber'] . "' AND department='" . $_POST['department'] . "' AND semester='" . $_POST['semester'] . "'";
    $attendance = (object)json_decode($conn->query($query)->fetch_array()[0]);
    $lectures = array();
    foreach($subjects as $key => $subject) {
        $totalLectures = (object)(json_decode($subject['total_lectures']));
        $totalLectures = isset($totalLectures->{$section}) ? $totalLectures->{$section} : 0;
        if(isset($attendance->{$subject['subject_code']})) {
            array_push($lectures, array(
                "totalLectures" => $totalLectures,
                "attendedLectures" => $attendance->{$subject['subject_code']},
                "percentage" => round(intval($attendance->{$subject['subject_code']}) / intval($totalLectures) * 100),
                "subjectName" => $subject['subject_name'],
                "subjectInitials" => $subject['subject_initials'],
                "type" => $subject['lecture_type']
            ));
        } else {
            array_push($lectures, array(
                "totalLectures" => $totalLectures,
                "attendedLectures" => 0,
                "percentage" => 0,
                "subjectName" => $subject['subject_name'],
                "subjectInitials" => $subject['subject_initials'],
                "type" => $subject['lecture_type']
            ));
        }
    }
    $lectures['length'] = count($lectures);
    $lectures['totalLectures'] = $attendance->totalLectures;
    $lectures['attendedLectures'] = isset($attendance->lectureCount) ? $attendance->lectureCount : 0;
    $lectures['percentage'] = round(intval($lectures['attendedLectures']) / intval($attendance->totalLectures) * 100);
    echo json_encode($lectures);
}

// To update the attendance of a student
if(isset($_POST['updateAttendance'])) {
    $query = "SELECT lecture_" . $_POST['lecture'] . " FROM " . $_POST['department'] . " WHERE semester='" . $_POST['semester'] . "' AND section='" . $_POST['section'] . "' AND date='" . $_POST['date'] . "'";
    $students = explode(",", $conn->query($query)->fetch_array()[0]);
    $newAttendance = "";
    $day = date('l', strtotime($_POST['date']));
    $query = "SELECT lecture_" . $_POST['lecture'] . " FROM " . $_POST['department'] . "_timetable WHERE semester='" . $_POST['semester'] . "' AND section='" . $_POST['section'] . "' AND day='" . $day . "'";
    $subject = explode(":", $conn->query($query)->fetch_array()[0])[0];
    $query = "SELECT attendance FROM roll_series WHERE roll_no='" . $_POST['rollNumber'] . "'";
    $attendance = (object)(json_decode($conn->query($query)->fetch_array()[0]));
    var_dump($attendance);
    if($_POST['wasPresent'] == 1) {
        foreach($students as $rollNumber) {
            if($rollNumber != $_POST['rollNumber']) {
                $newAttendance .= $rollNumber . ",";
            }
        }
        $attendance->{$subject}--;
        $attendance->lectureCount--;
    } else {
        array_push($students, $_POST['rollNumber']);
        sort($students);
        foreach($students as $rollNumber) {
                $newAttendance .= $rollNumber . ",";
        }
        if(isset($attendance->{$subject})) {
            $attendance->{$subject}++;
        } else {
            $attendance->{$subject} = 1;
        }
        $attendance->lectureCount++;
    }
    $attendance = json_encode($attendance);
    var_dump($attendance);
    $query = "UPDATE roll_series SET attendance='" . $attendance . "' WHERE roll_no='" . $_POST['rollNumber'] . "'";
    $conn->query($query);
    logAttendance("Attendance Updated",$_POST['department']);
    if(strlen($newAttendance) >= 1)
        $newAttendance = substr($newAttendance, 0, -1);
    $query = "UPDATE " . $_POST['department'] . " SET lecture_" . $_POST['lecture'] . "='" . $newAttendance . "' WHERE semester='" . $_POST['semester'] . "' AND section='" . $_POST['section'] . "' AND date='" . $_POST['date'] . "'";
    $conn->query($query);
}

// To show the attendance of a single student
if(isset($_POST['showMyAttendance'])) {
    $section = getSection($_POST['rollNumber'], $_POST['department']);
    $query = "SELECT * FROM " . $_POST['department'] . " WHERE date='" . $_POST['date'] . "' AND semester='" . $_POST['semester'] . "' AND section='" . $section . "'";
    $result = $conn->query($query)->fetch_array();
    $attendance = array();
    for($i = 1; $i <= 8; $i++) {
        if(array_search($_POST['rollNumber'], explode(",", $result['lecture_'.$i])) !== false) {
            $attendance[$i] = true;
        } else {
            $attendance[$i] = false;
        }
    }
    echo json_encode($attendance);
}


// To show the timetable of current day for teachers inlcuding adjustments
if(isset($_POST['fetchLectures'])) {
    $query = "SELECT * FROM subjects WHERE department='" . $_POST['department'] . "'";
    $result = $conn->query($query);
    $subjects = array();
    while($row = $result->fetch_array(MYSQLI_NUM)) {
        array_push($subjects, $row);
    }
    $timetable = array();
    $theQuery = "SELECT sec FROM departments WHERE initials='" . $_POST['department'] . "'";
    $sections = $conn->query($theQuery)->fetch_array()[0];
    $query = "SELECT * FROM ".$_POST['department']."_timetable WHERE day='" . date('l') . "'";
    $result = $conn->query($query);
    while($row = $result->fetch_array()) {
        for($i = 1; $i <= 8; $i++) {
            if(array_search($_POST['teacherCode'], explode(',', explode(":", $row['lecture_' . $i])[1])) === false) {
                $newQuery = "SELECT * FROM adjustments WHERE date='" . date("Y-m-j") . "' AND assigned_teacher='" . $_POST['teacherCode'] . "' AND lecture_no='" . $i . "'";
                $rows = $conn->query($newQuery)->num_rows;
                $data = $conn->query($newQuery)->fetch_array();
                if($rows > 0) {
                    for($j = 0; $j < count($subjects); $j++) {
                        if($subjects[$j][2] == $data['subject_code']) {
                            break;
                        }
                    }
                    $timetable[$i] = array(
                        "subject" => $subjects[$j][3],
                        "subjectCode" => $subjects[$j][2],
                        "type" => $subjects[$j][6],
                        "semester" => $subjects[$j][5],
                        "section" => $data['section'],
                        "assigned" => true
                    );
                } else {
                    continue;
                }
            } else {
                for($j = 0; $j < count($subjects); $j++) {
                    if($subjects[$j][2] == explode(":", $row['lecture_' . $i])[0]) {
                        break;
                    }
                }
                $timetable[$i] = array(
                    "subject" => $subjects[$j][3],
                    "subjectCode" => $subjects[$j][2],
                    "type" => $subjects[$j][6],
                    "semester" => $row['semester'],
                    "section" => $sections
                );
                if($subjects[$j][6] == "Practical")
                    $timetable[$i]['section'] = $row['section'];

                $newQuery = "SELECT * FROM adjustments WHERE date='" . date("Y-m-j") . "' AND teacher_code='" . $_POST['teacherCode'] . "' AND lecture_no='" . $i . "'";
                $rows = $conn->query($newQuery)->num_rows;
                $data = $conn->query($newQuery)->fetch_array();
                if($rows > 0) {
                    $myQuery = "SELECT * FROM teacher_list WHERE teacher_code='" . $data['assigned_teacher'] . "'";
                    $teacher = $conn->query($myQuery)->fetch_array();
                    $timetable[$i]['assignedTeacher'] = $teacher['name'] . " (" . $teacher['initials'] . ")";
                }
            }
        }
    }
    echo json_encode($timetable);
}

// To assign the new teacher for a lecture
if(isset($_POST['assignTeacher'])) {
    $query = "SELECT * FROM adjustments WHERE teacher_code='" . $_POST['teacherCode'] . "' AND lecture_no='" . $_POST['lecture'] . "' AND date='" . date("Y-m-j") . "' AND subject_code='" . $_POST['subject'] . "' AND department='" . $_POST['department'] . "' AND semester='" . $_POST['semester'] . "'";
    if($conn->query($query)->num_rows > 0) {
        $query = "UPDATE adjustments SET assigned_teacher='" . $_POST['assignedTeacher'] . "' WHERE teacher_code='" . $_POST['teacherCode'] . "' AND lecture_no='" . $_POST['lecture'] . "' AND date='" . date("Y-m-j") . "' AND subject_code='" . $_POST['subject'] . "' AND department='" . $_POST['department'] . "' AND semester='" . $_POST['semester'] . "'";
    } else {
        $query = "INSERT INTO adjustments VALUES(null, '" . $_POST['teacherCode'] . "', '" . $_POST['assignedTeacher'] . "', '" . $_POST['department'] . "', '" . $_POST['lecture'] . "', '" . date("Y-m-j") . "', '" . $_POST['subject'] . "', '" . $_POST['type'] . "', '" . $_POST['section'] . "', '" . $_POST['semester'] . "')";
    }
    $conn->query($query);
}

// To remove the assigned teacher for a lecture
if(isset($_POST['removeAssignedTeacher'])) {
    $query = "DELETE FROM adjustments WHERE teacher_code='" . $_POST['teacherCode'] . "' AND lecture_no='" . $_POST['lecture'] . "' AND date='" . date("Y-m-j") . "' AND subject_code='" . $_POST['subject'] . "' AND department='" . $_POST['department'] . "' AND semester='" . $_POST['semester'] . "'";
    $conn->query($query);
}

// To fetch the teachers that are free during a lecture 
if(isset($_POST['fetchFreeTeachers'])) {
    $query = "SELECT * FROM teacher_list WHERE department='". $_POST['department'] . "'";
    $teacherList = array();
    $result = $conn->query($query);
    while($row = $result->fetch_array()) {
        array_push($teacherList, $row);
    }
    $data = array();
    foreach($teacherList as $key => $teacher) {
        $query = "SELECT lecture_". $_POST['lecture'] ." FROM " . $_POST['department'] . "_timetable WHERE day='" . strtolower(date('l')) . "'";
        $result = $conn->query($query);
        $data[$key] = array(
            "name" => $teacher['name'],
            "code" => $teacher['teacher_code'],
            "initials" => $teacher['initials'],
            "free" => true
        );
        while($row = $result->fetch_array()) {
            if(array_search($teacher['teacher_code'], explode("," , explode(":", $row[0])[1])) !== false) {
                $data[$key]['free'] = false;
            }
        }
        if($data[$key]['free'] == true) {
            $query = "SELECT * FROM adjustments WHERE date='" . date("Y-m-j") . "' AND lecture_no='" . $_POST['lecture'] . "' AND assigned_teacher='" . $teacher['teacher_code'] . "'";
            $rows = $conn->query($query)->num_rows;
            if($rows > 0)
                $data[$key]['free'] = false;
        }
    }
    echo json_encode($data);
}

// To fetch logs
if(isset($_POST['fetchlogs'])) {
    $query = "SELECT l.sno, l.date, l.time, tl.name, l.info FROM log l INNER JOIN teacher_list tl ON l.teachercode=tl.teacher_username WHERE l.department='". $_POST['department']."'";
    $logs = array();
    $result = $conn->query($query);
    while($row = $result->fetch_array()) {
        array_push($logs, $row);
    }
    echo json_encode(array_reverse($logs));
}

// To add the new notice
if(isset($_POST['addNotice'])) {
    $query = "INSERT INTO notice VALUES(null,'".date('Y-m-j')."', '".$_POST['noticeTitle']."', '".$_POST['noticeContent']."', '".$_POST['level']."', '".$_POST['department']."')";
    $conn->query($query);
}

// To add the new assignment
if(isset($_POST['addAssignment'])) {
    if(!empty($_FILES['assignment-file']['name'])){
       $loc='./filecontent/assignments/';
       $filename = rand(100,999).$_FILES['assignment-file']['name'];
       move_uploaded_file($_FILES['assignment-file']['tmp_name'], $loc.$filename);
       if(file_exists($loc.$filename))
           $query = "INSERT INTO assignments VALUES(NULL, '".$_POST['assignment-no']."', '".$_POST['submission-date']."', '".$_POST['assignment-text']."', '".$filename."', '".$_POST['semester']."', '".$_POST['subject']."', '".$_POST['department']."')";
   }
   else
           $query = "INSERT INTO assignments VALUES(NULL, '".$_POST['assignment-no']."', '".$_POST['submission-date']."', '".$_POST['assignment-text']."', '', '".$_POST['semester']."', '".$_POST['subject']."', '".$_POST['department']."')";
   $result = $conn->query($query);
   if($result){
       echo "true";
   }
}


// To fetch the notices
if(isset($_POST['fetchNotices'])) {
    $query = "SELECT * FROM notice";
    $notices = array();
    $result = $conn->query($query);
    while($row = $result->fetch_array()) {
        array_push($notices, $row);
    }
    echo json_encode(array_reverse($notices));
}

// To fetch the assignments
if(isset($_POST['fetchAssignments'])) {
    $query = "SELECT * FROM assignments";
    $assignments = array();
    $result = $conn->query($query);
    while($row = $result->fetch_array()) {
        array_push($assignments, $row);
    }
    echo json_encode(array_reverse($assignments));
}

// To print the attendance report
if(isset($_POST['fetchReport'])) {
    $rollNumbers = array();
    $query = "SELECT roll_no FROM roll_series WHERE department='".$_POST['department']."' AND semester='".$_POST['semester']."'";
    $result = $conn->query($query);
    while($row = $result->fetch_array()) {
        array_push($rollNumbers, $row[0]);
    }


    $query = "SELECT * FROM ".$_POST['department']." WHERE semester=".$_POST['semester']." AND date BETWEEN '".$_POST['startDate']."' AND '".$_POST['endDate']."'";
    $result = $conn->query($query);
    $attendance = array();
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
        if(!isset($attendance[$row['date']]))
            $attendance[$row['date']] = array();
        for($i = 1; $i <= 8; $i++) {
            if(!isset($attendance[$row['date']][$i]))
                $attendance[$row['date']][$i] = array();
            if($row['lecture_'.$i] != "NA")
                array_push($attendance[$row['date']][$i], explode(",", $row['lecture_'.$i]));
        }
    }

    echo "<div class='table-responsive attendance-table'><table id='report-table' class='table table-bordered table-sm atten-table'><tr><th></th>";
    foreach($attendance as $date => $dateArray) {
        echo "<th colspan=8>$date</th>";
    }
    echo "</tr><tr><th></th>";
    foreach($attendance as $date) {
        for($i = 1; $i <= 8; $i++) {
            echo "<th>$i</th>";
        }
    }
    foreach($rollNumbers as $rollno) {
        echo "<tr><th>$rollno</th>";
        foreach($attendance as $date => $dateArray) {
                for($i = 1; $i <= 8; $i++) {
                    if($i == 8) $border = '5px solid var(--themeColorDark)';
                    else $border = '1px solid var(--themeColorDark)';
                    if(isset($dateArray[$i][0]) && array_search($rollno, $dateArray[$i][0]))
                        echo "<td style='color: green;border: $border'>P</td>";
                    else if(isset($dateArray[$i][1]) && array_search($rollno, $dateArray[$i][1]))
                        echo "<td style='color: green;border: $border'>P</td>";
                    else
                        echo "<td style='color: red;border: $border'>A</td>";
                }
        }
        echo "</tr>";
    }

}

?>
