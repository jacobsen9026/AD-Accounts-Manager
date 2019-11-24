<?php
if(isset($_POST["adminEmails"])){
    $appConfig["adminEmails"] = explode("\r\n",trim($_POST["adminEmails"])) ;
    saveConfig();

}
?>
<div class="settingsContainer">
        <form action="<?php echo $pageURL."#au_input"?>" method="post">

            <strong>
                Admin Email Addresses</strong><br/>Recieves all notification emails.<br/>


            <textarea placeholder="Enter list of emails, one per line." class="settingsList" name="adminEmails" rows="15" cols="31" spellcheck="false"><?php

                foreach($appConfig["adminEmails"] as $admin){

                    echo $admin."\n";

                }

                ?></textarea><?php
            if(isset($_POST["adminEmails"])){
                echo"<div class='alert'>GAdmin Usernames Updated Succefully!</div>";
            }
            ?>

            <br/><br/>
            <button id="au_input" type="submit" value="Update Admin Usernames">Update Admin Emails</button>
        </form>
    </div>