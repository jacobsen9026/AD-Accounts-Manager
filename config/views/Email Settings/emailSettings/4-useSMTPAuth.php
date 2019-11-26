
<div class="shortSettingsContainer">
    <div  class="settingsList">
        <div>
            <h3>
                Use SMTP Authentication
            </h3>
        </div>


        <div>
            <?php //echo  $appConfig["useSMTPAuth"];
            //echo $_POST["useSMTPAuthCheck"];?>
            <input type="text" name="useSMTPAuth" hidden/>
            <input type="checkbox"  name="useSMTPAuthCheck" value="true" <?php //echo  $appConfig["useSMTPAuth"];
                   if(isset($appConfig["useSMTPAuth"])){
                       if($appConfig["useSMTPAuth"]){
                           echo "checked";
                       }
                   }
                   ?>>
        </div>
    </div>




</div>