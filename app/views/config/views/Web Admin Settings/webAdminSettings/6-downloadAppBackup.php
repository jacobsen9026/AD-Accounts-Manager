<?php
if (file_exists("./lib/gam-64/oauth2.txt")){
?>
<div class="settingsContainer">
    <div  class="settingsList">
        <div>
            <h3>Entire App Backup</h3>
            <small>Download a backup of everything</small>


        </div>
		<iframe id="downloadAppBackup" style="display:none;"></iframe>
        <div>
            <a href="/?download=/" target="_blank"><button type="button">Download</button></a>
        </div>
    </div>
</div>

<?php
}
?>


