




<form name="loginPrompt" method="post" action="/?goto=/login/challenge.php">
	<table id="container">
		<tr>
			<td>
						<table id="loginPopupContainer">
		        <tr>
		            <th>
		                School Accounts Manager
		            </th>
		        </tr>
		        <tr>
		            <td>
		                <div style="width:100%;text-align:center;">
		                    <?php
		                    if(isset($_POST['badpass'])){
		
		                    ?>
		                    <script>var toast = new iqwerty.toast.Toast('Bad Username/Password');</script>
		                    <span style="color:red;font-size:0.6em;">Bad Username/Password</span><br/>
		
		                    <?php
		
		                    }
		                    if(isset($_POST['notauthorized'])){
		
		                    ?>
		                    <script>var toast = new iqwerty.toast.Toast('Not Authorized');</script>
		                    <span style="color:red;font-size:0.6em;">Not Authorized</span><br/>
		
		                    <?php
		
		                    }
		                    if(isset($_SESSION)){
		                        if(isset($timedOut)){
		                            if($timedOut=="true"){
		                                $timedOut="";
		                                session_unset();
		                    ?>
		
		                    <span style="color:black;font-size:0.6em;">Your session has timed out. Please log in again.</span><br/>
		
		                    <?php
		                            }
		                        }
		                    }
		                    ?>
		
		                    Username
		                    <input name="intent" value="<?php echo $goto;?>" hidden>
		                </div>
		                <input <?php if (!isset($_COOKIE["username"])) { ?>autofocus<?php } ?> type="text" name="username" value="<?php if (isset($_COOKIE["username"])) { echo $_COOKIE["username"]; } ?>" autocomplet /><br/>
		                Password
		                <input <?php if (isset($_COOKIE["username"])) { ?>autofocus<?php } ?> type="password" name="password" autocomplet/><br/>
		
		                <br />
		                <br /><div style="cursor:hand;" style="width:50%;height:3em;vertical-align:middle;display:inline">
		                <text onclick="checkOnPage('rememberUsernameCheckbox')">Remember Username</text> 
		                <input id="rememberUsernameCheckbox" style="cursor:hand;width:2em;vertical-align:middle;" type="checkbox" name="rememberUsername" <?php if (isset($_COOKIE["username"])) { echo 'checked=true'; }?>/>
		                <?php //print_r($_COOKIE);?>
		                </div><br/><br/>
		                <button type="submit">Login</button>
		
		            </td>
		        </tr>
		
		    </table>
			</td>
		</tr>
	</table>
   
</form>
<div id="disclaimer">This site is restricted to authorized personnel only. Violators will be logged and subject to discipline, termination, or legal action.
</div>
