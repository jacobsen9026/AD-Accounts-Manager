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
        //$this->layout = 'setup';
    }

    //put your code here
    public function index() {

        $this->districts = DistrictDatabase::getDistricts();
        $this->district = DistrictDatabase::getDistrict(1);
        var_dump($this->district);

        if ($this->districts == false) {
            return $this->view('settings/district/create');
        } else {
            foreach ($this->districts as $this->district) {
                $this->districID = $this->district[Schema::DISTRICT_ID[Schema::COLUMN]];
                return $this->show();
            }
        }

        //return $this->view('settings/district/index');
    }

    public function show($districtID = 1) {
        $this->staffADSettings = DistrictDatabase::getADSettings($districtID, 'Staff');
        //$this->staffGASettings = District::getGASettings($districtID, 'Staff');
        $this->districtID = $districtID;
        $this->district = DistrictDatabase::getDistrict($districtID);
        return $this->view('settings/district/show');
    }

    public function createPost() {
        $post = \system\Post::getAll();
        DistrictDatabase::createDistrict($post['name']);
        $this->redirect('/settings/districts');
    }

    public function editPost() {
        $post = \system\Post::getAll();
        //var_dump($post);
        $districtID = 1;
        $this->preProcessDistrictID($districtID);
        $this->district->setName($post['name']);
        $this->district->setAbbr($post['abbr']);
        $this->district->setAdFQDN($post['adFQDN']);
        $this->district->setAdNetBIOS($post['adNetBIOS']);
        $this->district->setAdPassword($post['adPassword']);
        $this->district->setAdServer($post['adServer']);
        $this->district->setAdStudentGroupName($post['adStudentGroup']);
        $this->district->setAdUsername($post['adUsername']);
        $this->district->setGsFQDN($post['gsFQDN']);
        $this->district->setParentEmailGroup($post['parentEmailGroup']);
        $this->district->saveToDB();
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
