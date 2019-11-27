

<form method="post" action="/?goto=/staff/google-groups/getGoogleGroups.php">
<table id="container">

<tr>
<th>

Staff Google Group Status Retrieval
</th>
</tr>

<tr>
<td>
Username<br/>
<input style="max-width:50%" type="text" name="username" autofocus><small>@<?php echo $appConfig["domainName"];?></small><br/>
</td>



<tr>
<td>
<div id="runText" style="color:red">&nbsp </div><br/>
<button id="submitButton" onclick="showMessege('Gathering staff google group status please wait...')" type="submit" value="Submit">Submit</button>

</td>
</tr>
</table>


</form>


<form method="post" action="/?goto=/staff/google-groups/manageGoogleGroups.php">
<table id="container">

<tr>
<th>

Staff Google Group Manager
</th>
</tr>

<tr>
<td>
Username<br/>
<input style="max-width:50%" type="text" name="username"><small>@<?php echo $appConfig["domainName"];?></small><br/>
</td>
</tr>
<tr>
<td>
<table class="emailGroupList">
<?php
foreach ($appConfig["staffEmailGroups"] as $group){
	?>
	<tr>
				<td>
					<input id='<?php echo trim(preg_replace('/\s+/', ' ', $group));?>' type='checkbox' name='<?php echo trim(preg_replace('/\s+/', ' ', $group));?>' value='<?php echo trim(preg_replace('/\s+/', ' ', $group));?>'>
				</td>
				<td class='clickable' onclick="(checkOnPage('<?php echo str_replace("\"","",trim(preg_replace('/\s+/', ' ', $group)));?>'))">
						<?php echo $group;?>
				</td>
			</tr>
			<?php
}
?>

</table>
</td>
</tr>
<tr>
<td>
Action<br/>
<select name="action">
  <option value="add">Add</option>
  <option value="remove">Remove</option>
</select><br/>
</td>
</tr>


<tr>
<td>
<div id="runText2" style="color:red">&nbsp </div><br/>
<button id="submitButton2" onclick="showMessege2('Gathering student account status please wait...')" type="submit" value="Submit">Submit</button>

</td>
</tr>
</table>


</form>

