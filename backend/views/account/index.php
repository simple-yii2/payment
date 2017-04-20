<?php

use yii\helpers\Html;
use yii\grid\GridView;

$title = Yii::t('payment', 'Accounts');

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
		'userEmail',
		[
			'attribute' => 'amount',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDecimal($model->amount, 2);
			}

		],
		[
			'class' => 'yii\grid\ActionColumn',
			'headerOptions' => ['style' => 'width: 50px;'],
			'template' => '{invoice} {transaction}',
			'buttons' => [
				'invoice' => function($url, $model, $key) {
					if ($model->user === null)
						return '';

					$title = Yii::t('payment', 'Invoices');

					return Html::a('<span class="glyphicon glyphicon-credit-card"></span>', $url, [
						'title' => $title,
						'aria-label' => $title,
						'data-pjax' => 0,
					]);
				},
				'transaction' => function($url, $model, $key) {
					if ($model->user === null)
						return '';

					$title = Yii::t('payment', 'Transactions');

					return Html::a('<span class="glyphicon glyphicon-transfer"></span>', $url, [
						'title' => $title,
						'aria-label' => $title,
						'data-pjax' => 0,
					]);
				},
			],
			'urlCreator' => function($action, $model, $key, $index) {
				$route = [$action . '/index'];

				if ($model->user)
					$route['search[user_email]'] = $model->user->email;

				return $route;
			},
		],
	],
]) ?>
