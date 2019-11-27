
<div class="shortSettingsContainer">
    <div  class="settingsList">
        <div>
            <h3>
                Redirect to HTTPS
            </h3>
            <small>
                <?php //echo  $appConfig["redirectHTTP"];
                //echo $_POST["redirectHTTPCheck"];

                if(isset($appConfig["redirectHTTP"])){
                    if ($appConfig["redirectHTTP"]){
                        if (strtolower(explode("=",$_SERVER['HTTPS_SERVER_ISSUER'])[1])==strtolower($_SERVER['SERVER_NAME'])){
                            echo "You are using a self-signed certificate.<br/>Understand the risks of allowing this<br/>to be published on the internet.";

                        }
                    }else{
                        echo "You are not using HTTPS.<br/>Understand the risks of allowing this<br/>to be published on the internet.";
                    } 
                }else{
                    echo "You are not using HTTPS.<br/>Understand the risks of allowing this<br/>to be published on the internet.";
                }  
                ?>
            </small>
        </div>

        <div>
            <input type="text" name="redirectHTTP" hidden/>
            <input type="checkbox"  name="redirectHTTPCheck" value="true" <?php //echo  $appConfig["redirectHTTP"];
                   if(isset($appConfig["redirectHTTP"])){
                       if($appConfig["redirectHTTP"]){
                           echo "checked";
                       }
                   }
                   ?>>
        </div>



    </div>

</div>