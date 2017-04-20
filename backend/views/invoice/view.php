<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$title = Yii::t('payment', 'Invoice #{number}', ['number' => $model->id]);

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('payment', 'Accounts'), 'url' => ['account/index']],
	['label' => $model->account->getUsername(), 'url' => ['index', 'id' => $model->account->id]],
	$title,
];

$formatter = Yii::$app->formatter;

$provider = Yii::createObject($model->provider);

?>
<h1><?= Html::encode($title) ?></h1>

<table class="table table-bordered">
	<tbody>
		<tr><th><?= $model->getAttributeLabel('createDate') ?></th><td><?= $formatter->asDatetime($model->createDate, 'short') ?></td></tr>
		<tr><th><?= $model->getAttributeLabel('provider') ?></th><td><?= $provider ? $provider->name() : '' ?></td></tr>
		<tr><th><?= $model->getAttributeLabel('amount') ?></th><td><?= $formatter->asDecimal($model->amount, 2) ?></td></tr>
		<tr><th><?= $model->getAttributeLabel('description') ?></th><td><?= Html::encode($model->description) ?></td></tr>
		<tr><th><?= $model->getAttributeLabel('state') ?></th><td><?= ArrayHelper::getValue($model::getStateNames(), $model->state, '') ?></td></tr>
		<tr><th><?= $model->getAttributeLabel('payDate') ?></th><td><?= $formatter->asDatetime($model->payDate, 'short') ?></td></tr>
		<tr><th><?= $model->getAttributeLabel('refundDate') ?></th><td><?= $formatter->asDatetime($model->refundDate, 'short') ?></td></tr>
	</tbody>
</table>
