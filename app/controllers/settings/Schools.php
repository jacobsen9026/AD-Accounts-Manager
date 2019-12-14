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
use app\models\district\Grade;
use app\models\district\School;
use app\database\Schema;

class Schools extends Controller {

    //put your code here
    /*
      public function index() {
      return $this->view('settings/district/schools/index');
      }
     *
     */

    public function show($districtID = null) {
        $this->preProcessDistrictID($districtID);
        $this->schools = District::getSchools($districtID);
        //var_dump($this->schools);
        if ($this->schools != false) {
            return $this->view('settings/district/schools/show');
        } else {
            return $this->view('settings/district/schools/create');
        }
    }

    public function edit($schoolID) {
        $this->schoolID = $schoolID;
        $this->school = School::getSchool($schoolID);
        $this->districtID = $this->school[Schema::SCHOOLS_DISTRICTID];
        //var_dump($this->school);
        if ($this->school != false) {
            return $this->view('settings/district/schools/edit');
        }
    }

    public function editPost($schoolID) {

        $post = \system\Post::getAll();
        $this->school = \app\models\district\School::editSchool($schoolID, $post);
        var_dump($post);
        $this->redirect('/schools/edit/' . $schoolID);
    }

    public function createPost($districtID = null) {
        $post = \system\Post::getAll();
        \app\models\district\School::createSchool($post['name'], $districtID);
        $this->redirect('/schools/show/' . $districtID);
        //return $this->index();
    }

    public function delete($schoolID) {
        $this->districtID = \app\models\district\School::getDistrictID($schoolID);
        \app\models\district\School::deleteSchool($schoolID);
        $this->redirect('/schools/show/' . $this->districtID);
    }

}
