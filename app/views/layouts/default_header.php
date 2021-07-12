<?php

use App\Models\Database\AppDatabase;
use App\Models\Database\AuthDatabase;
use App\Models\View\Javascript;
use App\Models\View\Modal;

echo $this->view("/layouts/head");


?>

<script>
    let sessionTimer = null;
    let finalMinuteTimer = null;

    function init() {
        startSessionTimer();
    }
</script>

<body class="bg-light"
      onload="init()">

<div class="container container-fluid centered px-0 px-sm-3 px-md-5">

    <?php
    $sessionExpireModal = new Modal();
    $sessionExpireModal->setTitle('Your session is about to expire')
        ->setId('sessionExpirationModal')
        ->setBody($this->modal('sessionExpiring'));
    echo $sessionExpireModal->print();

    ?>
    <script src="/js/sessionManager.js"></script>
    <script>
        const channel = new BroadcastChannel('<?= AppDatabase::getAppAbbreviation() ?>-ADAMWebsite');

        channel.postMessage('session_refreshed');

        channel.addEventListener('message', (event) => {
            switch (event.data) {
                case 'session_expiring':
                    console.log('Session expiring in another tab');
                    $("#<?=$sessionExpireModal->getId()?>").modal('show');
                    clearTimeout(finalMinuteTimer)
                    finalMinuteTimer = setTimeout(expireSession, 70 * 1000);
                    break;
                case 'session_refreshed':
                    console.log('Session refreshed from other tab');
                    $("#sessionExpirationModal").modal("hide");
                    startSessionTimer()
                    break;
                case 'session_expired':
                    console.log('Session expired in another tab');
                    window.location.href = window.location.pathname;
                    break;

            }
        });


        function startSessionTimer() {
            clearTimeout(sessionTimer);
            clearTimeout(finalMinuteTimer);
            <?=Javascript::debug('Starting session expiration timer: ' . (AuthDatabase::getSessionTimeout() - 60))?>
            sessionTimer = setTimeout(sessionExpiring, <?= (AuthDatabase::getSessionTimeout() - 60) * 1000 ?>)
        }

        function sessionExpiring() {
            channel.postMessage('session_expiring');
            $("#<?=$sessionExpireModal->getId()?>").modal('show');
            clearTimeout(finalMinuteTimer)
            finalMinuteTimer = setTimeout(expireSession, 70 * 1000);
        }


        function resetSession() {

            $("#sessionExpirationModal").modal("hide");
            channel.postMessage('session_refreshed');
            <?=Javascript::debug('Session refreshed')?>
            startSessionTimer();
        }

        function expireSession() {
            channel.postMessage('session_expired');
            window.location.href = window.location.pathname;
        }
    </script>
    <div class=' centered text-center text_centered container container-fluid py-5 px-1 px-md-5 mt-5 mt-md-8 mb-5 mx-auto shadow-lg bg-white'>




