<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

$title = Yii::t('payment', 'Transactions');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= GridView::widget([
	'dataProvider' => $search->getDataProvider(),
	'filterModel' => $search,
	'summary' => '',
	'tableOptions' => ['class' => 'table table-condensed'],
	'columns' => [
		'user_email',
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
			'attribute' => 'income',
			'enableSorting' => false,
			'contentOptions' => ['class' => 'text-success'],
			'value' => function($model, $key, $index, $column) {
				if ($model->income == 0)
					return '';

				return Yii::$app->formatter->asDecimal($model->income, 2);
			}
		],
		[
			'attribute' => 'expense',
			'enableSorting' => false,
			'contentOptions' => ['class' => 'text-danger'],
			'value' => function($model, $key, $index, $column) {
				if ($model->expense == 0)
					return '';

				return Yii::$app->formatter->asDecimal($model->expense, 2);
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
