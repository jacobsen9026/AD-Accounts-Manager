<?php

/*
 * The MIT License
 *
 * Copyright 2020 nbranco.
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

namespace system;

/**
 * Description of Encryption
 *
 * @author nbranco
 */
abstract class Encryption {

    //Varible for the cipher for changing as PHP updates openssl cipher lists
    const CIPHER = "aes-256-cbc";
    const KEY_PATH = CONFIGPATH . DIRECTORY_SEPARATOR . "private.key";

    public static function encrypt($plaintext) {


        if (in_array(self::CIPHER, openssl_get_cipher_methods())) {
            $ivlength = openssl_cipher_iv_length(self::CIPHER);
            $iv = openssl_random_pseudo_bytes($ivlength);
            $ciphertext = openssl_encrypt($plaintext, self::CIPHER, self::getKey(), $options = 0, $iv);
            $ciphertext .= "_" . bin2hex($iv);
            return bin2hex($ciphertext);
        } else {
            return false;
        }
    }

    public static function decrypt($ciphertext) {
        $ciphertext = hex2bin($ciphertext);
        $args = explode("_", $ciphertext);
        $plaintext = openssl_decrypt($args[0], self::CIPHER, self::getKey(), $options = 0, hex2bin($args[1]));
        return $plaintext;
    }

    /**
     *
     * @return byte The generated key
     */
    public static function genereatePrivateKeyFile() {
        $rand = openssl_random_pseudo_bytes(256);
        $key = $rand;
        File::overwriteFile(self::KEY_PATH, $rand);
        return $key;
    }

    private static function getKey() {
        $key = File::getContents(self::KEY_PATH);
        if ($key == "") {
            $key = self::genereatePrivateKeyFile();
        }
        return $key;
    }

}
