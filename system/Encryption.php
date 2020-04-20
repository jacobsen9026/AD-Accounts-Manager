<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
