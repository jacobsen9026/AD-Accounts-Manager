<div id="sessionTimeoutWarningContainer" class="sessionTimeoutWarningContainer">
    <table id="sessionTimeoutMessageContainer">
        <tr>
            <td>
                <div >Warning</div>
            </td>
        </tr>
        <tr>
            <td>
                <div  id="sessionTimeoutWarningText" class="sessionTimeoutWarningText">Your session is about to expire</div>
            </td>
        </tr>
        <tr>
            <td>
                <button onclick="refreshSession();">
                    Refresh Session
                </button>
            </td>
        </tr>
    </table>


</div>



<div id="sessionTimedOutContainer" class="sessionTimedOutContainer">
    <table id="sessionTimeoutMessageContainer">
        <tr>
            <td>
                <div ></div>
            </td>
        </tr>
        <tr>
            <td>
                <div  id="sessionTimeoutWarningText" class="sessionTimeoutWarningText">Please Log In</div>
            </td>
        </tr>
        <tr>
            <td>
                <form name="loginPrompt" method="post" action="/?goto=/login/challenge.php">
                    <?php
                    include("./login/loginPrompt.php");
                    ?>
                </form>
            </td>
        </tr>
    </table>


</div>



<?php





?>