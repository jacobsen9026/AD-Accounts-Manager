
<form method="post" action="/?goto=/staff/send-welcome-email/sendWelcomeEmail.php">
<table class="container">

<tr>
<th>

Send Staff Welcome Email
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
<div id="runText" style="color:red">&nbsp</div><br/>
<button id="submitButton" onclick="showMessege('Sending staff member welcome email please wait...')" type="submit" value="Submit">Submit</button>
<!--
<a href="/students/account-status-page"><input  id="action_button"  type="button" value="Clear Form"></a>
-->
</td>
</tr>
</table>


</form>