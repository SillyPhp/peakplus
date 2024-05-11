<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CoverageAmount $model */

$this->title = 'Update Coverage Amount: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Coverage Amounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coverage-amount-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
