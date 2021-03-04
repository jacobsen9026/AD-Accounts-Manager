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

namespace App\Controllers\Api;

/**
 * Description of App
 *
 *
 * @author cjacobsen
 */
class App extends APIController
{

    public const GET_APP_SETTINGS = "getApplicationSettings";
    public const GET_AUTH_SETTINGS = "getAuthenticationSettings";
    public const GET_EMAIL_SETTINGS = "getEmailSettings";
    public const GET_NOTIF_SETTINGS = "getNotificationSettings";
    public const GET_UPDATE_SETTINGS = "getUpdateSettings";
    public const GET_CONFIG = "getConfiguration";
    public const GET_AUDIT = "getAudit";

    public function appSettings()
    {
        return $this->returnHTML($this->view('settings/application'));
    }


    public function getConfiguration()
    {
        return $this->printConfig();
    }

    private function printConfig()
    {
        if ($this->user->superAdmin) {
            $output = "<h1>Configuration Database</h1>";
            foreach (\system\Database::get()->getAllTables() as $table) {
                $output .= "<h3>" . $table . "</h3>";
                $output .= $this->html_table(\system\Database::get()->query("SELECT * FROM " . $table));
                //ob_start();
                //var_dump(\system\Database::get()->query("SELECT * FROM " . $table));
                //$this->output .= ob_get_clean();
            }
        }
        return $this->returnHTML($output);
    }

    private function html_table($data = [])
    {
        $rows = [];

        foreach ($data as $row) {
            $cells = [];
            foreach ($row as $cell) {
                $cells[] = "<td>{$cell}</td>";
            }

            $rows[] = "<tr>" . implode('', $cells) . "</tr>";
        }
        return "<table class='table hci-table'>" . implode('', $rows) . "</table>";
    }

    public function authSettings()
    {
        return $this->returnHTML($this->view('settings/authentication'));
    }

    public function emailSettings()
    {
        return $this->returnHTML($this->view('settings/email'));
    }

    public function notifSettings()
    {
        return $this->returnHTML($this->view('settings/notification'));
    }

    public function update()
    {
        return $this->returnHTML($this->view('settings/update'));
    }

    public function getAudit()
    {
        return $this->returnHTML($this->view('settings/audit'));
    }

    public function backup()
    {
        return $this->returnHTML($this->view('settings/backup'));
    }

}
