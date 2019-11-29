
<?php

if(isset($_COOKIE["theme"])){
    if($_COOKIE["theme"]=="light"){
        echo '<link rel="stylesheet" type="text/css" href="/style/lightTheme.css">';
    }elseif ($_COOKIE["theme"]=="dark"){

        echo '<link rel="stylesheet" type="text/css" href="/style/darkTheme.css">';
    }

}else{
    echo '<link rel="stylesheet" type="text/css" href="/style/lightTheme.css">';

}
if (isset($_SESSION["authenticated_tech"]) and $appConfig["debugMode"] and $_SESSION["authenticated_tech"]){
    echo '<link rel="stylesheet" type="text/css" href="/style/sandbox.css">';
}
?>

<link rel="stylesheet" type="text/css" href="/css/lightTheme.css">
<link rel="stylesheet" media="screen and (max-width: 719px)" type="text/css" href="/css/style.css">

<link rel="stylesheet" media="screen and (max-width: 719px)" type="text/css" href="/css/mobilestyle.css">

<link rel="stylesheet" media="screen and (min-width: 720px)" type="text/css" href="/css/style.css">

