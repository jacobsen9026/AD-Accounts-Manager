<?= $this->extend('/layouts/default') ?>
<?= $this->section('content') ?>


    <div>
        <h3>
            Settings
        </h3>
    </div>
    <div>
        
            <?php //echo $_SESSION["userFirstName"]." ".$_SESSION["userLastName"];

            //if ($appConfig["debugMode"] and $_SESSION["authenticated_tech"]=="true"){
            //    echo "<br/><br/><strong>DEBUG MODE ENABLED</strong>";
            //}

            //var_dump($_SERVER);
            ?>

        </div>
    <div>
        
            <?php

            if(isset($appConfig["homepageMessage"]) and $appConfig["homepageMessage"][0]!=""){
                echo "<br/>";
                foreach ($appConfig["homepageMessage"] as $line){
                    echo $line."<br/>";
                }
            }

            //if ($_SESSION["authenticated_tech"]){
            ?>
            <br />If there are any errors displayed,
            <br />or a command that does not actually happen
            <br /> please <a href="https://github.com/jacobsen9026/School-Accounts-Manager/issues" target="_blank">enter a ticket</a>.
            <br/><br/>
            <?php
            //}
            ?>
            <!--            <img style="height:50%;border-radius:12%;box-shadow:0px 0px 15px #888888" onmouseover="this.style.boxShadow='0px 0px 50px #4285f4';" onmouseleave="this.style.boxShadow='0px 0px 15px #888888';" src="img/mobile.png"/>//-->
            <!--            <br/><br/>//-->
            This site is mobile friendly<br/>
        
    </div>

<?= $this->endSection() ?>