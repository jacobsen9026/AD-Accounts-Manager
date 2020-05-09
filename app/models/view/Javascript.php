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

namespace app\models\view;

/**
 * Description of Javascript
 * A tool for building JQuery functions
 *
 * @author cjacobsen
 */
use system\app\forms\Form;

abstract class Javascript extends ViewModel {

    /**
     *
     * @param type $url
     * @param type $outputID
     * @param array $data
     * @param type $showLoading
     * @return string
     */
    public static function buildAJAXRequest($url, $outputID = '', $data = null, $showLoading = false, $outputElement = 'html') {
        $ajaxCommand = '';
        $spinner = '\'<span class="spinner-border text-primary" role="status"></span>\'';
        if (is_array($data)) {
            $data['csrfToken'] = Form::getCSRFToken();
            $data = json_encode($data);
        }
        if ($outputID != '' and $showLoading) {
            $ajaxCommand .= '$("#' . $outputID . '").' . $outputElement . '(' . $spinner . ');';
        }
        if ($data != null) {
            $ajaxCommand .= '$.post("' . $url . '", ' . $data . ', function (data) {
                                    $("#' . $outputID . '").' . $outputElement . '(data);
                                    }
                            );';
        } else {
            $ajaxCommand .= '$.get("' . $url . '", function (data) {
                                    $("#' . $outputID . '").' . $outputElement . '(data);
                                    }
                            );';
        }
        return $ajaxCommand;
    }

    /**
     *
     * @param type $url
     * @param array $data
     * @return string
     */
    public static function buildClientRequest($url, array $data = null) {
        $command = '';
        if (is_array($data)) {

            $data['csrfToken'] = Form::getCSRFToken();
            $command = '$.redirect("' . $url . '", ' . json_encode($data) . ');';
        } else {
            $command = 'document.location.href = "' . $url . '"';
        }

        return $command;
    }

    /**
     *
     * @param type $inputID
     * @param type $function
     * @return string
     */
    public static function onClick(string $inputID, string $function) {
        $script = '     $("#' . $inputID . '").on("click", function () {
                        '
                . $function .
                '});';
        return $script;
    }

    public static function copyToClipboard($id) {
        $script = 'console.log("click");
            var $temp = $("<input>");
  $("body").append($temp);
  console.log($("#' . $id . '").val());
  $temp.val($("#' . $id . '").val()).select();
  document.execCommand("copy");
  $temp.remove();';
        return $script;
    }

}
