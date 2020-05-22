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
use App\Models\Database\DistrictDatabase;
use App\Models\Database\SchoolDatabase;
use App\Models\District\Department;
use app\database\Schema;

class Departments extends Controller
{

    //put your code here
    /*
      public function index() {
      return $this->view('settings/district/schools/index');
      }
     *
     */
    function __construct(\System\App\App $app)
    {
        parent::__construct($app);
        $this->layout = 'setup';
    }

    public function show($schoolID = null)
    {
        $this->preProcessSchoolID($schoolID);
        $this->departments = Department::getDepartments($schoolID);
        //var_dump($this->schools);
        if ($this->departments != false) {
            return $this->view('settings/district/schools/departments/show');
        } else {
            return $this->view('settings/district/schools/departments/create');
        }
    }

    public function edit($departmentID)
    {
        $this->preProcessDepartmentID($departmentID);

        $this->staffADSettings = Department::getADSettings($departmentID, 'Staff');
        $this->staffGASettings = Department::getGASettings($departmentID, 'Staff');
        //var_dump($this->school);
        if ($this->department != false) {
            return $this->view('settings/district/schools/departments/edit');
        }
    }

    public function editPost($departmentID)
    {
        \System\App\AppLogger::get()->debug('Edit Post');
        $post = \system\Post::getAll();
        //var_dump($post);
        //\App\Models\DatabasePost::setPost(Schema::DEPARTMENT, $departmentID, $post);
        //var_dump($post);
        $this->redirect('/departments/edit/' . $departmentID);
    }

    public function createPost($schoolID = null)
    {
        $post = \system\Post::getAll();
        Department::createDepartment($post[Schema::DEPARTMENT_NAME[Schema::COLUMN]], $schoolID);
        $this->redirect('/departments/show/' . $schoolID);
        //return $this->index();
    }

    public function delete($departmentID)
    {
        $this->schoolID = Department::getSchoolID($departmentID);
        Department::deleteDepartment($departmentID);
        $this->redirect('/departments/show/' . $this->schoolID);
    }

}
