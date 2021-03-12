<?php

/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
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

namespace System\App;

/**
 * Description of AppException
 *
 * @author cjacobsen
 */

use System\CoreException;

class AppException extends CoreException
{

    const PERMISSION_MISSING_ID = 601;
    const PERMISSION_MISSING_OU = 602;
    const FAIL_USER_READ_PERM = 401;
    const FAIL_USER_CHANGE_PERM = 402;
    const FAIL_USER_UNLOCK_PERM = 403;
    const FAIL_USER_DISABLE_PERM = 404;
    const FAIL_GROUP_READ_PERM = 411;
    const FAIL_GROUP_CHANGE_PERM = 412;
    const FAIL_GROUP_ADD_PERM = 413;
    const FAIL_GROUP_DELETE_PERM = 414;
    const USER_NOT_FOUND = 701;
    const GROUP_NOT_FOUND = 702;
    const OBJECT_NOT_FOUND = 703;
    const GROUP_ADD_EXISTS = 704;
    const CONTROLLER_NOT_FOUND = 301;
    const UNAUTHORIZED_ACCESS = 304;

}

?>
