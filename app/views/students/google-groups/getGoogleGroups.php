<table class="responseContainer" >

<tr>
<th>
Student Google Group Membership
</th>
</tr>
<tr>
<td sytle="text-align:center;font-size:1em;">
 <?php 
$username = $_POST["lookupUsername"];
if ($username!=""){
	if(substr($username,0,2)>21 and substr($username,0,2)<99){
	printGAUserGroupsEditable($username);
	}else{
		echo "This can only be used for students";
	}
}else{
echo "Missing Username.";
}
?>
<br/><br/>
<a href='/?goto=/students/google-groups/index.php'><button type='button' value='Back'>Back</button></a>
</td>
</tr>
</table>
