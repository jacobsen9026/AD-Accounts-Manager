<?php

use App\Models\Database\AuthDatabase;
use App\Models\View\Modal;

echo $this->view("/layouts/head");
?>

<body class="bg-light"
      onload="return setTimeout(sessionExpiring,<?= (AuthDatabase::getSessionTimeout() - 60) * 1000 ?>)">
<div class="w-100 h-100 p-0 m-0" onclick="$('#collapsibleNavbar').collapse('hide')">
    <div class="container container-fluid centered px-3 px-md-5">

        <?php
        $sessionExpireModal = new Modal();
        $sessionExpireModal->setTitle('Your session is about to expire')
            ->setId('sessionExpirationModal')
            ->setBody($this->modal('sessionExpiring'));
        echo $sessionExpireModal->print();

        ?>
        <script>

            function sessionExpiring() {
                $("#<?=$sessionExpireModal->getId()?>").modal('show');
                setTimeout(expireSession, 70 * 1000);
            }

            function resetSession() {
                $("#sessionExpirationModal").modal("hide");
                return setTimeout(sessionExpiring, <?= (AuthDatabase::getSessionTimeout() - 60) * 1000 ?>)
            }

            function expireSession() {
                window.location.href = "/logout";
            }
        </script>
        <div class=' centered text-center text_centered container container-fluid py-5 px-5 mt-8 mb-5 mx-auto shadow-lg bg-white'>




