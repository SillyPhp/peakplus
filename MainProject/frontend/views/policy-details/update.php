<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PolicyDetailsDocuments $model */

$this->title = 'Update Policy Details Documents: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Policy Details Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="policy-details-documents-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
