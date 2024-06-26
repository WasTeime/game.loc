<?php

use admin\components\GroupedActionColumn;
use admin\components\widgets\gridView\Column;
use admin\components\widgets\gridView\ColumnDate;
use admin\components\widgets\gridView\ColumnSelect2;
use admin\enums\GameStatus;
use admin\modules\rbac\components\RbacHtml;
use admin\widgets\sortableGridView\SortableGridView;
use kartik\grid\SerialColumn;
use yii\helpers\Url;
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
            ColumnDate::widget(['attr' => 'start', 'searchModel' => $searchModel, 'editable' => false]),
            ColumnDate::widget(['attr' => 'end', 'searchModel' => $searchModel, 'editable' => false]),
            Column::widget(['attr' => 'duration', 'editable' => false, 'format' => 'duration']),
            Column::widget(['attr' => 'points', 'editable' => false]),
            ColumnSelect2::widget([
                'attr' => 'user_id',
                'viewAttr' => 'user.username',
                'pathLink' => 'user/user',
                'editable' => false,
                'placeholder' => Yii::t('app', 'Search...'),
                'ajaxSearchConfig' => [
                    'url' => Url::to(['/user/user/list']),
                    'searchModel' => $searchModel
                ]
            ]),
            ColumnSelect2::widget(['attr' => 'status', 'editable' => false, 'items' => GameStatus::class, 'hideSearch' => true]),
            [
                'class' => GroupedActionColumn::class,
                'template' => '{view} {delete}'
            ]
        ]
    ]) ?>
</div>
