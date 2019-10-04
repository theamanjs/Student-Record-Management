<?php
ini_set("session.gc_maxlifetime", 864000);
session_start();
if(!isset($_SESSION['path'])) {
    $_SESSION['path'] = __DIR__ ; // Set current directory as default directory to open
    array_map("deleteFile", glob("pages/editor/*.php")); // Deletes temporary files, created for editing
}
if(!isset($_SESSION['fm_active'])) {
    header('Location: pages/login.php');
}
function deleteFile($file) {
    if($file == "pages/editor/index.php") return true;
    $fileDate = new DateTime(date('Y-m-j',filemtime($file)));
    $currDate = new DateTime(date('Y-m-j'));
    $diff = $currDate->diff($fileDate);
    if($diff->days > 2) unlink($file);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>The File Manager</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/material-icons.css">
    <link rel="icon" href=" ">

</head>

<body  ondragstart="return false;" ondrop="return false;">

<!-- LOADER -->
<div class="loader-container">
    <div class="loader-card">
        <div class="loader"></div>
        <div class="loader-text">Loading...</div>
    </div>
</div>
<!-- END OF LOADER -->

<!-- MODAL CONTAINER -->
<div class="modal-container">
    <div class="modal-window">
        <div class="modal-head">
            <span></span>
            <div class="btn-group">
                <button class="close-btn maximize-btn" onclick="maximizeModal()"><i class="maximize-icon"></i></button>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
        </div>
        <div class="modal">
            <!-- <div class='flex space-between'>
                <span id="uploadingFileName" style="max-width:200px">fdsfsdfs.txt</span>
                <span id="filesCount">3/8</span>
            </div>
            <div class="progress-bar">
                <div class="progress-bar-fill">  </div>
            </div> -->
        </div>
    </div>
</div>
<!-- END OF MODAL CONTAINER -->

<!-- IMAGE VIEWER -->
<div class="image-viewer">
    <div class="image-viewer-head">
        <span></span>
        <button class="close-btn" onclick="closeImageViewer()">&times;</button>
    </div>
    <div class="image-container">
    </div>
</div>
<!-- END OF IMAGE VIEWER -->

<!-- NAVIGATION BAR -->
<nav>
    <a href="javascript:scanPath('../')" class="round-btn"><i class="material-icons">arrow_back</i></a>
    <ol class="breadcrumb" id="breadcrumb">
        <!-- Fetched by PHP through AJAX -->
    </ol>
    <div class="options-menu">
        <div class="dropdown"> 
            <a class="dropdown-title option" onclick="showDropdown(this)"><i class="material-icons">add_box</i> New</a>
            <div class="dropdown-item-container">
                <a href="javascript:showModal('createFolder')" class="dropdown-item"><i class="material-icons">folder</i>
                <span class="dropdown-item-name">Folder</span></a>
                <a href="javascript:showModal('createFile')" class="dropdown-item"><i class="material-icons">insert_drive_file</i> <span class="dropdown-item-name">File</span></a>
            </div>
        </div>
        <div class="dropdown"> 
            <a class="dropdown-title option" onclick="showDropdown(this)"><i class="material-icons">cloud_upload</i> Upload</a>
            <div class="dropdown-item-container">
                <a href="javascript:showModal('uploadFolder')" class="dropdown-item"><i class="material-icons">folder</i> <span class="dropdown-item-name">Folder</span></a>
                <a href="javascript:showModal('uploadItem')" class="dropdown-item"><i class="material-icons">insert_drive_file</i> <span class="dropdown-item-name">Files </span></a>
            </div>
        </div>
        <div class="dropdown"> 
            <a class="dropdown-title option" onclick="showDropdown(this)"><i class="material-icons view-icon">view_list</i> View</a>
            <div class="dropdown-item-container">
                <a href="javascript:changeView('list')" class="dropdown-item"><i class="material-icons">view_list</i> <span class="dropdown-item-name">List</span></a>
                <a href="javascript:changeView('grid')" class="dropdown-item"><i class="material-icons">apps</i> <span class="dropdown-item-name">Grid</span></a>
                <a href="javascript:changeView('details')" class="dropdown-item"><i class="material-icons">menu</i> <span class="dropdown-item-name">Details</span></a>
            </div>
        </div>
        <div class="dropdown" id="moreOptions" style="display:none;"> 
            <a class="dropdown-title option" onclick="showDropdown(this)"><i class="material-icons">more_vert</i></a>
            <div class="dropdown-item-container">
                <a href="javascript:pasteItem()" class="dropdown-item" id="pasteButton" style="display:none"><i class="material-icons">content_paste</i><span class="dropdown-item-name">Paste</span></a>
                <a href="javascript:showModal('deleteItem')" class="dropdown-item"><i class="material-icons">delete</i><span class="dropdown-item-name">Delete</span></a>
                <a href="javascript:copyItem()" class="dropdown-item"><i class="material-icons">content_copy</i><span class="dropdown-item-name">Copy</span></a>
                <a href="javascript:cutItem()" class="dropdown-item"><i class="material-icons">content_cut</i><span class="dropdown-item-name">Cut</span></a>
                <a href="javascript:editFile()" class="dropdown-item" id="editButton"><i class="material-icons">create</i><span class="dropdown-item-name">Edit</span></a>
                <a href="javascript:showModal('renameItem')" class="dropdown-item" id="renameButton"><i class="material-icons">font_download</i><span class="dropdown-item-name">Rename</span></a>
                <a href="javascript:showModal('createZip')" class="dropdown-item"><i class="material-icons">archive</i><span class="dropdown-item-name">Archive</span></a>
                <a href="javascript:extractZip()" class="dropdown-item" id="extractButton"><i class="material-icons">unarchive</i><span class="dropdown-item-name">Extract</span></a>
                <a href="javascript:downloadFiles()" class="dropdown-item"><i class="material-icons">file_download</i><span class="dropdown-item-name">Download</span></a>
            </div>
        </div>
        <div class="dropdown"> 
            <a href="pages/logout.php" class="dropdown-title option"><i class="material-icons">exit_to_app</i> Logout</a>
        </div>
    </div>
</nav>
<!-- END OF NAVIGATION BAR -->

<div class="file-tree">
    <!-- Fetched by PHP through AJAX -->
</div>
<div class="splitter"></div>
<div class="list-group-section">
    <a class="list-group-head">
        <span style="width:25px;margin:0 10px;" class="icon-text">Icon</span>
        <span class="name" onclick="sortByName()">Name</span>
        <span class="size">Size</span>
        <span class="file-type">Type</span>
        <span class="date">Modified</span>
    </a>

    <div class="main-container">
        <div class="list-group" id="contentArea">
            <!-- Fetched by PHP through AJAX -->
        </div>
        <div class="select-box"></div>
    </div>
</div>

<!-- NOTIFICATIONS -->
<div class="notification-container">
</div>
<!-- END OF NOTIFICATIONS -->


 <!-- CONTEXT MENU -->
<div class="context-menu">
    <a href="javascript:pasteItem()" class="context-menu-item paste-btn" style="display:none"><i class="material-icons">content_paste</i> Paste</a>
    <div class="context-menu-upper">
        <a href="javascript:editFile()" class="context-menu-item edit-btn"><i class="material-icons">create</i> Edit</a>
        <a href="javascript:copyItem()" class="context-menu-item"><i class="material-icons">content_copy</i> Copy</a>
        <a href="javascript:cutItem()" class="context-menu-item"><i class="material-icons">content_cut</i> Cut</a>
        <a href="javascript:showModal('deleteItem')" class="context-menu-item"><i class="material-icons">delete</i> Delete</a>
        <a href="javascript:showModal('renameItem')" class="context-menu-item rename-btn"><i class="material-icons">font_download</i> Rename</a>
        <a href="javascript:showModal('createZip')" class="context-menu-item"><i class="material-icons">archive</i> Archive</a>
        <a href="javascript:extractZip()" class="context-menu-item extract-btn"><i class="material-icons">unarchive</i> Extract</a>
        <a href="javascript:downloadFiles()" class="context-menu-item"><i class="material-icons">file_download</i> Download</a>
    </div>
    <div class="context-menu-lower">
        <a href="javascript:selectAll()" class="context-menu-item"><i class="material-icons">select_all</i> Select All</a>
        <a href="javascript:scanPath('./')" class="context-menu-item"><i class="material-icons">refresh</i> Refresh</a>
        <div class="context-menu-item has-dropdown"><i class="material-icons">add_box</i> New
            <div class="context-menu-dropdown">
                <a href="javascript:showModal('createFolder')" class="context-dropdown-item"><i class="material-icons">folder</i> Folder</a>
                <a href="javascript:showModal('createFile')" class="context-dropdown-item"><i class="material-icons">insert_drive_file</i> File</a>
            </div>
        </div>
        <div class="context-menu-item has-dropdown"><i class="material-icons">cloud_upload</i> Upload
            <div class="context-menu-dropdown">
                <a href="javascript:showModal('uploadFolder')" class="context-dropdown-item"><i class="material-icons">folder</i> Folder</a>
                <a href="javascript:showModal('uploadItem')" class="context-dropdown-item"><i class="material-icons">insert_drive_file</i> Files</a>
            </div>
        </div>

    </div>
</div>
<!-- END OF CONTEXT MENU -->

<!-- MENU FOR EDITOR -->
<div class="menu-container">
    <a href="javascript:saveFile()" class="menu-item">Save</a>
    <a href="javascript:closeModal()" class="menu-item">Exit</a>
</div>
<!-- END OF MENU FOR EDITOR -->

<!-- JS Scripts -->
<script src="js/main.js"></script>
</body>
</html>