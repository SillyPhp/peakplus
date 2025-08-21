<?php
namespace api\modules\v1\controllers;
use api\modules\v1\controllers\ApiBaseController;
use common\models\CoverageAmount;
use common\models\Demo;
use common\models\Plans;
use Yii;
use yii\db\Expression;

class ScriptController extends ApiBaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'send-data' => ['GET'],
                'test' => ['GET'],
                'send-email' => ['POST'],
                'send-test-email' => ['POST','GET'],
                'send-contact-email' => ['POST'],
            ]
        ];
        return $behaviors;
    }
    public function actionSendEmail(){
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $coverageAmount = CoverageAmount::findOne(['id'=>$post['params']['coverage']])->coverage_amount;
            $post['coverageAmount'] = $coverageAmount;
            $mail = Yii::$app->mailer->compose(['html' => 'query-email-html'],
                ['post' => $post])
                ->setTo('contact@peakplusfinancial.ca')
                ->setFrom(['contact@peakplusfinancial.ca' => 'Peak Plus Website'])
                ->setReplyTo([$post['email'] => 'Website Guest User'])
                ->setSubject('Someone Got A Query For You Mani')
                ->send();

            if ($mail){
                return $this->response(200, ['status' => 200, 'Message' => 'Success']);
            }else{
                return $this->response(200, ['status' => 404, 'Message' => 'Failed']);
            }
        }
    }

    public function actionSendContactEmail(){
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $mail = Yii::$app->mailer->compose()
                ->setTo('contact@peakplusfinancial.ca')
                ->setFrom(['contact@peakplusfinancial.ca' => 'Peak Plus Website'])
                ->setReplyTo([$post['email'] => $post['name']])
                ->setSubject($post['subject'])
                ->setTextBody($post['message'])
                ->send();

            if ($mail){
                return $this->response(200, ['status' => 200, 'Message' => 'Success']);
            }else{
                return $this->response(200, ['status' => 404, 'Message' => 'Failed']);
            }
        }
    }

    public function actionSendTestEmail(){
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            return $this->response(200, ['status' => 200, 'Message' => 'Success','data'=>$post]);
        }else{
            return $this->response(200, ['status' => 404, 'Message' => 'data not found']);
        }
    }
    public function actionSendData(){
        if (Yii::$app->request->get()){
            $data = Yii::$app->request->get();
            $Totaldays = $data['number_of_days'];
            $insuraneType = $data['insurance-type'];
            $model_coverage = CoverageAmount::findOne(['id'=>$data['coverage']])->coverage_amount;
            $plan_2 = [
                "plan_id" => "579",
                "plan_sub_category" => "BASIC",
                "medical" => $data['medicalType'],
                "ref_url" => "https://www.desttravel.com/?id=77c6cdad-aa06-4f3b-99f0-0bec5c2233b8#/insuranceproducts/visitortocanadainsurance/quote/planprofile",
                "number_of_days" => $data['number_of_days'],
                "total_price" => null,
                "document_name" => "Destination-Canada-Policy-Details",
                "document_url" => "https://www.desttravel.com/downloads/DTC%20Visitors%20to%20Canada%20Policy_EN%200125.pdf",
                "min_age" => $data['age'],
                "max_age" => $data['age'],
                "policy_id" => "2",
                "rate_per" => null,
                "company_id" => "2",
                "deductible" => $data['deductible'],
                "coverage_amount" => $model_coverage,
                "pre_medical" => ($data['medicalType'] == 1) ? "Covered" : "Non Covered",
                "name" => "Destination Canada",
                "logo" => "https://api.peakplusfinancial.ca/assets/companies-logo/destination.png"
            ];
            if ($insuraneType==1){
                $results = Plans::find()
                    ->alias('a')
                    ->select(['a.plan_id',
                        'plan_sub_category',
                        'a.pre_medical as medical',
                        '(CASE
                WHEN a.pre_medical = 1 AND a.company_id = 5 THEN b.ref_url
                WHEN a.pre_medical = 0 AND a.company_id = 5 THEN b.ref_url_2
                ELSE b.ref_url
               END) as ref_url',
                        'number_of_days' => new \yii\db\Expression($Totaldays),
                        'total_price' => new \yii\db\Expression("CASE WHEN $Totaldays >= 365 AND a.company_id = 5 THEN ROUND(a.rate_per*360,2) ELSE ROUND(a.rate_per*$Totaldays,2) END"),
                        'd.document_name','url as document_url','a.min_age','a.max_age','a.policy_id',
                        'ROUND(a.rate_per,2) as rate_per',
                        'a.company_id','a.deductible','c.coverage_amount',
                        '(CASE
                WHEN a.pre_medical = 1 THEN "Covered"
                WHEN a.pre_medical = 0 THEN "Non Covered"
                ELSE NULL
               END) as pre_medical','b.name','b.logo'])
                    ->joinWith(['company b'=>function ($b){
                        $b->select(['b.id']);
                        $b->joinWith(['policyDetailsDocuments d'=>function($b){

                        }],false);
                    }],false)
                    ->joinWith('coverage c',false)
                    ->where(['coverage_id'=>$data['coverage']])
                    ->andWhere(['pre_medical'=>$data['medicalType']])
                    ->andWhere(['!=','a.company_id','2'])
                    ->andFilterWhere(['<=', 'min_age',$data['age']])
                    ->andFilterWhere(['>=', 'max_age',$data['age']])
                    ->orderBy(
                        [
                            new \yii\db\Expression('b.ref_url IS NULL ASC')
                        ]
                    )
                    ->andWhere(['deductible'=>$data['deductible']])
                    ->asArray()->all();
            }elseif ($insuraneType==2){
                $results = Plans::find()
                    ->alias('a')
                    ->select(['a.plan_id',
                        'plan_sub_category',
                        'a.pre_medical as medical',
                        '(CASE
                WHEN a.pre_medical = 1 AND a.company_id = 5 THEN b.ref_url
                WHEN a.pre_medical = 0 AND a.company_id = 5 THEN b.ref_url_2
                ELSE b.ref_url
               END) as ref_url',
                        'number_of_days' => new \yii\db\Expression($Totaldays),
                        'policy_for' => new \yii\db\Expression("'Couple'"),
                        'total_price' => new \yii\db\Expression("CASE WHEN $Totaldays >= 365 AND a.company_id = 5 THEN ROUND(a.rate_per*360,2) ELSE ROUND(a.rate_per*$Totaldays,2) END"),
                        'd.document_name','url as document_url','a.min_age','a.max_age','a.policy_id','ROUND(a.rate_per,2) as rate_per','a.company_id','a.deductible','c.coverage_amount',
                        '(CASE
                WHEN a.pre_medical = 1 THEN "Covered"
                WHEN a.pre_medical = 0 THEN "Non Covered"
                ELSE NULL
               END) as pre_medical','b.name','b.logo'])
                    ->joinWith(['company b'=>function ($b){
                        $b->select(['b.id']);
                        $b->joinWith(['policyDetailsDocuments d'=>function($b){

                        }],false);
                    }],false)
                    ->joinWith('coverage c',false)
                    ->where(['coverage_id'=>$data['coverage']])
                    ->andWhere(['pre_medical'=>$data['medicalType']])
                    ->andWhere(['!=','a.company_id','2'])
                    ->orderBy(
                        [
                            new \yii\db\Expression('b.ref_url IS NULL ASC')
                        ]
                    )
                    ->andWhere(['deductible'=>$data['deductible']]);
                $q1 = clone $results;
                $q2 = clone $results;

                $r1 =  $q1->andFilterWhere(['<=', 'min_age',$data['age']])
                    ->andFilterWhere(['>=', 'max_age',$data['age']])->asArray()->all();
                $r2 = $q2->andFilterWhere(['<=', 'min_age',$data['age2']])
                    ->andFilterWhere(['>=', 'max_age',$data['age2']])->asArray()->all();
                $results = array_merge($r1,$r2);
                if ($results){
                    // Create an empty array to store the merged and summed results
                    $merged_results = [];
                    // Iterate through each plan in the $results array
                    foreach ($results as $plan) {
                        // Extract the company_id, plan_sub_category, and total_price from the current plan
                        $company_id = $plan['company_id'];
                        $plan_sub_category = $plan['plan_sub_category'];
                        $total_price = $plan['total_price'];
                        $rate_per = $plan['rate_per'];

                        // Create a unique key by concatenating company_id and plan_sub_category
                        $key = $company_id . "_" . $plan_sub_category;

                        // Check if the key exists in the $merged_results array
                        if (isset($merged_results[$key])) {
                            // Add the total_price to the existing value
                            $merged_results[$key]['total_price'] += $total_price;
                            $merged_results[$key]['rate_per'] += $rate_per;
                        } else {
                            // Otherwise, add the current plan to the $merged_results array
                            $merged_results[$key] = $plan;
                        }
                    }
                    // Reset the array keys to start from 0
                    $merged_results = array_values($merged_results);
                    $results = $merged_results;
                }
            }

            //fetch from api


            $applicationDate = $data['start_date'];
            $tripDays        = $data['number_of_days'];
            $age             = $data['age'];
            $familyPlan      = false; // or true
            $scheduleNo = ($data['medicalType'] == 1) ? 1 : 2;

        // Build query params dynamically
            $params = http_build_query([
                'applicationDate' => $applicationDate,
                'tripDays'        => $tripDays,
                'age'             => $age,
                'familyPlan'      => $familyPlan ? 'true' : 'false'
            ]);

            $url = "https://devweb.desttravel.com/api/visitorquote/premiumoptions?" . $params;

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'Authorization: Basic cGVha3BsdXMuYXBpOlBlYWtwbHVzQCMxMjM='
                ],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

             $response = json_decode($response, true);

            // Search for matching plan
            $result_plan = array_filter($response, function($plan) use ($model_coverage, $scheduleNo) {
                return $plan['SumInsured'] == $model_coverage && $plan['ScheduleNo'] == $scheduleNo;
            });

// Reset keys
            $result_plan = array_values($result_plan);

// Show result
            if (!empty($result_plan)) {
                $deductibleKey = "Deductible" . $data['deductible'];
                if (isset($result_plan[0][$deductibleKey])) {
                    $deductibleValue = $result_plan[0][$deductibleKey];
                    $plan_2['rate_per'] = $result_plan[0]['DailyRate'];
                    $plan_2['total_price'] = $deductibleValue;
                    //fetch api data
                    $results[] = $plan_2; //plan for company id 2;
                }
            }
            if ($results){
                return $this->response(200, ['status' => 200, 'data' => $results]);
            }else{
                return $this->response(200, ['status' => 404, 'data' => '']);
            }
        }
    }

    public function actionErrorTest(){
        // new Expression(' a.rate_per*'.$Totaldays.' as total_price')
        if (Yii::$app->request->get()){
            $data = Yii::$app->request->get();
            $Totaldays = $data['number_of_days'];
            $insuraneType = $data['insurance-type'];
            $PolicyType = (($data['insurance-type']==2)?"Couple":"individual");
            if ($insuraneType==1){
                $results = Plans::find()
                    ->alias('a')
                    ->select(['a.plan_id',
                        'plan_sub_category',
                        'a.pre_medical as medical',
                        '(CASE
                WHEN a.pre_medical = 1 AND a.company_id = 5 THEN b.ref_url
                WHEN a.pre_medical = 0 AND a.company_id = 5 THEN b.ref_url_2
                ELSE b.ref_url
               END) as ref_url',
                        'number_of_days' => new \yii\db\Expression($Totaldays),
                        'total_price' => new \yii\db\Expression("CASE WHEN $Totaldays >= 365 AND a.company_id = 5 THEN ROUND(a.rate_per*360,2) ELSE ROUND(a.rate_per*$Totaldays,2) END"),
                        'd.document_name','url as document_url','a.min_age','a.max_age','a.policy_id','ROUND(a.rate_per,2) as rate_per','a.company_id','a.deductible','c.coverage_amount',
                        '(CASE
                WHEN a.pre_medical = 1 THEN "Covered"
                WHEN a.pre_medical = 0 THEN "Non Covered"
                ELSE NULL
               END) as pre_medical','b.name','b.logo'])
                    ->joinWith(['company b'=>function ($b){
                        $b->select(['b.id']);
                        $b->joinWith(['policyDetailsDocuments d'=>function($b){

                        }],false);
                    }],false)
                    ->joinWith('coverage c',false)
                    ->where(['coverage_id'=>$data['coverage']])
                    ->andWhere(['pre_medical'=>$data['medicalType']])
                    ->andFilterWhere(['<=', 'min_age',$data['age']])
                    ->andFilterWhere(['>=', 'max_age',$data['age']])
                    ->orderBy(
                        [
                            new \yii\db\Expression('b.ref_url IS NULL ASC')
                        ]
                    )
                    ->andWhere(['deductible'=>$data['deductible']])
                    ->asArray()->all();
            }elseif ($insuraneType==2) {
                $results = Plans::find()
                    ->alias('a')
                    ->select(['a.plan_id',
                        'plan_sub_category',
                        'a.pre_medical as medical',
                        '(CASE
                WHEN a.pre_medical = 1 AND a.company_id = 5 THEN b.ref_url
                WHEN a.pre_medical = 0 AND a.company_id = 5 THEN b.ref_url_2
                ELSE b.ref_url
               END) as ref_url',
                        'number_of_days' => new \yii\db\Expression($Totaldays),
                        'policy_for' => new \yii\db\Expression("'Couple'"),
                        'total_price' => new \yii\db\Expression("CASE WHEN $Totaldays >= 365 AND a.company_id = 5 THEN ROUND(a.rate_per*360,2) ELSE ROUND(a.rate_per*$Totaldays,2) END"),
                        'd.document_name','url as document_url','a.min_age','a.max_age','a.policy_id','ROUND(a.rate_per,2) as rate_per','a.company_id','a.deductible','c.coverage_amount',
                        '(CASE
                WHEN a.pre_medical = 1 THEN "Covered"
                WHEN a.pre_medical = 0 THEN "Non Covered"
                ELSE NULL
               END) as pre_medical','b.name','b.logo'])
                    ->joinWith(['company b'=>function ($b){
                        $b->select(['b.id']);
                        $b->joinWith(['policyDetailsDocuments d'=>function($b){

                        }],false);
                    }],false)
                    ->joinWith('coverage c',false)
                    ->where(['coverage_id'=>$data['coverage']])
                    ->andWhere(['pre_medical'=>$data['medicalType']])
                    ->orderBy(
                        [
                            new \yii\db\Expression('b.ref_url IS NULL ASC')
                        ]
                    )
                    ->andWhere(['deductible'=>$data['deductible']]);
                $q1 = clone $results;
                $q2 = clone $results;

                $r1 =  $q1->andFilterWhere(['<=', 'min_age',$data['age']])
                    ->andFilterWhere(['>=', 'max_age',$data['age']])->asArray()->all();
                $r2 = $q2->andFilterWhere(['<=', 'min_age',$data['age2']])
                    ->andFilterWhere(['>=', 'max_age',$data['age2']])->asArray()->all();
                $results = array_merge($r1,$r2);
                if ($results){
                    // Create an empty array to store the merged and summed results
                    $merged_results = [];
                    // Iterate through each plan in the $results array
                    foreach ($results as $plan) {
                        // Extract the company_id, plan_sub_category, and total_price from the current plan
                        $company_id = $plan['company_id'];
                        $plan_sub_category = $plan['plan_sub_category'];
                        $total_price = $plan['total_price'];
                        $rate_per = $plan['rate_per'];

                        // Create a unique key by concatenating company_id and plan_sub_category
                        $key = $company_id . "_" . $plan_sub_category;

                        // Check if the key exists in the $merged_results array
                        if (isset($merged_results[$key])) {
                            // Add the total_price to the existing value
                            $merged_results[$key]['total_price'] += $total_price;
                            $merged_results[$key]['rate_per'] += $rate_per;
                        } else {
                            // Otherwise, add the current plan to the $merged_results array
                            $merged_results[$key] = $plan;
                        }
                    }
                    // Reset the array keys to start from 0
                    $merged_results = array_values($merged_results);
                    $results = $merged_results;
              }
            }
            if ($results){
                return $this->response(200, ['status' => 200, 'data' => $results]);
            }else{
                return $this->response(200, ['status' => 404, 'data' => '']);
            }
        }
    }
}