
<form method="post" action="/?goto=/staff/password-reset/resetPassword.php">
    <table id="container">

        <tr>
            <th>

                Staff Password Reset
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
                Password (Blank for default)<br/>
                <input type="text" name="password"><br/><br/>
            </td>
        </tr>

        <tr style="text-align:center">
            <td>&nbsp;&nbsp;&nbsp;Google&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="width:10%" type="checkbox" name="reset_google" value="yes">
            </td>
        </tr>
        <tr style="text-align:center">
            <td><?php echo $appConfig['domainNetBIOS'];?>&nbsp;&nbsp;&nbsp;<input style="width:10%" type="checkbox" name="reset_ad" value="yes">
                <br/><br/>
            </td>
        </tr>





        <tr>
            <td>
                <div id="runText" style="color:red">&nbsp</div><br/>
                <button id="submitButton" onclick="showMessege('Attempting change on classroom please wait...')" type="submit" value="Reset Password">Reset Password</button>
                <a href="/?goto=/staff/password-reset/index.php"><button type="button" value="Clear Form">Clear Form</button></a>
            </td>
        </tr>
    </table>


</form>
