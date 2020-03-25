<?php

use app\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var ActiveDataProvider $dataProvider
 */

$this->title = 'User\'s balance';
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>
    <hr>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'username',
            [
                'attribute' => 'balance',
                'label' => Yii::t('app', 'Balance'),
                'value' => function (User $model) {
                    return Yii::$app->formatter->asCurrency($model->userBalance->value);
                },
            ],
        ],
    ]); ?>
</div>
