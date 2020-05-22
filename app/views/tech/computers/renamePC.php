<table class="responseContainer">
    <tr>
        <th>
            Rename Workstation
        </th>
    </tr>
    <tr>
        <td>
            <br/><br/>
            <?php
            if (isset($_POST["currentPCName"])) {
                if (isset($_POST["newPCName"])) {
                    if ($_POST["currentPCName"] != "") {
                        if ($_POST["newPCName"] != "") {
                            $currentPCName = $_POST["currentPCName"];
                            $newPCName = $_POST["newPCName"];
                            if (hostReachable($currentPCName)) {
                                echo renameADComputer($currentPCName, $newPCName);
                            } else {
                                echo "PC not reachable";
                            }
                        } else {
                            echo "Missing new PC name.";
                        }
                    } else {
                        echo "Missing current PC name.";

                    }
                }
            }
            ?>
            <br/><br/>
            <a href='/?goto=/tech/computers/index.php'>
                <button type='button' value='Back'>Back</button>
            </a>
        </td>
    </tr>
</table>
