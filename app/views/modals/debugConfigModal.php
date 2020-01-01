<div id="debugConfigModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h4 class="modal-title ">Config</h4>
                <button type="button" class="close text-light" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <div id="ajaxOutput"><span class="spinner-border text-primary" role="status"></span><br/>Loading Configuration Data... </div>
                <p class="text-break "><script>
                    $('#debugConfigButton').on('click', function () {
                        var queryString = 'debugConfig';
                        //$('#ajaxOutput').hide();
                        $.post('/api/draw', {query: queryString},
                                function (data) {
                                    //request completed
                                    //now update the div with the new data
                                    $('#ajaxOutput').html(data);
                                }
                        );
                        //$('#ajaxOutput').slideDown('slow');
                    });

                    </script>

                    <?php
                    /**
                      foreach (\system\Database::get()->getAllTables()as $table) {
                      echo $table;
                      var_dump(\system\Database::get()->query('SELECT * FROM ' . $table)[0]);
                      }
                     *
                     */
                    ?>

                </p>
            </div>

        </div>

    </div>
</div>