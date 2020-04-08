
<div class="shortSettingsContainer">
            <div  class="settingsList">
                <div>
                    <h3>
                        Send Test Email
                    </h3>
					
					
                </div>
                <div>
<?php
if(isset($appConfig["emailFromAddress"]) and isset($appConfig["emailFromName"]) and isset($appConfig["emailServerFQDN"]) and isset($appConfig["emailServerPort"]) and $appConfig["emailFromAddress"]!="" and $appConfig["emailFromName"]!="" and $appConfig["emailServerFQDN"]!="" and $appConfig["emailServerPort"]!=""){
?>        
<style>
.required input{
	border-color: initial;
}
</style>          
				  <input placeholder="Enter Email Address" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" name="testEmailTo"/>
<?php
				  }
				  else
				  {
					  ?>
					<input placeholder="Please complete SMTP information" type="text" disabled />
					  <?php
				  }
					if(isset($testEmailResult)){
					  ?>
					  <script>window.alert('<?php echo $testEmailResult;?>');</script>
					  
					  
					  <?php
					  echo "<small>".$testEmailResult."</small>";

				  }
				  
				 
				  ?>
                </div>
                
            </div>
           
    </div>
