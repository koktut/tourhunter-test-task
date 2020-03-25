<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use DateTime;
use yii\db\Expression;

/**
 * Class UserBalance
 * @package app\models
 *
 * @property int $id
 * @property int $user_id
 * @property float $value
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class UserBalance extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_balance}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['user_id', 'required'],
            ['value', 'number'],
        ];
    }
}
