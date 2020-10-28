


<h4>That username wasn't a perfect match</h4>
<h6>Possible matches</h6>
<?php





foreach ($params as $user){
    ?>
<div class="row">
    <div class="col"><a href="/users/search/<?=$user?>">
        <?php
    echo $user;
    ?>
        </a>
    </div>
</div>


<?php
}
