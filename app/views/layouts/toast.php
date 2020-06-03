<div class="<?= $params['toastClasses'] ?>" style="z-index:2000">
    <div class="toast mx-auto" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="true">
        <div class="toast-header">
            <?= $params['image'] ?>

            <strong class="ml-1 mr-auto">
                <?= $params['header'] ?></strong>
            <?php
            if ($params['timeout'] === 0 || $params['closable']) {
                ?>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php
            }
            ?>
        </div>
        <div class="toast-body">
            <?= $params['body'] ?>
        </div>
        <script>

            $(".toast").toast({'animation': true, 'autohide': true, 'delay':<?=$params['timeout']?>});
            $(".toast").toast("show");
        </script>
    </div>
</div>