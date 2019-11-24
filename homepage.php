
<table id="container">
    <tr>
        <th>
            School Accounts Manager

        </th>
    </tr>
    <tr>
        <td>
            <?php echo $_SESSION["userFirstName"]." ".$_SESSION["userLastName"];

            if ($appConfig["debugMode"] and $_SESSION["authenticated_tech"]=="true"){
                echo "<br/><br/><strong>DEBUG MODE ENABLED</strong>";
            }

            //var_dump($_SERVER);
            ?>

        </td></tr>
    <tr>
        <td>
            <br/><br/>
            Use these tools with caution, for most functions there is no undo.
            <br />
            <br />If there are any errors displayed,
            <br />or a command that does not actually happen
            <br /> please <a href="https://github.com/jacobsen9026/School-Accounts-Manager/issues/new" target="_blank">enter a ticket</a>.
            <br/><br/>

            <!--            <img style="height:50%;border-radius:12%;box-shadow:0px 0px 15px #888888" onmouseover="this.style.boxShadow='0px 0px 50px #4285f4';" onmouseleave="this.style.boxShadow='0px 0px 15px #888888';" src="img/mobile.png"/>//-->
            <!--            <br/><br/>//-->
            This site is mobile friendly<br/>
        </td>
    </tr>
</table>
<div class="homepage_footer centered"><?php
    if ($_SESSION["authenticated_tech"]=="true") {
        echo "Access Level: Technology";
        //echo "<br/>Tech: ".$_SESSION["authenticated_tech"];
        //echo "<br/>Admin: ".$_SESSION["authenticated_admin"];
        //echo "<br/>Power: ".$_SESSION["authenticated_power"];
        //echo "<br/>Basic: ".$_SESSION["authenticated_basic"];
    }elseif ($_SESSION["authenticated_admin"]=="true") {
        echo "Access Level: Office";
    }elseif ($_SESSION["authenticated_power"]=="true") {
        echo "Access Level: Tech Teacher";
    }elseif ($_SESSION["authenticated_basic"]=="true") {
        echo "Access Level: Teacher";
    }else{
        echo "Currently Not Logged In";
    }

    ?>
</div>