
<form method="post" action="/?goto=/staff/account-status/accountStatus.php">
	<table id="container">
	
	<tr>
	<th>
	
	Staff Account Status Retrieval
	</th>
	</tr>
	
	<tr>
	<td>
	Username<br/>
	<input style="max-width:50%" type="text" name="username" autofocus><small>@<?php echo $appConfig["domainName"];?></small><br/>
	</td>
	</tr>
	
	
	
	<tr>
	<td>
	<div id="runText" style="color:red">&nbsp</div><br/>
	<button id="submitButton" onclick="showMessege('Gathering staff account status please wait...')" type="submit" value="Submit">Submit</button>
	<!--
	<a href="/students/account-status-page"><input  id="action_button"  type="button" value="Clear Form"></a>
	-->
	</td>
	</tr>
	</table>


</form>