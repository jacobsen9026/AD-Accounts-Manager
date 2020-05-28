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

use System\App\Forms\FormButton;
use System\Core;
use System\File;
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


        // return;
        //return $loggerOutputs[0];
        $toggleDebugToolsOutputFunction = "$('.debugToolboxContainer').toggle();";
        $buttonColorClass = 'primary';
        if (self::errors_exist($loggers)) {
            $buttonColorClass = 'danger';
        }
        $logToolsOutput = ' <button type="button" id = "showDebugButton" class="mx-auto btn btn-' . $buttonColorClass . '  dark-shadow" data-toggle="modal" data-target="#logsModal" onclick="' . $toggleDebugToolsOutputFunction . '">Debug</button>';
        $logToolsOutput .= "<style>" . File::getContents(ROOTPATH . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "system.css") . "</style>";

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

        // $logToolsOutput .= '<div class="debugOutputContainer text-left  bg-dark debug-toolbar-tab">';


        $logToolsOutput .= self::printLogTools($loggers);


        $resizeBoundaryFunction = '<script>'
            . '$( window ).resize(function() {
                    position=$( ".debugToolboxContainer" ).css("top");

                    //console.log($( ".debugToolboxContainer" ).css("top"));
                    bottomBuffer = ($(window).height() - 50 );
                    //console.log(bottomBuffer);
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
        $first = true;
        $loggerOutputs = [];
        /* @var $logger CommonLogger */
        // foreach ($runtimeLoggers as $logger) {
        $logger = $runtimeLoggers[0];
        /** if ($logger == null) {
         * continue;
         * }*/
        $loggerPanelID = $logger->getName() . '_Runtime_Log_Panel';
        $loggerTabButtonID = $loggerPanelID . "_Tab";
        $runtimeLogs .= self::printLogButton($logger, $loggerPanelID, $loggerTabButtonID);
        $loggerOutputs[] = self::prepareRuntimeLoggerOutput($logger, $loggerPanelID);
        // }
        $runtimeLogs .= '</div></ul>';

        $runtimeLogs .= '<div class = "pt-0 tab-content log-tab-content loggerOutputContainer">';
        foreach ($loggerOutputs as $loggerOutput) {
            $runtimeLogs .= $loggerOutput;
        }
        $runtimeLogs .= '</div>';

        $runtimeLogs .= '</div>';
        //. '</div>'
        //. '</div>';


        return $runtimeLogs;
    }

    /**
     * @param $runtimeLoggers
     *
     * @return string
     */
    public static function printAJAXLogs($runtimeLoggers): string
    {
        $ajaxLog = '<div id="loggerAJAXLogs" class = "tab-pane container-fluid  p-0">

                         <ul id="AJAXSystemLoggingMenuList" class = "tab-list nav nav-pills bg-dark row mx-0" role="tablist">
                         <div class="container-fluid row">';
        $first = true;
        $loggerOutputs = [];
        /* @var $logger CommonLogger */
        foreach ($runtimeLoggers as $logger) {
            if (null === $logger) {
                continue;
            }

            $loggerPanelID = $logger->getName() . '_AJAX_Log_Panel';
            $loggerTabButtonID = $loggerPanelID . "_Tab";
            $ajaxLog .= self::printLogButton($logger, $loggerPanelID, $loggerTabButtonID);
            $loggerOutputs[] = self::prepareAJAXLoggerOutput($logger, $loggerPanelID);
        }
        $ajaxLog .= '</div></ul>';

        $ajaxLog .= '<div class = "pt-0 tab-content log-tab-content loggerOutputContainer">';
        foreach ($loggerOutputs as $loggerOutput) {
            $ajaxLog .= $loggerOutput;
        }
        $ajaxLog .= '</div>';

        $ajaxLog .= '</div>'
            . '</div>'
            . '</div>';


        return $ajaxLog;
    }

    /**
     *
     * @param CommonLogger $logger
     * @param string $loggerPanelID
     * @param string $loggerTabButtonID
     *
     * @return type
     */
    public static function printLogButton(CommonLogger $logger, string $loggerPanelID, string $loggerTabButtonID): ?string
    {
        $tabButton = new FormButton(ucfirst($logger->getName()));
        $tabButton->setId($loggerTabButtonID)
            ->setTheme('light')
            ->auto()
            ->setType("button");
        $function = '$(".log-tab-content .active").removeClass("active");'
            . '$("#' . $loggerPanelID . '").addClass("active");';
        $script = \App\Models\View\Javascript::on($loggerTabButtonID, $function);
        $tabButton->setScript($script);
        $tabButton->setBreakPoint('');
        return $tabButton->print();
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
        $script = \App\Models\View\Javascript::on($listenerButtonID, $function);
        $tabButton->setScript($script);
        $tabButton->setBreakPoint('');
        return $tabButton->print();
    }

    /**
     * @param $logger
     * @param $loggerPaneID
     *
     * @return string
     */
    public static function prepareRuntimeLoggerOutput($logger, $loggerPaneID): string
    {
        $loggerOutput = '<div class = "tab-pane container-fluid  p-0" id = "' . $loggerPaneID . '" role="tabpanel">';
        $loggerOutput .= LogPrinter::printLog($logger);
        $loggerOutput .= '</div>';
        return $loggerOutput;
    }

    /**
     * @param $logger
     * @param $loggerPaneID
     *
     * @return string
     */
    public static function prepareAJAXLoggerOutput($logger, $loggerPaneID): string
    {
        $loggerOutput = '<div class = "tab-pane container-fluid  p-0" id = "' . $loggerPaneID . '" role="tabpanel">';
        // $loggerOutput .= LogPrinter::printLog($logger);
        $loggerOutput .= '</div>';
        return $loggerOutput;
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
        //$logToolsOutput .= '</div>'
        //   . '<div class = ""tab-pane container-fluid p-0">';
        $logToolsOutput .= self::printAJAXLogs($loggers);
        $logToolsOutput .= '</div>';
        return $logToolsOutput;
    }

}
