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
use app\models\district\DistrictDatabase;
use app\database\Schema;
use app\models\DatabasePost;

class Districts extends Controller {

    function __construct(\system\app\App $app) {
        parent::__construct($app);
        $this->layout = 'setup';
    }

    //put your code here
    public function index() {

        $this->districts = DistrictDatabase::getDistricts();
        //var_dump($this->districts);

        if ($this->districts == false) {
            return $this->view('settings/district/create');
        } else {
            foreach ($this->districts as $this->district) {
                $this->districID = $this->district[Schema::DISTRICT_ID[Schema::COLUMN]];
                return $this->show($this->district[Schema::DISTRICT_ID[Schema::COLUMN]]);
            }
        }

        //return $this->view('settings/district/index');
    }

    public function show($districtID) {
        $this->staffADSettings = DistrictDatabase::getADSettings($districtID, 'Staff');
        $this->staffGASettings = DistrictDatabase::getGASettings($districtID, 'Staff');
        $this->districtID = $districtID;
        return $this->view('settings/district/show');
    }

    public function createPost() {
        $post = \system\Post::getAll();
        DistrictDatabase::createDistrict($post['name']);
        $this->redirect('/settings/districts');
    }

    public function schools() {

    }

    public function schoolsPost($function) {

    }

    public function editPost() {
        $post = \system\Post::getAll();
        //var_dump($post);
        $districtID = $post[Schema::DISTRICT_ID[Schema::NAME]];
        $this->preProcessDistrictID($districtID);
        DatabasePost::setPost(Schema::DISTRICT, $districtID, $post);
        $this->redirect('/districts');
    }

    public function delete($districtID = null) {
        DistrictDatabase::deleteDistrict($districtID);
        $this->redirect('/districts');
    }

    public function hasSchools($districtID) {
        $schools = DistrictDatabase::getSchools($districtID);
        if ($schools == false or count($schools) < 1) {
            return false;
        }
        return true;
    }

}
