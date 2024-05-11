<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\PlansSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="plans-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'plan_id') ?>

    <?= $form->field($model, 'policy_id') ?>

    <?= $form->field($model, 'min_age') ?>

    <?= $form->field($model, 'max_age') ?>

    <?= $form->field($model, 'coverage_id') ?>

    <?php // echo $form->field($model, 'company_id') ?>

    <?php // echo $form->field($model, 'rate_per') ?>

    <?php // echo $form->field($model, 'deductible') ?>

    <?php // echo $form->field($model, 'pre_medical') ?>

    <?php // echo $form->field($model, 'plan_sub_category') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
