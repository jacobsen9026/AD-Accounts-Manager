
<table class="responseContainer" id="container">

<tr>
<th>
Staff Welcome Email
</th>
</tr>
<tr>
<td>
 <?php 

if ($_POST["username"]!=""){
	
		
	
		$cmd = "gam info user ".$_POST["username"] ;
	
	

	
			$result = shell_exec($cmd);
			if($result==null){?>
			Email Account does not exist.<br/>
			<?php
				
			}else{
			
			include($_SERVER['DOCUMENT_ROOT']."/config/sendMail.php");
			}
		
	
}else{
echo "Missing Username.";
}


?>
<br/><br/>
<!--
<input onclick="CopyToClipboard('username')" type="button" value="Copy Username">
<input onclick="CopyToClipboard('password')" type="button" value="Copy Password">
-->
<a href='/?goto=/staff/send-welcome-email/index.php'><button type='button' value='Back'>Back</button></a>
</td>
</tr>
</table>
