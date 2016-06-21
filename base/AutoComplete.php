<?php

/*
 * @date:2015-06-26
 * @copyright:北京福福科技咨询有限公司
 */
namespace fufudao\base;

use fufudao\base\Html;
use yii\helpers\Url;

/**
 * Description of AutoComplete
 * AutoComplete renders an autocomplete jQuery UI widget.
 *
 * For example:
 *
 * ```php
 * echo AutoComplete::widget([
 *     'model' => $model,
 *     'attribute' => 'country',
 *     'clientOptions' => [
 *         'source' => ['USA', 'RUS'],
 *     ],
 * ]);
 * ```
 *
 * The following example will use the name property instead:
 *
 * ```php
 * echo AutoComplete::widget([
 *     'name' => 'country',
 *     'clientOptions' => [
 *         'source' => ['USA', 'RUS'],
 *     ],
 * ]);
 * ```
 *
 * You can also use this widget in an [[yii\widgets\ActiveForm|ActiveForm]] using the [[yii\widgets\ActiveField::widget()|widget()]]
 * method, for example like this:
 *
 * ```php
 * <?= $form->field($model, 'from_date')->widget(\yii\jui\AutoComplete::classname(), [
 *     'clientOptions' => [
 *         'source' => ['USA', 'RUS'],
 *     ],
 * ]) ?>
 * @author arlent <cy@fufudao.cn>
 */
class AutoComplete extends \yii\jui\AutoComplete{
	/**
	 * @var mixed the entries that the autocomplete should choose from. This can be
	 * <ul>
	 * <li>an Array with local data</li>
	 * <li>a String, specifying a URL that returns JSON data as the entries.</li>
	 * <li>a javascript callback. Please make sure you wrap the callback with
	 * {@link CJavaScriptExpression} in this case.</li>
	 * </ul>
	 */
	public $source=array();
	/**
	 * @var mixed the URL that will return JSON data as the autocomplete items.
	 * CHtml::normalizeUrl() will be applied to this property to convert the property
	 * into a proper URL. When this property is set, the {@link source} property will be ignored.
	 */
	public $sourceUrl;
	
	public $showDropdown=true;
	
	public $keyValue=true;
	
	public $readOnly=false;
	
	public function init(){
//		$this->options['id'] = Html::getInputId($this->model, $this->attribute).self::$counter;
	}

		/**
	 * Run this widget.
	 * This method registers necessary javascript and renders the needed HTML code.
	 */
	public function run()
	{
		$ret = '<span class="ui-autocomplete-span">';
//		list($name,$id)=$this->resolveNameID();
//		$name = Html::getAttributeName($this->attribute);
		$name =Html::getInputName($this->model, $this->attribute);
		$id = 'autocomplete-'.$this->attribute.'-'.self::$counter++;

		$defOptions = array(
			'id'=>$id,
			'name'=>$name,
			'autocomplete'=>'off',
		);
		$defClientOptions = array(
			'minLength'=>1,
			'delay'=>500,
			'autoFocus'=>false
		);
		
			
		$this->options = array_merge($defOptions,  $this->options);
		$this->clientOptions = array_merge($defClientOptions,$this->clientOptions);
		
		if($this->keyValue===true){
			$inputValue = isset($this->options['value'])?$this->options['value']:null;
			$value = Html::getAttributeValue($this->model,$this->attribute);
		
			$hiddenId = $this->options['id'].'-value';

			$ret.= Html::hiddenInput($name,$value,['id'=>$hiddenId]);

if( empty($this->clientEvents['select']) ){
	$this->clientEvents['select']=<<<Eof
function(event,ui){
	$(this).parents(".ui-autocomplete-span").children("input[type='hidden']").val(ui.item.id?ui.item.id:ui.item.value);
}
Eof;
	}

if( empty($this->clientEvents['change']) ){
	$this->clientEvents['change']=<<<Eof
function(event,ui){
	if(ui.item === null)
		$(this).parents(".ui-autocomplete-span").children("input[type='hidden']").val('');
}
Eof;
	}
			$name2 = 'query['.$this->model->formName().']['.$this->attribute.']';
			$ret.= Html::textInput($name2,$inputValue,$this->options);

		}
		else if($this->hasModel()){
//			\THtml::dump($this->options);
			$ret.= Html::activeTextInput($this->model,$this->attribute,$this->options);

		}else
			$ret.= Html::textInput($name,$this->value,$this->options);

		if($this->readOnly === TRUE ){
			$ret.= '</span>';
			return $ret;
		}

		
		if($this->sourceUrl!==null){
			$url = $this->sourceUrl;
			$field = isset($this->clientOptions['searchField'])?$this->clientOptions['searchField']:$this->attribute;
			if( is_string($url) ){ $url = array($url,'field'=>$field);}
			elseif(is_array($url)) {
				$url['field']=$field;
		   }
			$this->clientOptions['source']=Url::to($url);
		}else{
			$this->clientOptions['source']=$this->source;
		}
		
		$this->clientOptions['isOpened']=false;
if( empty($this->clientEvents['open']) ){
	$this->clientEvents['open']=<<<Eof
function(event,ui){
	$(this).autocomplete( "option", "isOpened",true );
}
Eof;
}
if( empty($this->clientEvents['close']) ){
	$this->clientEvents['close']=<<<Eof
function(event,ui){
	$(this).autocomplete( "option", "isOpened",false );
}
Eof;
}
		if($this->showDropdown===true){
			$ret.= '<span class="dropdown"><i class="iconfont small-btn">&#xe651;</i></span>';
$js=<<<Eof
$(".ui-autocomplete-span .dropdown").on('click',function(event){
	var obj=$(this).prev();
	var isOpened = obj.autocomplete( "option", "isOpened" );
	if( isOpened ){
		obj.autocomplete( "close");
	}else{
		if( obj.attr("readonly")==="readonly" )return;
		var minLength = obj.autocomplete( "option", "minLength" );
		obj.autocomplete( "option", "minLength", 0 );
		obj.autocomplete("search",""); 
		obj.autocomplete( "option", "minLength", minLength );
	}
});
Eof;
	
		$this->getView()->registerJs($js);
//			Yii::app()->getClientScript()->registerScript(__CLASS__,$js);
		}
		
		$ret.= '</span>';
		
//		$clientOptions= Json::encode($this->clientOptions);
//		$clientOptions=CJavaScript::encode($this->clientOptions);
//		$this->registerJs(__CLASS__.'#'.$id,"jQuery('#{$id}').autocomplete($clientOptions);");
		
		$this->registerWidget('autocomplete');
		return $ret;
	}
}
