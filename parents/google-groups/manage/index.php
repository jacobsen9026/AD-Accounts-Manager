
<form method="post" action="/?goto=/parents/google-groups/manage/manageGoogleGroupMembership.php">
<table id="container">

<tr>
<th>

Parent Google Group Membership Manager
</th>
</tr>

<tr>
<td>
Email Address<br/>
<input type="text" name="email" autofocus><br/>
</td>
</tr>



<!--
<tr>
<td style="text-align:center;">

Lock
<input style="width:10%;margin-right:0px;" type="radio" name="action" value="lock">

</td>
</tr>

-->

<tr>
<td>
<table class="emailGroupList">
<?php

	foreach ($appConfig["parentEmailGroups"] as $group){
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

<!--
<a href="/students/account-status-page"><input  id="action_button"  type="button" value="Clear Form"></a>
-->

</td>
</tr>
<tr>
<td>
Action<br/>
<select name="action">
  <option value="add">Add</option>
  <option value="remove">Remove</option>
</select><br/>
<div id="runText" style="color:red">&nbsp</div><br/>
<button id="submitButton" onclick="showMessege('Adding parent email to selected groups please wait...')" type="submit" value="Submit">Submit</button>
<div id="loader" class="loader" style="display:none;"></div>


</td>
</tr>
</table>


</form>

</body>

</html>