<?php

use admin\components\GroupedActionColumn;
use admin\components\widgets\gridView\Column;
use admin\components\widgets\gridView\ColumnSelect2;
use admin\enums\GameStatus;
use admin\modules\rbac\components\RbacHtml;
use admin\widgets\sortableGridView\SortableGridView;
use kartik\grid\SerialColumn;
use yii\widgets\ListView;

/**
 * @var $this         yii\web\View
 * @var $searchModel  common\models\GameSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $model        common\models\Game
 */

$this->title = Yii::t('app', 'Games');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-index">

    <h1><?= RbacHtml::encode($this->title) ?></h1>

<!--    <div>
        <?=
            RbacHtml::a(Yii::t('app', 'Create Game'), ['create'], ['class' => 'btn btn-success']);
//           $this->render('_create_modal', ['model' => $model]);
        ?>
    </div>-->

    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => SerialColumn::class],

//            Column::widget(),
            Column::widget(['attr' => 'start', 'editable' => false, 'format' => 'datetime']),
            Column::widget(['attr' => 'end', 'editable' => false, 'format' => 'datetime']),
            Column::widget(['attr' => 'points', 'editable' => false]),
            Column::widget(['attr' => 'user_id', 'editable' => false]),
            ColumnSelect2::widget(['attr' => 'status', 'editable' => false, 'items' => GameStatus::class, 'hideSearch' => true]),

            ['class' => GroupedActionColumn::class]
        ]
    ]) ?>
</div>
