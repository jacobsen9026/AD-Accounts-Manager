<?php
//Show the login page if not authenticated and not an error page or the logon challenge hop page
if($_SESSION["authenticated_basic"]!="true" and substr($goto,0,6)!="/error" and strpos($goto,'login/challenge')==false){
    //Show install page if not completed
    if($appConfig["installComplete"]){

        include("./app/views/login/index.php");
    }else{
        include("./app/views/install/index.php");
    }

}
//Check if goto variable was set
//If no goto address was passed show the homepage because the user is logged in
elseif($goto==null and !isset($grab)){
    //Show homepage if goto is blank or missing
    $_SESSION['timeout']=time();

    include("./app/views/homepage.php");


}
//Check the the file goto is calling actually exists
//If a goto variable file is found show it
elseif(file_exists("./app/views/".$goto) and !isset($grab)){
    //Show goto file if it exists
    $_SESSION['timeout']=time();

    include("./app/views".$goto);
}
elseif (isset($grab)){
	if ($grab!='' and file_exists("./app/views".$grab)){
		include ("./app/views".$grab);
		//exit();
		
		
	}
}

//Otherwise the file doesn't exist and show an error page
else{
    //Show error page when goto file is missing
    $_SESSION['timeout']=time();

    include("./app/views/error404.php");
}
?>