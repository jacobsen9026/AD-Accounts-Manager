<?php
$configSingleLineOptions = Array("emailFromName","emailFromAddress",
                                 "domainName","domainController","webAppName","domainNetBIOS","sessionTimeout");




$configMultiLineOptions = Array("adminUsernames", "parentEmailGroups", 
                                "staffEmailGroups", "welcomeEmailReceivers", "adminUsernames",
                                "adminEmails","homepageMessage");

$configCheckboxOptions = Array("redirectHTTP");


foreach ($configSingleLineOptions as $option){
    if(isset($_POST[$option])){
        debug("saving ".$option);
        $appConfig[$option] = trim($_POST[$option]) ;

        saveConfig();
    }
}


foreach ($configMultiLineOptions as $option){
    if(isset($_POST[$option])){
        debug("saving ".$option);
        $appConfig[$option] = explode("\r\n",trim($_POST[$option])) ;
        debug($appConfig[$option]);
        saveConfig();
    }
}

foreach ($configCheckboxOptions as $option){
    if(isset($_POST[$option])){
		if($_POST['redirectHTTPCheck']==true){
			$appConfig[$option]=true;
		}else{
			$appConfig[$option]=false;
		}

		saveConfig();
	}
}



if(isset($_POST["adminPassword"]) and trim($_POST["adminPassword"])!=""){
    $appConfig["adminPassword"] =  hash('sha256',trim($_POST["adminPassword"])) ;

    saveConfig();
}

if(isset($_POST["testEmailTo"]) and trim($_POST["testEmailTo"])!=""){
    $appConfig["testEmailTo"] = trim($_POST["testEmailTo"]) ;

    saveConfig();
}

if(isset($_POST["testEmailTo"]) and trim($_POST["testEmailTo"])!=""){

    debug(sendEmail($_POST["to"],"Test Email","This is a test notification from the ".$appConfig["webAppName"]."."));
}


if(isset($_FILES["oauth2_txt"])){

    debug($_FILES);
    if(file_exists("./lib/gam-64/oauth2.txt")){
        if (isset($_POST["overwrite"])){


            move_uploaded_file($_FILES["oauth2_txt"]["tmp_name"], "./lib/gam-64/oauth2.txt");
        }
    }else{
        move_uploaded_file($_FILES["oauth2_txt"]["tmp_name"], "./lib/gam-64/oauth2.txt");
    }
}







if(isset($_POST["welcomeEmailHTML"]) and $_POST["welcomeEmailHTML"]!=""){
    if(file_get_contents($_SERVER['DOCUMENT_ROOT']."/config/staffemail.html")!=$_POST["welcomeEmailHTML"] and file_get_contents($_SERVER['DOCUMENT_ROOT']."/config/staffemail.html.example")!=$_POST["welcomeEmailHTML"]) {
        $dateTime=date("Y-m-d_h-i-s");
        copy($_SERVER['DOCUMENT_ROOT']."/config/staffemail.html" ,$_SERVER['DOCUMENT_ROOT']."/config/backup/".$dateTime."_staffemail.html");
        file_put_contents($_SERVER['DOCUMENT_ROOT']."/config/staffemail.html",trim($_POST["welcomeEmailHTML"])) ;

        saveConfig();
    }
}





if(isset($_POST["websiteFQDN"])){
	$websiteFQDN=$_POST["websiteFQDN"];
	if($websiteFQDN != ""){
		
		if(strpos( $websiteFQDN,"//")!=false){
			$websiteFQDN=substr( $websiteFQDN,strpos( $websiteFQDN,"//")+2,strlen( $websiteFQDN)-strpos( $websiteFQDN,"//")-2);
		}
		$appConfig["websiteFQDN"] = trim($websiteFQDN) ;
	}else{
		$appConfig["websiteFQDN"] = "" ;
	}
    saveConfig();
}

debug("Debug Mode is on");
if(isset($_POST["debugMode"])){
    if(isset($appConfig["debugMode"]) and $appConfig["debugMode"]!=$_POST["debugModeCheck"]){
        $refresh=true;
    }else{
        $refresh=false;
    }
    if(isset($appConfig["debugMode"]) and $_POST['debugModeCheck']==true){
        $appConfig["debugMode"]=true;
    }else{
        $appConfig["debugMode"]=false;
    }
    //$appConfig["debugMode"] = IsChecked($_POST["debugMode"]) ;

    saveConfig();
    if ($refresh){
?>
<script>
    window.location="<?php echo str_replace("&rolloverGrades=true","",$pageURL);?>";
</script>
<?php
                 }
}




if(isset($_POST["techUserGroup"])){

    $appConfig["userMappings"]["basic"] = $_POST["basicUserGroup"];
    $appConfig["userMappings"]["power"] = $_POST["powerUserGroup"];
    $appConfig["userMappings"]["admin"] = $_POST["adminUserGroup"];
    $appConfig["userMappings"]["tech"] = $_POST["techUserGroup"];
    saveConfig();

}


if(isset($_POST["sgg8"])){

    $appConfig["studentEmailGroups"]["8"]=$_POST["sgg8"];
    $appConfig["studentEmailGroups"]["7"]=$_POST["sgg7"];
    $appConfig["studentEmailGroups"]["6"]=$_POST["sgg6"];
    $appConfig["studentEmailGroups"]["5"]=$_POST["sgg5"];
    $appConfig["studentEmailGroups"]["4"]=$_POST["sgg4"];
    $appConfig["studentEmailGroups"]["3"]=$_POST["sgg3"];
    $appConfig["studentEmailGroups"]["2"]=$_POST["sgg2"];
    $appConfig["studentEmailGroups"]["1"]=$_POST["sgg1"];
    $appConfig["studentEmailGroups"]["K"]=$_POST["sggk"];
    $appConfig["studentEmailGroups"]["PK4"]=$_POST["sggpk4"];
    $appConfig["studentEmailGroups"]["PK3"]=$_POST["sggpk3"];

    saveConfig();
}



if(isset($_POST["yog8"])){
    //global $appConfig;
    $appConfig["gradeMappings"]["8"]=$_POST["yog8"];
    $appConfig["gradeMappings"]["7"]=$_POST["yog7"];
    $appConfig["gradeMappings"]["6"]=$_POST["yog6"];
    $appConfig["gradeMappings"]["5"]=$_POST["yog5"];
    $appConfig["gradeMappings"]["4"]=$_POST["yog4"];
    $appConfig["gradeMappings"]["3"]=$_POST["yog3"];
    $appConfig["gradeMappings"]["2"]=$_POST["yog2"];
    $appConfig["gradeMappings"]["1"]=$_POST["yog1"];
    $appConfig["gradeMappings"]["K"]=$_POST["yogk"];
    $appConfig["gradeMappings"]["PK4"]=$_POST["yogpk4"];
    $appConfig["gradeMappings"]["PK3"]=$_POST["yogpk3"];
    saveConfig();
}

if(isset($_GET["rolloverGrades"]) and $_GET["rolloverGrades"]=="true"){

    $appConfig["gradeMappings"]["8"]=(intval($appConfig["gradeMappings"]["8"])+intval(1));
    $appConfig["gradeMappings"]["7"]=(intval($appConfig["gradeMappings"]["7"])+intval(1));
    $appConfig["gradeMappings"]["6"]=(intval($appConfig["gradeMappings"]["6"])+intval(1));
    $appConfig["gradeMappings"]["5"]=(intval($appConfig["gradeMappings"]["5"])+intval(1));
    $appConfig["gradeMappings"]["4"]=(intval($appConfig["gradeMappings"]["4"])+intval(1));
    $appConfig["gradeMappings"]["3"]=(intval($appConfig["gradeMappings"]["3"])+intval(1));
    $appConfig["gradeMappings"]["2"]=(intval($appConfig["gradeMappings"]["2"])+intval(1));
    $appConfig["gradeMappings"]["1"]=(intval($appConfig["gradeMappings"]["1"])+intval(1));
    $appConfig["gradeMappings"]["K"]=(intval($appConfig["gradeMappings"]["K"])+intval(1));
    $appConfig["gradeMappings"]["PK4"]=(intval($appConfig["gradeMappings"]["PK4"])+intval(1));
    $appConfig["gradeMappings"]["PK3"]=(intval($appConfig["gradeMappings"]["PK3"])+intval(1));
    //print_r($appConfig["gradeMappings"]);
    //var_export $appConfig["gradeMappings"];
    saveConfig();



?>
<script>
    window.location="<?php echo str_replace("&rolloverGrades=true","",$pageURL);?>";
</script>
<?php


}


?>
