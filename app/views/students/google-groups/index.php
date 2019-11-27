

<form method="post" action="/?goto=/students/google-groups/getGoogleGroups.php">
	<table id="container">
	
		<tr>
			<th>
				Student Google Group Status Retrieval
			</th>
		</tr>
		<tr>
			<td>
				Username<br/>
				<input style="max-width:50%" type="text" name="lookupUsername" autofocus><small>@<?php echo $appConfig["domainName"];?></small><br/>
			</td>
		</tr>
		<tr>
			<td>
				<br/>
				<div class="centered">
					<button id="submitButton" onclick="showMessege('Gathering student group membership status please wait...')" type="submit">Submit</button>
				</div>
			</td>
		</tr>
	</table>


</form>




<form method="post" action="/?goto=/students/google-groups/getGoogleGroupMembers.php">
<table id="container">

<tr>
<th>

Student Google Group Members
</th>
</tr>
<tr>
<td>
Group<br/>
<select class="text_centered" name="group">
  <option value="<?php echo $appConfig["studentEmailGroups"]["PK3"];?>"><?php echo $appConfig["studentEmailGroups"]["PK3"];?></option>
  <option value="<?php echo $appConfig["studentEmailGroups"]["PK4"];?>"><?php echo $appConfig["studentEmailGroups"]["PK4"];?></option>
  <option value="<?php echo $appConfig["studentEmailGroups"]["K"];?>"><?php echo $appConfig["studentEmailGroups"]["K"];?></option>
  <option value="<?php echo $appConfig["studentEmailGroups"]["1"];?>"><?php echo $appConfig["studentEmailGroups"]["1"];?></option>
  <option value="<?php echo $appConfig["studentEmailGroups"]["2"];?>"><?php echo $appConfig["studentEmailGroups"]["2"];?></option>
  <option value="<?php echo $appConfig["studentEmailGroups"]["3"];?>"><?php echo $appConfig["studentEmailGroups"]["3"];?></option>
  <option value="<?php echo $appConfig["studentEmailGroups"]["4"];?>"><?php echo $appConfig["studentEmailGroups"]["4"];?></option>
  <option value="<?php echo $appConfig["studentEmailGroups"]["5"];?>"><?php echo $appConfig["studentEmailGroups"]["5"];?></option>
  <option value="<?php echo $appConfig["studentEmailGroups"]["6"];?>"><?php echo $appConfig["studentEmailGroups"]["6"];?></option>
  <option value="<?php echo $appConfig["studentEmailGroups"]["7"];?>"><?php echo $appConfig["studentEmailGroups"]["7"];?></option>
  <option value="<?php echo $appConfig["studentEmailGroups"]["8"];?>"><?php echo $appConfig["studentEmailGroups"]["8"];?></option>
</select><br/>
</td>
</tr>
<tr>
<td>
<br/>
<div class="centered">
<button id="submitButton2" onclick="showMessege('Gathering group members please wait...')" type="submit">Submit</button>
</div>
</td>
</tr>
</table>


</form>




<form method="post" action="/?goto=/students/google-groups/manageGoogleGroups.php">
<table id="container">

<tr>
<th>

Student Google Group Manager
</th>
</tr>

<tr>
<td>
Username<br/>
<input style="max-width:50%" type="text" name="username"><small>@branchbrug.k12.nj.us</small><br/>
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
Action<br/>
<select name="action">
  <option value="add">Add</option>
  <option value="remove">Remove</option>
</select><br/>
</td>
</tr>


<tr>
<td>
<br/>
<div class="centered">
<button id="submitButton3" onclick="showMessege('Setting student group membership please wait...')" type="submit">Submit</button>
</div>
</td>
</tr>
</table>


</form>

