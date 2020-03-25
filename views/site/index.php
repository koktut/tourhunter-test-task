<?php

use app\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

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
            [
                'label' => Yii::t('app', 'Actions'),
                'content' => function (User $model) {
                    $disabled = Yii::$app->user->isGuest ? 'disabled' : '';

                    return Html::a(
                        'send',
                        Url::to(['/site/transaction', 'userId' => $model->id]),
                        ['class' => 'btn btn-primary ' . $disabled]
                    );
                }
            ]
        ],
    ]); ?>
</div>
