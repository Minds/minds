<?php

$target = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.dev' . DIRECTORY_SEPARATOR;
$ssl = openssl_pkey_new([
    "digest_alg" => "sha512",
    "private_key_bits" => 4096,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
]);

openssl_pkey_export($ssl, $privateKey);
$publicKey = openssl_pkey_get_details($ssl)['key'];

mkdir($target);
file_put_contents("{$target}minds.pem", $privateKey);
file_put_contents("{$target}minds.pub", $publicKey);
