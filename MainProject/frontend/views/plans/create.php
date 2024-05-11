<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Plans $model */

$this->title = 'Create Plans';
$this->params['breadcrumbs'][] = ['label' => 'Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plans-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
