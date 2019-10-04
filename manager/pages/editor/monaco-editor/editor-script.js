let codeEditor;
let timeout;

require.config({ paths: { "vs": "monaco-editor/min/vs" } });
require(["vs/editor/editor.main"], function() {
    let editor = monaco.editor.create(document.getElementById("editor"), {
        value: [document.getElementById("textContent").textContent].join(""),
        language: document.getElementById("fileType").value,
        theme: "vs-dark"
    });
    codeEditor = editor;
});

function saveFile() {
    let data = "saveFile=true&contents=" + encodeURIComponent(codeEditor.getValue()) + "&fileName=" + encodeURIComponent(document.getElementById("fileName").value) + "&filePath=" + encodeURIComponent(document.getElementById("filePath").value);
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../main.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
    xhr.addEventListener('load', function () {
        if(this.response == "done")
            showNotification("File Saved!");
        else
            showNotification("File Not Saved!");
    })
    if(document.title.startsWith("• ")) document.title = document.title.slice(2);
}

function closeFile() {
    window.close();
}
window.onkeydown = e => {
    if (e.ctrlKey && e.code == "KeyS") {
      e.preventDefault();
      saveFile();
    }
    if(e.code == "F4") {
        closeFile();
    }
    if(e.ctrlKey && e.code == "NumpadAdd") {
        codeEditor['_actions']['editor.action.fontZoomIn']['_run']();
        e.preventDefault();
    } else if(e.ctrlKey && e.code == "NumpadSubtract") {
        codeEditor['_actions']['editor.action.fontZoomOut']['_run']();
        e.preventDefault();
    }
    if(!document.title.startsWith("• ") && !e.ctrlKey && document.activeElement.tagName == "TEXTAREA") document.title = "• " + document.title;
}

function showNotification(message) {
    if (timeout) clearTimeout(timeout);
    document.querySelector(
        ".notification-container"
    ).innerHTML = `<div class="notification"> ${message} </div>`;
    setTimeout(() => {
        document.querySelector(".notification-container").className += " fade";
    }, 100);
    document.querySelector(".notification").addEventListener("click", el => {
        document.querySelector(".notification-container").className = "notification-container";
        setTimeout(() => {
            document.querySelector(".notification").remove();
            clearTimeout(timeout);
        }, 100);
    });
    timeout = setTimeout(() => {
        document.querySelector(".notification-container").className = "notification-container";
        setTimeout(() => {
            document.querySelector(".notification").remove();
        }, 100);
    }, 3000);
}