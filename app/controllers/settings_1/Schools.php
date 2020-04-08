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
use app\models\district\School;
use app\database\Schema;
use app\api\AD;

class Schools extends Controller {

    //put your code here
    /*
      public function index() {
      return $this->view('settings/district/schools/index');
      }
     *
     */
    function __construct(\system\app\App $app) {
        parent::__construct($app);
        $this->layout = 'setup';
    }

    public function getDistrictDirectory2() {
        $ad = AD::get();
        $buildings = $ad->getSubOUs("OU=Schools,DC=branchburg,DC=k12,DC=nj,DC=us");
        if (!is_null($buildings)) {
            if ($buildings != false and sizeof($buildings) > 0) {
                foreach ($buildings as $building) {
                    if (!is_null($building["ou"][0])) {
                        //var_dump($building);
                        echo "<br><br><br><br>";
                        var_dump($building["ou"][0]);
                        $grades = $ad->getSubOUs("OU=Students,OU=" . $building["ou"][0] . ",OU=Schools,DC=branchburg,DC=k12,DC=nj,DC=us");
                        //var_dump($grades);
                        foreach ($grades as $grade) {
                            if (!is_null($grade["ou"][0])) {
                                //var_dump($grade["ou"][0]);
                            }
                        }

                        $departments = $ad->getSubOUs("OU=Staff,OU=" . $building["ou"][0] . ",OU=Schools,DC=branchburg,DC=k12,DC=nj,DC=us");
                        //var_dump($grades);
                        foreach ($departments as $department) {

                            if (!is_null($department["ou"][0])) {
                                var_dump($department["ou"][0]);
                                if ($ad->hasSubOUs($department["distinguishedname"][0])) {
                                    $ad->getAllSubOUs($department["distinguishedname"][0]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function getDistrictDirectory() {
        $ad = AD::get();
        $directory = $ad->getSubOUs("OU=Schools,DC=branchburg,DC=k12,DC=nj,DC=us");
        if (!is_null($directory)) {
            if ($directory != false and sizeof($directory) > 0) {
                foreach ($directory as $subDir) {
                    if (!is_null($subDir["ou"][0])) {
                        //var_dump($building);
                        echo "<br><br><br><br>";
                        var_dump($subDir["ou"][0]);
                        $ad->getSubOUs("OU=Students,OU=" . $subDir["ou"][0] . ",OU=Schools,DC=branchburg,DC=k12,DC=nj,DC=us");
                        //var_dump($grades);
                        foreach ($grades as $grade) {
                            if (!is_null($grade["ou"][0])) {
                                //var_dump($grade["ou"][0]);
                            }
                        }

                        $departments = $ad->getSubOUs("OU=Staff,OU=" . $subDir["ou"][0] . ",OU=Schools,DC=branchburg,DC=k12,DC=nj,DC=us");
                        //var_dump($grades);
                        foreach ($departments as $department) {

                            if (!is_null($department["ou"][0])) {
                                var_dump($department["ou"][0]);
                                if ($ad->hasSubOUs($department["distinguishedname"][0])) {
                                    $ad->getAllSubOUs($department["distinguishedname"][0]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function show($districtID = null) {

        $this->getDistrictDirectory();

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
        $this->preProcessSchoolID($schoolID);

        $this->staffADSettings = School::getADSettings($schoolID, 'Staff');
        $this->staffGASettings = School::getGASettings($schoolID, 'Staff');
        //var_dump($this->school);
        if ($this->school != false) {
            return $this->view('settings/district/schools/edit');
        }
    }

    public function editPost($schoolID) {
        \system\app\AppLogger::get()->debug('Edit Post');
        $post = \system\Post::getAll();
        //var_dump($post);
        \app\models\DatabasePost::setPost(Schema::SCHOOL, $schoolID, $post);
        //var_dump($post);
        $this->redirect('/schools/edit/' . $schoolID);
    }

    public function createPost($districtID = null) {
        $post = \system\Post::getAll();
        School::createSchool($post[Schema::SCHOOL_NAME[Schema::NAME]], $districtID);
        $this->redirect('/schools/show/' . $districtID);
        //return $this->index();
    }

    public function delete($schoolID) {
        $this->districtID = School::getDistrictID($schoolID);
        School::deleteSchool($schoolID);
        $this->redirect('/schools/show/' . $this->districtID);
    }

}
