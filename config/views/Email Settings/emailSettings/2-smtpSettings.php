
<div class="shortSettingsContainer">
    <div  class="settingsList">
	
	
	
	
	
        <div>
            <h3>
                SMTP Email Server
            </h3>
        </div>


        <div class="required">
            <input placeholder="Enter FQDN" type="text" name="emailServerFQDN" value="<?php echo  $appConfig["emailServerFQDN"];?>">
        
		</div>


    
	
		  <div>
            <h3>
                SMTP Server Port
            </h3>
        </div>


        <div class="required">
            <input placeholder="Enter port number" type="text" name="emailServerPort" value="<?php echo  $appConfig["emailServerPort"];?>">
        </div>
	
	
	
	
	
	
	
	 <div>
            <h3>
                Use SMTP Authentication
            </h3>
        </div>


        <div>
            <?php //echo  $appConfig["useSMTPAuth"];
            //echo $_POST["useSMTPAuthCheck"];?>
            <input type="text" name="emailUseSMTPAuth" hidden/>
            <input type="checkbox"  name="emailUseSMTPAuthCheck" value="true" <?php //echo  $appConfig["useSMTPAuth"];
                   if(isset($appConfig["emailUseSMTPAuth"])){
                       if($appConfig["emailUseSMTPAuth"]){
                           echo "checked";
                       }
                   }
                   ?>>
        </div>
		
		
		
		
		
		

        <div>
            <h3>
                Use SSL over SMTP
            </h3>
        </div>


        <div>
            <?php //echo  $appConfig["useSMTPAuth"];
            //echo $_POST["useSMTPAuthCheck"];?>
            <input type="text" name="emailUseSSL" hidden/>
            <input type="checkbox"  name="emailUseSSLCheck" value="true" <?php //echo  $appConfig["useSMTPAuth"];
                   if(isset($appConfig["emailUseSSL"])){
                       if($appConfig["emailUseSSL"]){
                           echo "checked";
                       }
                   }
                   ?>>
        </div>





		<div>
            <h3>
                SMTP Username
            </h3>
        </div>


        <div>
            <input placeholder="Enter username" type="text" name="emailAuthUsername" value="<?php echo  $appConfig["emailAuthUsername"];?>">
        </div>
		
		
		
		
		
		
		
		
		
	   <div>
            <h3>
                SMTP Password
            </h3>
        </div>


        <div>
            <input placeholder="Enter password" type="password" name="emailAuthPassword" value="<?php echo  $appConfig["emailAuthPassword"];?>">
        </div>




		
		
		
		<div>
            <h3>
                Email From Address
            </h3>

        </div>
        <div class="required">

            <input placeholder="jdoe@example.com" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" name="emailFromAddress" value="<?php echo  $appConfig["emailFromAddress"];?>">

        </div>	
		



		
		
		
		
		
		
		
		
		  <div>
            <h3>
                Email From Name
            </h3>

        </div>
        <div class="required">

            <input placeholder="John Doe" type="text" name="emailFromName" value="<?php echo  $appConfig["emailFromName"];?>">

        </div>
		
		
		
		
		
		
		
		
		
		<div>
            <h3>
                Email Reply To Address
            </h3>

        </div>
        <div>

            <input placeholder="jdoe@example.com" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" name="emailReplyToAddress" value="<?php echo  $appConfig["emailReplyToAddress"];?>">

        </div>
		
		
		
		
		
		
		
		
		
		
		
		
		<div>
            <h3>
                Email Reply To Name
            </h3>

        </div>
        <div>

            <input placeholder="John Doe" type="text" name="emailReplyToName" value="<?php echo  $appConfig["emailReplyToName"];?>">

        </div>
		
		
		
		
		
		
		

	</div>
	
</div>