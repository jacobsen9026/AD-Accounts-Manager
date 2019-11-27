<?php
if(isset($_SESSION["authenticated_power"])){
    if($_SESSION["authenticated_power"]=="true"){
        if($_SESSION["authenticated_admin"]=="true" and $_SESSION["authenticated_tech"]!="true"){

        }else{
?>
<div>
    <a href="/?goto=/students/h-drive/index.php">
        <button<?php
            if(strpos($_SERVER['REQUEST_URI'],"/students/h-drive/")!==false){
                echo " class='currentPageButtonHighlight'";
            }
                ?>
                >
            H-Drive
        </button>
    </a>
</div>
<?php
        }
    }
}
?>