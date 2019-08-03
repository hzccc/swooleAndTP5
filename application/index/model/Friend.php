<?php

namespace app\index\model;

use mysqlpool\MysqlPool;
use think\Db;
use think\Model;
class Friend extends Model {


    public function queryFriendList($userId){

        if(empty($userId)){
            return null;
        }
        //这里直接使用Swoole Mysql的query方法
        $obj = MysqlPool::getInstance()->getConnection();
        if (!empty($obj)) {
            $db = $obj ? $obj['db'] : null;
        }
        if($db){
            $res = $db->query("select b.user_ava,b.nick_name from `cc_friend` a, `cc_user` b where a.f_id = b.id and a.u_id = {$userId}");
            MysqlPool::getInstance()->free($obj);
        }
        return $res;
    }

}