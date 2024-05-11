<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PolicyDetailsDocuments $model */

$this->title = 'Create Policy Details Documents';
$this->params['breadcrumbs'][] = ['label' => 'Policy Details Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-details-documents-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
