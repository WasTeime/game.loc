<?php

use common\components\helpers\UserUrl;
use common\models\GameSearch;
use yii\bootstrap5\Html;

/**
 * @var $this  yii\web\View
 * @var $model common\models\Game
 */

$this->title = Yii::t('app', 'Create Game');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Games'),
    'url' => UserUrl::setFilters(GameSearch::class)
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model, 'isCreate' => true]) ?>

</div>
