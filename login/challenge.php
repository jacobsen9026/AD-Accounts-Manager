

<?php
if(!isset($_SESSION)){
session_start();
}


if(!isset($_POST["rememberUsername"])){
    setcookie("username", "", time() - 3600);
}
if(!isset($_POST["rememberMe"])){
    setcookie("token", "", time() - 3600);
}else{
    runAutoLogon($_POST["password"]);
}

if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username']!="" && $_POST['password']!= "" && extension_loaded("ldap") && $_POST['username']!="admin"){

    $adServer = "ldap://".$appConfig["domainController"];
    $domainArray=explode(".",$appConfig["domainName"]);
    $distinguishedName='';
    foreach($domainArray as $domain){
        $distinguishedName=strval($distinguishedName."DC=".$domain.",");
    }
    $distinguishedName=substr($distinguishedName,0,strlen($distinguishedName)-1);

    $ldap = ldap_connect($adServer);
    $username = strtolower($_POST['username']);
    $password = $_POST['password'];

    $ldaprdn = $appConfig["domainNetBIOS"] . "\\" . $username;

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    $bind = @ldap_bind($ldap, $ldaprdn, $password);

    if ($bind) {
        $filter="(sAMAccountName=$username)";
        $result = ldap_search($ldap,$distinguishedName,$filter);
        //ldap_sort($ldap,$result,"sn");
        $info = ldap_get_entries($ldap, $result);

        $filterBasic="(&(sAMAccountName=".$username.")(memberOf:1.2.840.113556.1.4.1941:=CN=".$appConfig["userMappings"]["basic"].",OU=System Security Groups,".$distinguishedName."))";
        $filterPower="(&(sAMAccountName=".$username.")(memberOf:1.2.840.113556.1.4.1941:=CN=".$appConfig["userMappings"]["power"].",OU=System Security Groups,".$distinguishedName."))";
        $filterAdmin="(&(sAMAccountName=".$username.")(memberOf:1.2.840.113556.1.4.1941:=CN=".$appConfig["userMappings"]["admin"].",OU=System Security Groups,".$distinguishedName."))";
        $filterTech="(&(sAMAccountName=".$username.")(memberOf:1.2.840.113556.1.4.1941:=CN=".$appConfig["userMappings"]["tech"].",OU=System Security Groups,".$distinguishedName."))";
        $resultBasic = ldap_search($ldap,$distinguishedName,$filterBasic);
        $resultPower = ldap_search($ldap,$distinguishedName,$filterPower);
        $resultAdmin = ldap_search($ldap,$distinguishedName,$filterAdmin);
        $resultTech = ldap_search($ldap,$distinguishedName,$filterTech);
        $infoBasic = ldap_get_entries($ldap, $resultBasic);
        $infoPower = ldap_get_entries($ldap, $resultPower);
        $infoAdmin = ldap_get_entries($ldap, $resultAdmin);
        $infoTech = ldap_get_entries($ldap, $resultTech);

        //echo "<br/><br/><br/><br/>";
        $passed=false;

        for ($i=0; $i<$infoBasic["count"]; $i++)
        {
            //print_r($infoBasic[$i])."<br/><br/><br/>";
            if ($username == strtolower($infoBasic[$i]["samaccountname"][0])){
                $passed=true;
                $_SESSION["authenticated_basic"]="true";
                $_SESSION["authenticated_power"]="false";
                $_SESSION["authenticated_admin"]="false";
                $_SESSION["authenticated_tech"]="false";
            }
        }
        for ($i=0; $i<$infoPower["count"]; $i++)
        {
            //print_r($infoBasic[$i])."<br/><br/><br/>";
            if ($username == strtolower($infoPower[$i]["samaccountname"][0])){
                $passed=true;
                $_SESSION["authenticated_basic"]="true";
                $_SESSION["authenticated_power"]="true";
                $_SESSION["authenticated_admin"]="false";
                $_SESSION["authenticated_tech"]="false";

            }
        }
        for ($i=0; $i<$infoAdmin["count"]; $i++)
        {
            //print_r($infoBasic[$i])."<br/><br/><br/>";
            if ($username == strtolower($infoAdmin[$i]["samaccountname"][0])){
                $passed=true;
                $_SESSION["authenticated_basic"]="true";
                $_SESSION["authenticated_power"]="true";
                $_SESSION["authenticated_admin"]="true";
                $_SESSION["authenticated_tech"]="false";
            }
        }
        for ($i=0; $i<$infoTech["count"]; $i++)
        {

            if ($username == strtolower($infoTech[$i]["samaccountname"][0])){
                $passed=true;
                $_SESSION["authenticated_basic"]="true";
                $_SESSION["authenticated_power"]="true";
                $_SESSION["authenticated_admin"]="true";
                $_SESSION["authenticated_tech"]="true";

            }
        }


        if($passed){
            //Output Authenticated for reauthenticate Javascript
            echo "<div id='authenticated'></div>";
?>
<script>reauthenticatedSession();</script>

<?php



            $_SESSION["userLastName"] = $infoTech[$i]["sn"][0];
            $_SESSION["userFirstName"] = $infoTech[$i]["givenname"][0];
            $_SESSION["username"] = $username;
            auditLogon($username);
            if(isset($_POST["rememberUsername"])||isset($_POST["rememberMe"])){

                setcookie("username",$username,time() + (86400 * 3650));
            }
            if(isset($_POST["rememberMe"])){

                setcookie("token",json_encode(Array(getSIDHash($username),hash("sha256",$_SESSION["authenticated_basic"]),hash("sha256",$_SESSION["authenticated_power"]),hash("sha256",$_SESSION["authenticated_admin"]),hash("sha256",$_SESSION["authenticated_tech"])),time() + (86400 * 3650)));

            }

            if($_POST["intent"]!=""){

?>
<script>
    window.location="/?goto=<?php echo $_POST['intent']; ?>";
</script>
<?php
                                    }else{

?>
<script>
    window.location="/";
</script>
<?php
            }
        }else{
            echo "<div id='notAuthorized'></div>";
?>
<body onload="document.getElementById('form').submit();">
    <form id="form" method="post" action="/">
        <input style="opacity:0;" type="checkbox" name="notauthorized" checked value="notauthorized"/>
    </form>
</body>
<?php
            session_unset();
        }
        debug($info);




        @ldap_close($ldap);

    }else {
        echo "<div id='badPass'></div>";
?>

<body onload="document.getElementById('form').submit();">
    <form id="form" method="post" action="/">
        <input style="opacity:0;" type="checkbox" name="badpass" checked value="badpass"/>
    </form>
</body>
<?php
    }
}





if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username']!="" && $_POST['password']!= "" && strtolower($_POST['username']) == "admin"){
    $username = strtolower($_POST['username']);
    //Fall back in case LDAP authentication isn't working

    if(hash("sha256",$_POST["password"])==$appConfig['adminPassword']){



        $_SESSION["authenticated_basic"]="true";
        $_SESSION["authenticated_power"]="true";
        $_SESSION["authenticated_admin"]="true";
        $_SESSION["authenticated_tech"]="true";
        $_SESSION["userLastName"] = "Admin";
        $_SESSION["userFirstName"] = "Welcome";
        $_SESSION["username"] = $username;

        auditLogon($username);

        //Output Authenticated for reauthenticate Javascript
        echo "<div id='authenticated'></div>";

        if(isset($_POST["rememberUsername"])||isset($_POST["rememberMe"])){

            setcookie("username",$username,time() + (86400 * 3650));

        }
        if(isset($_POST["rememberMe"])){
            setcookie("token",json_encode(Array(hash("sha256",$_POST["password"]),hash("sha256",$_SESSION["authenticated_basic"]),hash("sha256",$_SESSION["authenticated_power"]),hash("sha256",$_SESSION["authenticated_admin"]),hash("sha256",$_SESSION["authenticated_tech"]))) ,time() + (86400 * 3650));
        }

        if($_POST["intent"]!=""){
?>
<script>
    window.location="/?goto=<?php echo $_POST['intent']; ?>";
</script>
<?php
                                }else{
?>
<script>
    window.location="/";
</script>
<?php
        }
    }
}

?>












<body onload="document.getElementById('form').submit();">
    <form id="form" method="post" action="/">
        <input style="opacity:0;" type="checkbox" name="badpass" checked value="badpass"/>
    </form>
</body>

