<?php

/*
 * @date:2015-03-31
 * @copyright:北京福福科技咨询有限公司
 */
namespace fufudao\base;

/**
 * Description of Util
 *
 * @author arlent <cy@fufudao.cn>
 */
class Util {
/**
 * 生成13位的ID
 *
 */
public static function get13ID($wait=false)
{
	if($wait)usleep(1);
	return uniqid();
}

/**
 * 生成32位的ID
 *
 */
public static function get32ID($wait=false)
{
	if($wait)usleep(1);
	return md5(uniqid(mt_rand(), true));
}

/**
 * Yii::t 增加 $message 可以为数组
 * @param string $category
 * @param string|array $message
 * @param array $params
 * @param string $language
 * @return string
 */
public static function t($category, $message, $params = [], $language = null)
{
	$str ='';
	if(is_string($message)){
		\Yii::t($category, $message, $params, $language);
	}else if( is_array($message)){
		foreach ($message as $msg){
			$str .= \Yii::t($category, $msg, $params, $language);
		}
	}
	return $str;
}


public static function json_encode($data,$options=JSON_UNESCAPED_UNICODE ) {
//	return urldecode(json_encode(self::url_encode($data)));	
	return json_encode($data, $options);
}




}