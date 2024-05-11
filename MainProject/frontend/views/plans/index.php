<?php

use common\models\Plans;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\PlansSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Plans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plans-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Plans', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'plan_id',
            'min_age',
            'max_age',
            [
                'label'=>'Coverage',
                'attribute'=>'coverage_amount',
                'value'=>function($model){
                    return $model->coverage->coverage_amount.' $';
                }
            ],
            [
                'label'=>'Company',
                'attribute' => 'company',
                'value'=>function($model){
                    return $model->company->name;
                }
            ],
            [
                'label'=>'Rate Per',
                'attribute' => 'rate_per',
                'value'=>function($model){
                    return $model->rate_per.' $';
                }
            ],
            [
                'label'=>'Deductible',
                'attribute'=>'deductible',
                'value'=>function($model){
                    return $model->deductible.' $';
                }
            ],
            [
                'label'=>'Pre Medical',
                'attribute'=>'pre_medical',
                'filter'=>[0=>"No",1=>"Yes"],
                'value'=>function($model){
                     if ($model->pre_medical==0){
                         return 'No';
                     }elseif ($model->pre_medical==1){
                         return 'Yes';
                     }
                }
            ],
            'created_on',
            'updated_on',
            'plan_sub_category',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Plans $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'plan_id' => $model->plan_id]);
                 }
            ],
        ],
    ]); ?>


</div>
