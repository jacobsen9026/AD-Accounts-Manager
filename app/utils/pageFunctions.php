<?php

function printGAUserGroups($username){
    global $appConfig;
    $result = doesGAUserExist($username);

    if($result != false){
        $groups=getGAUserGroups($result);
        echo ("<br/><strong>".getGAUserEmail($result)."</strong><br/>");
        echo ("<br/><strong>Google Groups: ".$numberOfGroups."</strong><br/>");
        foreach($groups as $group){
            echo $group[0]."<br/>";
            echo "<email onclick='copyThis(this)'>".$group[1]."</email><br/>";


        }	

    }else{
        echo "User not found in Google";
    }
}

function debug($message){
	//echo "debug";
    global $appConfig;
    if($appConfig["debugMode"]){
		//echo "debug mode on";
        if ((isset($_SESSION["authenticated_tech"]) and $_SESSION["authenticated_tech"]) or $appConfig["installComplete"]!=true){
            //echo "passed checks";
			if (is_array($message)){
				//echo "debug array";
                $message=debugArray($message);
            }
			//echo "debug string";
			
            $message = str_replace("\n","",$message);
            $bt = debug_backtrace(1);
            $caller = array_shift($bt);
            $caller["file"]=str_replace("\\","/",$caller['file']);
            $consoleMessage="Called From: ".$caller["file"]." Line: ".$caller["line"].$message;
            $htmlMessage="Called From: ".$caller["file"]." Line: ".$caller["line"]."<br/>".$message;
			//echo $htmlMessage;
            echo '<script>console.log("'.$consoleMessage.'")</script>';
?>
<script>document.getElementById("debugConsoleText").innerHTML=document.getElementById("debugConsoleText").innerHTML+"<?php //echo $htmlMessage;?><br/><br/><br/>"</script>


<?php
        }
    }
}

function debugArray($array){
	if(isset($array)){
		$message="<div>";
		foreach ($array as $name=>$option){
			if(is_array($option)){
				$message=$message."<strong>".$name."</strong><br/>";
				foreach ($option as $name=>$option2){

					$message = $message.$name.": ".var_export($option2,true)."<br/>";

				}
			}else{
				$message = $message."<strong>".$name."</strong><br/>".var_export($option,true)."<br/>";
			}
			$message=$message."<br/>";
		}
		$message=$message."</div>";
		return $message;
	}
}

function printGAUserGroupsEditable($username){
    global $appConfig;
    $result = doesGAUserExist($username);

    if($result != false){
        $groups=getGAUserGroups($result);


        echo ("<br/><strong>".getGAUserEmail($result)."</strong><br/>");
        echo ("<br/><strong>Google Groups:</strong><br/>");
        $counter=0;
        echo"<br />";
        foreach($groups as $group){
            if($group[0]!=""){
                $counter++;

                echo "<form action='/?goto=/students/google-groups/manageGoogleGroups.php' method='post'><table class='editableEmailEntry'><tr><td rowspan='2'>$counter</td><td><input name='username' value='$username' hidden><input name='group' value='".$group[1]."' hidden><input name='action' value='remove' hidden>".$group[0]."</td><td rowspan='2'><button type='submit' value='-'>Remove</button></td></tr>";
                echo "<tr><td><email onclick='copyThis(this)'>".$group[1]."</email></td></tr></table></form>";

            }
        }	


    }else{
        echo "User not found in Google";
    }
}

function printGAStaffGroupsEditable($username){
    global $appConfig;
    $result = doesGAUserExist($username);

    if($result != false){
        $groups=getGAUserGroups($result);


        echo ("<br/><strong>".getGAUserEmail($result)."</strong><br/>");
        echo ("<br/><strong>Google Groups:</strong><br/>");
        $counter=0;
        echo"<br />";
        foreach($groups as $group){
            if($group[0]!=""){
                $counter++;
?>
<form action='/?goto=/staff/google-groups/manageGoogleGroups.php' method='post'>
    <table class='editableEmailEntry'>
        <tr>
            <td rowspan='2'>
                <?php echo $counter;?>
            </td>
            <td>
                <input name='username' value='<?php echo $username;?>' hidden>
                <input name='<?php echo substr($group[1],0,strpos($group[1],"@"));?>' value='<?php echo substr($group[1],0,strpos($group[1],"@"));?>' hidden>
                <input name='action' value='remove' hidden>
                <?php echo $group[0];?>
            </td>
            <td rowspan='2'>
                <button onclick="showMessege('Removing staff from selected group please wait...')" type='submit' value='-'>Remove</button>
            </td>
        </tr>
        <tr>
            <td>
                <email onclick='copyThis(this)'><?php echo $group[1];?></email>
            </td>
        </tr>
    </table>
</form>
<?php
            }
        }	


    }else{
        echo "User not found in Google";
    }
}



function printParentGoogleGroupMembership($emailAddress){
    global $appConfig;
    debug("inside print parents");
    //include("./utils/googleFunctions.php");
    //include("./config/siteVariables.php");
    $groups = getParentGoogleGroupMembership($emailAddress);
    if(count($groups)>0){
        echo "$emailAddress is in the following groups:<br />";
        echo "<form action='/?goto=/parents/google-groups/manage/manageGoogleGroupMembership.php' method='POST'>";
        echo"<table  class='emailGroupList'>";
        foreach ($groups as $group){


?>
<tr>
    <td class="clickable" onclick='checkOnPage("<?php echo $group;?>")'>

        <?php echo $group;?>

    </td>
    <td>
        <input id='<?php echo $group;?>' type='checkbox' name='<?php echo $group;?>' value='<?php echo $group;?>'/><br/>
    </td>
</tr>

<?php
                                   }
        echo "<input name='action' value='remove' hidden/>";
        echo"</table>";
?>
<input name="email" value="<?php echo $emailAddress;?>" hidden />
<br /><br />
<button type='submit' onclick="showMessege('Removing email from selected groups please wait...')">Remove from selected groups</button>
</form>

<form method="post" action="/?goto=/parents/google-groups/manage/manageGoogleGroupMembership.php">

    <!--
<table id="container">
-->

    <tr>
        <th>

            Add this user to other groups
        </th>
    </tr>

    <tr>
        <td>

            <input type="text" name="email" value="<?php echo $_POST["email"];?>" hidden><br/>
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
            <table class="emailGroupList">
                <?php

        foreach ($appConfig["parentEmailGroups"]as $group){
            //echo "<tr><td>".$groups."->".$group."</td><td></td></tr>";
            if(!in_array($group,$groups)){
                ?>
                <tr>
                    <td>
                        <input id='<?php echo trim(preg_replace('/\s+/', ' ', $group));?>' type='checkbox' name='<?php echo trim(preg_replace('/\s+/', ' ', $group));?>' value='<?php echo trim(preg_replace('/\s+/', ' ', $group));?>'>
                    </td>
                    <td class='clickable' onclick="(checkOnPage('<?php echo str_replace("\"","",trim(preg_replace('/\s+/', ' ', $group)));?>'))">
                        <?php echo $group;?>
                    </td>
                </tr>
                <?php
            }
        }
                ?>

            </table>

            <!--
<a href="/students/account-status-page"><input  id="action_button"  type="button" value="Clear Form"></a>
-->

        </td>
    </tr>
    <tr>
        <td>

            <input name="action" value="add" hidden/>

            <button id="submitButton2" onclick="showMessege('Adding parent email to selected groups please wait...')" type="submit" value="Add to selected groups">Add to selected groups</button>


            <br /><br />
            <br /><br />

        </td>
    </tr>
    <!--
</table>
-->

</form>

<?php
    }else{


        echo "$emailAddress not found in any groups.";



    }
}

function printGAGroupMembers($group){
    global $appConfig;
    $groupMembers = getGAGroupMembers($group);
?>
<form action='/?goto=/students/google-groups/manageGoogleGroups.php' method='post'>
    <table class='editableEmailEntry'>
        <tr>
            <th colspan='3'>
                <?php echo	$group;?>
            </th>
        </tr>
        <tr>
            <td colspan='3'>
                <div id="runText2" class="runText">&nbsp </div><br/>
                <button id='submitButton2' onclick="showMessege2('Removing selected students from group please wait...')" class='centered' type="submit">Remove Selected Users from Group</button>
                <div id="loader2" class="loader" style="display:none;"></div>
            </td>
        </tr>
        <?php
    foreach($groupMembers as $groupMember){
        ?>
        <tr>
            <td style="width:10%;text-align:right;">

                <input type='checkbox' name='username[]' value='<?php echo $groupMember;?>' id='<?php echo $groupMember;?>'>
            </td>
            <td class='clickable left'  onclick='checkOnPage("<?php echo $groupMember;?>")'>
                <?php echo $groupMember;?>
            </td>
            <td>
                <form></form>
                <form style="vertical-align:center;height:100%;display:table-cell" action="/?goto=/students/google-groups/getGoogleGroups.php" method="post">
                    <input name="lookupUsername" value="<?php echo $groupMember;?>" hidden/>
                    <button type="submit"><small>User's Groups</small></button>
                </form>

            </td>
        </tr>
        <?php


    }
        ?>
        <tr>
            <td colspan='3' >
                <input name='group' value='<?php echo $group;?>' hidden>
                <input name='action' value='remove' hidden>
                <div id="runTex3t" class="runText">&nbsp </div><br/>
                <button id="submitButton3" onclick="showMessege3('Removing selected students from group please wait...')" class='centered' type="submit">Remove Selected Users from Group</button>
                <div id="loader3" class="loader" style="display:none;"></div>
            </td>
        </tr>
    </table>
</form>
<?php
}




function printGAParentGroupMembers($group){
    $groupMembers = getGAGroupMembers($group);
?>
<form action='/?goto=/parents/google-groups/manage/manageGoogleGroupMembership.php' method='post'>
    <table class='editableEmailEntry'>
        <tr>
            <th colspan='3'>
                <?php echo	$group;?>
            </th>
        </tr>
        <tr>
            <td colspan='3'>

                <button class='centered' type="submit">Remove Selected Users from Group</button>
            </td>
        </tr>
        <?php
    foreach($groupMembers as $groupMember){
        ?>
        <tr>
            <td style="width:10%;text-align:right;">

                <input type='checkbox' name='username[]' value='<?php echo $groupMember;?>' id='<?php echo $groupMember;?>'>
            </td>
            <td class='clickable left'  onclick='checkOnPage("<?php echo $groupMember;?>")'>
                <?php echo $groupMember;?>
            </td>
            <td>
                <form style="vertical-align:center;height:100%;" action="/?goto=/parents/google-groups/getGoogleGroupMembership.php" method="post">
                    <input name="email" value="<?php echo $groupMember;?>" hidden/>
                    <button onclick="show60SecondMessege('Searching through all parent email groups.<br/> This will take a while (~60 Seconds) please wait...')"  type="submit"><small>User's Groups</small></button>


                </form>

            </td>
        </tr>
        <?php


    }
        ?>
        <tr>
            <td colspan='3' >
                <input name='<?php echo $group;?>' value='<?php echo $group;?>' hidden>
                <input name='action' value='remove' hidden>
                <button class='centered' type="submit">Remove Selected Users from Group</button>
            </td>
        </tr>
    </table>
</form>
<?php
}





?>