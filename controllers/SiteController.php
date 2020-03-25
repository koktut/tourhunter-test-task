<?php

namespace app\controllers;

use app\common\TransactionService;
use app\models\forms\LoginForm;
use app\models\forms\TransactionForm;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->joinWith('userBalance balance'),
            'sort' => [
                'attributes' => [
                    'id',
                    'username',
                    'balance' => [
                        'asc' => ['balance.value' => SORT_ASC],
                        'desc' => ['balance.value' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'user' => Yii::$app->user->identity,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @param null $userId
     * @return Response|string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionTransaction($userId = null)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }

        $model = new TransactionForm();

        if ($userId) {
            if ($user = User::findOne($userId)) {
                $model->userId = $userId;
            };
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            /** @var User $sender */
            $sender = Yii::$app->user->identity;
            $recipient = $model->getUser();

            if (!$recipient) {
                Yii::$app->session->addFlash('error', Yii::t('app', 'Recipient not found'));
            } else {
                /** @var TransactionService $service */
                $service = Yii::$app->get('transactionService');

                try {
                    $service->sendValue($sender, $recipient, $model->value);

                    Yii::$app->session->addFlash(
                        'success',
                        Yii::t('app', 'Amount {value} transferred to the recipient {user}', [
                            'value' => $model->value,
                            'user' => $recipient->username,
                        ])
                    );

                    $model = new TransactionForm();
                } catch (\Exception $e) {
                    Yii::$app->session->addFlash('error', $e->getMessage());
                }
            }
        }

        return $this->render('transaction', [
            'model' => $model,
            'user' => Yii::$app->user->identity,
        ]);
    }
}
