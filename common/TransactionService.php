<?php

namespace app\common;

use app\models\User;
use app\models\UserBalance;
use Yii;
use yii\base\Component;

/**
 * Class TransactionService
 * @package app\common
 */
class TransactionService extends Component
{
    /**
     * @param User $sender
     * @param User $recipient
     * @param float $value
     * @throws \Exception
     */
    public function sendValue(User $sender, User $recipient, $value)
    {
        if ($sender->id == $recipient->id) {
            throw new \Exception(Yii::t('app', 'You can\'t send the amount to yourself'));
        }

        if (!$this->canSendValue($sender, $value)) {
            throw new \Exception(Yii::t('app', 'Insufficient balance'));
        }

        if (!$sender || !$sender->userBalance) {
            throw new \Exception(Yii::t('app', 'Invalid sender'));
        }

        if (!$recipient || !$recipient->userBalance) {
            throw new \Exception(Yii::t('app', 'Invalid recipient'));
        }

        $transaction = UserBalance::getDb()->beginTransaction();

        $result = true;
        $sender->userBalance->value -= $value;
        $recipient->userBalance->value += $value;

        $result &= $sender->userBalance->save();
        $result &= $recipient->userBalance->save();

        if ($result) {
            $transaction->commit();
        } else {
            $transaction->rollBack();

            throw new \Exception(Yii::t('app', 'Error while saving transaction'));
        }
    }

    /**
     * @param User $sender
     * @param float $value
     * @return bool
     */
    public function canSendValue(User $sender, float $value)
    {
        if ($sender->userBalance->value - $value < Yii::$app->params['minBalanceValue']) {
            return false;
        }

        return true;
    }
}
