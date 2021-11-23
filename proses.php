<?php
// DEFINE our cipher
define('AES_256_CBC', 'aes-256-cbc');

// Generate a 256-bit encryption key
// This should be stored somewhere instead of recreating it each time
$encryption_key = openssl_random_pseudo_bytes(32);

// Generate an initialization vector
// This MUST be available for decryption as well
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));

// Create some data to encrypt
$data = "Keamanan";
echo "Before encryption: $data\n";

// Encrypt $data using aes-256-cbc cipher with the given encryption key and
// our initialization vector. The 0 gives us the default options, but can
// be changed to OPENSSL_RAW_DATA or OPENSSL_ZERO_PADDING
$encrypted = openssl_encrypt($data, AES_256_CBC, $encryption_key, 0, $iv);
echo "Encrypted: $encrypted\n";

// If we lose the $iv variable, we can't decrypt this, so:
// - $encrypted is already base64-encoded from openssl_encrypt
// - Append a separator that we know won't exist in base64, ":"
// - And then append a base64-encoded $iv
$encrypted = $encrypted . ':' . base64_encode($iv);

// To decrypt, separate the encrypted data from the initialization vector ($iv).
$parts = explode(':', $encrypted);
// $parts[0] = encrypted data
// $parts[1] = base-64 encoded initialization vector

// Don't forget to base64-decode the $iv before feeding it back to
//openssl_decrypt
$decrypted = openssl_decrypt($parts[0], AES_256_CBC, $encryption_key, 0, base64_decode($parts[1]));
echo "Decrypted: $decrypted\n";
?>