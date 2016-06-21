<?php
namespace fufudao\base;
/*
 * @date:2015-06-30
 * @copyright:北京福福科技咨询有限公司
 */

use fufudao\base\Html;

/**
 * Description of GridView
 *
 * @author arlent <cy@fufudao.cn>
 */
class GridView extends \yii\grid\GridView{

	public $layout='{items}{summary}{pager}';
	public $tableOptions = ['class' => 'items'];

	public $pager=array(
		'firstPageLabel'=>'|<', 
		'prevPageLabel'=>'<',
		'nextPageLabel'=>'>',
		'lastPageLabel'=>'>|',
		'maxButtonCount'=>5,
	);
	
	
	public function init() {
		parent::init();
		$this->emptyText = Html::t('templete','noData');
		$this->summary = Html::t('templete','summary');
		$this->getView()->registerCssFile('/css/listStyle.css');
	}
}
