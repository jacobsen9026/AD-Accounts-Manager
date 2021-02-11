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

namespace System\Traits;

/**
 * Description of Domain
 *
 * @author cjacobsen
 */
trait DomainTools
{

    /**
     * Breaks up a FQDN into a DistiguishedName
     *
     * EG: contoso.com -> dc=contoso,dc=com
     *
     * @param string $fqdn
     *
     * @return string
     */
    public static function FQDNtoDN($fqdn)
    {
        $baseDN = '';
        $afterFirst = false;
        foreach (explode(".", $fqdn) as $part) {
            if ($afterFirst) {
                $baseDN .= ',';
            }
            $baseDN .= 'DC=' . $part;
            $afterFirst = true;
        }
        return $baseDN;
    }

    public static function getOUFromDN($dn)
    {
        $ous = explode("OU=", $dn);
        $fullOU = '';
        $first = true;
        foreach ($ous as $ou) {
            if (!$first) {
                $fullOU .= "OU=" . $ou;
            }
            $first = false;
        }
        return $fullOU;
    }

    /**
     *
     * @param string $ou
     *
     * @return string
     */
    public static function cleanOU($ou): string
    {
        $search = [' ', 'OU=', ',', 'DC='];
        return str_replace($search, '', $ou);
    }

    /**
     * Removes spaces, commas, and ='s from the OU
     * to make safe for use as an HTML id
     *
     * @param string $ou
     *
     * @return string
     */
    public static function getHTML_ID_FromOU($ou): string
    {
        $remove = [" ", ",", "="];
        return str_replace($remove, "_", $ou);
        //var_dump($ou);
    }

    /**
     * Returns the first OU element of a Distinguished Name
     *
     * @param string $dn
     *
     * @return string
     */
    public static function leftOU($dn)
    {
        return explode(',', str_replace('OU=', '', $dn))[0];
    }

    public static function getOuTree($ou)
    {
        $tree = [];

        $parts = explode(',', $ou);

        for ($y = 0; $y < count($parts); $y++) {
            $branch = '';
            for ($x = $y; $x < count($parts); $x++) {
                $branch .= $parts[$x];
                if ($x != count($parts) - 1) {
                    $branch .= ',';
                }
            }
            if (substr($branch, 0, 2) != "DC") {
                $tree[] = $branch;
            }
        }
        return $tree;
    }


    public static function prettifyOU(string $ou)
    {
        $ouPath = explode("OU=", str_replace(",", "", substr($ou, 0, strpos($ou, "DC"))));
        $ouPath = array_reverse($ouPath);
        $pathString = "";
        foreach ($ouPath as $ou) {
            $pathString .= "/" . $ou;
        }
        return $pathString;
    }

}
