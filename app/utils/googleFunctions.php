<?php
function runGAMCommand($command){
    $cmd = $_SERVER["DOCUMENT_ROOT"].'/lib/gam-64/gam.exe '.$command;
    //echo $cmd;
    debug("CMD: ".$cmd);
    //debug(shell_exec ($cmd));
    $result = explode("\n",shell_exec ($cmd));
    debug($result);
    return $result;
}

function doesGAUserExist($username) {
    $cmd = "gam info user ".$username;
    debug("CMD: ".$cmd);
    $result = shell_exec($cmd);
    if ($result != ""){
        return explode("\n",$result);
    }else{
        return false;
    }
}
function addGAUserToGroup($username,$group){
    //debug("GROUPS: ".$group);
    $cmd = "update group ".$group." add member ".$username;
    //debug("CMD: ".$cmd);
    //$result = shell_exec($cmd." 2>&1; echo $?");
    $result = runGAMCommand($cmd);
    debug("RESULT: ".$result);
    if(strpos($result,"Duplicate")!==false){
        return "<br/>Already in ".$group;
    }else{
        return "<br/>Added to ".$group;
    }
}

function enableGAUserAccount($username){
    runGAMCommand("update user ".$username." suspended off");
}

function disableGAUserAccount($username){
    runGAMCommand("update user ".$username." suspended on");
}

function getGAUser($username){
    $cmd = "gam info user ".$username;
    debug("CMD: ".$cmd);
    $result = explode("\n",shell_exec($cmd));
    //Break out result to array
    foreach($result as $line){
        $keyValue = explode(':', $line,2);
        if(trim($keyValue[0]) != '' ){
            $output[trim($keyValue[0])]=trim($keyValue[1]);
        }
        if(strpos($line, "Groups:")!==false){
            //echo $line;
            //$numberOfGroups=substr($line,strpos($line,": ")+2,strlen($line)-(strpos($line,":")-4));
            $numberOfGroups=$keyValue[1];
            $numberOfGroups=str_replace("(","",$numberOfGroups);
            $numberOfGroups=str_replace(")","",$numberOfGroups);
            $inGroups=true;

        }elseif(strpos($line,"Licenses:")!==false){
            $inGroups=false;
        }
        elseif($inGroups){
            if($line!=""){
                //echo $line."<br/>";
                $group[0]=trim(substr($line,0,strpos($line,"<")));//Group Name
                $group[1]=trim(substr($line,strpos($line,"<")+1,strlen($line)-strpos($line,"<")-2));//Group Email
                $groups[$x]=$group;
                $x++;
            }
        }

    }


    //Cleanup Array Output
    $output["Groups"]=$groups;
    $output["Last login time"]=str_replace("T"," ",$output["Last login time"]);
    $output["Last login time"]=str_replace(".000Z","",$output["Last login time"]);
    if($GAUser["Account Suspended"]=="False"){
        $output["Account Enabled"]="True";
    }else{
        $output["Account Enabled"]="False";
    }

    debug ($output);
    return $output;
}

function removeGAUserFromGroup($username,$group){
    $cmd = "gam update group ".$group." remove member ".$username;
    debug("CMD: ".$cmd);
    return shell_exec($cmd." 2>&1; echo $?");
}

function getGAUserGroups($userInfo){
    $inGroups=false;
    $groups=array(array());

    $x=0;
    foreach($userInfo as $line){
        if(strpos($line, "Groups:")!==false){
            //echo $line;
            $numberOfGroups=substr($line,strpos($line,": ")+2,strlen($line)-(strpos($line,":")-4));
            $numberOfGroups=str_replace("(","",$numberOfGroups);
            $numberOfGroups=str_replace(")","",$numberOfGroups);
            $inGroups=true;

        }elseif(strpos($line,"Licenses:")!==false){
            $inGroups=false;

        }
        elseif($inGroups){
            if($line!=""){
                //echo $line."<br/>";
                $group[0]=trim(substr($line,0,strpos($line,"<")));//Group Name
                $group[1]=trim(substr($line,strpos($line,"<")+1,strlen($line)-strpos($line,"<")-2));//Group Email
                $groups[$x]=$group;
                $x++;
            }
        }
    }
    return $groups;	
}

function getGAGroupMembers($group){
    $inMembers=false;
    $cmd = "gam info group ".$group;
    $result = shell_exec($cmd);
    debug($result);
    foreach(explode("\n",$result) as $line){
        debug($line);
        if($line=="Members:"){
            $inMembers=true;
        }
        if($inMembers){
            if(strpos($line,"member")!=false){
                $members[]=substr($line,strpos($line,": ")+2,strlen($line)-strpos($line,": ")-2-strpos($line,"(")-4);
            }

        }
    }
    return $members;
}

function getGAUserEmail($userInfo){
    foreach($userInfo as $line){
        if(strpos($line, "User:")!==false){
            $userEmailAddress=substr($line,strpos($line,": ")+2,strlen($line)-(strpos($line,":")-2));
        }


    }
    return $userEmailAddress;


}


//Student Google Buttons



function addStudentToGoogleGroup($username,$group){


    $removebutton="
		<form action='/?goto=/students/google-groups/manageGoogleGroups.php' method='post'>
		<input name='username' value='".$_POST["username"]."' hidden>
		<input name='action' value='remove' hidden>
		<input name='grade' value='".$_POST["grade"]."' hidden>
		<input type='submit' value='Remove from group'>
		</form>
		";
    $undoremovebutton="
		<form action='/?goto=/students/google-groups/manageGoogleGroups.php' method='post'>
		<input name='username' value='".$_POST["username"]."' hidden>
		<input name='action' value='remove' hidden>
		<input name='grade' value='".$_POST["grade"]."' hidden>
		<input name='undo' value='true' hidden>
		<button type='submit'>Undo</button>
		</form>
		";

    $cmd="gam update group ".$group." add member ".$username." 2>&1; echo $?";

    $result = shell_exec($cmd);
    if(strpos($result,"Member already exists.")!==false){
        $result=$username." was already in ".$group;
        $result=$result;
    }else{
        $result=$username." was added to ".$group;
        debug("UNDO: ".$_POST["undo"]);
        //echo $undoremovebutton;
        if($_POST["undo"]==""){
            $result=$result.$undoremovebutton;
        }
    }
    return $result;
}

function removeStudentFromGoogleGroup($username,$group){

    $addbutton="
		<form action='/?goto=/students/google-groups/manageGoogleGroups.php' method='post'>
		<input name='username' value='".$_POST["username"]."' hidden>
		<input name='action' value='add' hidden>
		<input name='grade' value='".$_POST["grade"]."' hidden>
		<input type='submit' value='Add to group'>
		</form>
		";
    $undoaddbutton="
		<form action='/?goto=/students/google-groups/manageGoogleGroups.php' method='post'>
		<input name='username' value='".$_POST["username"]."' hidden>
		<input name='action' value='add' hidden>
		<input name='grade' value='".$_POST["grade"]."' hidden>
		<input name='undo' value='true' hidden>
		<button  type='submit'>Undo</button>
		</form>
		";

    $cmd="gam update group ".$group." remove member ".$username." 2>&1; echo $?";
    debug("CMD: ".$cmd);

    $result = shell_exec($cmd);
    if(strpos($result,"404")!==false){
        $result=$username." was not found in ".$group;
        $result=$result."<br />";
    }elseif(strpos($result,"ERROR")!==false){
        $result="There was an error";
    }else{
        $result=$username." was removed from ".$group;
        if($_POST["undo"]==""){
            $result=$result.$undoaddbutton;
        }
    }
    return $result;
}

function resetGAUserPassword($username,$password,$resetPassword){
    if($resetPassword=="true"){
        $cmd = "gam update user ". $username . " password " . $password . " changepassword on";
    }else{

        $cmd = "gam update user ". $username . " password " . $password . " changepassword off";
    }
    debug("CMD: ".$cmd);
    $result = shell_exec($cmd);
    if(strpos($result,"404")!==false){
        return false;
    }elseif(strpos($result,"ERROR")!==false){
        $result="There was an error";
        return false;
    }

    return true;
}


function getParentGoogleGroupMembership($email){
    global $appConfig;
    debug("inside getparents");
    //include("./config/email-groups/parentEmailGroups.php");
    debug("EMAIL: ".$email);
    debug($appConfig["parentEmailGroups"]);
    //header('Content-Type: text/event-stream');
    //header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
    $x=0;
    foreach($appConfig["parentEmailGroups"] as $group){
        //$serverTime = time();
        //send_message($serverTime, 'server time: ' . date("h:i:s", time()) , ($i+1)*10); 
        $cmd = "gam info group ".$group;
        debug("CMD: ".$cmd);
        $result = shell_exec($cmd);
        debug ("RESULT: ".$result);
        if(strpos(strtolower($result),strtolower($email))!=false){
            $output[$x]=$group;
            $x++;
        }

    }
    debug ($output);
    return $output;

}

function addTechDrivePermission ($username,$id,$url){
    global $appConfig;
    //include("./config/siteVariables.php");
    $cmd = "gam user $username@".$appConfig["domainName"]." add drivefileacl $id group techstaff@".$appConfig["domainName"]." role writer";
    //echo $Error[0];
    debug("CMD: ".$cmd);
    //echo $cmd;
    $result = shell_exec($cmd);
    if($result!=""){
        $flagFile = $_SERVER['DOCUMENT_ROOT']."/tech/google-drive/history/".$id;
        //echo $flagFile;
        if(!file_exists($flagFile)){
            $fo = fopen($flagFile,"w");
            fwrite($fo,$username.",docs.google.com/d/".$id.",".$url);
            fclose($fo);
        }
        return true;
    }
    return false;
}

function removeTechDrivePermission ($username,$id){
    global $appConfig;
    //include("./config/siteVariables.php");
    $cmd = "gam user ".$username."@".$appConfig["domainName"]." delete drivefileacl $id techstaff@".$appConfig["domainName"];
    //echo $Error[0];
    //echo $cmd;
    debug("CMD: ".$cmd);
    $result = shell_exec($cmd);
    $flagFile = $_SERVER['DOCUMENT_ROOT']."/tech/google-drive/history/".$id;

    unlink($flagFile);



    return true;

}










?>