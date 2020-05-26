<?php

use System\App\Forms\Form;

?>
<html class="no-js" lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Latest compiled and minified CSS    -->
    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!-- jQuery library -->
    <!--    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>

    <!-- Popper JS -->
    <!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <!-- Latest compiled JavaScript -->
    <!--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>-->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>

    <!-- Additional JQuery Dependencies -->
    <!--<link href="https://code.jquery.com/ui/1.11.4/themes/flick/jquery-ui.css" rel="stylesheet">-->
    <!--<link href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet">-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script><?= system\File::getContents(ROOTPATH . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'jquery.redirect.js') ?></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.30.7/js/jquery.tablesorter.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script src="js/vendor/modernizr-3.11.2.min.js"></script>

    <script>

        //TouchPunch
        !function (a) {
            function f(a, b) {
                if (!(a.originalEvent.touches.length > 1)) {
                    a.preventDefault();
                    var c = a.originalEvent.changedTouches[0], d = document.createEvent("MouseEvents");
                    d.initMouseEvent(b, !0, !0, window, 1, c.screenX, c.screenY, c.clientX, c.clientY, !1, !1, !1, !1, 0, null), a.target.dispatchEvent(d)
                }
            }

            if (a.support.touch = "ontouchend" in document, a.support.touch) {
                var e, b = a.ui.mouse.prototype, c = b._mouseInit, d = b._mouseDestroy;
                b._touchStart = function (a) {
                    var b = this;
                    !e && b._mouseCapture(a.originalEvent.changedTouches[0]) && (e = !0, b._touchMoved = !1, f(a, "mouseover"), f(a, "mousemove"), f(a, "mousedown"))
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


</head>