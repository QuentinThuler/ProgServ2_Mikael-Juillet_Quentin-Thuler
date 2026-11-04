<?php

/**
 * encriptToken : encript a string into sha256 format.
 * 
 * @param string $token given 
 * @return string sha256 format hash of token given
 */
function encriptToken(string $token): string {
    return hash('sha256', $token);
}

/**
 * generateToken : generate a random string in ASCII format in the lenght given.
 *
 * @param int $length of the token wanted
 * @return string random string in ASCII format
 */
function generateToken(int $length): string {

    // Source : https://www.geeksforgeeks.org/php/how-to-generate-a-random-unique-alphanumeric-string-in-php/
    // random_bytes returns random $lenght number of bytes
    // bin2hex converts them into hexadecimal format 1 bytes = 2 char
    // substr cut to the actual number wanted of char
    return substr(bin2hex(random_bytes($length)), 0, $length);
}
