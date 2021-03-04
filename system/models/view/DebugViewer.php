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

namespace System\Models\View;

/**
 * Description of LogViewer
 *
 * @author cjacobsen
 */

use App\Models\View\Javascript;
use System\App\Forms\FormButton;
use System\Log\CommonLogger;
use System\Log\CommonLogEntry;

class DebugViewer
{

    /**
     *
     * @param array $loggers
     *
     * @return string
     */
    public static function printDebugTools(array $loggers)
    {


        $toggleDebugToolsOutputFunction = "$('.debugToolboxContainer').toggle();";
        $buttonColorClass = 'primary';
        if (self::errors_exist($loggers)) {
            $buttonColorClass = 'danger';
        }
        $logToolsOutput = ' <button type="button" id = "showDebugButton" class="mx-auto btn btn-' . $buttonColorClass . '  dark-shadow" data-toggle="modal" data-target="#logsModal" onclick="' . $toggleDebugToolsOutputFunction . '">Debug</button>';

        $logToolsOutput .= '<div class="debugToolboxContainer resizable-y bg-dark" style="display: none;">';
        $logToolsOutput .= '<div class="container-fluid mb-0 bg-secondary text-light text-center pb-2 h2 debugToolboxContainerHandle">'
            . 'Debug Tools'
            . '<button type = "button" class = "close" data-dismiss = "modal" onclick="' . $toggleDebugToolsOutputFunction . '">&times;
                            </button>';
        $logToolsOutput .= '</div>'
            . '<script>'
            . '$(function () {$(".resizable-y").resizable({
                    maxHeight: "100vh",
                    handles:"n, s"
                }
                );
                });'
            . '</script>';


        $logToolsOutput .= self::printLogTools($loggers);


        $resizeBoundaryFunction = '<script>'
            . '$( window ).resize(function() {
                    position=$( ".debugToolboxContainer" ).css("top");

                    bottomBuffer = ($(window).height() - 50 );
                      if($( ".debugToolboxContainer" ).css("top") < "0px"){
                     $(".debugToolboxContainer").css("top","0px");
                    }
                      if(parseInt($( ".debugToolboxContainer" ).css("top")) >= bottomBuffer){
                     $(".debugToolboxContainer").css("top",bottomBuffer);
                    }
                    });'
            . '</script>';
        $logToolsOutput .= $resizeBoundaryFunction;

        return $logToolsOutput;
    }

    /**
     * @param array $loggers
     *
     * @return bool
     */
    public static function errors_exist(array $loggers): bool
    {
        /* @var $logger CommonLogger */
        foreach ($loggers as $logger) {
            /*  @var $entry CommonLogEntry */
            if ($logger !== null and $logger->hasLogEntries()) {
                foreach ($logger->getLogEntries() as $entry) {
                    if ($entry->getLevel() == 'error') {
                        return true;
                    }
                }
            }
        }
        return false;

    }

    public static function printLogTools($loggers)
    {
        $logToolsOutput = '<div class = "border-0 text-light">
<ul id="loggerToolboxMenusList" class = "tab-list nav nav-pills bg-secondary row mx-0" role="tablist">';
        $logToolsOutput .= self::printLogGroupButton('Runtime Logs', 'loggerRuntimeLogs', 'showRuntimeLogButtton');
        $logToolsOutput .= self::printLogGroupButton('AJAX Logs', 'loggerAJAXLogs', 'showAJAXLogButtton');
        $logToolsOutput .= '</ul>

        </div>

        <div class = "pt-0 tab-content log-groups-content">';

        $logToolsOutput .= self::printRuntimeLogs($loggers);
        $logToolsOutput .= self::printAJAXLogs($loggers);
        $logToolsOutput .= '</div>';
        return $logToolsOutput;
    }

    /**
     *
     * @param string $label
     * @param string $targetShowID
     * @param string $listenerButtonID
     *
     * @return type
     */
    public static function printLogGroupButton(string $label, string $targetShowID, string $listenerButtonID): ?string
    {
        $tabButton = new FormButton(ucfirst($label));
        $tabButton->setId($listenerButtonID)
            ->setTheme('dark')
            ->setType($listenerButtonID);
        $function = '$(".log-groups-content .active").removeClass("active");'
            . '$("#' . $targetShowID . '").addClass("active");';
        $script = Javascript::on($listenerButtonID, $function);
        $tabButton->setScript($script);
        $tabButton->setBreakPoint('');
        return $tabButton->print();
    }

    /**
     * @param $runtimeLoggers
     *
     * @return string
     */
    private static function printRuntimeLogs($runtimeLoggers): string
    {

        $runtimeLogs = '<div id="loggerRuntimeLogs" class = "tab-pane container-fluid  p-0 active">

                         <ul id="SystemLoggingMenuList" class = "tab-list nav nav-pills bg-secondary row mx-0" role="tablist">
                         <div class="container-fluid row">';

        $loggerOutputs = [];
        /* @var $logger CommonLogger */
        $logger = $runtimeLoggers[0];
        $loggerPanelID = $logger->getName() . '_Runtime_Log_Panel';
        $loggerOutputs[] = self::prepareRuntimeLoggerOutput($logger, $loggerPanelID);

        $runtimeLogs .= '</div></ul>';

        $runtimeLogs .= '<div class = "pt-0 tab-content log-tab-content loggerOutputContainer">';
        foreach ($loggerOutputs as $loggerOutput) {
            $runtimeLogs .= $loggerOutput;
        }
        $runtimeLogs .= '</div>';

        $runtimeLogs .= '</div>';


        return $runtimeLogs;
    }


    /**
     * @param $logger
     * @param $loggerPaneID
     *
     * @return string
     */
    public static function prepareRuntimeLoggerOutput($logger, $loggerPaneID): string
    {
        $loggerOutput = LogPrinter::printLog($logger);
        return $loggerOutput;
    }

    /**
     * @param $runtimeLoggers
     *
     * @return string
     */
    public static function printAJAXLogs($runtimeLoggers): string
    {
        $ajaxLog = '<div id="loggerAJAXLogs" class = "tab-pane container-fluid loggerOutputContainer p-0" sytle="max-height:100%;overflow:auto">

                        ';
        $loggerPanelID = $runtimeLoggers[0]->getName() . '_AJAX_Log_Panel';
        $ajaxLog .= '<div class = "tab-pane container-fluid  p-0" id = "' . $loggerPanelID . '" role="tabpanel">';

        $ajaxLog .= '</div>';
        //$ajaxLog .= '<div class = "pt-0  loggerOutputContainer">';

        //$ajaxLog .= '</div>';
        $ajaxLog .= '</div>';
        // . '</div>'
        // . '</div>';


        return $ajaxLog;
    }


}
