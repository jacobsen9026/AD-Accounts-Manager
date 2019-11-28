<?php
function runPowershellCommand($command){
	global $appConfig;
	
	if(isset($appConfig["powershellPassword"]) and  $appConfig["powershellPassword"] !=''){
		
		$cmd = "\$encrypted = '".$appConfig['powershellPassword']."'; "
		."[Byte[]] \$key = (128,108,221,185,88,252,107,140,27,25,76,94,3,135,109,10); "
		."\$reencrypted = ConvertTo-SecureString -String \$encrypted -Key \$key; "
		."\$domainUsername = '".$appConfig["powershellUsername"]."'; "
		."\$domainUser = '".$appConfig["domainNetBIOS"]."\\".$appConfig["powershellUsername"]."'; "
		."\$Credentials = New-Object System.Management.Automation.PSCredential \$domainUser,\$reencrypted;";
		$cmd = 'Powershell.exe Invoke-Command -ScriptBlock{'.$cmd.$command.'}';
	}else{
    $cmd = 'Powershell.exe Invoke-Command -ScriptBlock{'.$command.'}';
	}
	//echo $cmd;
	//exit;
    //debug("CMD: ".$cmd);
    $result = explode("\n",shell_exec ($cmd));
    debug($result);
    return $result;

}

function runLocalPowershellCommand($command){
	global $appConfig;
	
	
		
    $cmd = 'Powershell.exe Invoke-Command -ScriptBlock{'.$command.'}';
	
	//echo $cmd;
    //debug("CMD: ".$cmd);
    $result = explode("\n",shell_exec ($cmd));
    debug($result);
    return $result;

}


function runSimplePowershellCommand($command){
    $cmd = 'Powershell.exe '.$command;
	echo $cmd;
    //debug("CMD: ".$cmd);
	$result=shell_exec ($cmd);
	debug($result);
	
    $result = explode("\n",);
    debug($result);
    return $result;

}


function doesADUserExist($username) {
    //include("./config/siteVariables.php");
    $cmd = 'powershell.exe Invoke-Command -ScriptBlock{'
        .'$ADUserFound=\'false\'; try{' 
        .'$adUserInfo = Get-ADUser -Identity '.$username.' -Properties * ;'
        .'$ADUserFound=\'true\';'
        .'}catch{ '
        .'$ADUserFound=\'false\';'
        .'}' 
        .'Write-Host $ADUserFound ;'
        .'}' ;
    debug("CMD: ".$cmd);
    $result = shell_exec($cmd);
    if (strpos($result,"alse")!= false){

        return false;
    }else{
        return true;
    }
}
function encryptADPassword($password){
	$cmd = '$password = ConvertTo-SecureString -String "'.$password.'" -AsPlainText -force ;'
	.'[Byte[]] $key = (128,108,221,185,88,252,107,140,27,25,76,94,3,135,109,10);'
	.'$passwordText = ConvertFrom-SecureString -SecureString $password -Key $key;'
	.'echo $passwordText;';
	//echo $cmd;
	return runLocalPowershellCommand($cmd);
	
}


function getADUser($username){
	debug ("getADUser");
    $lines = runPowershellCommand ('Get-ADUser -Credential $Credentials -Identity '.$username.' -Properties *');
    //$x=0;
    foreach($lines as $line){
        $keyValue = explode(':', $line);
        if(trim($keyValue[0]) != '' and isset($keyValue[1]) and trim($keyValue[1]) != ''){
            $output[trim($keyValue[0])]=trim($keyValue[1]);
        }
        //$x++;
    }
    $groups = runPowershellCommand('Get-ADPrincipalGroupMembership '.$username);
    $x=0;
    foreach($groups as $group){
        $keyValue = explode(':', $group);
        if(trim($keyValue[0]) == 'name'){
            $groupNames[$x]=$keyValue[1];
            $x++;
        }
        //$x++;
    }
    //var_export ($groupNames);
    //echo '<br/><br/><br/>';
    $output['Groups']=$groupNames;
    return $output;
}

function getADUserHDrive($username){
    include("./config/siteVariables.php");
    $cmd = 'powershell.exe Invoke-Command -ScriptBlock{'
        .'$ADUserFound=\'false\';'
        .'try{'
        .'$adUserInfo = Get-ADUser -Identity '.$username.' -Properties *;'
        .'$ADUserFound=\'true\';'
        .'}catch{'
        .'$ADUserFound=\'false\';'
        .'}'
        .'echo $adUserInfo.HomeDirectory;'
        .'}';

    //$cmd = "powershell.exe ./utils/powershell/getADUserHDrive.ps1 ".$username;
    //echo $cmd;
    $result = shell_exec($cmd);


    return $result;
}

function disableADUserAccount($username){
    debug (runPowershellCommand("Disable-ADAccount -Credential \$Credentials -Identity ".$username));
}

function enableADUserAccount($username){
    debug (runPowershellCommand("Enable-ADAccount -Credential \$Credentials -Identity ".$username));
    debug (runPowershellCommand("Unlock-ADAccount -Credential \Credentials -Identity ".$username));
}

function fixADUserHDrive($username){
    include("./config/siteVariables.php");

    $cmd = 'powershell.exe Invoke-Command -ScriptBlock{'
        .' $ADUserFound=\'false\';'
        .'try{'
        .'$adUserInfo = Get-ADUser -Identity '.$username.' -Properties *;'
        .'$ADUserFound=\'true\';'
        .'cd $adUserInfo.HomeDirectory;'
        .'$cmd = (\'ICACLS \'+$adUserInfo.HomeDirectory+\' /grant:r \'+$username+\':(OI^)(CI^)M /Q\');'
        .'cmd /c $cmd;'
        .'}catch{'
        .'$ADUserFound=\'false\';'
        .'}'
        .'}';



    //$cmd = "powershell.exe ./utils/powershell/fixADUserHDrive.ps1 ".$username." 2>&1; echo $?";	
    //echo $cmd;
    $result=explode("\n",shell_exec($cmd));
    foreach ($result as $line){
        if(strpos($line,"Success")!==false){
            $output=$line;
        }
    }
    return $output;
}

function printADUserGroups($username){



    if($result != false){
        $groups=getGAUserGroups($result);
        echo ("<br/><strong>".getGAUserEmail($result)."</strong><br/>");
        echo ("<br/><strong>Google Groups: ".$numberOfGroups."</strong><br/>");
        foreach($groups as $group){
            echo $group[0]."<br/>";
            echo "<email onclick='copyThis(this)'>".$group[1]."</email><br/>";


        }	

    }else{
        echo "User not found in Google";
    }
}



function createADUserAccount($firstname,$lastname,$grade,$yog,$accountPassword,$forcePasswordChange){
    echo "testing";

}

function resetADUserPassword($username,$password,$resetPassword){
    //echo "Reset";
    //echo $username;
    //echo $password;
    //echo $resetPassword;


    $cmd = 'powershell.exe Invoke-Command -ScriptBlock{'
        .' $ADUserFound=\'false\';'
        .'try{'
        .'$adUserInfo = Get-ADUser -Identity '.$username.' -Properties *;'
        .'$ADUserFound=\'true\';'
        .'$SecPaswd= ConvertTo-SecureString –String '.$password.' –AsPlainText –Force;'
        .'Set-ADAccountPassword -Identity '.$username.' -NewPassword $SecPaswd;'
        .'if (\''.$resetPassword.'\' -eq \'true\'){'
        .'Set-ADUser -Identity '.$username.' -ChangePasswordAtLogon 1;'

        .'}'
        .'}catch{'
        .'$ADUserFound=\'false\';'
        .'} echo $ADUserFound;'
        .'}';


    //exit;
    include("./config/siteVariables.php");
    //$cmd = "powershell.exe ./utils/powershell/resetADUserPassword.ps1 ".$username." ".$password." ".$resetPassword." 2>&1; echo $?";
    //echo $cmd."<br />";
    $result = shell_exec($cmd);
    //echo strpos($result,"rue");
    if(strpos($result,"rue")!=false){
        return 1;
    }else{
        return 0;
    }

}





function renameADComputer($old,$new){
    $rebootButton="	
<br/>
<form action='/?goto=/tech/computers/rebootPC.php' method='post'>
	<input name='PCName' value='$old' hidden/>

	<input name='delay' value='0' hidden/>
<button type='submit'>Reboot this workstation now.</button><br/>


</form>";
    include("./config/siteVariables.php");
    //prepDomainUsername();

    $cmd = "powershell.exe rename-computer -NewName ".$new." -ComputerName ".$old;
    echo $cmd."<br />";
    $result = shell_exec($cmd);
    echo $result;
    //echo strpos($result,"rue");
    if(strpos($result,"No such host is known")!=false){
        return "$old was not found on the network!";

    }else{
        return $result.$rebootButton;
    }

}



function rebootADComputer($pc,$delay){
    //include("./config/siteVariables.php");
    $cmd = "shutdown /r /f /m \\\\$pc /t $delay";
    //echo $cmd."<br />";
    $result = shell_exec($cmd);
    //echo strpos($result,"rue");
    if(strpos($result,"Access is denied")!=false){
        return "$pc denied access!";

    }else{
        return "$pc is currently rebooting";
    }

}


function getADGroupFromGAGroup($googleGroup){
    $groupMapping[0]=@array("cms-special-education","CMS Special Ed");
    $groupMapping[1]=@array("cmschampions","CMS Champion Team");
    $groupMapping[2]=@array("cmsencore","CMS Encore Team");
    $groupMapping[3]=@array("cmsforce","CMS Force Team");
    $groupMapping[4]=@array("cmsinnovators","CMS Innovators Team");
    $groupMapping[5]=@array("cmsinstructionalaides","CMS Aides");
    $groupMapping[6]=@array("cmsolympians","CMS Olympian Team");
    $groupMapping[7]=@array("cmspathfinders","CMS Pathfinders Team");
    $groupMapping[8]=@array("cmstrailblazers","CMS Trailblazers Team");
    $groupMapping[9]=@array("cmsworldlanguage","CMS World Language");
    $groupMapping[10]=@array("sbsaides","SBS Aides");
    $groupMapping[11]=@array("sbsgrade4teachers","SBS Grade 4 Teachers");
    $groupMapping[12]=@array("sbsgrade5teachers","SBS Grade 5 Teachers");
    $groupMapping[13]=@array("sbsspecedteachers","SBS Spec Ed Teachers");
    $groupMapping[14]=@array("wesaides","WES Aides");
    $groupMapping[15]=@array("wesgrade1teachers","WES Grade 1 Teachers");
    $groupMapping[16]=@array("wesgrade2teachers","WES Aides");
    $groupMapping[17]=@array("wesgrade3teachers","WES Grade 1 Teachers");
    $groupMapping[18]=@array("wesist","WES Instructional Support Teachers");
    $groupMapping[19]=@array("weskindergartenteachers","WES Kindergarten Teachers");
    $groupMapping[20]=@array("wespreschoolteachers","WES Kindergarten Teachers");
    $groupMapping[21]=@array("wesspecialedteachers","WES spec Ed Teachers");
    $groupMapping[22]=@array("wesspecialsubjectteachers","WES Special Subject Teachers");
    $groupMapping[23]=@array("wesspeechteachers","WES Speech Teachers");
    $groupMapping[24]=@array("childstudyteam","Child Study Team");
    foreach ($groupMapping as $mapping){
        if($mapping[0]==$googleGroup){
            return $mapping[1];
        }
    }
}
function installTermSrvHack($pc,$os){

    include("./config/siteVariables.php");
    $cmd = "sc \\\\$pc stop UmRdpService";	
    //echo $cmd;
    $result = shell_exec($cmd);

    //echo $result."<br/><br/>";

    sleep(1.5);
    $cmd = "sc \\\\$pc stop IntelUSBoverIP";

    $result = shell_exec($cmd);
    //echo $result."<br/><br/>";

    sleep(0.5);
    $cmd = "sc \\\\$pc stop TermService";
    $result = shell_exec($cmd);
    //echo $result."<br/><br/>";
    //return;
    sleep(1.5);
    //echo "<br/>".$cmd;

    $cmd = "takeown /f \\\\$pc\C$\windows\system32\\termsrv.dll";
    $result = shell_exec($cmd);
    //echo $result."<br/><br/>";
    sleep(0.1);
    //echo "<br/>".$cmd;
    $cmd = "ICACLS \\\\$pc\C$\windows\system32\\termsrv.dll /grant administrators:(F) ";
    //echo "<br/>".$cmd;
    $result = shell_exec($cmd);
    //echo $result."<br/><br/>";
    sleep(0.1);



    $cmd = "copy ./tech/computers/termsrv/$os.dll \\\\$pc\C$\windows\system32\\termsrv.dll";
    $cmd = str_replace("/","\\",$cmd);
    $cmd=$cmd." /y";
    //echo "<br/>".$cmd; 
    $result = shell_exec($cmd);
    //echo $result."<br/>";
    sleep(1.0);

    $cmd = "sc \\\\$pc start TermService";
    //echo "<br/>".$cmd;
    $result = shell_exec($cmd);
    //echo $result."<br/>";
    sleep(0.5);
    //$cmd = "sc \\\\$pc start UmRdpService";
    //$result = shell_exec($cmd);
    //echo $result."<br/>";
    //	sleep(0.1);
    return true;
}
function rpcReachable($pc){
    $cmd="powershell.exe Test-NetConnection -ComputerName $pc -Port 135";
    //echo $cmd;
    $result = shell_exec($cmd);
    if(strpos($result,'WARNING')!=false){
        return false;
    }
    return true;
    //echo $result;
}

function testAdministrator (){  
    $cmd='$user = [Security.Principal.WindowsIdentity]::GetCurrent();'
        .'(New-Object Security.Principal.WindowsPrincipal $user).IsInRole([Security.Principal.WindowsBuiltinRole]::Administrator)';
    return boolval(runPowershellCommand($cmd)[0]);
}

function testADCredentials(){
	
	global $appConfig;
	$result = (runPowershellCommand('Get-ADUser -Credential $Credentials -Identity $domainUsername;'));
	if (strpos($result[9],$appConfig["powershellUsername"])!=0){
		return true;
	}else{
		return false;
	}
	
}


?>