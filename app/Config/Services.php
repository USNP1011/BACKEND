<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

    public static function getSecretKey()
    {
        return getenv('Usn@1011');
    }

    public static function getPrivateKey()
    {
        $privateKey = <<<EOD
            -----BEGIN RSA PRIVATE KEY-----
            MIICWwIBAAKBgQCFtts5EZTpCBXjn2ItC54etK+iWQWSO4lJU0xUAoJHnxyM7uIH
            6TOE67XPIRDSC4X44rOA+YwbCdeiYSvj9RPgJGs3n7v3skcHr3gJgZb2XCVaeaZA
            /wbxCTFHXCl8uw+LXQq1Q8r2KQCk9SjRHs6zSKdK4DBtmcD76chVhMNWhQIDAQAB
            AoGADihVP86fwKitgKNQhspzHHcvHXZlg50wJQZfz4lQd/rd0AI/Bm1nmgxG/L5Y
            hJTZw4dlUCOb9QTuwu+c8Q7y4qJewCT0+8dG6IHHEoT5vjgWlEDkGMeMW/Mp9VAI
            ZGZWDxtA7I3tJtOZow3sOwCbNre3fADVJvjCzTTYP63P+mECQQDCPmhoDhL0hbBc
            /rIop3OSES/As+DSP1ApjJncSFs36GhtMIE+2+13ZuzbgN0UJ6aOcaH5gj1Ah6gn
            Vr7vH/XNAkEAsDnsocdSj9XJqjiL2CH5JemnVFOsqsOWtXDvr45TxT+BoSiblU58
            LYG275Zukawz6QTvPoRJJM61NPFKzeArmQJALXdGIPUKYsnYGixTr0hiuNHlB4oT
            GaNQNCmA6hrVnyR7LPOpjPkVgFlLH6XuGYWeasEWVLyzcaNPLMYVMwucEQJAIdqe
            2N1fwP4DBc5jHxw3rs7aNFr2ur9kPmr5wLII6cWvc3RHn0E6nctjh1dQ2m9IcaVf
            CJWFL6r+KLOJ+U7tUQJAezlbJRBpaWW8CzapLTOGH33XHLIcUN+ckPdaobayOe3A
            5pSojF3lILjHKLtgZT1NIAJ9+/oTZxYWc0I6nxQbJw==
            -----END RSA PRIVATE KEY-----
            EOD;
        // -----BEGIN PUBLIC KEY-----
        // MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCJe7mXYcAphNP8RCb8SpxiajUFjj7cr
        // J6MUztGxvMrIWnEUOeB8lBoQIEP5SBBslhsLY8QKnDiqwI72R0TEmh
        // -----END PUBLIC KEY-----
        return $privateKey;
    }

    public static function getPublicKey()
    {
        $publicKey = <<<EOD
            -----BEGIN PUBLIC KEY-----
            MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCFtts5EZTpCBXjn2ItC54etK+i
            WQWSO4lJU0xUAoJHnxyM7uIH6TOE67XPIRDSC4X44rOA+YwbCdeiYSvj9RPgJGs3
            n7v3skcHr3gJgZb2XCVaeaZA/wbxCTFHXCl8uw+LXQq1Q8r2KQCk9SjRHs6zSKdK
            4DBtmcD76chVhMNWhQIDAQAB
            -----END PUBLIC KEY-----
            EOD;
        // -----BEGIN PUBLIC KEY-----
        // MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCJe7mXYcAphNP8RCb8SpxiajUFjj7cr
        // J6MUztGxvMrIWnEUOeB8lBoQIEP5SBBslhsLY8QKnDiqwI72R0TEmh
        // -----END PUBLIC KEY-----
        return $publicKey;
    }
}
