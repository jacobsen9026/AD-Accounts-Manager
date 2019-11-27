

<form method="post" action="/?goto=/students/classroom/getGoogleClassroom.php">
	<table id="container">
	
		<tr>
			<th>
			
				Student Google Group Classroom Reporter
			</th>
		</tr>
		
		<tr>
			<td>
				Classroom ID (Course Code/Section Number)<br/>
				<input type="text" name="classroomID" value="" autofocus><br/>
			</td>
		</tr>
		
		
		
		<tr>
			<td>
				<br/>
				<div class="centered">
				<button id="submitButton" onclick="showMessege('Gathering classroom information please wait...')" type="submit">Submit</button>
				</div>
				
			</td>
		</tr>
	</table>


</form>


<form method="post" action="/?goto=/students/classroom/manageGoogleClassroom.php">
<table id="container">

<tr>
<th>

Student Google Classroom Manager
</th>
</tr>

<tr>
<td>
Username<br/><small>You can enter multiple usernames seperated by commas</small><br/>
<input type="text" name="username"><br/>
</td>
</tr>
<tr>
<td>
Classroom ID (Course Code/Section Number)<br/><small>You can enter multiple classrooms seperated by commas</small><br/>
<input type="text" name="classroomID"><br/>
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
<button id="submitButton2" onclick="showMessege('Setting classroom configuration please wait...')" type="submit">Submit</button>
</div>
</td>
</tr>
</table>


</form>

