<?php

/*
 * @date:2015-05-15
 * @copyright:北京福福科技咨询有限公司
 */
namespace fufudao\base;
use Yii;
use yii\helpers\VarDumper;
use yii\helpers\Url;

/**
 * Description of THtml
 *
 * @author arlent <cy@fufudao.cn>
 */
class Html extends \yii\helpers\BaseHtml{
	public static function dump($var){		
		VarDumper::dump($var, 5, true);
	}
	
		//添加空格组合功能
	public static function t($category, $message, $params=array(), $source=NULL, $language=NULL) 
	{
		$many = explode('.',$message);
		$text ='';
		foreach($many as $one){
			$text.= Yii::t($category,$one,$params,$source,$language);
		}
		return $text;
	}
	
	/*
	权限链接
	htmlOptions[permission] 权限项目，false不检查权限，默认null，使用Url作为权限item
	*/
	public static function link($text,$url='#',$htmlOptions=array())
	{	
//		if($url!=='')
//			$htmlOptions[0]=$url;
		
		//权限检查
		
		$right = isset($htmlOptions['permission'])?$htmlOptions['permission']:$url;
		if(is_array($right))$right=Url::to($right);
//		echo $right;
		unset($htmlOptions['permission']);
		if( !Yii::$app->user->checkAccessURI($right))return '';
		
//		self::clientChange('click',$htmlOptions);
		return self::a($text,$url,$htmlOptions);
//		return self::tag('a',$htmlOptions,$text);
	}


	
	public static function LabelAndField($model,$attribute,$htmlOptions=array())
	{
	
	if(isset($htmlOptions['for']))
    {
        $for=$htmlOptions['for'];
        unset($htmlOptions['for']);
    }
    else
        $for=self::getInputId ($model, $attribute);
		
    if(isset($htmlOptions['label']))
    {
        if(($label=$htmlOptions['label'])===false)
            return '';
        unset($htmlOptions['label']);
    }
    else
        $label=$model->getAttributeLabel($attribute);
	
	$htmlOptions = array_merge(array(
		'id'=>$for,
		'class'=>'text'),	
		$htmlOptions);

	// self::clientChange('change',$htmlOptions);
	$value='';
	if(isset($htmlOptions['value'])){
		$value = $htmlOptions['value'];
		unset($htmlOptions['value']);
	}else if(isset($htmlOptions['type'])){
		$value = self::getAttributeValue($model,$attribute);
		switch ($htmlOptions['type']){
			case 'date':
				$value = empty($value)?'':date('Y-m-d',strtotime($value));
				break;
			case 'datetime':
				$value = empty($value)?'':date('Y-m-d H:i:s',strtotime($value));
				break;
		}
		unset($htmlOptions['type']);
	}else{
		$value=self::getAttributeValue($model,$attribute);
	}

	return self::tag('label',$label,['for'=>$for]).self::tag('span',self::encode($value),$htmlOptions);
	
	}
	
	/*
	 * 获取_Get请求，如果包含query的数组，优先
	 */
	public static function getParams(){
		$params = Yii::$app->request->getQueryParams();
		if( !empty($params['query'])){
			foreach($params['query'] as $key=>$value){
				$params[$key]= $value;
			}
		}
		return $params;
	}
	
	public static function getUri(){
		return '/'.Yii::$app->requestedRoute;
	}

	/**
	* 时间通用格式转换
	* @param unknown_type $timestr
	* @param unknown_type $format
	* @return string
	*/
	public static function datetime($timestr=null,$format='Y-m-d H:i:s'){
	return empty($timestr)?'':date($format,strtotime($timestr));
	}

	/**
	 * 
	 * @param unknown_type $str 时间字符串
	 * @param unknown_type $return 是否返回 还是直接显示
	 * @return string 返回 Y.m.d格式的日期
	 */
	 public static function time2Ymd($str,$return = false){
		$date=date('Y年m月d日',strtotime($str));
		if($return) return $date;
		else echo $date;
	 }
	 
	 
	/*
	controller->setFlash($mess,$name='flashbox') 
	*/
	public static function FlashBox($view,$flashName='flashbox',$divId='flashmessage',$jsCallback='') {
		$ret = '';
		if (Yii::$app->session->hasFlash($flashName)){
			$ret = '<div id="'.$divId.'" class="msgbox">'.Yii::$app->session->getFlash($flashName,null,true).'</div>'; 
		   $view->registerJs(
			 '$(".msgbox").animate({opacity: 0}, 2000).fadeOut(500);'.$jsCallback,
			 yii\web\View::POS_READY
		   );
		}
		// else{echo 'none msg';}
		return $ret;
	}
	 
	/**
	 * 
	 * @param array $htmlOptions
	 * @param string $url url 优先 htmlOptions['href']
	 * @return bool
	 */
	protected static function checkAccessForHtmlOptions($htmlOptions,$url=null){
		//权限检查
		$right = FALSE;
		if( isset($htmlOptions['permission']) ){
			
			if($htmlOptions['permission'] === FALSE){
				$right = TRUE;
			}else{
				$right = Yii::$app->user->can($htmlOptions['permission']);
			}
			unset($htmlOptions['permission']);
		}else{
			if( empty($url) && isset($htmlOptions['href']))$url=$htmlOptions['href'];
			$right = Yii::$app->user->checkAccessURI($url);
		}
		return $right;
	}
}
