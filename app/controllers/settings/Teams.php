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
        if ($this->teams != false) {
            return $this->view('settings/district/schools/grades/teams/show');
        } else {
            return $this->view('settings/district/schools/grades/teams/create');
        }
    }

    public function edit($teamID) {
        $this->preProcessTeamID($teamID);

        //var_dump($this->school);
        if ($this->grade != false) {
            return $this->view('settings/district/schools/grades/teams/edit');
        }
    }

    public function editPost($teamId) {
        $post = \system\Post::getAll();
        \app\models\DatabasePost::setPost(basename(get_class()), $teamID, $post);
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
