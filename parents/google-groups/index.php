
<form method="post" action="/?goto=/parents/google-groups/getGoogleGroupMembership.php">
<table id="container">

<tr>
<th>

Parent Google Group Membership Lookup
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
<button id="submitButton" onclick="show60SecondMessege('Searching through all parent email groups.<br/> This will take a while (~60 Seconds) please wait...')" type="submit" value="Submit">Submit</button>

<!--
<a href="/students/account-status-page"><input  id="action_button"  type="button" value="Clear Form"></a>
-->
</td>
</tr>
</table>


</form>







<form method="post" action="/?goto=/parents/google-groups/getGoogleGroupMembers.php">
<table id="container">

<tr>
<th>

Parent Google Group Members
</th>
</tr>

<tr>
<td>
Group<br/>
<select class="text_centered" name="group">
	<?php foreach($appConfig["parentEmailGroups"] as $group){
	?>
		
		<option value="<?php echo $group;?>"><?php echo $group;?></option>
		<?php
	}
	?>
  
</select><br/>
</td>
</tr>
<tr>
<td>
<div class="centered">
<button id="submitButton2" onclick="showMessege('Gathering group members status please wait...')" type="submit">Submit</button>
</div>
</td>
</tr>
</table>


</form>


