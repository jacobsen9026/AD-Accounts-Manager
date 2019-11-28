
<div class="shortSettingsContainer">
    <div  class="settingsList">
        <div>
            <h3>
                Test AD Credentials
            </h3>
           
        </div>


        <div>
            <?php //echo  $appConfig["testADCredentials"];
            //echo $_POST["testADCredentialsCheck"];?>
            <input type="text" name="testADCredentials" hidden/>
            <input type="checkbox"  name="testADCredentialsCheck" value="true" <?php //echo  $appConfig["testADCredentials"];
                   if(isset($appConfig["testADCredentials"])){
                       if($appConfig["testADCredentials"]){
                           echo "checked";
                       }
                   }
                   ?>>
        </div>
		<div>
		</div>
		<div style = "width:100%;height:100%; text-align:center;display:none;" id="testingADCredentials">
		<div style="margin-left:auto; margin-right:auto; width:150px;height:150px;" class="loader"></div><br/>
		Testing Credentials...
	</div>
    </div>
	<script>
		fillWithHTTPResponse('testingADCredentials','/?grab=/config/testAdCredentials.php');
	</script>
	




</div>