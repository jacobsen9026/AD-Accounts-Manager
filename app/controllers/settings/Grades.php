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

namespace App\Controllers\Settings;

/**
 * Description of District
 *
 * @author cjacobsen
 */
use App\Controllers\Controller;
use App\Models\District\Grade;
use System\Post;
use app\database\Schema;
use System\App\AppLogger;
use App\Models\DatabasePost;

class Grades extends Controller {

    //put your code here
    /*
      public function index() {
      return $this->view('settings/district/schools/index');
      }
     *
     */
    function __construct(\System\App\App $app) {
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
        //DatabasePost::setPost(Schema::GRADE, $gradeID, $post);
        //var_dump($post);
        //var_dump($post);
        $this->redirect('/grades/edit/' . $gradeID);
    }

    public function editStaffPost($gradeID) {
        AppLogger::get()->debug('Edit Post');
        $post = Post::getAll();
        var_dump($post);
        //DatabasePost::setPost(Schema::GRADE, $gradeID, $post, 'Staff');
        //var_dump($post);
        //var_dump($post);
        $this->redirect('/grades/edit/' . $gradeID);
    }

    public function editStudentsPost($gradeID) {
        AppLogger::get()->debug('Edit Post');
        $post = Post::getAll();
        var_dump($post);
        //DatabasePost::setPost(Schema::GRADE, $gradeID, $post, 'Student');
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
        $this->schoolID = \App\Models\District\Grade::getSchoolID($gradeID);

        \App\Models\District\Grade::deleteGrade($gradeID);
        $this->redirect('/grades/show/' . $this->schoolID);
    }

}
