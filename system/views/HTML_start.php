<?php

use System\File;

?>
<html class="no-js" lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>

    <!-- Popper JS -->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>


    <!-- Dependency Styling -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- Custom JQuery UI without Tooltip widget -->
    <script><?= File::getContents(ROOTPATH . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'jquery.redirect.js') ?></script>
    <script><?= File::getContents(ROOTPATH . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'jquery-ui.js') ?></script>

    <!--
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.30.7/js/jquery.tablesorter.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
-->
    <script>

        //TouchPunch
        !function (a) {
            function f(a, b) {
                if (!(a.originalEvent.touches.length > 1)) {
                    a.preventDefault();
                    var c = a.originalEvent.changedTouches[0], d = document.createEvent("MouseEvents");
                    d.initMouseEvent(b, !0, !0, window, 1, c.screenX, c.screenY, c.clientX, c.clientY, !1, !1, !1, !1, 0, null),
                        a.target.dispatchEvent(d)
                }
            }

            if (a.support.touch = "ontouchend" in document, a.support.touch) {
                var e, b = a.ui.mouse.prototype, c = b._mouseInit, d = b._mouseDestroy;
                b._touchStart = function (a) {
                    var b = this;
                    !e && b._mouseCapture(a.originalEvent.changedTouches[0]) && (e = !0, b._touchMoved = !1, f(a, "mouseover"), f(a,
                        "mousemove"), f(a, "mousedown"))
                }, b._touchMove = function (a) {
                    e && (this._touchMoved = !0, f(a, "mousemove"))
                }, b._touchEnd = function (a) {
                    e && (f(a, "mouseup"), f(a, "mouseout"), this._touchMoved || f(a, "click"), e = !1)
                }, b._mouseInit = function () {
                    var b = this;
                    b.element.bind({
                        touchstart: a.proxy(b, "_touchStart"),
                        touchmove: a.proxy(b, "_touchMove"),
                        touchend: a.proxy(b, "_touchEnd")
                    }), c.call(b)
                }, b._mouseDestroy = function () {
                    var b = this;
                    b.element.unbind({
                        touchstart: a.proxy(b, "_touchStart"),
                        touchmove: a.proxy(b, "_touchMove"),
                        touchend: a.proxy(b, "_touchEnd")
                    }), d.call(b)
                }
            }
        }(jQuery);
        //End TouchPunch


    </script>

    <meta name="theme-color" content="#ffffff">

    <style>
        <?php

        include(ROOTPATH .
        DIRECTORY_SEPARATOR .
        "system" .
        DIRECTORY_SEPARATOR .
        "system.css"
        );
        ?>
    </style>

</head>