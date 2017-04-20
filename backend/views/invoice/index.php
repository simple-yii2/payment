<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

$title = $model->getUsername();

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('payment', 'Accounts'), 'url' => ['account/index']],
	$title,
];

?>
<h1><?= Html::encode($title) ?> <small><?= Yii::t('payment', 'Invoices') ?></small></h1>

<?= GridView::widget([
	'dataProvider' => $search->getDataProvider(),
	'filterModel' => $search,
	'summary' => '',
	'tableOptions' => ['class' => 'table table-condensed'],
	'columns' => [
		[
			'attribute' => 'createDate',
			'format' => 'html',
			'value' => function($model, $key, $index, $column) {
				$formatter = Yii::$app->formatter;
				$date = $formatter->asDate($model->createDate, 'short');
				$time = $formatter->asTime($model->createDate, 'short');

				return $date . ' ' . Html::tag('span', $time, ['class' => 'text-muted']);
			}
		],
		'id',
		[
			'attribute' => 'amount',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDecimal($model->amount, 2);
			}
		],
		'description',
		[
			'attribute' => 'state',
			'filter' => $search::getStateNames(),
			'value' => function($model, $key, $index, $column) {
				return ArrayHelper::getValue($model::getStateNames(), $model->state);
			}
		],
		[
			'class' => 'yii\grid\ActionColumn',
			'headerOptions' => ['style' => 'width: 25px;'],
			'template' => '{view}',
		],
	],
]) ?>
