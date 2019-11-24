<?php
if(isset($_POST["parentEmails"])){
    $appConfig["parentEmailGroups"] = explode("\r\n",trim($_POST["parentEmails"])) ;

    saveConfig();
}
?>
<div class="settingsContainer">
        <form action="<?php echo $pageURL."#pe_input";?>" method="post">

            <strong>
                Parent Email Groups</strong><br/>
            <textarea  placeholder="Enter parent email groups, one per line" class="settingsList" name="parentEmails" rows="20" cols="31" spellcheck="false"><?php
                foreach($appConfig["parentEmailGroups"] as $group){

                    echo $group."\n";

                }

                ?></textarea>
            <?php
            if(isset($_POST["parentEmails"])){
                echo"<div class='alert'>Group Mappings Updated Succefully!</div>";
            }
            ?>

            <br/><br/>
            <button id="se_input" type="submit" value="Update ParentOf Groups">Update ParentOf Groups</button>
        </form>
    </div>