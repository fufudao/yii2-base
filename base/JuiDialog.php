<?php
namespace fufudao\base;

use yii\helpers\Json;
use yii\jui\Dialog;

class JuiDialog extends Dialog
{
	/**
	 * @var string the name of the container element that contains all panels. Defaults to 'div'.
	 */
	public $loadjs = false;
	/**
	 * Renders the open tag of the dialog.
	 * This method also registers the necessary javascript code.
	 */
	public function init()
	{
		parent::init();

		$id=$this->getId();
		if (isset($this->options['id'])) {
			$id = $this->options['id'];
		} else {
			$this->options['id'] = $id;
		}


		if( !isset($this->options['position']) ){
			$this->options['position'] = array('my'=>'center-20% top','at'=>'top top+60px');
		}
		$options= Json::encode($this->options);
		$js = "$(function(){ $('#{$id}').dialog($options);";
		$js .='});';
		if ($this->loadjs) {
			echo '<script type="text/javascript">' . $js . '</script>';
		} else {
			$this->view->registerJs(__CLASS__ . '#' . $id, $js);
		}
		
//		echo \fufudao\base\THtml::tag('div',['id'=>$id],  FALSE,true);
	}


}