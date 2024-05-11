<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/** @var yii\web\View $this */
/** @var common\models\Plans $model */
/** @var yii\widgets\ActiveForm $form */
//deductible in dollers

$deductible = [
        '0'=>'0',
        '100'=>'100',
        '500'=>'500',
        '1000'=>'1000'
];
?>

<div class="plans-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'policy_id')->dropDownList(ArrayHelper::map(\common\models\Policies::find()->asArray()->all(), 'id', 'plan_name'))->label('Policy') ?>

    <?= $form->field($model, 'min_age')->textInput() ?>

    <?= $form->field($model, 'max_age')->textInput() ?>

    <?= $form->field($model, 'coverage_id')->dropDownList(ArrayHelper::map(\common\models\CoverageAmount::find()->asArray()->all(), 'id', 'coverage_amount'))->label('Coverage ($)') ?>

    <?= $form->field($model, 'company_id')->dropDownList(ArrayHelper::map(\common\models\Companies::find()->asArray()->all(), 'id', 'name'))->label('Company') ?>

    <?= $form->field($model, 'rate_per')->textInput()->label('Rate Per ($)') ?>

    <?= $form->field($model, 'deductible')->dropDownList($deductible) ?>

    <?= $form->field($model, 'pre_medical')->dropDownList(['0'=>'No','1'=>'Yes']) ?>

    <?= $form->field($model, 'plan_sub_category')->dropDownList([ 'BASIC' => 'BASIC', 'STANDARD' => 'STANDARD', 'ENHANCED' => 'ENHANCED']) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
