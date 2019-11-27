const refreshSessionEvent = new BroadcastChannel('refreshedSession');
refreshSessionEvent.onmessage = function(e){
    console.log("Refresh Broadcast Recieved");
    hideSessionTimeoutWarningMessege();
    clearTimeout(timeoutTimer);
    clearTimeout(timer);
    startSessionTimeoutTimer();
};

const lockSessionEvent = new BroadcastChannel('lockSession');
lockSessionEvent.onmessage = function(e){
    console.log("Lock Broadcast Recieved");
    showSessionTimedOutMessege();
};

const reauthenticatedSessionEvent = new BroadcastChannel('reauthenticatedSession');
reauthenticatedSessionEvent.onmessage = function(e){
    console.log("Reauthenticate Broadcast Recieved");
    hideSessionTimedOutMessege();
    clearTimeout(timeoutTimer);
    clearTimeout(timer);
    startSessionTimeoutTimer();
};












function refreshSession(){
    fetch('/');
    hideSessionTimeoutWarningMessege();
    console.log("Refresh Broadcast Sent");
    refreshSessionEvent.postMessage('refreshSessionEvent');
    clearTimeout(timeoutTimer);
    startSessionTimeoutTimer();
}



function showSessionTimedOutMessege(){

    hideSessionTimeoutWarningMessege();
    blurPage();
    document.getElementById("sessionTimedOutContainer").style="visibility:visible";


}

function blurPage(){
    console.log("blurring page");
    document.getElementById("wrapper").classList.add("blur");
    document.getElementById("navigation").classList.add("blur");
}

function unblurPage(){
    console.log("unblurring page");
    document.getElementById("wrapper").classList.remove("blur");
    document.getElementById("navigation").classList.remove("blur");

}



function hideSessionTimedOutMessege(){
    unblurPage();
    hideSessionTimeoutWarningMessege();
    document.getElementById("sessionTimedOutContainer").style="visibility:hidden";


}


function hideSessionTimeoutWarningMessege(){
    unblurPage();
    document.getElementById("sessionTimeoutWarningContainer").style="visibility:hidden";
}


function reauthenticatedSession(){

    reauthenticatedSessionEvent.postMessage('reauthenticatedSessionEvent');
    console.log("Reauthenticated Broadcast Sent");
    hideSessionTimedOutMessege();
}

async function reauthenticateSession(){

    let formData = new FormData(document.forms.loginPrompt);

    // add one more field
    //formData.append("middle", "Lee");

    // send it out
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/?goto=/login/challenge.php");
    xhr.send(formData);
    let response = xhr.response;
    //response.getElementById("authenticated");

    xhr.onload = () => {
        let authenticated=xhr.response.includes('authenticated');
        let badPass=xhr.response.includes('badPass');
        let notAuthorized=xhr.response.includes('notAuthorized');
        console.log(authenticated);
        console.log(badPass);
        console.log(notAuthorized);


        if(authenticated){
            reauthenticatedSession();
        }
        else if(notAuthorized){
            document.getElementById("notAuthorizedMessage").style="";
        }
        else if(badPass){
            document.getElementById("badPassMessage").style="";
        }
        else{
            document.getElementById("notAuthorizedMessage").style="display:none";
            document.getElementById("badPassMessage").style="display:none";
        }

    };
    document.getElementById('passwordInput').value="";

}

function lockSession(){

    console.log("Lock Broadcast Sent");
    lockSessionEvent.postMessage('lockSession');
}

function password_onkeypress(event){
                if (event.keyCode == 13 || event.which == 13){
                    reauthenticateSession();
                }
            }



