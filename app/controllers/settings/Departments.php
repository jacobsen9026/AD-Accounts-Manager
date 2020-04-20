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
use app\models\district\SchoolDatabase;
use app\models\district\Department;
use app\database\Schema;

class Departments extends Controller {

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

    public function show($schoolID = null) {
        $this->preProcessSchoolID($schoolID);
        $this->departments = Department::getDepartments($schoolID);
        //var_dump($this->schools);
        if ($this->departments != false) {
            return $this->view('settings/district/schools/departments/show');
        } else {
            return $this->view('settings/district/schools/departments/create');
        }
    }

    public function edit($departmentID) {
        $this->preProcessDepartmentID($departmentID);

        $this->staffADSettings = Department::getADSettings($departmentID, 'Staff');
        $this->staffGASettings = Department::getGASettings($departmentID, 'Staff');
        //var_dump($this->school);
        if ($this->department != false) {
            return $this->view('settings/district/schools/departments/edit');
        }
    }

    public function editPost($departmentID) {
        \system\app\AppLogger::get()->debug('Edit Post');
        $post = \system\Post::getAll();
        //var_dump($post);
        \app\models\DatabasePost::setPost(Schema::DEPARTMENT, $departmentID, $post);
        //var_dump($post);
        $this->redirect('/departments/edit/' . $departmentID);
    }

    public function createPost($schoolID = null) {
        $post = \system\Post::getAll();
        Department::createDepartment($post[Schema::DEPARTMENT_NAME[Schema::COLUMN]], $schoolID);
        $this->redirect('/departments/show/' . $schoolID);
        //return $this->index();
    }

    public function delete($departmentID) {
        $this->schoolID = Department::getSchoolID($departmentID);
        Department::deleteDepartment($departmentID);
        $this->redirect('/departments/show/' . $this->schoolID);
    }

}
