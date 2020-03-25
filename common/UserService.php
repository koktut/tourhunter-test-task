<?php

namespace app\common;

use app\models\User;
use app\models\UserBalance;
use yii\base\Component;

/**
 * Class UserService
 * @package common
 */
class UserService extends Component
{
    /**
     * @param string $userName
     * @return User
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    public function createUser(string $userName)
    {
        $transaction = User::getDb()->beginTransaction();
        $result = true;

        $user = new User(['username' => $userName]);
        $result &= $user->save();

        if ($result) {
            $userBalance = new UserBalance(['user_id' => $user->id]);
            $result &= $userBalance->save();
        }

        if ($result) {
            $transaction->commit();
        } else {
            $transaction->rollBack();

            throw new \Exception('Error while registering user');
        }

        return $user;
    }
}
