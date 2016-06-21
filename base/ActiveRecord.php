<?php
namespace fufudao\base;

use yii\behaviors\TimestampBehavior;
//use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord as ar;
use yii\db\Expression;

class ActiveRecord extends ar
{
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created','modified'],
					ActiveRecord::EVENT_BEFORE_UPDATE => ['modified']
                ],
				'value' => new Expression(date('\'Y-m-d H:i:s\'')),
            ],
        ];
    }
	
	public function newID(){
		$schema = $this->getTableSchema();
		$primaryKey = $schema->primaryKey[0];
		$col = $schema->columns[$primaryKey];
		$ret = $this->getAttribute($primaryKey);
		if( empty($ret) ){
			if( $col->phpType === \yii\db\Schema::TYPE_STRING ){
				switch ($col->size){
					case 13:
						$ret = uniqid();
						break;
					case 32:
						$ret = md5(uniqid(mt_rand(), true));
						break;
				}
			}
		}
		
		return $ret;
	}
	

    
}