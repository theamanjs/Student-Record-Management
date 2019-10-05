# Student Record Management and Performance Evaluation 


**A best solution of many problems about student record management regarding attandance, assignments, notices, output report generator, timetable display and the most unique feature showing current lecture of class(which helps in saving a lot of time of teachers and students).**


# Summary of project
This project is desinged mainly to solve this problem for polytechnic colleges but we are adding the best functionality to make this software that much flexible that it could run in any institute or organisation(Educational). This project was started around `20-01-2019` in the college Guru Nanak Dev Polytechnic College, Ludhiana. Our ambition is to make the project work like a robot and automate task for students and teachers. 

# Problems which are supposed to be elimated in real world
There are a lot of problems which are supposed to be eliminated like calculating attendance for student(subject-wise), consistency of notices, making adjustment of lectures, creating logs of every action, generating report in the form of pdf or xls, finding current lecture and class and many more. There are a lot of projects out there which has the basic options, but in this software there are some unique features like


## <div align="center">INDEX</div>

1. **_Introduction_**<br/>
   1.1. Purpose<br/>
   1.2. Document Conventions<br/>
   1.3. Intended Audience and Reading Suggestion<br/>
   1.4. Project Scope<br/>
   1.5. Definitions and Acronyms<br/>
   1.6. References
2. **_Overall Description_**<br/>
   2.1. Product Perspective<br/>
   2.2. User class and Characteristics<br/>
   2.3. Operating Environment<br/>
   2.4. Design and Implementations Constraints
3. **_System Features_**
4. **_System Requirements_**<br/>
   4.1. **External Interface Requirements**<br/>
   4.1.1. User Interface<br/>
   4.1.2. Hardware Interface<br/>
   4.1.3. Software Interface<br/>
   4.1.4. Communication Interface<br/>
   4.2. **Other Requirements**<br/>
   4.2.1. Performance Requirements<br/>
   4.2.2. Safety Requirements<br/>
   4.2.3. Security Requirements

---

### **1. Introduction**

#### 1.1. Purpose

<p align="justify">The purpose of this document is to build an “Question Papers OTA” to ease the generation of questions papers for Student’s End Semester Examination and then will view to authenticate users.</p>

#### 1.2. Document Conventions

| Initials | Full Form          |
| -------- | ------------------- |
| SRMPE      | Student Record Management and Performance Evaluation         |
| db       | DataBase            |
| ID       | Identity Card       |
| E-R      | Entity Relationship |

#### 1.3. Intended Audience and Reading Suggestion

<p align="justify">This software is made with having main focus on institute or organisation(Educational). This project currently can be run in any polytechnic college, but we are improving day by day to make this project flexible and scalable so that it could be used in any college</p>

#### 1.4. Project Scope

- Attendace can be filled and seen just in a single click.
- Evaluation of lecture counting will be much easier.
- Only authenticated user can have access to certain previlieges.
- Teachers and Student can see easily where the lecture is going to be held which makes it easier to see current lecture and can save time.
- Reduces the uses of hardcopy of papers.
- Assignment consistency is promoted in the project

#### 1.5. Definitions and Acronyms

- There will be a risk if anyone have filled attendace wrong or inconsistence. But, the user can talk to HOD and let the attendance changed with his privilege.

#### 1.6. References

[Github](https://www.github.com)
[Creative Tim Dashboard](https://www.creative-tim.com/product/material-dashboard)

### **2. Overall Description**

#### 2.1. Product Perspective

A distributed Online Student Record Management and Performance Evaluation stores the following information:

- **User details:**
  It includes the username, password and user-type fields for log in of authenticated users.
- **Department details:**
  It includes the department-name field that describe the departments of the students, teacher and HoD.
- **Semester Details:**
  It includes the semester-name field that describe at which semester the student is studying.
- **Subject Details:**
  It includes the subject-name, subject-code, semester-name, and department-name fields that detailed the subjects allotted to students according to their department-name and semester-name.

#### 2.2. User Class and Characteristics

- **The Principal (user) should be able to do following functions:**
  - Change the semester which is currently going on.
  - Declaring the holiday in college
- **The HoD (user) should be able to do following functions:**
  - Update the Attendance
  - Generate Hardcopy of report
  - Update the timetable
  - Mark the lecture adjustment of lectuers(In case a lecturer is not available.
  - Updating the notice related to instition 
  - Updating the Assignments 
- **The lecturer (user) should be able to do following functions:**
  - Fill the attendance of students.
  - Can fill the attendace of other students in case of adjustment
  - Updating the notice related to instition 
  - Updating the Assignments 

#### 2.3. Operating Environment

- Client/Server system.
- Operating System: Windows, Linux, MacOS.
- Database: MySQL.
- Platform: JavaScript using AJAX, PHP.

#### 2.4. Design and Implementations Constraints

- The global schema.
- SQL commands for queries/applications.
- Implement the database at least using a centralized database management system.

### **3. System Features**

- It is fast and much accurate.
- It also needs less manpower, to execute the examination.
- It saves student's time in examination.
- It also helps the environment by saving papers.

#### **3.1 Other Key Features**
- Real Time class allocator 
- Adjustment of lecture and generating adjustment reports of lectures   
- Generating output for printing lectures
- Creating report of every activity
- Simplest UI design to have good UX
- Consistency of Notices
- User Firendly
- Double Click TimeTable Update
- Single click Adding new things(Subjects, Teachers and Timetable)
- Detaining and Retaining Students in one click
- Power of atorney can be set to different faculty members
- View attendance of Students in single click

 

### **4. System Requirements**

#### 4.1. External Interface Requirements

##### 4.1.2. User Interfaces

- **Front-end languages:** HTML, CSS, JavaScript.
- **Back-end technologies:** PHP, MySQL.
- **Browser** that support CGI, HTML, CSS and JavaScript.

##### 4.1.2. Hardware Interfaces

- **Processor:** Above Pentium4.
- **Operating System:** Windows/Linux/MAC.

##### 4.1.3. Software Interfaces

| **Software Used**     | **Description**                                                                                                                                                    |
| --------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Operating System      | We have chosen Windows/Linux/Mac operating system for its best support and user-friendliness.                                                                                |
| Database              | To save records of the user, attendance record, and subjects, we have used MySQL db.                                                                                       |
| Server-Side Scripting | To perform database operation through scripting at server-side, we have used PHP language.                                                                               |
| Front-End Languages   | To design an interactive front-end, we use HTML (for structuring the application), CSS (for styling the HTML structure) and JavaScript (for interactive webpages). |

##### 4.1.4. Communication Interfaces

<p align="justify">The project support all type of web browsers(not Internet Explorer). We are using simple and user-friendly communication interfaces.</p>

#### 4.2. Other Requirements

##### 4.2.1. Performance Requirements

- **E-R Diagram:**
  The E-R Diagram constitutes a technique for representing the logical structure of a database in a pictorial manner. This analysis is then used to organize data as a relation and finally obtaining a relation database.
  <br/>**Entities** specify distinct real-world items in an application.
  <br/>**Attributes/Properties** specifies properties of an entity and relationships.
  <br/>**Relationships** connect entities and represent meaningful dependencies between them.<br/>
- **Normalization:**
  The basic objective of normalization is to reduce redundancy which means that information is stored only once. Storing information several times leads to wastage of storage space and increase in the total size of the data stored. The normalization is the process of breaking down a table into smaller tables.

##### 4.2.2. Safety Requirements

<p align="justify"><p align="justify">If there is extensive damage to a wide portion of the database due to catastrophic failure, such as a disk crash, the recovery method restores a past copy of the database that was backed up to archival storage and reconstructs a more current state by reapplying or redoing the operations of committed transactions from the backed up log, up to the time of failure.</p>

##### 4.2.3. Security Requirements

<p align="justify">Security systems need database storage just like many other applications. However, the special requirements of the security market mean that vendors must choose their database partner carefully.</p>


> Because of getting the operations done in single click, this software is highly scalable and flexible to use. But we are continuously improving this to make it better day by day :v:
