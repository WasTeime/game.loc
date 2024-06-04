<?php

namespace api\modules\v1\controllers;

use api\behaviors\returnStatusBehavior\JsonSuccess;
use common\models\Rating;
use common\models\RatingSearch;
use OpenApi\Attributes as OA;
use Yii;
use yii\helpers\ArrayHelper;

class RatingController extends AppController
{

    /**
     * {@inheritdoc}
     */
    public $modelClass = Rating::class;

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), ['auth' => ['except' => ['index']]]);
    }

    /**
     * Returns a list of Text's
     */
    #[OA\Get(
        path: '/rating/index',
        operationId: 'rating-index',
        description: 'Возвращает рейтинг',
        summary: 'Рейтинг',
        security: [['bearerAuth' => []]],
        tags: ['rating']
    )]
    #[JsonSuccess(content: [
        new OA\Property(
            property: 'texts', type: 'array',
            items: new OA\Items('#/components/schemas/Rating'),
        )
    ])]
    public function actionIndex(): array
    {
        $searchModel = new RatingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['max_points' => SORT_DESC, 'updated_at' => SORT_ASC];
        $dataProvider->pagination->pageSize = 10;
        return $this->returnSuccess($dataProvider->models, 'rating');
    }
}
