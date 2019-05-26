<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 18/7/26
 * Time: 下午11:59
 */

namespace app\common;

class ApiResultUtils
{
    /**
     * api返回成功数据
     *
     * @param $retData
     * @param $msg 
     * @return string
     */
    public static $OK = 0;
    public static $IllegalAesKey = -41001;
    public static $IllegalIv = -41002;
    public static $IllegalBuffer = -41003;
    public static $DecodeBase64Error = -41004;

    public static function ofSuccessv1($retData,$msg='') {
        $resultMap = array();
        $resultMap['code'] = 'success';
        if (!empty($msg)) {
            $resultMap['message'] = $msg;
        }
        if (!empty($retData)) {
            $resultMap['data'] = $retData;
        }
        return $resultMap;
    }



    public static function ofSuccess($retData,$msg='') {
        $resultMap = array();
        $resultMap['static'] = 1;
        $resultMap['code'] = 'success';
        if (!empty($msg)) {
            $resultMap['message'] = $msg;
        }
        if (!empty($retData)) {
            $resultMap['data'] = $retData;
        }
        return json_encode($resultMap);
    }

    public static function ofBizCodeSuccess($bizCode,$msg=''){
        $resultMap = array();
        $resultMap['code'] = 'success';
        if (!empty($msg)) {
            $resultMap['message'] = $msg;
        }
        if (!empty($bizCode)) {
            $resultMap['bizCode'] = $bizCode;
        }
        return json_encode($resultMap);
    }
    public static function ofPageSuccess($retData,$total) {
        $resultMap = array();
        $resultMap['code'] = 'success';
        $resultMap['total'] = $total;

        if (!empty($retData)) {
            $resultMap['data'] = $retData;
        }

        return json_encode($resultMap);
    }

    /**
     * api返回失败数据
     *
     * @param $errMsg
     * @return string
     */
    public static function ofFail($errMsg) {
        $resultMap = array();
        $resultMap['static'] = 0;
        $resultMap['code'] = 'fail';
        if (!empty($errMsg)) {
            $resultMap['message'] = $errMsg;
        }

        return json_encode($resultMap);
    }
    public static function ofFailv1($errMsg) {
        $resultMap = array();
        $resultMap['code'] = 'fail';
        if (!empty($errMsg)) {
            $resultMap['message'] = $errMsg;
        }

        return $resultMap;
    }
    /**
    * 判断参数是否存在
    *
    */
    public static function isEmpty($param){
        foreach ($param as $key => $value) {
            if (empty($param[$key])) {
                return ApiResultUtils::ofFail($key." is empty");
            }
        }
    }

    /*
    -----------------------------------------------------------
    简要描述：姓名昵称合法性检查，只能输入中文英文
    输入：string
    输出：boolean
    -----------------------------------------------------------
    */
    public static function isName($val){
        if( preg_match("/^[\x80-\xffa-zA-Z0-9]{3,60}$/", $val) ){
            return true;
        }
        return false;
    }

    /*
    -----------------------------------------------------------
    简要描述：微信号合法性检查,只能输入英文,数字,下划线
    输入：string
    输出：boolean
    -----------------------------------------------------------
    */
    public static function isWxNo($val){
        if( preg_match("/^[a-zA-Z0-9_]{1,}$/", $val) ){
            return true;
        }
        return false;
    }

    /*
    -----------------------------------------------------------
    简要描述：地址合法性检查,只能中文,数字
    输入：string
    输出：boolean
    -----------------------------------------------------------
    */
    public static function isAddr($val){
        if( preg_match("/^[\x80-\xffa-zA-Z0-9]+$/", $val) ){
            return true;
        }   
        return false;
    }
    /*
    -----------------------------------------------------------
    简要描述：输入内容合法性检查,只能中文,英文,数字
    输入：string
    输出：boolean
    -----------------------------------------------------------
    */
    public static function isZYS($val){
        if( preg_match("/^[\x80-\xffa-zA-Z0-9]+$/", $val) ){
            return true;
        }   
        return false;
    }
    /**
     * 通过想象地址换算经纬度信息
     *
     * @param $address
     * @return bool|null|string
     */
    public static function addresstolatlag($address) {
        $url = 'http://apis.map.qq.com/ws/geocoder/v1/?address=' . $address . '&key=FZ2BZ-OAK6O-5NKW2-SCP7Q-6FN4H-QOFAD';
        $result = file_get_contents($url);
        if (!empty($result)) {
            $res = $result;
            $res = json_decode($res,true);
            if (empty($res['result'])) {
                return null;
            }
            return json_encode($res['result']['location']);
        }
        return null;
    }



    /**
     * 微信支付返回处理成功
     *
     * @return string
     */
    public static function ofWxPayResultSuccess() {
        return '<xml>
                    <return_code><![CDATA[SUCCESS]]></return_code>
                    <return_msg><![CDATA[OK]]></return_msg>
                </xml>';
    }

}