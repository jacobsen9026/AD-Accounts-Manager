
<form method="post" action="/?goto=/staff/create-user/createUser.php">
<table class="container">

<tr>
<th>
New User Creator
</th>
</tr>

<tr>
<td>
First Name<br/>
<input type="text" name="firstname"><br/>
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
	<div class="radioList floatingPanel">

<div class="centered">Primary Location</div>
	<input type="radio" name="primaryLocation" value="BCMS" checked >BCMS<br/>
	<input type="radio" name="primaryLocation" value="SBS">SBS<br/>
	<input type="radio" name="primaryLocation" value="WES">WES
</div>
</td>
</tr>

<tr>
<td>
<strong>Groups</strong>
<div style="width:100%;height:250px;overflow-y:scroll;">
<table class="emailGroupList">
<?php
foreach ($appConfig["staffEmailGroups"] as $group){
	
echo "<tr><td><input id='".trim(preg_replace('/\s+/', ' ', $group))."' type='checkbox' name='".trim(preg_replace('/\s+/', ' ', $group))."' value='".trim(preg_replace('/\s+/', ' ', $group))."'></td><td class='clickable' ";?>onclick="(checkOnPage('<?php echo str_replace("\"","",trim(preg_replace('/\s+/', ' ', $group)));?>'))" <?php echo ">".$group."</td></tr>\r";
}
?>

</table>
</div>
</td>
</tr>
<tr style="text-align:center">
<td><br/><strong>Create account for</strong><br/>&nbsp;&nbsp;&nbsp;Google&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="width:10%" type="checkbox" name="reset_google" checked value="yes">
</td>
</tr>
<tr style="text-align:center">
<td><?php echo $appConfig['domainNetBIOS'];?>&nbsp;&nbsp;&nbsp;<input style="width:10%" type="checkbox" name="reset_ad" checked value="yes">
<br/><br/>
</td>
</tr>
<tr>
<td>
<div id="runText" style="color:red">&nbsp</div><br/>
<button id="submitButton" onclick="showMessege('Creating new staff member accounts please wait...')" type="submit" value="Create User">Create User</button>
<a href="/?goto=/staff/create-user/index.php"><button type="button" value="Clear Form">Clear Form</button></a>
</td>
</tr>
</table>


</form>


