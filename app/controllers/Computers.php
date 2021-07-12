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

namespace App\Controllers;

use App\Api\Windows\WindowsRM;
use App\Models\Audit\Action\Computer\RenameComputerAuditAction;
use App\Models\Audit\Action\Computer\RestartComputerAuditAction;
use App\Models\Audit\Action\Computer\SearchComputerAuditAction;
use App\Models\Domain\DomainComputer;
use System\App\AppException;
use System\Post;

/**
 * Description of Tech
 *
 * @author cjacobsen
 */
class Computers extends Controller
{
    public function index()
    {

        return $this->view('computers/search');
    }

    public function search($hostname)
    {
        $this->computer = new DomainComputer($hostname);
        if ($this->computer->exists()) {
            $this->audit(new SearchComputerAuditAction($hostname));
            return $this->view('computers/show');
        } else {
            return $this->view('computers/notFound');
        }
    }

    public function editPost($hostname)
    {

        switch (Post::get('action')) {
            case "rename":
                $computer = new DomainComputer($hostname);
                $newName = Post::get('new_name');
                if ($newName != '') {
                    $computer->rename($newName, Post::isSet('reboot'));

                    $this->audit(new RenameComputerAuditAction($computer->getName(), $newName));
                    if (Post::isSet('reboot')) {

                        $this->audit(new RestartComputerAuditAction($computer->getName()));
                    }
                    $this->redirect('/computers/search/' . Post::get('new_name'));
                } else {
                    throw new AppException("No new computer name was provided");
                }
                break;
            default:
                break;
        }
    }


    public function compmgmtPost($computer = "BBPC")
    {
        $action = Post::get('action');
        $computer = Post::get('destination');
        switch ($action) {
            case 'rebootPC':
                WindowsRM::reboot($computer);
                $this->audit(new RestartComputerAuditAction($computer));
                break;
            default:
                break;

        }
    }


}
