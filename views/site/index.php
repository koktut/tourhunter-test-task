<?php

use app\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var ActiveDataProvider $dataProvider
 * @var User $user
 */

$this->title = 'User\'s balance';
$columns = [
    'id',
    'username',
    [
        'attribute' => 'balance',
        'label' => Yii::t('app', 'Balance'),
        'value' => function (User $model) {
            return Yii::$app->formatter->asCurrency($model->userBalance->value);
        },
    ],
];

if (!Yii::$app->user->isGuest) {
    $columns[] = [
        'label' => Yii::t('app', 'Actions'),
        'content' => function (User $model) use (&$user) {
            if ($user && $user->id == $model->id) {
                return '';
            }

            return Html::a(
                'transaction',
                Url::to(['/site/transaction', 'userId' => $model->id]),
                ['class' => 'btn btn-primary']
            );
        }
    ];
}

?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>
    <hr>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
    ]); ?>
</div>
