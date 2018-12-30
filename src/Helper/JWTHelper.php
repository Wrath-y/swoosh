<?php

namespace Src\Helper;

class JWTHelper
{
    private static $header = [
        'alg' => 'HS256',
        'typ' => 'JWT'
    ];

    // The key used to generate the message digest using HMAC
    private static $key = 'ysama';


    /**
     * Get the jwt token
     * @param array $payload
     * [
     *  'iss'=>'www.ysama.cn',  // The issuer of the JWT
     *  'iat'=>time(),  // Issuing time
     *  'exp'=>time()+7200,  // expire date
     *  'nbf'=>time(),  // Do not receive the Token before this time
     *  'sub'=>'ysama',  // Targeted user
     *  'jti'=>md5(uniqid('JWT').time())  // The Token unique identifier
     * ]
     * @return bool|string
     */
    public static function getToken(array $payload)
    {
        if (is_array($payload)) {
            $base64header = self::base64UrlEncode(json_encode(self::$header, JSON_UNESCAPED_UNICODE));
            $base64payload = self::base64UrlEncode(json_encode($payload, JSON_UNESCAPED_UNICODE));
            return $base64header . '.' . $base64payload . '.' . self::signature($base64header . '.' . $base64payload, self::$key, self::$header['alg']);
        } else {
            return false;
        }
    }


    /**
     * Verify that the token is valid, default validation exp, nbf, iat time
     * @param string $Token The token to be verified
     * @return bool|string
     */
    public static function verifyToken($Token)
    {
        $tokens = explode('.', $Token);
        if (count($tokens) != 3) {
            return false;
        }

        list($base64header, $base64payload, $sign) = $tokens;

        //获取jwt算法
        $base64decodeheader = json_decode(self::base64UrlDecode($base64header), JSON_OBJECT_AS_ARRAY);
        if (empty($base64decodeheader['alg'])) {
            return false;
        }

        //签名验证
        if (self::signature($base64header . '.' . $base64payload, self::$key, $base64decodeheader['alg']) !== $sign) {
            return false;
        }

        $payload = json_decode(self::base64UrlDecode($base64payload), JSON_OBJECT_AS_ARRAY);

        //签发时间大于当前服务器时间验证失败
        if (isset($payload['iat']) && $payload['iat'] > time()) {
            return false;
        }

        //过期时间小宇当前服务器时间验证失败
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }

        //该nbf时间之前不接收处理该Token
        if (isset($payload['nbf']) && $payload['nbf'] > time()) {
            return false;
        }

        return $payload;
    }




    /**
     * base64UrlEncode   https://jwt.io/
     * @param string $input The string to be encoded
     * @return string
     */
    private static function base64UrlEncode(string $input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    /**
     * base64UrlEncode  https://jwt.io/
     * @param string $input The string to be decoded
     * @return bool|string
     */
    private static function base64UrlDecode(string $input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $addlen = 4 - $remainder;
            $input .= str_repeat('=', $addlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * HMACSHA256签名   https://jwt.io/
     * @param string $input 为base64UrlEncode(header).".".base64UrlEncode(payload)
     * @param string $key
     * @param string $alg
     * @return mixed
     */
    private static function signature(string $input, string $key, string $alg = 'HS256')
    {
        $alg_config = [
            'HS256' => 'sha256'
        ];
        return self::base64UrlEncode(hash_hmac($alg_config[$alg], $input, $key, true));
    }
}
