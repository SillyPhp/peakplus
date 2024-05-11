<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Policies $model */

$this->title = 'Create Policies';
$this->params['breadcrumbs'][] = ['label' => 'Policies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policies-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
