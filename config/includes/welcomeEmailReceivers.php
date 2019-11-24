<?php
if(isset($_POST["welcomeEmailReceivers"])){
    $appConfig["welcomeEmailReceivers"] = explode("\r\n",trim($_POST["welcomeEmailReceivers"])) ;
    saveConfig();

}
?>
<div class="settingsContainer">
        <form action="<?php echo $pageURL."#we_input"?>" method="post">

            <strong>
                Welcome Email Recipients</strong><br/>Blinded all welcome notification emails.<br/>


            <textarea placeholder="Enter list of emails, one per line." class="settingsList" name="welcomeEmailReceivers" rows="15" cols="31" spellcheck="false"><?php

                foreach($appConfig["welcomeEmailReceivers"] as $admin){

                    echo $admin."\n";

                }

                ?></textarea><?php
            if(isset($_POST["welcomeEmailReceivers"])){
                echo"<div class='alert'>Welcome Email Recipients Updated Succefully!</div>";
            }
            ?>

            <br/><br/>
            <button id="we_input" type="submit" value="Update Welcome Email Recipients">Update Welcome Email Recipients</button>
        </form>
    </div>