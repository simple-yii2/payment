<?php

use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use cms\payment\backend\assets\TurnoverAsset;
use cms\payment\common\models\Transaction;

TurnoverAsset::register($this);

$title = Yii::t('payment', 'Turnovers');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	$title,
];

//years
$years = [];
$query = Transaction::find()->select(['year'])->distinct()->orderBy(['year' => SORT_ASC]);
foreach ($query->asArray()->all() as $row) {
	$years[$row['year']] = $row['year'];
}
$y = date('Y');
if (!in_array($y, $years))
	$years[$y] = $y;

?>
<h1><?= Html::encode($title) ?></h1>

<?php $f = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
]); ?>

	<fieldset>
		<?= Html::activeHiddenInput($filter, 'user_id') ?>
		<?= $f->field($filter, 'user_email', [
			'inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default turnover-filter-remove" type="button"><span class="glyphicon glyphicon-remove"></span></button></span></div>',
		])->widget(AutoComplete::className(), [
			'options' => ['class' => 'form-control', 'data-value' => $filter->user_email],
			'clientOptions' => [
				'source' => Url::toRoute('users'),
				'create' => new JsExpression('function(event, ui) {
					$("#role-form input.ui-autocomplete-input").autocomplete("instance")._renderItem = function(ul, item) {
						return $("<li>").html(item.html).appendTo(ul);
					};
				}'),
			],
		]) ?>
		<?= $f->field($filter, 'year')->dropDownList($years) ?>
	</fieldset>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('payment', 'Apply'), ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>

<hr>

<?= GridView::widget([
	'dataProvider' => $filter->getDataProvider(),
	'summary' => '',
	'tableOptions' => ['class' => 'table table-bordered'],
	'columns' => [
		[
			'header' => Yii::t('payment', 'Month'),
			'format' => 'html',
			'value' => function($model, $key, $index, $column) use ($filter) {
				$month = ArrayHelper::getValue($model, 'month');

				if ($month === null) {
					$value = Html::tag('strong', Html::encode(Yii::t('payment', 'TOTAL:')));
				} else {
					$date = mktime(0, 0, 0, $month, 1, $filter->year);
					$value = Yii::$app->formatter->asDate($date, 'MMM');
				}

				return $value;
			},
		],
		[
			'header' => Yii::t('payment', 'Income'),
			'contentOptions' => ['class' => 'text-success'],
			'value' => function($model, $key, $index, $column) use ($filter) {
				$income = ArrayHelper::getValue($model, 'income', 0);
				if ($income == 0)
					return '';

				return Yii::$app->formatter->asDecimal($income, 2);
			},
		],
		[
			'header' => Yii::t('payment', 'Expense'),
			'contentOptions' => ['class' => 'text-danger'],
			'value' => function($model, $key, $index, $column) use ($filter) {
				$expense = ArrayHelper::getValue($model, 'expense', 0);
				if ($expense == 0)
					return '';

				return Yii::$app->formatter->asDecimal($expense, 2);
			},
		],
		[
			'header' => Yii::t('payment', 'Turnover'),
			'value' => function($model, $key, $index, $column) use ($filter) {
				$turnover = ArrayHelper::getValue($model, 'turnover', 0);
				if ($turnover == 0)
					return '';

				return Yii::$app->formatter->asDecimal($turnover, 2);
			},
		],
	],
]) ?>
