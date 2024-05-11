<?php

namespace frontend\controllers;

use common\models\Demo;
use common\models\Plans;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
/**
 * Site controller
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
                'only' => ['logout','signup','index'],
                'rules' => [
                    [
                        'actions' => ['logout','index','signup'],
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
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
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

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionUpdate($page=1,$limit=100)
    {
        $get = Plans::find()
            ->where(['company_id'=>8,'deductible'=>0])
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->asArray()
            ->all();

        foreach ($get as $g){
            $x = 3000;
            $update = new Plans();
            $update->policy_id = 2;
            $update->min_age = $g['min_age'];
            $update->max_age = $g['max_age'];
            $update->company_id = 8;
            $update->pre_medical = $g['pre_medical'];
            $update->coverage_id = $g['coverage_id'];
            $update->deductible = $x;
            if ($update->min_age >=80 && $update->max_age <=89) {
                // print_r($model);exit();
                switch ($x) {
                //    case 100:
                    //    $update->rate_per = round(($g['rate_per'] * 0.95), 4);
                    //    break;
                   // case 250:
                     //   $update->rate_per = round(($g['rate_per'] * 0.90), 4);
                     //   break;
                    case 500:
                        $update->rate_per = $g['rate_per'];
                        break;
                    case 1000:
                        $update->rate_per = round(($g['rate_per'] * 0.85), 4);
                        break;
                    case 3000:
                        $update->rate_per = round(($g['rate_per'] * 0.75), 4);
                        break;
                    //                   case 5000:
                    //                       $update->rate_per = round(($g['rate_per'] * 0.70), 4);
//                        break;
//                    case 10000:
                    //                       $update->rate_per = round(($g['rate_per'] * 0.55), 4);
                    //                       break;
                    default:
                        // Handle default case here if necessary
                        break;
                }
                $update->updated_on = date('Y-m-d H:i:s');
                $update->created_on = date('Y-m-d H:i:s');
                if (!$update->save()) {
                    print_r($update->getErrors());
                    exit();
                }
            }
                echo 'ok';
            }
        }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function actionTest(){
        $model = Demo::find()->asArray()->all();
        print_r($model);
    }

    public function actionManage(){
        $csv = [];
        $i = 0;
        try {
        ini_set('auto_detect_line_endings', TRUE);
        if (($handle = fopen(Url::to("/assets/.csv",'https'), "r")) !== false) {
            $columns = fgetcsv($handle, 1000, ",");
            while (($row = fgetcsv($handle)) !== false) {
                $csv[] = array_combine($columns, $row);
                $i++;
            }
            ini_set('auto_detect_line_endings', FALSE);
            fclose($handle);
        }
        $csv = $this->utf8ize($csv);
        $len = count($csv);
        $k = 0;
        for ($i=0;$i<$len;$i++){
            $k++;
            $model = new Plans();
            $model->min_age = $csv[$i]['min_age'];
            $model->max_age = $csv[$i]['max_age'];
            $model->policy_id = $csv[$i]['policy_id'];
            $model->coverage_id = $csv[$i]['coverage_id'];
            $model->company_id = $csv[$i]['company_id'];
            $model->rate_per = $csv[$i]['rate_per'];
            $model->deductible = $csv[$i]['deductible'];
            $model->pre_medical = $csv[$i]['pre_medical'];
            $model->plan_sub_category = $csv[$i]['plan_sub_category'];
            if ($model->save()){
                echo 'success '.$k;
            }else{
                echo 'error '.$k;
               print_r($model->getErrors());
            }
        }
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }
    private function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->utf8ize($value);
            }
        } elseif (is_string($mixed)) {
            return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
        }
        return $mixed;
    }

    public function actionEmail(){
        return Yii::$app->mailer->compose()
            ->setTo('snehkant93@gmail.com')
            ->setFrom(['admin@snehkant.com' => 'Sneh Kant'])
           // ->setReplyTo([$this->email => $this->name])
            ->setSubject('hello')
            ->setTextBody('hello')
            ->send();
    }

    public function actionEditor(){
        return $this->render('editor');
    }

    public function actionUpdatePlans(){
//        $model = Plans::find()
//            ->where(['pre_medical'=>0,'coverage_id'=>10,'company_id'=>2])
//            ->asArray()
//            ->all();
//        if ($model){
//            foreach ($model as $mod){
//                $data = Plans::findOne(['plan_id'=>$mod['plan_id']]);
//                $data->rate_per = $mod['rate_per'] + ($mod['rate_per'] * 0.05);
//                if ($data->save()){
//                    echo 'done';
//                }else{
//                    print_r($data->getErrors());
//                }
//            }
//        }else{
//            return 'no data';
//        }
    }
}

