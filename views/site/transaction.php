<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forms\TransactionForm */

use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;
use app\models\User;

$this->title = 'Transaction';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'transaction-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'userId')->widget(Select2::class, [
        'initValueText' => $model->userId ? [$model->userId => User::findOne($model->userId)->username] : [],
        'pluginOptions' => [
            'allowClear' => false,
            'ajax' => [
                'url' => '/select2/user',
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }'),
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(user) { return user.text; }'),
            'templateSelection' => new JsExpression('function (user) { return user.text; }'),        ],
    ]) ?>

    <?= $form->field($model, 'value')->input('number', ['step' => '0.01']) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Send', ['class' => 'btn btn-primary', 'name' => 'send-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
