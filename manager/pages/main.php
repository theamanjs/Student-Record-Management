<?php
session_start();
ini_set("max_execution_time","864000");
require_once("file-types.php");
// To navigate to the specific path when clicked on a breadcrumb item
if(isset($_POST['navToPath'])) {
    if(!is_readable(realpath($_POST['pathSelected']))) {
        return false;
    }
    $_SESSION['path'] = $_POST['pathSelected'];
    scanPath();
}

// To update the path when clicked on a specific directory
if(isset($_POST['scanPath'])) {
        $_SESSION['dirSelected'] = reConvertSymbol(rawurldecode($_POST['dirSelected']));
        if(!is_readable(realpath($_SESSION['path']). DIRECTORY_SEPARATOR . $_SESSION['dirSelected'])) {
            return false;
        }
        if($_SESSION['dirSelected'] == "../") {
            $_SESSION['previousDir'] = basename(realpath($_SESSION['path']));
        } else {
            $_SESSION['previousDir'] = null;
        }
        $_SESSION['path'] = realpath($_SESSION['path']). DIRECTORY_SEPARATOR . $_SESSION['dirSelected'];
        scanPath();
}

// To call createBreadcrumb() function for updation
if(isset($_POST['createBreadCrumb'])) {
    createBreadCrumb();
}

// To call createTree() to create the tree of directories
if(isset($_POST['createTree'])) {
    if($_POST['path'] == "null") 
        createTree($_SESSION['path']);
    else
        createTree($_POST['path']);
}

// To call printDirectories() to print the list of directories
if(isset($_POST['listDirectories'])) {
    $dirList = listDir($_POST['path']);
    if($dirList == false) {
        return false;
    }
    printDirectories($_POST['path'],$dirList);
}

// To make a new directory in current directory
if(isset($_POST['createFolder'])) {
    $dirName = realpath($_SESSION['path']).DIRECTORY_SEPARATOR.rawurldecode($_POST['folderName']);
    $counter = 2;
    while(file_exists($dirName)) {
        $dirName = realpath($_SESSION['path']).DIRECTORY_SEPARATOR.rawurldecode($_POST['folderName'])." (".$counter.")";
        $counter++;
    }
    mkdir($dirName);
    echo basename($dirName);
}

// To make a new empty file in current directory
if(isset($_POST['createFile'])) {
    $fileName = realpath($_SESSION['path']).DIRECTORY_SEPARATOR.rawurldecode($_POST['fileName']);
    $counter = 2;
    while(file_exists($fileName)) {
        $ext = (isset(pathinfo(realpath($_SESSION['path']).DIRECTORY_SEPARATOR.rawurldecode($_POST['fileName']))['extension'])) ? ".".pathinfo(realpath($_SESSION['path']).DIRECTORY_SEPARATOR.rawurldecode($_POST['fileName']))['extension'] : "";
        $fileName = realpath($_SESSION['path']) . DIRECTORY_SEPARATOR . pathinfo(realpath($_SESSION['path']).DIRECTORY_SEPARATOR.rawurldecode($_POST['fileName']))['filename']." (".$counter.")".$ext;
        $counter++;
    }
    touch($fileName);
    echo basename($fileName);
}

// To get the contents of selected file for editing
if(isset($_POST['editFile'])) {
    $fileType = @pathinfo(realpath($_SESSION['path']).DIRECTORY_SEPARATOR.reConvertSymbol(rawurldecode($_POST['fileName'])))['extension'];
    if($fileType == "")
        $fileType = "php";
    if($fileType == "js") 
        $fileType = "javascript";
    $file = '<?php session_start(); ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <title>'.reConvertSymbol(rawurldecode($_POST['fileName'])).'</title>
                <link rel="stylesheet" href="monaco-editor/editor-style.css">
            </head>
            <body>
                <input type="hidden" id="filePath" value="'.realpath($_SESSION['path']).'">
                <input type="hidden" id="fileName" value="'.reConvertSymbol(rawurldecode($_POST['fileName'])).'">
                <input type="hidden" id="fileType" value="'.$fileType.'">
                <div class="notification-container"></div>
                <div id="textContent" style="display:none"><?php echo htmlentities(file_get_contents("'.str_replace("\\", "/", realpath($_SESSION["path"]).DIRECTORY_SEPARATOR.reConvertSymbol(rawurldecode($_POST["fileName"]))).'")); ?></div>
                <div id="editor"></div>
                <script src="monaco-editor/min/vs/loader.js"></script>
                <script src="monaco-editor/editor-script.js"></script>
            </body>
            </html>';
    $fileGenerated = rand(1000,9999) . "-" . reConvertSymbol(rawurldecode($_POST['fileName'])) . ".php";
    file_put_contents("./editor/" . $fileGenerated, $file);
    echo $fileGenerated;
}

// To save the opened file
if(isset($_POST['saveFile'])) {
    file_put_contents((realpath($_POST['filePath']).DIRECTORY_SEPARATOR.reConvertSymbol(rawurldecode($_POST['fileName']))), rawurldecode($_POST['contents']));
    echo "done";
}


// To delete the selected items
if(isset($_POST['deleteItem'])) {
    for($i=0;$i<$_POST['length'];$i++) {
       deleteItem(realpath($_SESSION['path']).DIRECTORY_SEPARATOR.reConvertSymbol($_POST["itemNo$i"]));
    }
}

// To copy the selected items to a specific path
if(isset($_POST['copyItem'])) {
    $_SESSION['sourcePath'] = $_SESSION['path'];
    $_SESSION['operationName'] = "copy";
    $_SESSION['length'] = $_POST['length'];
    for($i=0;$i<$_POST['length'];$i++) {
        $_SESSION['itemsToCopy']["itemNo$i"] = reConvertSymbol($_POST["itemNo$i"]);
    }
}

// To move the selected items to a specific path
if(isset($_POST['cutItem'])) {
    $_SESSION['sourcePath'] = $_SESSION['path'];
    $_SESSION['operationName'] = "cut";
    $_SESSION['length'] = $_POST['length'];
    for($i=0;$i<$_POST['length'];$i++) {
        $_SESSION['itemsToCut']["itemNo$i"] = reConvertSymbol($_POST["itemNo$i"]);
    }
}
// To paste the cut/copied items to a specific path
if(isset($_POST['pasteItem'])) {
    echo $_SESSION['length'];
    if($_SESSION['operationName'] == "copy") {
        for($i=0;$i<$_SESSION['length'];$i++) {
            if(is_dir(realpath($_SESSION['sourcePath'].DIRECTORY_SEPARATOR.$_SESSION['itemsToCopy']["itemNo$i"]))) {
                if(0 == strcmp($_SESSION['sourcePath'].DIRECTORY_SEPARATOR.$_SESSION['itemsToCopy']["itemNo$i"], $_SESSION['path'])) {
                    echo "999"; // Just a code to tell that a directory can't be copied to itself.
                    continue;
                }
                $newName = $_SESSION['path'].DIRECTORY_SEPARATOR.$_SESSION['itemsToCopy']["itemNo$i"];
                $counter = 2;
                while(file_exists($newName)) {
                    $newName = $_SESSION['path'].DIRECTORY_SEPARATOR.$_SESSION['itemsToCopy']["itemNo$i"]." (".$counter.")";
                    $counter++;
                }
                mkdir($newName);
                copyItem(realpath($_SESSION['sourcePath'].DIRECTORY_SEPARATOR.$_SESSION['itemsToCopy']["itemNo$i"]), $newName);
            }
            else {
                    copyItem(realpath($_SESSION['sourcePath'].DIRECTORY_SEPARATOR.$_SESSION['itemsToCopy']["itemNo$i"]), realpath($_SESSION['path']));
            }
        }
        unset($_SESSION['itemsToCopy']);
    } else if($_SESSION['operationName'] == "cut") {
        for($i=0;$i<$_SESSION['length'];$i++) {
            if(0 == strcmp($_SESSION['sourcePath'].DIRECTORY_SEPARATOR.$_SESSION['itemsToCut']["itemNo$i"], $_SESSION['path'])) {
                echo "999"; // Just a code to tell that a directory can't be moved to itself.
                continue;
            }
            $newName = $_SESSION['path'].DIRECTORY_SEPARATOR.$_SESSION['itemsToCut']["itemNo$i"];
            $counter = 2;
            while(file_exists($newName)) {
                $ext = isset(pathinfo($_SESSION['path'].DIRECTORY_SEPARATOR.$_SESSION['itemsToCut']["itemNo$i"])['extension']) ? ".".pathinfo($_SESSION['path'].DIRECTORY_SEPARATOR.$_SESSION['itemsToCut']["itemNo$i"])['extension'] : "";
                $newName = $_SESSION['path'].DIRECTORY_SEPARATOR.pathinfo($_SESSION['itemsToCut']["itemNo$i"])['filename']." (".$counter.")".$ext;
                $counter++;
            }
            rename(realpath($_SESSION['sourcePath'].DIRECTORY_SEPARATOR.$_SESSION['itemsToCut']["itemNo$i"]), $newName);
        }
        unset($_SESSION['itemsToCut']);
    }
    unset($_SESSION['sourcePath']);
    unset($_SESSION['operationName']);
    unset($_SESSION['length']);
}

// To rename the selected item
if(isset($_POST['renameItem'])) {
    if(file_exists(realpath($_SESSION['path']).DIRECTORY_SEPARATOR.rawurldecode($_POST['newName']))) {
        echo "false";
        return false;
    }
    rename(realpath($_SESSION['path'].DIRECTORY_SEPARATOR.reConvertSymbol(rawurldecode($_POST['oldName']))), realpath($_SESSION['path']).DIRECTORY_SEPARATOR.rawurldecode($_POST['newName']) );
    echo $_POST['newName'];
}

// To create a zip archive of the selected files
if(isset($_POST['createZip'])) {
    $zip = new ZipArchive();
    $zipName = realpath($_SESSION['path']).DIRECTORY_SEPARATOR.reConvertSymbol(rawurldecode($_POST['zipName'])).".zip";
    $counter = 2;
    while(file_exists($zipName)) {
        $zipName = realpath($_SESSION['path']) . DIRECTORY_SEPARATOR. reConvertSymbol(rawurldecode($_POST['zipName'])) ." (".$counter.")".".zip";
        $counter++;
    }
    $zip->open($zipName, ZipArchive::CREATE);
    for($i=0;$i<$_POST['length'];$i++) {
        createZip($zip,realpath($_SESSION['path']).DIRECTORY_SEPARATOR.reConvertSymbol(rawurldecode($_POST["itemNo$i"])).DIRECTORY_SEPARATOR,reConvertSymbol(rawurldecode($_POST["itemNo$i"])).DIRECTORY_SEPARATOR);
    }
    $zip->close();
    echo basename($zipName);
}

// To extract the contents of a zip archive
if(isset($_POST['extractZip'])) {
    $zip = new ZipArchive;
    if ($zip->open(realpath($_SESSION['path']) . DIRECTORY_SEPARATOR. reConvertSymbol(rawurldecode($_POST['zipName']))) === TRUE) {
        $dirName = $_SESSION['path'].DIRECTORY_SEPARATOR. pathinfo($_SESSION['path'].DIRECTORY_SEPARATOR.reConvertSymbol(rawurldecode($_POST['zipName'])))['filename'];
        $counter = 2;
        while(file_exists($dirName)) {
            $dirName = $_SESSION['path'].DIRECTORY_SEPARATOR. pathinfo($_SESSION['path'].DIRECTORY_SEPARATOR.reConvertSymbol(rawurldecode($_POST['zipName'])))['filename']." (".$counter.")";
            $counter++;
        }
        mkdir($dirName);
        $zip->extractTo($dirName);
        $zip->close();
    }
}

// Create empty directories to place the uploaded files (While uploading a folder)
if(isset($_POST['createDirs'])) {
    $rootDirectory = realpath($_SESSION['path']) . DIRECTORY_SEPARATOR . $_POST['root'];
    $counter = 2;
    while (file_exists($rootDirectory)) {
        $rootDirectory = realpath($_SESSION['path']) . DIRECTORY_SEPARATOR . $_POST['root']." (".$counter.")";
        $counter++;
    }
    if($counter > 2) {
        for($i=0;$i<$_POST['filesCount'];$i++) {
            $dirName = substr($_POST[$i], strlen($_POST['root']));
            @mkdir(realpath($_SESSION['path']).DIRECTORY_SEPARATOR.basename($rootDirectory).$dirName,0777,true);
        }
        echo basename($rootDirectory);
    } else {
        for($i=0;$i<$_POST['filesCount'];$i++) {
            @mkdir(realpath($_SESSION['path']).DIRECTORY_SEPARATOR.$_POST["$i"],0777,true);
        }
    }
    
}

// To upload the files in current directory
if(isset($_POST['uploadItem'])) {
    $fileName = realpath($_SESSION['path']). DIRECTORY_SEPARATOR .$_POST['fileName'];
    if(isset($_POST['isFirstSlice'])) {
        $counter = 2;
        while(file_exists($fileName)) {
            $ext = isset(pathinfo(realpath($_SESSION['path']). DIRECTORY_SEPARATOR .$_POST['fileName'])['extension']) ? ".".pathinfo(realpath($_SESSION['path']). DIRECTORY_SEPARATOR .$_POST['fileName'])['extension'] : "";
            $fileName = realpath($_SESSION['path']). DIRECTORY_SEPARATOR . pathinfo(realpath($_SESSION['path']). DIRECTORY_SEPARATOR .$_POST['fileName'])['filename']." (".$counter.")".$ext;
            $counter++;
        }
        if($counter > 2) {
            echo pathinfo($fileName)['basename'];
        }
    }
    $data = base64_decode($_POST['sliceData']);
    $result = @file_put_contents($fileName, $data, FILE_APPEND);
    while(!$result) {
        $result = @file_put_contents($fileName, $data, FILE_APPEND);
    }
}

// To remove the half uploded file
if(isset($_POST['removeUploadedFile'])) {
    unlink(realpath($_SESSION['path']) . DIRECTORY_SEPARATOR . rawurldecode($_POST['name']));
}

// To create archive of selected files and download them 
if(isset($_POST['downloadFiles'])) {
    $zip = new ZipArchive();
    if($_POST['length'] == 1 && is_file(realpath($_SESSION['path']).DIRECTORY_SEPARATOR.rawurldecode($_POST["itemNo0"]))) {
        copy(realpath($_SESSION['path']).DIRECTORY_SEPARATOR.rawurldecode($_POST["itemNo0"]), sys_get_temp_dir().DIRECTORY_SEPARATOR.rawurldecode($_POST["itemNo0"]));
        createDownload(rawurldecode($_POST["itemNo0"]));
    } else {
        if(file_exists(sys_get_temp_dir().DIRECTORY_SEPARATOR."files.zip")) unlink(sys_get_temp_dir().DIRECTORY_SEPARATOR."files.zip");
        $zipName = sys_get_temp_dir().DIRECTORY_SEPARATOR."files.zip";
        $zip->open($zipName, ZipArchive::CREATE);
        for($i=0;$i<$_POST['length'];$i++) {
            createZip($zip,realpath($_SESSION['path']).DIRECTORY_SEPARATOR.rawurldecode($_POST["itemNo$i"]).DIRECTORY_SEPARATOR, $_POST["itemNo$i"].DIRECTORY_SEPARATOR);
        }
        $zip->close();
        createDownload("files.zip");
    }
}

// Copy the image into relative path for opening
if(isset($_POST['openImage'])) {
    if(file_exists("image.tmp")) unlink("image.tmp");
    if(file_exists("image.svg")) unlink("image.svg");
    if(strpos($_POST['imageName'], ".svg") !== false) {
        copy(realpath($_SESSION['path']. DIRECTORY_SEPARATOR. rawurldecode(reConvertSymbol($_POST['imageName']))), "image.svg");
        echo "image.svg?f=".rand(10000,99999);
    } else {
    copy(realpath($_SESSION['path']. DIRECTORY_SEPARATOR. rawurldecode(reConvertSymbol($_POST['imageName']))), "image.tmp");
    echo "image.tmp?f=".rand(10000,99999);
    }
}

// To delete the temporary created image file
if(isset($_POST['deleteImage'])) {
    if(file_exists("image.tmp")) unlink("image.tmp");
    if(file_exists("image.svg")) unlink("image.svg");
}

/*--------------------------------------------------------------------------------------
#                           USER-DEFINED FUNCTIONS                                     #
--------------------------------------------------------------------------------------*/

// To scan the files in a directory
function scanPath() {
    $_SESSION['path'] = realpath($_SESSION['path']);
    $dirScanned = scandir($_SESSION['path']);
    if(is_array($dirScanned)) {
        if(count($dirScanned) == 2) {
            echo "<div class='empty-msg'>This folder is empty.</div>";
            return false;
        }
    } else {
        return false;
    }
    $dirScanned = sortAsDirFirst($dirScanned);
    foreach ($dirScanned as $content) {
        if($content == "." || $content == ".." || intval((substr(sprintf('%o',fileperms(realpath($_SESSION['path'].DIRECTORY_SEPARATOR.$content))), -4)) == 0)) {
            continue;
        }
        if(is_dir($_SESSION['path'].DIRECTORY_SEPARATOR.$content)) {
            if(isset($_SESSION['previousDir'])) {
                if($content == $_SESSION['previousDir']) 
                    $classSelected = " selected";
                else $classSelected = "";
            } else $classSelected = "";
            echo "<a href=\"javascript:scanPath('".convertSymbol($content)."')\" class=\"list-group-item".$classSelected."\" data-name='".convertSymbol($content)."'>
                <i class='material-icons file-icons' style='color: #90a4ae'>folder</i>
                <span class='name'>".$content."</span>
                <span class='size'>&nbsp;</span>
                <span class='file-type'>Folder</span>
                <span class='date'>".date('M j, Y', filemtime(realpath($_SESSION['path'].'/'.$content)))."</span>
                 </a>";
        }
        else {
                $ext = isset(pathinfo($_SESSION['path'].DIRECTORY_SEPARATOR.$content)['extension']) ? pathinfo($_SESSION['path'].DIRECTORY_SEPARATOR.$content)['extension'] : false;
                $fileType = findIcon($ext);
                $href = ($fileType == "image") ? "javascript:openImage('".convertSymbol($content)."')" : '#';
                echo "<a href=\"".$href."\" class=\"list-group-item\" data-name='".convertSymbol($content)."' data-type='".$fileType."' data-ext='".$ext."'>
                <i class='material-icons file-icons'>".$fileType."</i>
                <span class='name'>".$content."</span>
                <span class='size'>".convertFileSize(filesize($_SESSION['path'].'/'.$content))."</span>
                <span class='file-type'>".findFileType($ext)."</span>
                <span class='date'>".date('M j, Y', filemtime(realpath($_SESSION['path'].'/'.$content)))."</span>
                </a>";
        }
    }
}

// To create breadcrumb menu according to current path location
function createBreadCrumb() {
    $path = $_SESSION['path'];
    $dirsInPath = explode(DIRECTORY_SEPARATOR,$path);
    for($i=0;$i<count($dirsInPath);$i++) {
        if($dirsInPath[$i] == "") {
            continue;
        }
        $dirPath = "";
        for ($j=0; $j <= $i; $j++) { 
            $dirPath.= $dirsInPath[$j].DIRECTORY_SEPARATOR;
        }
        if($i == count($dirsInPath) - 1 ) {
            echo "<li class=\"breadcrumb-item active\">".basename($dirPath)."</li>"; 
        }
        else {
            echo "<li class=\"breadcrumb-item\"><a href=\"javascript:navigateToPath('".addslashes(realpath($dirPath))."')\">".basename($dirPath)."</a></li>"; 
        }
    }
}

// To generate the tree for the given path
function createTree ($path) {
    $dirNames = explode(DIRECTORY_SEPARATOR,$path);
    for($i=0;$i<count($dirNames);$i++) {
        if($dirNames[$i] == "") {
            continue;
        }
        $dirFullPaths[$i] = "";
        for ($j=0; $j <= $i; $j++) { 
            $dirFullPaths[$i].= $dirNames[$j].DIRECTORY_SEPARATOR;
        }
        $dirContents[$i] = listDir(realpath($dirFullPaths[$i]));
    }
    echo "<ul class='tree'>";
    if(count($dirContents) == 1)
        echo "<li class='tree-item opened current'><i onclick='expandTree(this)' class='material-icons'>arrow_drop_down</i>&3;".addslashes($dirNames[0])."')\">&0;$dirNames[0]</a></li>";
    else
        echo "&2;&3;".addslashes($dirNames[0])."')\">&0;$dirNames[0]</a></li>";
    printTree($dirContents, $dirFullPaths ,$dirNames);
    echo "</ul>";
}

// To list the names of all the directories inside a directory
function listDir($sourceDir) {
    if(!is_readable($sourceDir)) {
        echo "false";
        return false;
    }
    $dir = opendir($sourceDir); 
    $i = 0;
    $dirContents = array();
    while(false !== ( $itemInDir = readdir($dir)) ) { 
        if (( $itemInDir != '.' ) && ( $itemInDir != '..' )) { 
            if ( is_dir($sourceDir . '/' . $itemInDir) ) { 
                $dirContents[$i] = $itemInDir;
                $i++;
            } 
        } 
    } 
    closedir($dir); 
    return $dirContents;
}

// To print the tree
function printTree($dirContents, $dirFullPaths, $dirNames) {
    $firstDirTree = current($dirContents);
    $currentPath = $dirFullPaths[0];
    if(is_array($firstDirTree)) {
        echo "<ul class='tree'>";
        foreach ($firstDirTree as $key => $value) {
            $dirUrl = $currentPath . $value;
            if(count($dirFullPaths) > 1 && $dirUrl.DIRECTORY_SEPARATOR == $dirFullPaths[1]) {
                if(count($dirContents) == 2) {
                    $style = "";
                    if(!count(listDir($dirUrl))) $style = "opacity:0";
                    echo "<li class='tree-item opened current'><i style='".$style."' onclick='expandTree(this)' class='material-icons'>arrow_drop_down</i>&3;".addslashes($dirUrl)."')\">&0;$value</a></li>";
                } else {
                    echo "&2;&3;".addslashes($dirUrl)."')\">&0;$value</a></li>";
                }
                $subTree  = array_slice($dirContents,1);
                $dirFullPaths = array_slice($dirFullPaths,1);
                $dirNames = array_slice($dirNames, 1);
                printTree($subTree, $dirFullPaths , $dirNames );
            }
            else {
                echo "&1;&3;".addslashes($dirUrl)."')\">&0;$value</a></li>";
            }
        }
        echo "</ul>";
    }
}

// To print only the one list of directories
function printDirectories($path,$dirsList) {
    echo "<ul class='tree'>";
    foreach($dirsList as $key => $value) {
        $dirUrl = $path . "\\\\" . $value;
        echo "<li class='tree-item'><i onclick='expandTree(this)' class='material-icons' style='transform:rotate(-90deg)'>arrow_drop_down</i><a href=\"javascript:navigateToPath('".str_replace("\\\\",DIRECTORY_SEPARATOR,addslashes($dirUrl))."')\"><i class='material-icons' style='color: #90a4ae'>folder</i>$value</a></li>";
    }
    echo "</ul>";
}

// To find a suitable icon for a file using its extension
function findIcon($ext) {
    $ext = strtolower($ext);
    $types = array(
        "play_circle_filled" => array('','mp3','ogg','wav','m4a','aac','wma','amr'),
        "image" => array('','jpeg','jpg','bmp','png','gif','tiff','svg'),
        "movie" => array('','mp4','3gp','flv','avi','mkv','wmv','vob','webm'),
        "archive" => array('','zip','rar','7z','tar','gz','bz2')
    );
    foreach ($types as $key => $value) {
        if(array_search($ext,$value)) return $key;
    }
    return "insert_drive_file";
}

// To find the file type from extension
function findFileType($ext) {
    if(!$ext) return "Unknown File";
    global $fileTypes;
    foreach($fileTypes as $key => $value) {
        if(strtoupper($ext) == $key) return $value;
    }
    return "Unknown File";
}


// To delete the specified Directory (with its contents) or a File
function deleteItem($src) {
    chmod($src,0777);
    if(is_dir($src)) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                deleteItem($full);
            }
        }
        closedir($dir);
        rmdir($src);
    }
    else {
        unlink($src);
    }
}

// Sorts an array of files & directories name. Arrange the directories first and then files. Returns the sorted array.
function sortAsDirFirst($arrayToSort)  {
    for( $i = 0 ; $i < count ($arrayToSort) ; $i++ ) {
        if ( is_dir( $_SESSION['path'] . DIRECTORY_SEPARATOR . $arrayToSort[$i] ) ) {
            $dirs[$i] = $arrayToSort[$i];
            natcasesort($dirs);
        } else { 
            $files[$i] = $arrayToSort[$i];
            natcasesort($files);
        }
    }
    if(!isset($dirs)) return $files;
    else if(!isset($files)) return $dirs;
    else return array_merge($dirs,$files);
}

// Converts filesize into a readable form
function convertFileSize($bytes, $decimals = 0) {
    $factor = floor((strlen($bytes) - 1) / 3);
    if ($factor > 0) $sz = 'KMGT';
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) .' '. @$sz[$factor - 1] . 'B';
}

// Copies file or directories from source path to destination path
function copyItem($src,$dst) { 
	if(is_dir($src)) {
    	$dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    copyItem($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    }
    else {
        $newName = $dst . DIRECTORY_SEPARATOR . basename($src);
        $counter = 2;
        while(file_exists($newName)) {
            $ext = (isset(pathinfo($src)['extension'])) ? ".".pathinfo($src)['extension'] : "";
            $newName = $dst . DIRECTORY_SEPARATOR . pathinfo($src)['filename']." (".$counter.")".$ext;
            $counter++;
        }
        copy($src, $newName); 
    }
}

// Create zip archive of the files selected
function createZip($zip,$path,$root){
    if (is_dir($path)){
        if(count(scandir($path)) == 2)
            $zip->addEmptyDir(substr($root,0,-1));
        else if ($dh = opendir($path)){
            while (($file = readdir($dh)) !== false){
                if (is_file($path.$file)) {
                        $zip->addFile($path.$file,$root.$file);
                } else {
                    if(is_dir($path.$file) ){
                        if($file != '.' && $file != '..') {
                            $zip->addEmptyDir($root.$file);
                            $folder = $path.$file.'/';
                            $newRoot = $root.$file.'/';
                            createZip($zip,$folder,$newRoot);
                        }
                    }
                }
            }
            closedir($dh);
        }
    } else {
        $zip->addFile(substr($path,0,-1),basename($path));
    }
}

// To replace the symbols with some keywords
function convertSymbol($string) {
    return str_replace("'","&&39",$string);
}

// To regain the replaced symbols
function reConvertSymbol($string) {
    return str_replace("&&39","'",$string);
}

// To create the page for downloading a file
function createDownload($filename) {
    $downloadFile = fopen('download.php', w);
    fwrite($downloadFile, '<?php
    $file = sys_get_temp_dir().DIRECTORY_SEPARATOR."'.$filename.'";
    if (file_exists($file)) {
        header(\'Content-Description: File Transfer\');
        header(\'Content-Type: application/octet-stream\');
        header(\'Content-Disposition: attachment; filename="\'.basename($file).\'"\');
        header(\'Expires: 0\');
        header(\'Cache-Control: must-revalidate\');
        header(\'Pragma: public\');
        header(\'Content-Length: \' . filesize($file));
        readfile($file);
        exit;
    }
    ?>');
}
?>