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

namespace app\controllers\settings;

/**
 * Description of District
 *
 * @author cjacobsen
 */
use app\controllers\Controller;
use app\models\district\Team;

class Teams extends Controller {

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

    public function show($gradeId = null) {
        $this->preProcessGradeID($gradeId);
        //var_dump($this->schools);
        return $this->view('settings/district/schools/grades/teams/show');
    }

    public function edit($teamID) {
        $this->preProcessTeamID($teamID);
        $this->staffADSettings = Team::getADSettings($teamID, 'Staff');
        $this->staffGASettings = Team::getGASettings($teamID, 'Staff');

        $this->studentADSettings = Team::getADSettings($teamID, 'Student');
        $this->studentGASettings = Team::getGASettings($teamID, 'Student');
        //var_dump($this->school);
        if ($this->grade != false) {
            return $this->view('settings/district/schools/grades/teams/edit');
        }
    }

    public function editPost($teamId) {
        $post = \system\Post::getAll();
        var_dump($post);
        //\app\models\DatabasePost::setPost($teamID, $post);
        //var_dump($post);
        $this->redirect('/teams/edit/' . $teamID);
    }

    public function createPost($teamID = null) {
        $post = \system\Post::getAll();
        Team::createTeam($teamID, $post);
        $this->redirect('/teams/show/' . $teamID);
    }

    public function delete($teamId) {
        $this->gradeID = \app\models\district\Team::getGradeID($teamId);

        \app\models\district\Team::deleteTeam($teamId);
        $this->redirect('/teams/show/' . $this->gradeID);
    }

}
