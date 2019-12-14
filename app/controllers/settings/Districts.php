<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\settings;

/**
 * Description of District
 *
 * @author cjacobsen
 */
use app\controllers\Controller;
use app\models\district\District;

class Districts extends Controller {

    //put your code here
    public function index() {

        $this->districts = \app\models\district\District::getDistricts();
        return $this->view('settings/district/index');
    }

    public function createPost() {
        $post = \system\Post::getAll();
        District::createDistrict($post['name']);
        $this->redirect('/districts');
    }

    public function schools() {

    }

    public function schoolsPost($function) {

    }

    public function editPost() {
        $post = \system\Post::getAll();
        $districtID = $post['districtID'];
        District::editDistrict($districtID, $post);
        $this->redirect('/districts');
    }

    public function delete($districtID = null) {
        District::deleteDistrict($districtID);
        $this->redirect('/districts');
    }

    public function hasSchools($districtID) {
        $schools = District::getSchools($districtID);
        if ($schools == false or count($schools) < 1) {
            return false;
        }
        return true;
    }

}
