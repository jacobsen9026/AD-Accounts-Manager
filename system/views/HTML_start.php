<?php

use System\App\Forms\Form;

?>
<html lang="en">
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>


    <!-- Additional JQuery Dependencies -->
    <!--<link href="https://code.jquery.com/ui/1.11.4/themes/flick/jquery-ui.css" rel="stylesheet">-->
    <!--<link href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet">-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script><?php use App\Models\View\Javascript;

        echo system\File::getContents(ROOTPATH . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'jquery.redirect.js'); ?></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.30.7/js/jquery.tablesorter.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        //Custom JQuery to enable data-text-alt tag on btn class objects
        jQuery(function ($) {
            $('.btn[data-toggle="collapse"]').on('click', function () {
                console.log($(this).data('text-alt'));
                $(this).data('text-original', $(this).text());
                $(this).text($(this).data('text-alt'));
                $(this).data('text-alt', $(this).data('text-original'));
            });
            $(document).on('click', '.clickable[data-text-alt]', function () {
                console.log($(this).data('text-alt'));
                $(this).data('text-original', $(this).html());
                $(this).html($(this).data('text-alt'));
                $(this).data('text-alt', $(this).data('text-original'));
            });
           
        });
        //Custom JQuery for district settings navigation
        jQuery(function ($) {


            // remove active class from all
            var location = window.location.pathname.split('/')[1];
// remove active class from all
            $(".nav .nav-item").removeClass('active');
            console.log('.nav-item a[href=' + location + '*]');
// add active class to div that matches active url
            $(".nav-item a[href='/" + location + "']").addClass('active');
        });

        $(document).ready(function () {
            $(".resizable-y").resizable({
                    handles:
                        "n, s"
                }
            );
        });
        $(document).ready(function () {
            $(".resizable-x").resizable({
                    handles:
                        "e, w"
                }
            );
        });
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
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>