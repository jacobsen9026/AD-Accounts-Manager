
<form method="post" action="/?goto=/students/h-drive/manageHDrive.php">
<table id="container">

<tr>
<th>
Student H-Drive Utility
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
<select name="action">
<option value="query">Query
<option value="fix">Fix Permissions



</select>
</td>

<tr>
<td>
<br/>
<div class="centered">
<button id="submitButton" onclick="showMessege('Getting/Setting student h-drive information please wait...')" type="submit">Submit</button>
</div>

<br />
<a href="/?goto=/students/h-drive/index.php"><div class="centered">
<button type="button">Clear Form</button>
</div></a>
</td>
</tr>
</table>


</form>

