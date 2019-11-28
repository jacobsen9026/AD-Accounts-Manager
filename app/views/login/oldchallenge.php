<?php 
session_start();

    if (hash("sha256",$_POST["password"])=="a5d36c3a6d9aca6791b97831911925f08d06b5a1094dcbe848efeb7338de43b2"){
        $_SESSION["authenticated_basic"]="true";
        $_SESSION["authenticated_power"]="false";
        $_SESSION["authenticated_admin"]="false";
        $_SESSION["authenticated_tech"]="false";
        $_SESSION['timeout'] = time();
        if($_POST["intent"]!=""){
            header( 'Location: '.$_POST['intent'] );
        }else{
            header( 'Location: /' ) ;
        }
    }elseif (hash("sha256",$_POST["password"])=="73669103f57783f4c553a054b34c19acae50c163d341d48121b1fa7736091620"){
        $_SESSION["authenticated_basic"]="true";
        $_SESSION["authenticated_power"]="true";
        $_SESSION["authenticated_admin"]="false";
        $_SESSION["authenticated_tech"]="false";
        $_SESSION['timeout'] = time();
        if($_POST["intent"]!=""){
            header( 'Location: /?goto='.$_POST['intent'] );
        }else{
            header( 'Location: /' ) ;
        }
    }elseif (hash("sha256",$_POST["password"])=="fd400501e6ab72a9fe371267786ef3773f495f19082f90800affd354a3dca591"){
        $_SESSION["authenticated_basic"]="true";
        $_SESSION["authenticated_power"]="true";
        $_SESSION["authenticated_admin"]="true";
        $_SESSION["authenticated_tech"]="false";
        $_SESSION['timeout'] = time();
        if($_POST["intent"]!=""){
            header( 'Location: /?goto='.$_POST['intent'] );
        }else{
            header( 'Location: /' ) ;
        }
    }elseif (hash("sha256",$_POST["password"])=="0d563ac23336847d2b8e3b9108585aa9ae3f3bd6c99e151882201451d7da3ef7"){
        $_SESSION["authenticated_basic"]="true";
        $_SESSION["authenticated_power"]="true";
        $_SESSION["authenticated_admin"]="true";
        $_SESSION["authenticated_tech"]="true";
        $_SESSION['timeout'] = time();
        if($_POST["intent"]!=""){
            header( 'Location: /?goto='.$_POST['intent'] );
        }else{
            header( 'Location: /' ) ;
        }
    }else{
?>
<body onload="document.getElementById('form').submit();">
    <form id="form" method="post" action="/">
        <input style="opacity:0;" type="checkbox" name="badpass" checked value="badpass"/>
    </form>
</body>
<?php
        //header( 'Location: /login' ) ;
    }

?>