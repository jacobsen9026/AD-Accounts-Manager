

<form method="post" action="/?goto=/students/account-status-change/accountStatusChange.php">
    <table class="container">

        <tr>
            <th>

                Student Account Lock/Unlock Manager
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
                Action:

            </td>
        </tr>
        <tr>
            <td style="text-align:center;">
                Un-Lock
                <input style="width:10%" type="radio" name="action" value="unlock" checked>
            </td>
        </tr>

        <?php
        if($_SESSION["authenticated_tech"]=="true"){?>
        <tr>
            <td style="text-align:center;">

                Lock
                <input style="width:10%;margin-right:0px;" type="radio" name="action" value="lock">

            </td>
        </tr>

        <?php
        }
        ?>

        <tr>
            <td>
                <br/>
                <div class="centered">
                    <button id="submitButton" onclick="showMessege('Performing requested action please wait...')" type="submit" value="">Submit</button>
                </div>
                <!--
<a href="/students/account-status-page"><input  id="action_button"  type="button" value="Clear Form"></a>
-->
            </td>
        </tr>
    </table>


</form>
