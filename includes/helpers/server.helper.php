<?php

class ServerHelper
{
    public static function randomHostname($base, $prefix='bulk', $length=8)
    {
        $i = 0;
        $rand = '';

        while ($i <= $length)
        {
            $rand .= self::randLetter();
            $i++;
        }

        if ($prefix == 'none')
        {
            $hostname = $rand.'.'.$base;
        }
        else
        {
            $hostname = $rand.'.'.$prefix.'.'.$base;
        }

        return $hostname;

    }

    public static function generatePassword($length=12, $strength=8) {
        $vowels = 'aeiouy';
        $consonants = 'bdghjmnpqrstvz';
        if ($strength > 1) {
            $consonants .= 'BDGHJLMNPQRSTVWXZ';
        }
        if ($strength > 2) {
            $vowels .= "AEIOUY";
        }
        if ($strength > 4) {
            $consonants .= '23456789';
        }
        if ($strength > 8) {
            $consonants .= '@#$%^!}';
        }

        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        return $password;
    }

    public static function randLetter()
    {
        return chr(97 + mt_rand(0, 25));
    }

}