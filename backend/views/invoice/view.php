<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$title = Yii::t('payment', 'Invoice #{number}', ['number' => $model->id]);

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('payment', 'Invoices'), 'url' => ['invoice/index']],
	$title,
];

$formatter = Yii::$app->formatter;

$provider = Yii::createObject($model->provider);

//user
$user = null;
if ($model->user) {
	$user = Html::encode($model->user->getUsername());
	$user .= ' ' . Html::tag('span', $model->user->email, ['class' => 'label label-primary']);
}

//state
$state = ArrayHelper::getValue($model::getStateNames(), $model->state, '');
switch ($model->state) {
	case $model::STATE_SUCCESS:
		$stateClass = 'label label-success';
		break;
	
	case $model::STATE_FAIL:
		$stateClass = 'label label-danger';
		break;
		
	case $model::STATE_REFUND:
		$stateClass = 'label label-warning';
		break;

	default:
		$stateClass = 'label label-default';
		break;
}

?>
<h1><?= Html::encode($title) ?></h1>

<div class="btn-toolbar" role="toolbar">
	<?= Html::a(Yii::t('payment', 'Process'), ['process', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
</div>

<table class="table table-bordered">
	<tbody>
		<tr><th><?= $model->getAttributeLabel('user_email') ?></th><td><?= $formatter->asHtml($user) ?></td></tr>
		<tr><th><?= $model->getAttributeLabel('createDate') ?></th><td><?= $formatter->asDatetime($model->createDate, 'short') ?></td></tr>
		<tr><th><?= $model->getAttributeLabel('provider') ?></th><td><?= $provider ? $provider->name() : '' ?></td></tr>
		<tr><th><?= $model->getAttributeLabel('amount') ?></th><td><?= $formatter->asDecimal($model->amount, 2) ?></td></tr>
		<tr><th><?= $model->getAttributeLabel('description') ?></th><td><?= Html::encode($model->description) ?></td></tr>
		<tr><th><?= $model->getAttributeLabel('state') ?></th><td><?= Html::tag('span', $state, ['class' => $stateClass]) ?></td></tr>
		<tr><th><?= $model->getAttributeLabel('payDate') ?></th><td><?= $formatter->asDatetime($model->payDate, 'short') ?></td></tr>
		<tr><th><?= $model->getAttributeLabel('refundDate') ?></th><td><?= $formatter->asDatetime($model->refundDate, 'short') ?></td></tr>
	</tbody>
</table>
