<?php
if(isset($_POST["adminUsernames"])){
    $appConfig["adminUsernames"] = explode("\r\n",trim($_POST["adminUsernames"])) ;
    saveConfig();

}
?>
<div class="settingsContainer">
        <form action="<?php echo $pageURL."#au_input";?>" method="post">

            <strong>
                Admin Usernames</strong><br/>Users in this list will not be able to<br/>be modified via the tools on this site.<br/>


            <textarea placeholder="Enter list of usernames, one per line." class="settingsList" name="adminUsernames" rows="14" cols="31" spellcheck="false"><?php

                foreach($appConfig["adminUsernames"] as $admin){

                    echo $admin."\n";

                }

                ?></textarea><?php
            if(isset($_POST["adminUsernames"])){
                echo"<div class='alert'>GAdmin Usernames Updated Succefully!</div>";
            }
            ?>

            <br/><br/>
            <button id="au_input" type="submit" value="Update Admin Usernames">Update Admin Usernames</button>
        </form>
    </div>