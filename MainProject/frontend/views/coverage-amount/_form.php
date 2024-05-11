<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CoverageAmount $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="coverage-amount-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'coverage_amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
