<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

?>
<div class="verify-email">
    <p>Hey Could You Help Me? My Email Is <?= $post['email'] ?>  And Contact Details: <?= (($post['phone'])?$post['phone']:"")?></p>
      <p>Looking For Policy Details Below</p></div></br>
       <p> Age: <b> <?= $post['params']['age'] ?> </b></p>
       <p> Pre Medical: <b> <?= (($post['params']['medicalType']=="1")?"Yes":"No") ?> </b></p>
       <p> Plan: <b> <?= (($post['params']['plan_type']=="1")?"SUPER VISA INSURANCE":"VISITORS VISA INSURANCE") ?> </b></p>
       <p> Number Of Days: <b> <?= $post['params']['number_of_days'] ?> </b></p>
       <p> Coverage Amount: <b> <?= "$ ".$post['coverageAmount'] ?> </b></p>
        <?php if (isset($post['params']['deductible'])){ ?>
       <p> Deductible: <b> <?= "$ ". $post['params']['deductible'] ?> </b></p>
        <?php }else{
           echo "<p> Deductible: <b>$ 0</b></p>";
        } ?>
</div>
