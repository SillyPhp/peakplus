<?php
use kartik\editors\Summernote;
use kartik\form\ActiveForm;

$form = \yii\widgets\ActiveForm::begin();

// Usage without model
echo Summernote::widget([
    'name' => 'comments',
    'value' => '<b>Some Initial Value.</b>',
    // other widget settings
]);

\yii\widgets\ActiveForm::end();
?>
