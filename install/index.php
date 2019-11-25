<?php
//var_export($_POST);
if(isset($_POST["complete_install"])){
    //echo $_POST["complete_install"];
	//exit();
    
	initializeConfig();
?>
<script>
    window.location="/";
</script>
<?php
}



function isGitAvailable(){
	$result=shell_exec("git");
	if(strpos($result,"--version")>0){
		return true;
	}
	return false;
}



if (isset($_GET["advancedConfig"])){
	include("./config/includes/configController.php");
	include("./config/index.php");
	
}else{
?>

<table id="container">
    <tr>
        <th>
            Installation
    </tr>
    <tr>
        <td>


            <br/>
            <table class="settingsList">
			<!--
				<tr>
                    <td>Git Available</td>
                    <td>
                        <?php
                       /* if(isGitAvailable()){
                            $gitChecked=true;
                            echo "Yes";
                        }else{
                            echo "No";
                        }
						*/
                        ?>
                    </td>

                </tr>
				-->
                <tr>
                    <td>LDAP Extension Enabled</td>
                    <td>
                        <?php
                        if(extension_loaded("ldap")){
                            $ldapChecked=true;
                            echo "Yes";
                        }else{
                            echo "No";
                        }
                        ?>
                    </td>

                </tr>
                <tr>
                    <td>Administrator Privelege</td>
                    <td>
                        <?php
                        if(testAdministrator()){
                            $adminChecked=true;
                            echo "Yes";
                        }else{
                            echo "<text class='red'>No</text>";
                        }
                        ?>
                    </td>

                </tr>

                <tr>

                    <td>Admin Password</td>
                    <td>
                        <?php
                        if(isset($appConfig["adminPassword"]) and $appConfig["adminPassword"] !=''){
                            $passwordChecked=true;
                            echo "Yes";
                        }else{
                            echo "<text class='red'>No</text>";
                        }
                        ?>
                    </td>

                </tr>
				<tr>

                    <td>Domain Name</td>
                    <td>
                        <?php
                        if(isset($appConfig["domainName"]) and $appConfig["domainName"]!=''){
                            $domainNameChecked=true;
                            echo "Yes";
                        }else{
                            echo "<text class='red'>No</text>";
                        }
                        ?>
                    </td>

                </tr>
				<tr>

                    <td>Web App Name</td>
                    <td>
                        <?php
                        if(isset($appConfig["webAppName"]) and $appConfig["webAppName"]!=''){
                            $webAppNameChecked=true;
                            echo "Yes";
                        }else{
                            echo "<text class='red'>No</text>";
                        }
                        ?>
                    </td>

                </tr>


            </table>
            <br/><br/>
        </td>
    </tr>
	<form action="/?goto=/config/index.php&advancedConfig=true" method="post">
	<tr>
				
                <td>
                    <input type="text" name="advancedConfig" value="true" hidden />
                        <button type="submit">
                            Initial Config
                        </button>
						<br/><br/>
				</td>
                   
	</tr>
	 </form>
    <?php

    if($webAppNameChecked and $domainNameChecked and $passwordChecked and $adminChecked and !$appConfig["installComplete"]){
    ?>

    <tr>
        <td>
            <?php 
			
			
			if(!$ldapChecked){
        echo "<strong>You won't be able to log in via<br/>Active Directory/LDAP credentials.<br/><br/>Only the admin user will work.</strong><br/><br/><br/>";
    }
	
            ?>
            <form action="<?php echo $pageURL;?>" method="post">

                All Checks Passed<br/><br/>
                <input name="complete_install" value="yes" hidden/>
                <button  type="submit" value="Complet Install">Finish</button>
            </form>
        </td>
    </tr>
</table>
<?php
    }
	}
?>



