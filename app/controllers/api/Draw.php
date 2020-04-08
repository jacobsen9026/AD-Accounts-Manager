<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\api;

/**
 * Description of Draw
 *
 * @author cjacobsen
 */
use system\Post;

class Draw extends APIController {

    private $output;

    //put your code here
    public function indexPost() {

        $post = Post::get('query');
        switch ($post) {
            case 'debugConfig':
                if ($this->user->privilege >= \app\models\user\Privilege::ADMIN) {
                    $this->output = "<h1>Configuration Database</h1>";
                    foreach (\system\Database::get()->getAllTables()as $table) {
                        $this->output .= "<h3>" . $table . "</h3>";
                        $this->output .= $this->html_table(\system\Database::get()->query("SELECT * FROM " . $table));
                        ob_start();
                        //var_dump(\system\Database::get()->query("SELECT * FROM " . $table));
                        $this->output .= ob_get_clean();
                    }
                }
                break;

            default:
                break;
        }
        return $this->output;
        //exit;
    }

    private function html_table($data = array()) {
        $rows = array();

        foreach ($data as $row) {
            $cells = array();
            foreach ($row as $cell) {
                $cells[] = "<td>{$cell}</td>";
            }

            $rows[] = "<tr>" . implode('', $cells) . "</tr>";
        }
        return "<table class='table hci-table'>" . implode('', $rows) . "</table>";
    }

}
