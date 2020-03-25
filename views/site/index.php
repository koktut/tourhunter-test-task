<?php

use app\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/**
 * @var $this yii\web\View
 * @var ActiveDataProvider $dataProvider
 */

$this->title = 'Balance';
?>
<div>
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
