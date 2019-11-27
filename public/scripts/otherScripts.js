
async function getHTTPResponse(url, formData){

    

    // send it out
    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);
    xhr.send(formData);

    xhr.onload = () => {
		let response = xhr.response;
		console.log(response);
		return response;
	}
	return false;

}

async function getInstallChecklist(){
	
	var url = "/install/runChecks.php";
	 console.log(url);

    // send it out
    let xhr = new XMLHttpRequest();
    xhr.open("GET", url);
    xhr.send();

    xhr.onload = () => {
		let response = xhr.response;
		document.getElementById('container').innerHTML=response;
		console.log(response);
		
	}

	//console.log("get install checklist");
	
	//console.log(response.responseText);
	
}



async function getHTTPResponse(url){

    console.log(url);

    // send it out
    let xhr = new XMLHttpRequest();
    xhr.open("GET", url);
    xhr.send();

    xhr.onload = () => {
		let response = xhr.response;
		console.log(response);
		return response;
	}
	return false;
}

function CopyToClipboard(containerid) {
    if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(document.getElementById(containerid));
        window.getSelection().empty();
        window.getSelection().addRange(range);
        document.execCommand("Copy");
        //alert("text copied") ;
        var options = {
            settings: {
                duration: 50
            }
        };
        var toast = new iqwerty.toast.Toast("Text Copied");
    }

}
function copyThis(target) {

    console.log(target.innerHTML);
    if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(target);
        window.getSelection().empty();
        window.getSelection().addRange(range);
        document.execCommand("Copy");
        //alert("text copied") ;
        var options = {
            settings: {
                duration: 50
            }
        };
        var toast = new iqwerty.toast.Toast("Text Copied");
    }

}

function showMessege(messageText){

    document.getElementById("runText").innerHTML=messageText;
    document.getElementById("pageLoaderContainer").style="visibility:visible";


}
function show60SecondMessege(messageText){

    document.getElementById("runText60").innerHTML=messageText;
    document.getElementById("pageLoaderContainer60Sec").style="visibility:visible";


}








function checkOnPage(targetId){
    document.getElementById(targetId).checked=!document.getElementById(targetId).checked;
}



function checkCapsLock(){
    console.log("Checking Caps Lock");
    if (CapsLock.isOn()){
        console.log("It's On");
        document.getElementById("capsLockOn").innerHTML = "Caps Lock is on.";

    }else{
        document.getElementById("capsLockOn").innerHTML = "";

    }
}

function hoverOverEditButton(button){
    button.style.cursor="hand";	
    button.classList.add('highlightEditButton');

}
function revertEditButton(button){
    button.classList.remove('highlightEditButton');
}
function hoverOverHelpButton(button){
    button.style.cursor="hand";	
    button.classList.add('highlightEditButton');
}
function revertHelpButton(button){
    button.classList.remove('highlightEditButton');
}
function showVideoControls(videoPlayer){
    videoPlayer.controls = true;	
}
function hideVideoControls(videoPlayer){

    videoPlayer.controls = false;	
}
