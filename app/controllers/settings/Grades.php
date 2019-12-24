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
use app\models\district\Grade;
use system\Post;
use app\database\Schema;
use system\app\AppLogger;
use app\models\DatabasePost;

class Grades extends Controller {

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

        if (isset($this->grades) and $this->grades != false) {
            return $this->view('settings/district/schools/grades/show');
        } else {
            return $this->view('settings/district/schools/grades/create');
        }
    }

    public function edit($gradeID) {
        $this->preProcessGradeID($gradeID);
        //var_dump($this->school);
        $this->staffADSettings = Grade::getADSettings($gradeID, 'Staff');
        $this->staffGASettings = Grade::getGASettings($gradeID, 'Staff');

        $this->studentADSettings = Grade::getADSettings($gradeID, 'Student');
        $this->studentGASettings = Grade::getGASettings($gradeID, 'Student');
        if ($this->school != false) {
            return $this->view('settings/district/schools/grades/edit');
        }
    }

    public function editPost($gradeID) {
        AppLogger::get()->debug('Edit Post');
        $post = Post::getAll();
        var_dump($post);
        DatabasePost::setPost(Schema::GRADE, $gradeID, $post);
        //var_dump($post);
        //var_dump($post);
        $this->redirect('/grades/edit/' . $gradeID);
    }

    public function editStaffPost($gradeID) {
        AppLogger::get()->debug('Edit Post');
        $post = Post::getAll();
        var_dump($post);
        DatabasePost::setPost(Schema::GRADE, $gradeID, $post, 'Staff');
        //var_dump($post);
        //var_dump($post);
        $this->redirect('/grades/edit/' . $gradeID);
    }

    public function editStudentsPost($gradeID) {
        AppLogger::get()->debug('Edit Post');
        $post = Post::getAll();
        var_dump($post);
        DatabasePost::setPost(Schema::GRADE, $gradeID, $post, 'Student');
        //var_dump($post);
        //var_dump($post);
        $this->redirect('/grades/edit/' . $gradeID);
    }

    public function createPost($schoolID = null) {
        $post = \system\Post::getAll();
        Grade::createGrade($schoolID, $post);
        $this->redirect('/grades/show/' . $schoolID);
    }

    public function delete($gradeID) {
        $this->schoolID = \app\models\district\Grade::getSchoolID($gradeID);

        \app\models\district\Grade::deleteGrade($gradeID);
        $this->redirect('/grades/show/' . $this->schoolID);
    }

}
