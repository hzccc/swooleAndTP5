<?php

namespace app\index\model;

use mysqlpool\MysqlPool;
use think\Db;
use think\Model;
class User extends Model {


    public function queryUserByNameAndPasswd($userName,$passwd){

        if(empty($userName) || empty($passwd)){
            return null;
        }
        //这里直接使用Swoole Mysql的query方法
        $obj = MysqlPool::getInstance()->getConnection();
        if (!empty($obj)) {
            $db = $obj ? $obj['db'] : null;
        }
        if($db){
            $res = $db->query("select * from `cc_user` where login_name='$userName' and password='$passwd'");
            $result = isset($res[0]) ? $res[0] : null;
            MysqlPool::getInstance()->free($obj);
        }
        return $result;
    }

}