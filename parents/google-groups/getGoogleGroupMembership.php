<?php



/* 
 function send_message($id, $message, $progress) 
{
    $d = array('message' => $message , 'progress' => $progress);
     
    echo "id: $id" . PHP_EOL;
    echo "data: " . json_encode($d) . PHP_EOL;
    echo PHP_EOL;

    //PUSH THE data out by all FORCE POSSIBLE
    ob_flush();
    flush();
} */
?>
<table class="responseContainer" id="container">

	<tr>
		<th>
			Parent Google Group Status
		</th>
	</tr>
	<tr>
		<td>
			 <?php 

			if ($_POST["email"]!=null){
                $email=$_POST["email"];
				if(strpos($email,"@")!=false){

							//echo "before";
							//getParentGoogleGroupMembership($email);
							//echo "between";
						    printParentGoogleGroupMembership($email);

							echo "after";
							
							
							echo $result;
							

						
				}else{
					echo "Malformed email address: ".$email;
				}
			}else{
				echo "Missing Username.".$email;
			}
			?>
			<br/><br/>
		</td>
	</tr>
	<tr>
		<td>
			<a href='/?goto=/parents/google-groups/index.php'><button type='' value='Back'>Back</button></a>
		</td>
	</tr>
</table>


<!--</body>
</html>
-->
