<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Class TransactionForm
 * @package app\models\forms
 *
 * @property int $userId
 * @property float $value
 */
class TransactionForm extends Model
{
    /** @var int */
    public $userId;
    /** @var float */
    public $value;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['userId', 'value'], 'required'],
            ['userId', 'integer'],
            ['value', 'number'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'userId' => Yii::t('app', 'User'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return User::findOne($this->userId);
    }
}
