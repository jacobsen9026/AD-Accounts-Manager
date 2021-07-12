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

namespace App\Config;

/**
 * Description of Theme
 *
 * @author cjacobsen
 */
class Theme
{

    //put your code here
    const DEFAULT_THEME = "default_theme";
    const BROWN_THEME = "brown_theme";
    const BLUE_THEME = "blue_theme";
    const GREEN_THEME = "green_theme";
    const ORANGE_THEME = "orange_theme";
    const PURPLE_THEME = "purple_theme";
    const RED_THEME = "red_theme";
    const SLATE_THEME = "slate_theme";

    /**
     *
     * @return type An array of all theme constants
     */
    public static function getThemes()
    {
        $rc = new \ReflectionClass(new Theme);
        return $rc->getConstants();
    }

}
