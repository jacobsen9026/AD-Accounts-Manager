<?php
if(isset($_POST["staffEmails"])){

    $appConfig["staffEmailGroups"] = explode("\r\n",trim($_POST["staffEmails"])) ;
    saveConfig();
}
?>
<div class="settingsContainer">
        <form action="<?php echo $pageURL."#se_input";?>" method="post">

            <strong>
                Staff Email Groups</strong><br/>
            <textarea placeholder="Enter staff email groups, one per line" class="settingsList" name="staffEmails" rows="20" cols="31" spellcheck="false"><?php
                foreach($appConfig["staffEmailGroups"] as $group){

                    echo $group."\n";

                }

                ?></textarea><?php
            if(isset($_POST["staffEmails"])){
                echo"<div class='alert'>Group Mappings Updated Succefully!</div>";
            }
            ?>

            <br/><br/>
            <button id="sgg_input" type="submit" value="Update Staff Groups">Update Staff Groups</button>
        </form>
    </div>