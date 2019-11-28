<?php
if($_SESSION["authenticated_tech"]=="true"){





if(isset($_GET["page"])){
	?>

<iframe src="/edit?page=<?php echo $_GET["page"];?>">Browser not compatible.</iframe>

<?php
}else{
?>
<iframe src="/edit">Browser not compatible.</iframe>

<?php
}
}else{
	
echo "<div id='container' style='text-align:center'>You really shouldn't be here.</div>";
}
?>