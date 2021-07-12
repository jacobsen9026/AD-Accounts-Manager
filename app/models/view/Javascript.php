<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace App\Models\View;

use App\App\App;
use System\App\Forms\Form;

/**
 * Description of Javascript
 * A tool for building JQuery functions
 *
 * @author cjacobsen
 */
abstract class Javascript extends ViewModel
{

    public static $spinner = '\' <div class="mr-5 d-inline-flex straight-loader text-secondary"></div><div class="ml-4 pl-3 d-inline-flex straight-loader delayed text-secondary"></div>\'';
    public static $hiddenSpinner = '<div class="mr-5 d-inline-flex straight-loader text-secondary hidden"></div><div class="ml-4 pl-3 d-inline-flex straight-loader delayed text-secondary hidden"></div>';


    public static $logInjectionScript = '  $.each(data.output.ajax.logs, function(key, value){
                                     //console.log(key+"_AJAX_Log_Panel");
                                    $("#"+key+"_AJAX_Log_Panel").append(value);
                                    });
                                    if(data.output.ajax.logs !=null && data.output.ajax.logs.hasErrors){
                                    $("#showDebugButton").removeClass("btn-primary");
                                    $("#showDebugButton").addClass("btn-danger");
                                    }';

    /**
     *
     * @param type $url
     * @param type $outputID
     * @param array $data
     * @param type $showLoading
     *
     * @return string
     */
    public static function buildAJAXRequest($url, $outputID = '', $data = null, $showLoading = false, $outputElement = 'html')
    {
        $ajaxCommand = '';
        //$spinner = '\'<div class="round-loader text-secondary"></div>\'';


        $jsonObject = 'data.output.ajax.' . $outputElement;


        if (is_array($data)) {
            $data['csrfToken'] = Form::getCSRFToken();
            $data = json_encode($data);
        }


        if ($outputID != '' and $showLoading) {
            $ajaxCommand .= '$("#' . $outputID . '").' . $outputElement . '(' . self::$spinner . ');';
        }


        if ($data != null) {
            $ajaxCommand .= '$.post("' . $url . '", ' . $data . ', function (data) {
                                    data = JSON.parse(data);

                                    $("#' . $outputID . '").' . $outputElement . '(' . $jsonObject . ');
                                    //console.log(data.output.ajax.logs);
                                    preparePageHooks();
                                    ' . self::$logInjectionScript . '

                            });';
        } else {
            $ajaxCommand .= '$.get("' . $url . '", function (data) {
                                    data = JSON.parse(data);
                                    $("#' . $outputID . '").' . $outputElement . '(' . $jsonObject . ');
                                    preparePageHooks();
                                    ' . self::$logInjectionScript . '
}
                            );';
        }
        return $ajaxCommand;
    }

    /**
     *
     * @param type $url
     * @param array $data
     *
     * @return string
     */
    public static function buildClientRequest($url, array $data = null)
    {
        $command = '';
        if (is_array($data)) {

            $data['csrfToken'] = Form::getCSRFToken();
            $command = '$.redirect("' . $url . '", ' . json_encode($data) . ');';
        } else {
            $command = 'document.location.href = "' . $url . '"';
        }

        return $command;
    }

    public static function onChange(string $listeningID, string $function)
    {
        $script = '$("#' . $listeningID . '").change(function (e) {
                        '
            . $function .
            '});';
        return $script;
    }

    /**
     *
     * @param type $inputID
     * @param type $function
     *
     * @return string
     */
    public static function on(string $inputID, string $function, string $triggers = "click")
    {
        $script = '     $("#' . $inputID . '").on("' . $triggers . '", function (e) {
                        '
            . $function .
            '});';
        return $script;
    }

    public static function copyToClipboard($id)
    {
        $script = 'console.log("click");
            var $temp = $("<input>");
  $("body").append($temp);
  console.log($("#' . $id . '").val());
  $temp.val($("#' . $id . '").val()).select();
  document.execCommand("copy");
  $temp.remove();';
        return $script;
    }

    public static function onPageLoad($function)
    {
        return "$(document).ready(function (e) {" . $function . "});";
    }

    /**
     *
     * @param string $requestURL
     * @param string $targetID
     *
     * @return string
     */
    public static function buildAutocomplete(string $requestURL, string $targetID, int $resultsShown = 5)
    {
        $script = ' $(function (e) {
        var ' . $targetID . ' = function (request, response) {
            $.getJSON(
                "' . $requestURL . '" + request.term,
                function (data) {
                    response(data.output.ajax.autocomplete);
                    ' . self::$logInjectionScript . '
                });
        };

        var selectItem = function (event, ui) {

            $("#' . $targetID . '").val(ui.item.value);
            return false;
        }

        $("#' . $targetID . '").autocomplete({
            source: ' . $targetID . ',
            select: selectItem,
            delay:450,
            minLength: 1,
            change: function() {
                $("#' . $targetID . '").css("display", 2);
            },
            open: function(event, ui){
                var $input = $(event.target),
                    $results = $input.autocomplete("widget"),
                    top = $results.position().top,
                    height = $results.height(),
                    inputHeight = $input.height(),
                    newTop = top - height - inputHeight,
                    leftBelow = $(document).height()- top - height ;
                console.log("height");
                console.log(height);
                console.log("top");
                console.log(top);
                console.log("leftBelow");
                console.log(leftBelow);
                if(height>=leftBelow+15){
                    $results.css("top", newTop + "px");
                }
            }

        });

    });';
        return $script;
    }

    public static function debug(string $string)
    {
        if (App::inDebugMode()) {
            return 'console.log("' . $string . '");';
        }
    }

}
