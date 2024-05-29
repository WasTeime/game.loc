<?php

use yii\bootstrap5\Modal;

/**
 * @var $this  yii\web\View
 * @var $model common\models\Game
 */
?>

<?php $modal = Modal::begin([
    'title' => Yii::t('app', 'New Game'),
    'toggleButton' => [
        'label' => Yii::t('app', 'Create Game'),
        'class' => 'btn btn-success'
    ]
]) ?>

<?= $this->render('_form', ['model' => $model, 'isCreate' => false]) ?>

<?php Modal::end() ?>
