
<form id="formID" method="post" action="/?goto=/students/password-reset/resetPassword.php">
	<table class="container">
	
		<tr>
		<th>
		
		Student Password Reset
		</th>
	</tr>
	
	<tr>
		<td>
		<div style="margin:0 auto;max-width:300px;"><small>Please try to reset to the same password as is listed in Genesis for this student. If you use a different one please update Genesis afterwards.</small></div><br/>
		Username<br/>
		<input style="max-width:50%" type="text" name="username" autofocus><small>@<?php echo $appConfig["domainName"];?></small><br/>
		</td>
	</tr>
	<tr>
		<td>
		Password (Blank for random)<br/>
		<input type="text" name="password"><br/><br/>
		</td>
	
	</tr>
	<tr style="text-align:center">
		<td>
			&nbsp;&nbsp;&nbsp;Google&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="width:10%" type="checkbox" name="reset_google" checked value="yes">
		</td>
	</tr>
	<tr style="text-align:center">
		<td><?php echo $appConfig['domainNetBIOS'];?>&nbsp;&nbsp;&nbsp;<input style="width:10%" type="checkbox" name="reset_ad" checked value="yes">
		<br/><br/>
		</td>
	</tr>
	<tr>
		<td>
			<br/>
			<div class="centered">
			<button id="submitButton" onclick="showMessege('Resetting student password please wait...')" type="submit">Reset Password</button>
			</div>
			
			<br />
			
			<a href="/?goto=/students/password-reset/index.php"><div class="centered">
			<button type="button">Clear Form</button>
			</div></a>
		</td>
	</tr>
</table>


</form>

