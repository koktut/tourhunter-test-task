<?php

namespace app\controllers;

use app\models\User;
use yii\rest\Controller;

/**
 * Class Select2Controller
 * @package app\controllers
 */
class Select2Controller extends Controller
{
    /**
     * @param string|null $q
     * @param int $id
     * @return array
     */
    public function actionUser($q = null, $id = 0)
    {
        $results = ['results' => ['id' => '', 'text' => '']];

        $data = User::find()
            ->select(['id', 'text' => 'username'])
            ->filterWhere(['ilike', 'username', $q])
            ->limit(20)
            ->asArray()
            ->all();

        $results['results'] = array_values($data);

        return $results;
    }
}
