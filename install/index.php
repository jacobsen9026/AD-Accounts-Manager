<?php
//var_export($_POST);
if(isset($_POST["complete_install"])){
    //echo $_POST["complete_install"];
    $appConfig["installComplete"]=true;
    $appConfig["sessionTimeout"]=1200;
    saveConfig();
?>
<script>
    window.location="/";
</script>
<?php
}

if(isset($_POST["to"])){
    sendEmail($_POST["to"],"Test Email","This is a test notification from the School Accounts Manager.");
}

if(isset($appConfig["adminPassword"])){
    $passwordChecked=true;
}
?>
<table id="container">
    <tr>
        <th>
            Installation
    </tr>
    <tr>
        <td>

            <?php 

            //include("./config/includes/yearOfGraduation.php");
            //include("./config/includes/studentGoogleGroups.php");
            //include("./config/includes/staffEmailGroups.php");
            //include("./config/includes/parentEmailGroups.php");
            //include("./config/includes/adminUsernames.php");
            //include("./config/includes/adminEmails.php");
            //include("./config/includes/accessLevels.php");
            //include("./config/includes/domainName.php");
            //include("./config/includes/domainController.php");
            //include("./config/includes/domainNetbios.php");
            include("./config/includes/resetAdminPassword.php");
            //include("./config/includes/sessionTimeout.php");
            //include("./config/includes/websiteFQDN.php");
            //include("./config/includes/redirectHTTP.php");
            //include("./config/includes/debugMode.php");
            //include("./config/includes/editWelcomeEmail.php");
            //include("./config/includes/logonAudit.php");
            //phpinfo();
            ?>


            <br/>
            <table class="settingsList">
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
                        if(isset($appConfig["adminPassword"])){
                            $passwordChecked=true;
                            echo "Yes";
                        }else{
                            echo "<text class='red'>No</text>";
                        }
                        ?>
                    </td>

                </tr>
 <form action="/" method="post">
                <tr>
                    <td><input type="text" name="to"/></td>
                    <td>
                        <button type="submit">
                            Send Test Email
                        </button>
                    </td>

                </tr>
                    </form>
            </table>
            <br/><br/>
        </td>
    </tr>
    <?php

    if($passwordChecked and $adminChecked and !$appConfig["installComplete"]){
    ?>

    <tr>
        <td>
            <?php if(!$ldapChecked){
        echo "<strong>You won't be able to log in via<br/>Active Directory/LDAP credentials.<br/><br/>Only the admin user will work.</strong><br/><br/><br/>";
    }
            ?>
            <form action="<?php echo $pageURL;?>" method="post">

                No going back. More settings inside.<br/><br/>
                <input name="complete_install" value="yes" hidden/>
                <button  type="submit" value="Complet Install">Finish</button>
            </form>
        </td>
    </tr>
</table>
<?php
    }
?>



