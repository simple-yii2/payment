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
<h1><?= Html::encode($title) ?> <small><?= Yii::t('payment', 'Transactions') ?></small></h1>

<?= GridView::widget([
	'dataProvider' => $search->getDataProvider(),
	'summary' => '',
	'tableOptions' => ['class' => 'table table-condensed'],
	'columns' => [
		[
			'attribute' => 'date',
			'enableSorting' => false,
			'format' => 'html',
			'value' => function($model, $key, $index, $column) {
				$formatter = Yii::$app->formatter;
				$date = $formatter->asDate($model->date, 'short');
				$time = $formatter->asTime($model->date, 'short');

				return $date . ' ' . Html::tag('span', $time, ['class' => 'text-muted']);
			},
		],
		[
			'attribute' => 'receipt',
			'value' => function($model, $key, $index, $column) {
				if (empty($model->receipt))
					return '';

				return Yii::$app->formatter->asDecimal($model->receipt, 2);
			}
		],
		[
			'attribute' => 'paid',
			'value' => function($model, $key, $index, $column) {
				if (empty($model->paid))
					return '';

				return Yii::$app->formatter->asDecimal($model->paid, 2);
			}
		],
		[
			'attribute' => 'balance',
			'enableSorting' => false,
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDecimal($model->balance, 2);
			}
		],
		[
			'attribute' => 'description',
			'enableSorting' => false,
			'format' => 'html',
			'value' => function($model, $key, $index, $column) {
				$description = Html::encode($model->description);
				if ($model->url)
					$description = Html::a($description, $model->url);

				return $description;
			}
		],
	],
]) ?>