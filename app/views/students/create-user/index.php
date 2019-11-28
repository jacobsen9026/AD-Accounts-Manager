
<form method="post" action="/?goto=/students/create-user/createUser.php">
<table class="container">

<tr>
<th>
Student New User Creator
</th>
</tr>

<tr>
<td>
	<br/>
	<div style="max-width:290px;text-align:center;margin-left:auto;margin-right:auto;">
<small>	
Please check for account existance with the <a href="/?goto=/students/account-status/index.php">Account Status</a> tool first before creating any new accounts.<br/> <br/> 
Check <i>(Year of Graduation)(First Initial)(Last Name)</i>[Optionally with a trailing 1 or 2]</small>
</div>
<br/><br/>
First Name<br/>
<input type="text" name="firstname" autofocus><br/>
</td>
</tr>
<tr>
<td>
Middle Inital<br/>
<input type="text"  name="middlename" maxlength="1"><br/>
</td>
</tr>
<tr>
<td>
Last Name<br/>
<input type="text" name="lastname"><br/>
</td>
</tr>
<tr>
<td>
Grade<br/>
<select name="grade">
  <option value="PK3">PK3</option>
  <option value="PK4">PK4</option>
  <option value="K">K</option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>
  <option value="6">6</option>
  <option value="7">7</option>
  <option value="8">8</option>
</select><br/>
</td>
</tr>
<tr>
<td>
Password (Blank for random)<br/>
<input type="text" name="password"><br/><br/>
</td>
</tr>
<tr style="text-align:center">
<td>&nbsp;&nbsp;&nbsp;Google&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="width:10%" type="checkbox" name="create_google" checked value="yes">
</td>
</tr>
<tr style="text-align:center">
<td><?php echo $appConfig['domainNetBIOS'];?>&nbsp;&nbsp;&nbsp;<input style="width:10%" type="checkbox" name="create_ad" checked value="yes">
<br/><br/>
</td>
</tr>
<tr>
<td>
<br/>
<div class="centered">
<button id="submitButton" onclick="showMessege('Creating user account(s) please wait...')" type="submit">Create User</button>
</div>

<br />

<a href="/?goto=/students/create-user/index.php"><div class="centered">
<button type="button">Clear Form</button>
</div></a>
</td>
</tr>
</table>


</form>

