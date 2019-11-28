
var studentmenu = "";
var pagecontainer = "";
var staffmenu="";
var staffcontainer="";
var staffbutton= "";
var parentmenu="";
var parentbutton="";
var usermenu="";
var userbutton="";
function onLoad(){
	
	studentmenu=document.getElementById("studentDropdown");
	pagecontainer=document.getElementById("wrapper");
	studentbutton=document.getElementById("studentButton");
	staffmenu=document.getElementById("staffDropdown");
	staffbutton=document.getElementById("staffButton");
	parentmenu=document.getElementById("parentDropdown");
	techmenu=document.getElementById("techDropdown");
	subnavContainer=document.getElementById("subnavigationContainer");
	pageLoader=document.getElementById("pageLoaderContainer");
	parentbutton=document.getElementById("parentButton");
	usermenu=document.getElementById("userDropdown");
	userbutton=document.getElementById("userButton");
	techbutton=document.getElementById("techButton");
	
}

function showStudentDropdown(){
	
	hideStaffDropdown();
	hideParentDropdown();
	hideUserDropdown();
	hideTechDropdown();
	
	
	studentButton.classList.add("navigation_selected");
	studentbutton.onclick=hideStudentDropdown;
	pagecontainer.onclick=hideStudentDropdown;
	pageLoaderContainer.onclick=hideStudentDropdown;
	studentmenu.style.visibility = "visible";	
}


function hideStudentDropdown(){
	
	studentButton.classList.remove("navigation_selected");
	studentbutton.onclick=showStudentDropdown;

	studentmenu.style.visibility = "hidden";	
}
function showStaffDropdown(){
	hideStudentDropdown();
	hideParentDropdown();
	hideUserDropdown();
	hideTechDropdown();
	staffButton.classList.add("navigation_selected");
	staffbutton.onclick=hideStaffDropdown;
	staffmenu.style.visibility = "visible";	
	
	pagecontainer.onclick=hideStaffDropdown;
	pageLoaderContainer.onclick=hideStaffDropdown;
	
	
}


function hideStaffDropdown(){
		if (typeof(staffButton) !== 'undefined') {
			staffButton.classList.remove("navigation_selected");
			staffbutton.onclick=showStaffDropdown;
		
			staffmenu.style.visibility = "hidden";	
		}
}


function showParentDropdown(){
	hideStudentDropdown();
	hideStaffDropdown();
	hideUserDropdown();
	hideTechDropdown();
	
	parentButton.classList.add("navigation_selected");
	parentbutton.onclick=hideParentDropdown;
	pagecontainer.onclick=hideParentDropdown;

	pageLoaderContainer.onclick=hideParentDropdown;
	parentmenu.style.visibility = "visible";	
}
function hideParentDropdown(){
	if (typeof(parentButton) !== 'undefined') {
	parentButton.classList.remove("navigation_selected");
	parentbutton.onclick=showParentDropdown;

	parentmenu.style.visibility = "hidden";	
	}
}

//User Menu


function showUserDropdown(){
	hideStudentDropdown();
	hideStaffDropdown();
	hideParentDropdown();
	hideTechDropdown();
	
	userbutton.classList.add("navigation_selected");
	userbutton.onclick=hideUserDropdown;
	pagecontainer.onclick=hideUserDropdown;
	
	pageLoaderContainer.onclick=hideUserDropdown;
	usermenu.style.visibility = "visible";	
}
function hideUserDropdown(){
	
	userbutton.classList.remove("navigation_selected");
	userbutton.onclick=showUserDropdown;

	usermenu.style.visibility = "hidden";	
}

//User Menu


function showTechDropdown(){
	hideStudentDropdown();
	hideStaffDropdown();
	hideParentDropdown();
	hideUserDropdown();
	
	techbutton.classList.add("navigation_selected");
	techbutton.onclick=hideTechDropdown;
	pagecontainer.onclick=hideTechDropdown;
	
	pageLoaderContainer.onclick=hideTechDropdown;
	techmenu.style.visibility = "visible";	
}
function hideTechDropdown(){
	if (typeof(techButton) !== 'undefined') {
	techbutton.classList.remove("navigation_selected");
	techbutton.onclick=showTechDropdown;

	techmenu.style.visibility = "hidden";	
	}
}


