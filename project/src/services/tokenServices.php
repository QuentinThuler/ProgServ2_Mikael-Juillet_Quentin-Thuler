<?php

namespace App\services;

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

/**
 * compareToken : compare a token with a hash of token.
 *
 * @param string $token given
 * @param string $tokenHash hash of the token given
 * @return bool true if the token is the same as the hash, false otherwise
 */
function compareToken(string $token, string $tokenHash): bool {
    return hash_equals(encriptToken($token), $tokenHash);
}
