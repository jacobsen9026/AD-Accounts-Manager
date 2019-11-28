<form action="/?goto=/tech/google/createNonStaffEmail.php" method="post"> 
<table class="container">
<tr>
<th>
Google Apps Manager
</th>
</tr>
<tr>
	<td>
		<br/><strong>Create Non-Staff Email</strong>
<br/><br/>
		First Name:<br/>
		<input type="text" name="firstname" ><br/>
	</td>
</tr>
<tr>
	<td>
	
		Last Name:<br/>
		<input type="text" name="lastname" ><br/>
	</td>
</tr>
<tr>
<td>


Email Address:<br/>
<input class="usernameInput" type="text" name="username" autofocus><small>@<?php echo $appConfig["domainName"];?></small><br/>
</td>
</tr>
<tr>
	<td>
		Password:<br/>
		<input type="text" name="password" ><br/>
	</td>
</tr>
<tr>
	<td>
Category:
<select name="category">
	<option value="apple">Apple ID's</option>
	<option value="other">Other</option>
	<option value="system">System ID's</option>
</select>
<br/><br/>
<button onclick="showMessege('Creating account please wait...')" type="submit">Submit</button>

<br/><br/>
</td>
</tr>
</table>
</form>



