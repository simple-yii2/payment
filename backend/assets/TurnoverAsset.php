<?php

namespace cms\payment\backend\assets;

use yii\web\AssetBundle;

class TurnoverAsset extends AssetBundle
{

	public $js = [
		'turnover-filter.js',
	];
	
	public $depends = [
		'yii\web\JqueryAsset',
	];

	public function init()
	{
		parent::init();

		$this->sourcePath = __DIR__ . '/turnover';
	}

}
