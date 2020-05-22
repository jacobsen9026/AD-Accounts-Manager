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
use App\Api\AD;

class Schools extends Controller
{

    /**
     * public function index() {
     * return $this->show(1);
     * if (DistrictDatabase::getSchools(1)) {
     *
     * }
     * }
     *
     */
    public function show($districtID = null)
    {

        //$this->getDistrictDirectory();

        $this->preProcessDistrictID($districtID);
        //var_dump($this->district);
        $this->tree = $this->district->getDirectoryTree();
        //var_dump($tree);
        //var_dump($this->schools);
        if ($this->schools != false) {
            return $this->view('settings/district/schools/show');
        } else {
            return $this->view('settings/district/schools/create');
        }
    }

    public function edit($schoolID)
    {
        $this->preProcessSchoolID($schoolID);

        $this->staffADSettings = SchoolDatabase::getADSettings($schoolID, 'Staff');
        $this->staffGASettings = SchoolDatabase::getGASettings($schoolID, 'Staff');
        //var_dump($this->school);
        if ($this->school != false) {
            return $this->view('settings/district/schools/edit');
        }
    }

    public function editPost($schoolID)
    {
        \System\App\AppLogger::get()->debug('Edit Post');
        $post = \system\Post::getAll();
        //var_dump($post);
        //\App\Models\DatabasePost::setPost(Schema::SCHOOL, $schoolID, $post);
        //var_dump($post);
        $this->redirect('/schools/edit/' . $schoolID);
    }

    public function createPost($districtID = null)
    {
        $post = \system\Post::getAll();
        //SchoolDatabase::createSchool($post['name'], $post['abbr'], $post['ou'], $districtID);
        $this->redirect('/schools/show/' . $districtID);
        //return $this->index();
    }

    public function delete($schoolID)
    {
        $this->districtID = SchoolDatabase::getDistrictID($schoolID);
        SchoolDatabase::deleteSchool($schoolID);
        $this->redirect('/schools/show/' . $this->districtID);
    }

}
