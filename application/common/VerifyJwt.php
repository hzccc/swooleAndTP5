<?php
/* 
 *验证JWT
 */
namespace app\common;

use phpjwt\JWT;
class VerifyJwt {
    /* 验证JWT是否合法 */
    public static function JwtDecode($token,$key){
        if(empty($token)){
            return false;
        } else {
            JWT::$leeway = 60;
            $decoded = JWT::decode($token, $key, ['HS256']);
            $arr = (array)$decoded;
            if ($arr['exp'] < time()) {
                return false;
            } else {
                return $arr;
            }
        }
    }
}
