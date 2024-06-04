<?php

use admin\components\GroupedActionColumn;
use admin\components\widgets\gridView\Column;
use admin\components\widgets\gridView\ColumnDate;
use admin\components\widgets\gridView\ColumnSelect2;
use admin\modules\rbac\components\RbacHtml;
use admin\widgets\sortableGridView\SortableGridView;
use kartik\grid\SerialColumn;
use yii\helpers\Url;
use yii\widgets\ListView;

/**
 * @var $this         yii\web\View
 * @var $searchModel  common\models\RatingSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('app', 'Ratings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rating-index">

    <h1><?= RbacHtml::encode($this->title) ?></h1>


    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => SerialColumn::class],

//            Column::widget(),
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
            Column::widget(['attr' => 'max_points', 'editable' => false]),
            ColumnDate::widget(['attr' => 'updated_at', 'searchModel' => $searchModel, 'editable' => false]),
            /*[
                'class' => GroupedActionColumn::class,
                'template' => '{view} {delete}'
            ]*/
        ]
    ]) ?>
</div>
