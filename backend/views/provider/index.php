<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$title = Yii::t('payment', 'Payment providers');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('payment', 'Accounts'), 'url' => ['account/index']],
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= ListView::widget([
	'dataProvider' => $dataProvider,
	'summary' => '',
	'itemView' => function($model, $key, $index, $widget) {
		$title = Html::tag('h3', Html::encode($model->name()), ['class' => 'panel-title']);

		$resultUrl = '<tr><td>Result URL:</td><td>' . Html::encode(Url::toRoute(['/payment/result/index', 'name' => $key], true)) . '</td></tr>';
		$successUrl = '<tr><td>Success URL:</td><td>' . Html::encode(Url::toRoute(['/payment/success/index', 'name' => $key], true)) . '</td></tr>';
		$failUrl = '<tr><td>Fail URL:</td><td>' . Html::encode(Url::toRoute(['/payment/fail/index', 'name' => $key], true)) . '</td></tr>';
		$urls = Html::tag('table', '<tbody>' . $resultUrl . $successUrl . $failUrl . '</tbody>', ['class' => 'table']);

		$heading = Html::tag('div', $title, ['class' => 'panel-heading']);
		$body = Html::tag('div', $urls, ['class' => 'panel-body']);

		return Html::tag('div', $heading . $urls, ['class' => 'panel panel-default']);
	},
]) ?>
