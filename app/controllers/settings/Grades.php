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

class Grades extends Controller {

    //put your code here
    /*
      public function index() {
      return $this->view('settings/district/schools/index');
      }
     *
     */


    public function show($schoolID = null) {
        $this->preProcessSchoolID($schoolID);
        //var_dump($this->schools);
        if ($this->grades != false) {
            return $this->view('settings/district/schools/grades/show');
        } else {
            return $this->view('settings/district/schools/grades/create');
        }
    }

    public function edit($gradeID) {
        $this->preProcessGradeID($gradeID);

        //var_dump($this->school);
        if ($this->school != false) {
            return $this->view('settings/district/schools/grades/edit');
        }
    }

    public function editPost($gradeID) {
        $post = \system\Post::getAll();
        \app\models\DatabasePost::setPost(basename(get_class()), $gradeID, $post);
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
