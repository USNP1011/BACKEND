<?php

use App\Models\UserModel;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getJWTFromRequest($authenticationHeader): string
{
    if (is_null($authenticationHeader)) { //JWT is absent
        throw new Exception('Missing or invalid JWT in request');
    }
    //JWT is sent from client in the format Bearer XXXXXXXXX
    return explode(' ', $authenticationHeader)[1];
}

function validateJWTFromRequest(string $encodedToken)
{
    $decodedToken = JWT::decode($encodedToken, new Key(Services::getPublicKey(), 'RS256'));
    throw new Exception('Missing or invalid JWT in request');
    // $userModel = new UserModel();
    // $userModel->findUserByEmailAddress($decodedToken->data->email);
}

function getSignedJWTForUser(array $itemData)
{
    $issuedAtTime = time();
    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;    // expire time in seconds
    $notBeforeClaim = $issuedAtTime + 10;                   // not before in seconds
    $pvtKey = Services::getPrivateKey();                    // get RSA private key (NOT IN USE)
    $payload = [
        "iss" => "Issuer of the JWT", // this can be the servername. Example: https://domain.com
        "aud" => "Audience that the JWT",
        "sub" => "Subject of the JWT",
        "nbf" => $notBeforeClaim,
        'iat' => $issuedAtTime,
        'exp' => $tokenExpiration,
        "data" => $itemData
    ];

    $jwt = JWT::encode($payload, Services::getPrivateKey(), 'RS256');
    return $jwt;
}