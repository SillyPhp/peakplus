<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Plans $model */

$this->title = 'Update Plans: ' . $model->plan_id;
$this->params['breadcrumbs'][] = ['label' => 'Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->plan_id, 'url' => ['view', 'plan_id' => $model->plan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="plans-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
