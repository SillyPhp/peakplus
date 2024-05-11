<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CoverageAmount $model */

$this->title = 'Create Coverage Amount';
$this->params['breadcrumbs'][] = ['label' => 'Coverage Amounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coverage-amount-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
