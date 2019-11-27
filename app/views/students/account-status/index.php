

<form method="post" action="/?goto=/students/account-status/accountStatus.php">
    <table id="container">
        <tr>
            <th>
                Student Account Status Retrieval
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
                <br/>
                <div class="centered">
                    <button id="submitButton" onclick="showMessege('Gathering student account status please wait...')" type="submit">Submit</button>

                </div>

            </td>
        </tr>
    </table>


</form>

