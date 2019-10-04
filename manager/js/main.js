'use strict';

// Global Variables Declaration
let isModalOpen = false;
let isDropdownOpen = false;
let isImageViewerOpen = false;
let isContextMenuOpen = false;
let mouse = {};
let filePaths = {};
let timeout;
let totalDone;
let totalSize;
let cancelUpload = false;
let sortedByName = true;

// To scan the current directory by default on page load
scanPath('./');

/*--------------------------------------------------------------------------------------
#                                  CONTEXT MENU                                        #
--------------------------------------------------------------------------------------*/
// Disable the original Context menu & show custom Context menu
window.oncontextmenu = function() {
	if (!isModalOpen && !isImageViewerOpen) {
		$('.context-menu').style.display = 'block';

		// To set context menu position veritcally according to mouse pointer Y-axis
		if (mouse.curY + $('.context-menu').offsetHeight > window.innerHeight) {
			$('.context-menu').style.top =
				window.innerHeight - $('.context-menu').offsetHeight + 'px';
		} else {
			$('.context-menu').style.top = mouse.curY + 'px';
		}

		// To set context menu position horizontally according to mouse pointer X-axis
		if (mouse.curX + $('.context-menu').offsetWidth > window.innerWidth) {
			$('.context-menu').style.left =
				window.innerWidth - $('.context-menu').offsetWidth + 'px';
		} else {
			$('.context-menu').style.left = mouse.curX + 'px';
		}

		// To check the options to be displayed in context menu
		$('.context-menu .rename-btn').style.display =
			countSelected() == 1 ? 'block' : 'none';
		$('.context-menu .edit-btn').style.display =
			countSelected() == 1 &&
			$('.list-group-item.selected').getAttribute('data-type') == 'insert_drive_file'
				? 'block'
				: 'none';
		$('.context-menu .extract-btn').style.display =
			countSelected() == 1 &&
			$('.list-group-item.selected').getAttribute('data-ext') == 'zip'
				? 'block'
				: 'none';
		$('.context-menu-upper').style.display = !countSelected()
			? 'none'
			: 'block';
		isContextMenuOpen = true;
	}
	return false;
};

/*--------------------------------------------------------------------------------------
#                               MOUSE EVENTS                                           #
--------------------------------------------------------------------------------------*/

// To show select box when mouse button is pressed
window.onmousedown = function(e) {
	mouse.isDown = true;
	mouse.X = e.clientX;
	mouse.Y = e.clientY;

	// To ensure that user wants to select the files
	if (
		e.clientX > $('.main-container').offsetLeft &&
		e.clientY > $('.main-container').offsetTop &&
		!isModalOpen &&
		!isImageViewerOpen &&
		!mouse.onSplitter
	) {
		let sb = $('.select-box').style;
		sb.top = sb.left = sb.width = sb.height = '0px';
		mouse.posX =
			e.clientX -
			$('.main-container').offsetLeft +
			parseInt($('.main-container').scrollLeft);
		mouse.posY =
			e.clientY -
			$('.main-container').offsetTop +
			parseInt($('.main-container').scrollTop);
		sb.display = 'block';
		mouse.isSelecting = true;
	}
};

// To hide the select box when mouse button is released
window.onmouseup = function(e) {
	mouse.isDown = false;
	mouse.isSelecting = false;
	$('.select-box').style.display = 'none';

	// To show more options only if an item is selected
	setTimeout(toggleMoreOptions, 10);

	if (isDropdownOpen) {
		closeDropdown();
	}

	if (e.target.tagName == 'BUTTON' || e.target.tagName == 'A') {
		e.target.blur();
	}
};

window.onmousemove = function(e) {
	mouse.curX = e.clientX;
	mouse.curY = e.clientY;

	// To move the image when the image is dragged
	if (mouse.isDown && isImageViewerOpen) {
		$('.image-container').style.left =
			parseInt($('.image-container').style.left.slice(0, -2)) +
			parseInt(e.clientX - mouse.X) +
			'px';
		$('.image-container').style.top =
			parseInt($('.image-container').style.top.slice(0, -2)) +
			parseInt(e.clientY - mouse.Y) +
			'px';
		mouse.X = e.clientX;
		mouse.Y = e.clientY;
	}

	// To select the items when mouse moves over them and mouse button is pressed
	if (mouse.isSelecting) {
		let sb = $('.select-box').style;
		let mc = $('.main-container');

		// Create Selector Box when mouse moves horizontally
		if (e.clientX - mc.offsetLeft + parseInt(mc.scrollLeft) > mouse.posX) {
			sb.left = mouse.posX + 'px';
			sb.width =
				e.clientX - mc.offsetLeft + parseInt(mc.scrollLeft) - mouse.posX + 'px';
		} else {
			sb.left = e.clientX - mc.offsetLeft + parseInt(mc.scrollLeft) + 'px';
			sb.width =
				mouse.posX -
				(e.clientX - mc.offsetLeft + parseInt(mc.scrollLeft)) +
				'px';
		}

		// Create Selector Box when mouse moves verically
		if (e.clientY - mc.offsetTop + parseInt(mc.scrollTop) > mouse.posY) {
			sb.height =
				e.clientY -
				mc.offsetTop +
				parseInt(mc.scrollTop) -
				mouse.posY +
				4 +
				'px';
			sb.top = mouse.posY + 'px';
		} else {
			sb.top = e.clientY - mc.offsetTop + parseInt(mc.scrollTop) + 4 + 'px';
			sb.height =
				mouse.posY - (e.clientY - mc.offsetTop + parseInt(mc.scrollTop)) + 'px';
		}

		// To reset the previous selections
		if (e.clientY > 104 && !e.ctrlKey) {
			$$('.list-group-item.selected').forEach(item => {
				item.className = 'list-group-item';
			});
		}
		if (mc.scrollHeight > mc.offsetHeight) {
			if (
				window.innerHeight - e.clientY < 40 &&
				window.innerHeight - e.clientY > 1
			) {
				$('.main-container').scrollTop = $('.main-container').scrollTop + 40;
			} else if (e.clientY - mc.offsetTop < 40) {
				$('.main-container').scrollTop = $('.main-container').scrollTop - 40;
			}
		}

		// To select the items which are under the selector box
		if (sessionStorage['view'] == 'grid') {
			let maxHorizontalItems = Math.floor(
				$('.list-group').offsetWidth / $('.list-group-item').offsetWidth
			);
			let x1 = Math.floor(
				sb.left.slice(0, -2) / $('.list-group-item').offsetWidth
			);
			if (x1 < 0) x1 = 0;
			let x2 = Math.floor(
				(parseInt(sb.left.slice(0, -2)) + parseInt(sb.width.slice(0, -2))) /
					$('.list-group-item').offsetWidth
			);
			if (x2 >= maxHorizontalItems) x2 = maxHorizontalItems - 1;
			let y1 = Math.floor(
				sb.top.slice(0, -2) / ($('.list-group-item').offsetHeight + 4)
			);
			if (y1 < 0) y1 = 0;
			let y2 = Math.floor(
				(parseInt(sb.top.slice(0, -2)) + parseInt(sb.height.slice(0, -2))) /
					($('.list-group-item').offsetHeight + 4)
			);
			for (let i = y1; i <= y2; i++) {
				for (let j = x1; j <= x2; j++) {
					if ($$('.list-group-item')[i * maxHorizontalItems + j])
						$$('.list-group-item')[i * maxHorizontalItems + j].classList.add(
							'selected'
						);
				}
			}
		} else {
			let start = Math.floor(
				parseInt(sb.top.slice(0, -2)) / $('.list-group-item').offsetHeight
			);
			if (start < 0) start = 0;
			let end = Math.floor(
				(parseInt(sb.top.slice(0, -2)) + parseInt(sb.height.slice(0, -2))) /
					$('.list-group-item').offsetHeight
			);
			if (end >= $$('.list-group-item').length)
				end = $$('.list-group-item').length - 1;
			for (let i = start; i <= end; i++) {
				$$('.list-group-item')[i].classList.add('selected');
			}
		}
	}
};

// To select an item when clicked on it
window.onclick = function(e) {
	if (isContextMenuOpen) {
		closeContextMenu();
	}

	if (e.button == 0 || e.button == 1) {
		// To select item when user clicks on list item
		if (
			e.target.className == 'list-group-item' ||
			e.target.className == 'list-group-item selected'
		) {
			if (e.ctrlKey == false) {
				$$('.list-group-item.selected').forEach(function(item) {
					if (item == e.target) return;
					item.className = 'list-group-item';
				});
				e.target.className = 'list-group-item selected';
			} else {
				if (e.target.className == 'list-group-item')
					e.target.className = 'list-group-item selected';
				else e.target.className = 'list-group-item';
			}
			return false;
		}

		// To select item when user clicks on span-elements or icons in list item
		else if (
			e.target.parentElement &&
			(e.target.parentElement.className == 'list-group-item' ||
				e.target.parentElement.className == 'list-group-item selected')
		) {
			if (e.ctrlKey == false) {
				$$('.list-group-item.selected').forEach(function(item) {
					if (item == e.target.parentElement) return;
					item.className = 'list-group-item';
				});
				e.target.parentElement.className = 'list-group-item selected';
			} else {
				if (e.target.parentElement.className == 'list-group-item')
					e.target.parentElement.className = 'list-group-item selected';
				else e.target.parentElement.className = 'list-group-item';
			}
			return false;
		}

		// To remove selection of item when clicked on empty area
		else if (e.target == $('.main-container') || e.target == $('.list-group')) {
			$$('.list-group-item.selected').forEach(function(item) {
				item.className = 'list-group-item';
			});
		}
	}
};

// To open an item(directory) when double clicked on it
window.ondblclick = function(e) {
	// Open directory when double clicked on list item
	if (
		e.target.className == 'list-group-item' ||
		e.target.className == 'list-group-item selected'
	) {
		if (e.target.getAttribute('data-type') == 'file') {
			editFile();
		} else if (!e.target.href.endsWith('#')) {
			window.location.href = e.target.href;
			$('.list-group').scrollTop = 0;
		}
	}

	// Open directory when double clicked on span-elements or icons in list item
	else if (
		e.target.parentElement.className == 'list-group-item' ||
		e.target.parentElement.className == 'list-group-item selected'
	) {
		if (
			e.target.parentElement.getAttribute('data-type') == 'insert_drive_file'
		) {
			editFile();
		} else if (!e.target.parentElement.href.endsWith('#')) {
			window.location.href = e.target.parentElement.href;
			$('.list-group').scrollTop = 0;
		}
	}
};

// SPLITTER CODE
//--------------
$('.splitter').addEventListener('mousedown', e => {
	mouse.onSplitter = true;
});

window.addEventListener('mouseup', e => {
	mouse.onSplitter = false;
});

window.addEventListener('mousemove', e => {
	// To move the splitter when user holds & drags it
	if (mouse.onSplitter) {
		if ((e.clientX / window.innerWidth) * 100 < 75) {
			$('.file-tree').style.width = (e.clientX / window.innerWidth) * 100 + '%';
			$('.list-group-section').style.width =
				((window.innerWidth - e.clientX - 10) / window.innerWidth) * 100 + '%';
		}
	}
});

/*--------------------------------------------------------------------------------------
#                               KEYBOARD EVENTS                                        #
--------------------------------------------------------------------------------------*/

// To show more options only if an item is selected
window.onkeyup = function() {
	toggleMoreOptions();
};

// Actions to be performed when a key is pressed
window.onkeydown = function(key) {
	if ($('.modal-container').style.display !== 'flex') {
		isModalOpen = false;
	}

	if (isDropdownOpen) {
		closeDropdown();
	}

	if (isContextMenuOpen) {
		closeContextMenu();
	}

	if (key.code == 'Escape') {
		closeModal();
		return false;
	}

	// To disable Backspace Key when a modal is open and also when the user is not typing in an input field
	if (
		isModalOpen &&
		document.activeElement.tagName != 'INPUT' &&
		document.activeElement.tagName != 'TEXTAREA'
	) {
		if (key.code == 'Backspace') {
			return false;
		}
	}

	if (isModalOpen && $('.modal-head').classList.contains('editor')) {
		if (key.ctrlKey && key.code == 'KeyS') {
			saveFile();
			key.preventDefault();
		}
	}

	// To check that keybindings only work when the modal is not opened
	if (!isModalOpen) {
		// Goto previous directory when Backspace key is pressed
		if (key.code == 'Backspace') {
			scanPath('../');
		}

		// F1 shortcut for creating a new folder
		if (key.code == 'F1' && !key.ctrlKey) {
			showModal('createFolder');
		}

		// F3 shortcut for creating a new file
		if (key.code == 'F3') {
			showModal('createFile');
		}

		// F2 shortcut for rename when one item is selected
		if (key.code == 'F2' && countSelected() == 1) {
			showModal('renameItem');
		}

		// To select all items when Ctrl + A is pressed
		if (key.ctrlKey && key.code == 'KeyA') {
			selectAll();
		}

		// To copy all items when Ctrl + C is pressed
		if (key.ctrlKey && key.code == 'KeyC' && countSelected()) {
			copyItem();
		}

		// To cut all items when Ctrl + X is pressed
		if (key.ctrlKey && key.code == 'KeyX' && countSelected()) {
			cutItem();
		}

		// To paste all items when Ctrl + V is pressed
		if (key.ctrlKey && key.code == 'KeyV') {
			pasteItem();
		}

		//Select upper item from current selected item, reset all other selections, and move/scroll to the selected item
		if (key.code == 'ArrowDown') {
			if (!countSelected()) {
				$('.list-group-item').className = 'list-group-item selected';
			} else {
				let lastChildNum = countSelected() - 1;
				let childNum = getChildNum(
					$$('.list-group-item.selected')[lastChildNum]
				);
				if (childNum == $$('.list-group-item').length - 1) return false;
				if (key.shiftKey) {
					$$('.list-group-item')[childNum + 1].className =
						'list-group-item selected';
					$('.main-container').scrollTop =
						(childNum -
							Math.floor($('.main-container').offsetHeight / 44) +
							2) *
						44;
				} else {
					$$('.list-group-item.selected').forEach(function(item) {
						item.className = 'list-group-item';
					});
					$$('.list-group-item')[childNum + 1].className =
						'list-group-item selected';
					$('.list-group-item.selected').scrollIntoView();
				}
			}
		}

		//Select lower item from current selected item, reset all other selections, and move/scroll to the selected item
		if (key.code == 'ArrowUp') {
			if (!countSelected()) {
				$$('.list-group-item')[$$('.list-group-item').length - 1].className =
					'list-group-item selected';
			} else {
				let childNum = getChildNum($('.list-group-item.selected'));
				if (!childNum) return false;
				if (key.shiftKey) {
					$$('.list-group-item')[childNum - 1].className =
						'list-group-item selected';
					$$('.list-group-item')[childNum - 1].scrollIntoView();
				} else {
					$$('.list-group-item.selected').forEach(function(item) {
						item.className = 'list-group-item';
					});
					$$('.list-group-item')[childNum - 1].className =
						'list-group-item selected';
				}
			}
			$('.list-group-item.selected').scrollIntoView();
		}

		// Open the selected item (directory)
		if (key.code == 'Enter') {
			if (countSelected() == 1) {
				if (
					$('.list-group-item.selected').getAttribute('data-type') == 'file'
				) {
					editFile();
				} else {
					window.location.href = $('.list-group-item.selected').href;
					$('.list-group').scrollTop = 0;
				}
			}
		}

		// To delete the selected items
		if (key.code == 'Delete' && countSelected()) {
			showModal('deleteItem');
		}

		// To move/scroll to item that starts with the alphabetic key pressed
		if (
			key.code.includes('Key') &&
			!key.ctrlKey &&
			!key.shiftKey &&
			!key.altKey
		) {
			let childNum;
			if (countSelected() == 0) childNum = null;
			else childNum = getChildNum($('.list-group-item.selected'));
			if (childNum == null || childNum == $$('.list-group-item').length - 1)
				childNum = -1;
			let counter = 0;
			for (let i = childNum + 1; i < $$('.list-group-item').length; i++) {
				if (
					$$('.list-group-item')
						[i].getAttribute('data-name')
						.toLowerCase()
						.charAt(0) == key.code.charAt(3).toLowerCase()
				) {
					$$('.list-group-item.selected').forEach(function(item) {
						item.className = 'list-group-item';
					});
					$$('.list-group-item')[i].className = 'list-group-item selected';
					break;
				}
				counter++;
				if (counter == $$('.list-group-item').length) break;
				else if (i == $$('.list-group-item').length - 1) {
					i = -1;
					continue;
				}
			}
			$('.list-group-item.selected').scrollIntoView();
		}
		if (key.code == 'F5' || key.code == 'F12') return true;
		else return false;
	}
};

/*--------------------------------------------------------------------------------------
#                              AJAX CALL FUNCTIONS                                     #
--------------------------------------------------------------------------------------*/

// To call PHP to scan the items in selected directory
function scanPath(dirSelected, newItemName) {
	showLoader();
	ajax('scanPath=true&dirSelected=' + encodeURIComponent(dirSelected), function(
		obj
	) {
		if (obj.response == '') {
			hideLoader();
			showModal('accessDeniedAlert');
			return false;
		}
		sortedByName = true;
		$('#contentArea').innerHTML = obj.response;
		hideLoader();
		createBreadCrumb();
		if (newItemName) {
			selectItem(newItemName);
		}
		toggleMoreOptions();
		if (countSelected() == 1) $('.list-group-item.selected').scrollIntoView();
	});
}

// To create/update the breadcrumb
function createBreadCrumb() {
	ajax('createBreadCrumb=true', function(obj) {
		$('#breadcrumb').innerHTML = obj.response;
		createTree();
	});
}

// To create the tree of directories
function createTree() {
	ajax('createTree=true&path=null', function(obj) {
		let response = obj.responseText;
		response = response.replace(
			/&0;/g,
			"<i class='material-icons' style='color: #90a4ae'>folder</i>"
		);
		response = response.replace(
			/&1;/g,
			"<li class='tree-item'><i onclick='expandTree(this)' class='material-icons' style='transform:rotate(-90deg)'>arrow_drop_down</i>"
		);
		response = response.replace(
			/&2;/g,
			"<li class='tree-item opened'><i onclick='expandTree(this)' class='material-icons'>arrow_drop_down</i>"
		);
		response = response.replace(
			/&3;/g,
			'<a href="javascript:navigateToPath(\''
		);
		$('.file-tree').innerHTML = response;
		$('.tree-item.current').scrollIntoView({ behavior: 'smooth' });
	});
}

// To add events on the updated tree
function expandTree(el) {
	if (el.parentElement.nextElementSibling) {
		let tree = el.parentElement.nextElementSibling;
		if (
			tree.tagName == 'UL' &&
			(tree.style.display == '' || tree.style.display == 'block')
		) {
			tree.style.display = 'none';
			el.style.transform = 'rotate(-90deg)';
			el.innerHTML = 'arrow_drop_down';
		} else if (tree.tagName == 'UL' && tree.style.display == 'none') {
			tree.style.display = 'block';
			el.style.transform = 'rotate(0deg)';
			el.innerHTML = 'arrow_drop_down';
		} else if (tree.tagName == 'LI') {
			listDirectories(
				el.nextElementSibling.href.slice(27, -2),
				el.parentElement
			);
		}
	}
	if (!el.parentElement.nextElementSibling) {
		listDirectories(el.nextElementSibling.href.slice(27, -2), el.parentElement);
	}
}

// To get the list of directories
function listDirectories(path, position) {
	ajax('listDirectories=true&path=' + encodeURIComponent(path), function(obj) {
		if (obj.response == '') {
			position.firstChild.style.opacity = 0;
			position.firstChild.removeAttribute('onclick');
		} else if (obj.response == 'false') {
			showModal('accessDeniedAlert');
		} else {
			position.firstChild.innerHTML = 'arrow_drop_down';
			position.firstChild.style.transform = 'rotate(0deg)';
			position.outerHTML += obj.response;
		}
	});
}

// To move to the specific path when clicked on a Breadcrumb item
function navigateToPath(pathSelected) {
	showLoader();
	ajax('navToPath=true&pathSelected=' + pathSelected, function(obj) {
		if (obj.response == '') {
			hideLoader();
			showModal('accessDeniedAlert');
			return false;
		}
		$('#contentArea').innerHTML = obj.responseText;
		hideLoader();
		createBreadCrumb();
	});
}

// To create a New Folder
function createFolder() {
	ajax(
		'createFolder=true&folderName=' +
			encodeURIComponent($('#folderName').value.trimEnd()),
		function(obj) {
			scanPath('./', obj.response);
			closeModal();
			showNotification('New Folder Created!');
		}
	);
}

// To create a new empty file
function createFile() {
	ajax(
		'createFile=true&fileName=' +
			encodeURIComponent($('#fileName').value.trimEnd()),
		function(obj) {
			scanPath('./', obj.response);
			closeModal();
			showNotification('New File Created!');
		}
	);
}

// To edit a file
function editFile() {
	ajax(
		'editFile=true&fileName=' +
			encodeURIComponent(
				$('.list-group-item.selected').getAttribute('data-name')
			),
		function(obj) {
			window.open('pages/editor/' + obj.response);
		}
	);
}

// To delete the selected items
function deleteItem() {
	closeModal();
	showLoader();
	let data = collectData();
	ajax('deleteItem=true&length=' + countSelected() + '&' + data, function(obj) {
		scanPath('./');
		closeModal();
		showNotification(countSelected() + ' Item(s) Deleted!');
		hideLoader();
	});
}

// To copy the selected items
function copyItem() {
	let data = collectData();
	ajax('copyItem=true&length=' + countSelected() + '&' + data, function() {});
	$('#pasteButton').style.display = 'inline-block';
	$('.context-menu .paste-btn').style.display = 'block';
	showNotification(countSelected() + ' Item(s) Copied!');
}

// To cut the selected items
function cutItem() {
	let data = collectData();
	$$('.list-group-item.selected').forEach(el => {
		el.className = 'list-group-item selected cut';
	});
	ajax('cutItem=true&length=' + countSelected() + '&' + data, function() {});
	$('#pasteButton').style.display = 'block';
	$('.context-menu .paste-btn').style.display = 'block';
	showNotification(countSelected() + ' Item(s) Cut!');
}

// To paste the copied/cut items
function pasteItem() {
	if ($('#pasteButton').style.display == 'none') return false;
	showLoader();
	$('#pasteButton').style.display = 'none';
	$('.context-menu-item.paste-btn').style.display = 'none';
	ajax('pasteItem=true', function(obj) {
		scanPath('./');
		hideLoader();
		if (obj.response.endsWith('999'))
			showNotification("The folder can't be placed inside itself.");
		else showNotification(obj.response + ' Item(s) Pasted!');
	});
}

// To rename the selected item
function renameItem() {
	let data =
		'renameItem=true&oldName=' +
		encodeURIComponent(
			$('.list-group-item.selected').getAttribute('data-name')
		) +
		'&newName=' +
		encodeURIComponent($('#newItemName').value.trimEnd());
	ajax(data, function(obj) {
		scanPath('./', obj.response);
		closeModal();
		if (obj.response == 'false')
			showNotification('Item already exists with same name!');
		else showNotification('Item Renamed!');
	});
}

// To upload a folder
function uploadFolder() {
	let files = $('#folderUpload').files;
	let data = new FormData();
	for (let key in filePaths) {
		data.append(key, filePaths[key]);
	}
	data.append('filesCount', filePaths.length);
	data.append('createDirs', true);
	ajax(
		data,
		obj => {
			if (obj.response != '') {
				uploadItems(files, 0, true, obj.response, filePaths.root);
			} else {
				uploadItems(files, 0, true);
			}
			filePaths = {};
		},
		false
	);
}

// To upload items from PC to current directory
function uploadItems(
	files,
	filesDone,
	isFolder = false,
	newName = false,
	rootFolder = false
) {
	let fileToBeUploaded = files[filesDone];
	let chunkSize = 1024 * 1024 * 1;
	let start = 0;
	let end = chunkSize;
	if (filesDone < files.length) {
		createSlices(0, fileToBeUploaded.name);
	} else if (filesDone == files.length) {
		scanPath('./');
		$('.modal-head span').innerHTML = 'Completed!';
		$('#fileProgress').style.display = 'none';
		showNotification(files.length + ' File(s) Uploaded!');
	}
	// Slice the file
	function createSlices(sliceDone, fileName) {
		if (cancelUpload == true) {
			cancelUpload = false;
			if (isFolder) {
				ajax(
					'removeUploadedFile=true&name=' +
						encodeURIComponent(fileToBeUploaded.webkitRelativePath),
					() => {}
				);
			} else {
				ajax(
					'removeUploadedFile=true&name=' +
						encodeURIComponent(fileToBeUploaded.name),
					() => {}
				);
			}
			scanPath('./');
			closeModal();
			showNotification('Upload Cancelled!');
			return function() {
				return false;
			};
		}
		let slicesDone = sliceDone;
		let slicesCount = Math.ceil(fileToBeUploaded.size / chunkSize);
		let fileData = new Blob([fileToBeUploaded]);
		let fileChunk = fileData.slice(start, end);
		start = end;
		end = end + chunkSize;
		let reader = new FileReader();
		reader.onloadend = function() {
			let arrayBuffer = new Uint8Array(reader.result);
			let sliceData = '';
			for (let i = 0; i < arrayBuffer.length; i++) {
				sliceData += String.fromCharCode(arrayBuffer[i]);
			}
			if (sliceDone < slicesCount) sendSlice(sliceData);
			else if (sliceDone == slicesCount) {
				filesDone++;
				if (isFolder) {
					if (newName && rootFolder) {
						uploadItems(files, filesDone, true, newName, rootFolder);
					} else {
						uploadItems(files, filesDone, true);
					}
				} else {
					uploadItems(files, filesDone);
				}
			}
		};
		reader.readAsArrayBuffer(fileChunk);
		// Upload the slice
		function sendSlice(slice) {
			let data = new FormData();
			if (isFolder) {
				if (newName && rootFolder) {
					let fileName =
						newName +
						fileToBeUploaded.webkitRelativePath.slice(rootFolder.length);
					data.append('fileName', fileName);
				} else {
					data.append('fileName', fileToBeUploaded.webkitRelativePath);
				}
			} else {
				data.append('fileName', fileName);
			}
			data.append('sliceData', btoa(slice));
			data.append('uploadItem', true);
			if (start == chunkSize) {
				data.append('isFirstSlice', true);
			}
			ajax(
				data,
				function(obj) {
					slicesDone++;
					totalDone += fileChunk.size;
					updateProgress(
						slicesDone,
						slicesCount,
						fileToBeUploaded.name,
						filesDone,
						files.length,
						(totalDone / totalSize) * 100
					);
					if (obj.response != '') {
						createSlices(slicesDone, obj.response);
					} else {
						createSlices(slicesDone, fileName);
					}
				},
				false
			);
		}
	}
}

// To create a zip archive of the selected files
function createZip() {
	closeModal();
	showLoader();
	let data = collectData();
	ajax(
		'createZip=true&length=' +
			countSelected() +
			'&zipName=' +
			encodeURIComponent($('#zipName').value) +
			'&' +
			data,
		function(obj) {
			scanPath('./', obj.response);
			hideLoader();
			showNotification('ZIP file created!');
		}
	);
}

// To extract the contents of selected zip file
function extractZip() {
	showLoader();
	ajax(
		'extractZip=true&zipName=' +
			encodeURIComponent(
				$('.list-group-item.selected').getAttribute('data-name')
			),
		function() {
			scanPath('./');
			hideLoader();
			showNotification('ZIP file extracted!');
		}
	);
}

// To make archive of selected items and download them
function downloadFiles() {
	showLoader();
	let data = collectData();
	ajax('downloadFiles=true&length=' + countSelected() + '&' + data, function(
		obj
	) {
		scanPath('./');
		hideLoader();
		window.location.href = './pages/download.php';
	});
}

// To get contents of selected image and open it
function openImage(imageName) {
	showImageViewer();
	ajax('openImage=true&imageName=' + encodeURIComponent(imageName), function(
		obj
	) {
		$('.image').src = 'pages/' + obj.response;
	});
}

/*--------------------------------------------------------------------------------------
#                           USER-DEFINED FUNCTIONS                                     #
--------------------------------------------------------------------------------------*/

// Short Notation for document.querySelector()
function $(el) {
	return document.querySelector(el);
}

// Short notation for document.querySelectorAll()
function $$(el) {
	return document.querySelectorAll(el);
}

// To send data to PHP through AJAX
function ajax(data, result, sendHeaders = true) {
	let xhr = new XMLHttpRequest();
	xhr.open('POST', 'pages/main.php', true);
	if (sendHeaders)
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) result(xhr);
	};
	xhr.send(data);
}

// To show the CSS loader
function showLoader() {
	isModalOpen = true;
	$('.loader-container').style.display = 'flex';
	$('.list-group').style.display = 'none';
}

// To hide the CSS loader
function hideLoader() {
	isModalOpen = false;
	$('.loader-container').style.display = 'none';
	$('.list-group').style.display = 'block';
}

// To make a query of selected items
function collectData() {
	let data = '';
	let elements = $$('.list-group-item.selected');
	for (let i = 0; i < elements.length; i++) {
		data +=
			'itemNo' +
			i +
			'=' +
			encodeURIComponent(elements[i].getAttribute('data-name')) +
			'&';
	}
	return data.slice(0, -1);
}

// To find the number of items selected
function countSelected() {
	return $$('.list-group-item.selected').length;
}

// To select all the items
function selectAll() {
	$$('.list-group-item').forEach(el => {
		el.className = 'list-group-item selected';
	});
}

// To count & collect information about the items to be uploaded (Only for Uploading a Folder)
function prepareFolderUpload(e) {
	filePaths = {};
	totalSize = totalDone = 0;
	cancelUpload = false;
	$('.custom-file-upload').innerHTML = e.files.length + ' file(s)';
	let files = e.files;
	filePaths['root'] = files[0].webkitRelativePath.split('/')[0];
	for (let i = 0, counter = 0; i < files.length; i++) {
		totalSize += files[i].size;
		if (
			!isInObject(
				files[i].webkitRelativePath.slice(0, -files[i].name.length),
				filePaths,
				counter
			)
		) {
			filePaths[counter] = files[i].webkitRelativePath;
			filePaths[counter] = filePaths[counter].slice(0, -files[i].name.length);
			counter++;
		}
		if (i == files.length - 1) filePaths['length'] = counter;
	}
	$('.upload-btn').disabled = false;
	$('.upload-btn').focus();
}

// Prepare File uploading
function prepareUpload(e) {
	totalSize = totalDone = 0;
	cancelUpload = false;
	$('.custom-file-upload').innerHTML = e.files.length + ' file(s)';
	let files = e.files;
	for (let i = 0; i < files.length; i++) {
		totalSize += files[i].size;
	}
	$('.upload-btn').disabled = false;
	$('.upload-btn').focus();
}

//To update the upload progress bar
function updateProgress(
	progressDone,
	totalProgress,
	fileName,
	filesDone,
	totalFiles,
	totalDone
) {
	// $("#fileProgress").innerHTML =
	// Math.floor((progressDone / totalProgress) * 100) + "%";
	$('#filesCount').innerHTML = filesDone + 1 + '/' + totalFiles;
	$('#uploadingFileName').innerHTML = fileName;
	$('.progress-bar-fill').style.width = totalDone + '%';
	$('.modal-head span').innerHTML = 'Uploading ' + Math.floor(totalDone) + '%';
}

// To find the index of a child
function getChildNum(el) {
	if (el == null) return false;
	let i;
	for (i = 0; i < el.parentElement.children.length; i++) {
		if (el == el.parentElement.children[i]) break;
	}
	return i;
}

// To check if an item exists in Array
function isInArray(item, itemArray) {
	let flag = false;
	for (let i = 0; i < itemArray.length; i++) {
		if (itemArray[i] == item) {
			flag = true;
			break;
		}
	}
	return flag;
}

// To check if an value exists in Object
function isInObject(value, object, length) {
	let flag = false;
	for (let i = 0; i <= length; i++) {
		if (object[i] == value) {
			flag = true;
			break;
		}
	}
	return flag;
}

// To show the dropdown menus
function showDropdown(el) {
	$$('.dropdown-item-container').forEach(e => {
		e.style.display = 'none';
	});
	let dropdown = el.nextElementSibling;
	dropdown.style.display = 'block';
	if (dropdown.querySelectorAll('.dropdown-item').length < 3) {
		dropdown.style.width = '146px';
		dropdown.style.left = '-55px';
	} else {
		dropdown.style.width = '209px';
		dropdown.style.left = '-85px';
	}
	isDropdownOpen = true;
}

// To close all the dropdown menus
function closeDropdown() {
	$$('.dropdown-item-container').forEach(el => {
		el.style.display = 'none';
	});
	isDropdownOpen = false;
}

// To close the context menu
function closeContextMenu() {
	$('.context-menu').style.display = 'none';
	isContextMenuOpen = false;
}

// To toggle more options
function toggleMoreOptions() {
	$('#moreOptions').style.display =
		countSelected() > 0 ? 'inline-block' : 'none';
	$('#renameButton').style.display =
		countSelected() == 1 ? 'inline-block' : 'none';
	$('#editButton').style.display =
		countSelected() == 1 &&
		$('.list-group-item.selected').getAttribute('data-type') ==
			'insert_drive_file'
			? 'inline-block'
			: 'none';
	$('#extractButton').style.display =
		countSelected() == 1 &&
		$('.list-group-item.selected').getAttribute('data-ext') == 'zip'
			? 'inline-block'
			: 'none';
}

// To show a notification message & auto hide it after 3s
function showNotification(message) {
	if (timeout) clearTimeout(timeout);
	$(
		'.notification-container'
	).innerHTML = `<div class="notification"> ${message} </div>`;
	setTimeout(() => {
		$('.notification-container').className += ' fade';
	}, 100);
	$('.notification').addEventListener('click', el => {
		$('.notification-container').className = 'notification-container';
		setTimeout(() => {
			$('.notification').remove();
			clearTimeout(timeout);
		}, 100);
	});
	timeout = setTimeout(() => {
		$('.notification-container').className = 'notification-container';
		setTimeout(() => {
			$('.notification').remove();
		}, 100);
	}, 3000);
}

// To show/open a Modal
function showModal(modalType) {
	$('.modal-container').style.display = 'flex';
	(function() {
		setTimeout(() => {
			$('.modal-window').className = 'modal-window fade';
		}, 100);
	})();
	$('.modal').className = 'modal';

	// Creating New Folder
	if (modalType == 'createFolder') {
		$('.modal-head span').innerHTML = 'Enter Name';
		$('.modal').innerHTML = `
    <form onsubmit="createFolder()">
    <span> Folder Name : </span>
    <div class="textbox-container">
      <input type="text" id="folderName" class="textbox" required oninput="validateName(this)" value="New Folder">
      <span class="textbox-focused"></span>
    </div>
    <button type="submit" class="btn btn-primary"> Create </button>
    </form>
    `;
		$('#folderName').focus();
		$('#folderName').selectionStart = 0;
		$('#folderName').selectionEnd = 10;
	}

	// Creating New File
	else if (modalType == 'createFile') {
		$('.modal-head span').innerHTML = 'Enter Name';
		$('.modal').innerHTML = `
    <form onsubmit="createFile()">
    <span> File Name : </span>
    <div class="textbox-container">
      <input type="text" id="fileName" class="textbox" required oninput="validateName(this)" value="New File.txt">
      <span class="textbox-focused"></span>
    </div>
    <button type="submit" class="btn btn-primary"> Create </button>
    </form>
    `;
		$('#fileName').focus();
		$('#fileName').selectionStart = 0;
		$('#fileName').selectionEnd = 8;
	}

	// Deleting an item
	else if (modalType == 'deleteItem') {
		$('.modal-head span').innerHTML = 'Confirm';
		$('.modal').innerHTML = `
    <form onsubmit="deleteItem()">
      <span> Are you sure to Delete ${countSelected()} items? </span>
      <div class="btn-group">
        <button type="submit" class="btn btn-primary"> Yes </button>
        <button type="reset" class="btn btn-secondary" onclick="closeModal()"> No </button>
      </div>
    <form>`;
		$('button[type=submit]').focus();
	}

	// Uploading Folder
	else if (modalType == 'uploadFolder') {
		$('.modal-head span').innerHTML = 'Upload Folder';
		$('.modal').innerHTML = `
      <span> Select files : </span>
        <div class="file-upload-container">
          <input type="file" name="uploadFolder" multiple class='file-upload' id="folderUpload" onchange="prepareFolderUpload(this)" mozdirectory directory webkitdirectory required>
          <label class="custom-file-upload" for="folderUpload">Choose Folder</label>
          <button type="submit" class="btn btn-primary upload-btn" disabled onclick="uploadFolder()"> Upload </button>
        </div>
    `;
		$('.upload-btn').addEventListener('click', () => {
			showModal('uploadProgress');
		});
	}

	// Uploading Files
	else if (modalType == 'uploadItem') {
		$('.modal-head span').innerHTML = 'Upload Files';
		$('.modal').innerHTML = `
      <span> Select files : </span>
        <div class="file-upload-container">
          <input type="file" name="uploadItem" multiple class='file-upload' id="fileUpload" required onchange="prepareUpload(this)">
          <label class="custom-file-upload" for="fileUpload">Choose Files</label>
          <button type="submit" class="btn btn-primary upload-btn" disabled onclick="uploadItems($('#fileUpload').files, 0)"> Upload </button>
        </div>
    `;
		$('.upload-btn').addEventListener('click', () => {
			showModal('uploadProgress');
		});
	}

	// To rename an item
	else if (modalType == 'renameItem') {
		$('.modal-head span').innerHTML = 'Rename';
		$('.modal').innerHTML = `
    <form onsubmit="renameItem()">
      <span> Enter a new name : </span>
      <div class="textbox-container">
        <input type="text" id="newItemName" class="textbox" required  oninput="validateName(this)">
        <span class="textbox-focused"></span>
      </div>
      <button type="submit" class="btn btn-primary"> Apply </button>
    </form>`;
		$('#newItemName').value = $('.list-group-item.selected').getAttribute(
			'data-name'
		);
		while ($('#newItemName').value.search('&&39') != -1) {
			$('#newItemName').value = $('#newItemName').value.replace('&&39', "'");
		}
		$('#newItemName').focus();
		$('#newItemName').selectionStart = 0;
		$('#newItemName').selectionEnd = $('#newItemName').value.length;
	}

	// Creating ZIP Archive
	else if (modalType == 'createZip') {
		$('.modal-head span').innerHTML = 'Create Zip';
		$('.modal').innerHTML = `
    <form onsubmit="createZip()">
      <span> Enter zipfile name : </span>
      <div class="textbox-container">
        <input type="text" id="zipName" class="textbox" required  oninput="validateName(this)" value="my-files">
        <span class="textbox-focused"></span>
      </div>
      <button type="submit" class="btn btn-primary"> Create </button>
    </form>`;
		$('#zipName').focus();
		$('#zipName').selectionStart = 0;
		$('#zipName').selectionEnd = 8;
	}

	// Show Uploading Progress bar
	else if (modalType == 'uploadProgress') {
		$('.modal-head span').innerHTML = 'Uploading';
		$('.modal').innerHTML = `
      <div class='flex space-between'>
        <div class="flex">
          <span id="uploadingFileName" style="max-width:200px">Calculating...</span>
          <span id="fileProgress" class="file-progress"> </span>
        </div>
        <span id="filesCount"> </span>
      </div>
      <div class="progress-bar">
        <div class="progress-bar-fill">  </div>
      </div>`;
	}

	// For alert message
	else if (modalType == 'accessDeniedAlert') {
		$('.modal-head span').innerHTML = 'Access Denied';
		$('.modal').innerHTML = `<span style='padding:5px 0px'></span>
    <span> Cannot access this folder! </span>
    </form>`;
	}

	isModalOpen = true;
	if ($('form'))
		$('form').addEventListener('submit', e => {
			e.preventDefault();
		});
}

// To close any open Modal
function closeModal() {
	isModalOpen = false;
	$('.modal-window').className = 'modal-window';
	setTimeout(() => {
		$('.modal-container').style.display = 'none';
		$('.modal-container').classList.remove('maximized');
		$('.modal').classList.remove('p-0');
	}, 200);
}

// To validate the filename entered in textbox
function validateName(el) {
	let counter = 0;
	while (counter < el.value.length) {
		['/', '\\', '*', '?', '<', '>', '|', '"', ':'].forEach(char => {
			if (el.value.indexOf(char) != -1) {
				el.value = el.value.replace(char, '');
			}
		});
		counter++;
	}
}

// To show the image viewer
function showImageViewer() {
	isImageViewerOpen = true;
	$('.image-viewer').style.display = 'block';
	$('.image-container').innerHTML = `
  <img src="" class='image' />
  `;
	$('.image-container').style.left = $('.image-container').style.top = '0px';
	let scale = 1.0;
	$('.image-container').addEventListener('wheel', e => {
		scale += e.deltaY > 0 ? -0.05 : 0.05;
		if (scale < 0) scale = 0.0;
		$('.image').style.transform = 'scale(' + scale + ')';
	});
}

// To hide the image viewer
function closeImageViewer() {
	$('.image-viewer').style.display = 'none';
	isImageViewerOpen = false;
	ajax('deleteImage=true', function() {});
}

// To change the view of files
function changeView(view) {
	// List View
	if (view == 'list') {
		$('.list-group-head').style.display = 'block';
		$('.list-group-head').className = 'list-group-head';
		$('.list-group').className = 'list-group';
		$('.view-icon').innerHTML = 'view_list';
		$('.main-container').style.height = window.innerHeight - 104 + 'px';
		sessionStorage['view'] = 'list';
	}

	// Grid View
	else if (view == 'grid') {
		$('.list-group-head').style.display = 'none';
		$('.list-group-head').className = 'list-group-head';
		$('.list-group').className = 'list-group grid';
		$('.view-icon').innerHTML = 'apps';
		$('.main-container').style.height = window.innerHeight - 60 + 'px';
		sessionStorage['view'] = 'grid';
	}

	// Details View
	else if (view == 'details') {
		$('.list-group-head').style.display = 'block';
		$('.list-group-head').className = 'list-group-head details';
		$('.list-group').className = 'list-group details';
		$('.view-icon').innerHTML = 'menu';
		$('.main-container').style.height = window.innerHeight - 104 + 'px';
		sessionStorage['view'] = 'details';
	}
}

// To change the view if it is set, on page load
(function() {
	if (sessionStorage['view']) {
		changeView(sessionStorage['view']);
	}
})();

// To select the item having specified name
function selectItem(name) {
	for (let i = 0; i < $$('.list-group-item').length; i++) {
		if ($$('.list-group-item span.name')[i].innerHTML == name) {
			$$('.list-group-item')[i].classList.add('selected');
			break;
		}
	}
}

// To sort the contents by its name
function sortByName() {
	let temp;
	for (let i = 0; i < $$('.list-group-item').length; i++) {
		for (let j = 0; j < $$('.list-group-item').length; j++) {
			if (
				($$('.list-group-item')[i].hasAttribute('data-ext') &&
					$$('.list-group-item')[j].hasAttribute('data-ext')) ||
				(!$$('.list-group-item')[i].hasAttribute('data-ext') &&
					!$$('.list-group-item')[j].hasAttribute('data-ext'))
			) {
				if (sortedByName) {
					if (
						$$('.list-group-item')
							[i].getAttribute('data-name')
							.localeCompare(
								$$('.list-group-item')[j].getAttribute('data-name')
							) > 0
					) {
						temp = $$('.list-group-item')[i].outerHTML;
						$$('.list-group-item')[i].outerHTML = $$('.list-group-item')[
							j
						].outerHTML;
						$$('.list-group-item')[j].outerHTML = temp;
					}
				} else {
					if (
						$$('.list-group-item')
							[i].getAttribute('data-name')
							.localeCompare(
								$$('.list-group-item')[j].getAttribute('data-name')
							) < 0
					) {
						temp = $$('.list-group-item')[i].outerHTML;
						$$('.list-group-item')[i].outerHTML = $$('.list-group-item')[
							j
						].outerHTML;
						$$('.list-group-item')[j].outerHTML = temp;
					}
				}
			}
		}
	}
	sortedByName = sortedByName ? false : true;
}
