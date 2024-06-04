<?php

namespace admin\controllers;

use admin\controllers\AdminController;
use common\components\helpers\UserUrl;
use common\models\Rating;
use common\models\RatingSearch;
use kartik\grid\EditableColumnAction;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * RatingController implements the CRUD actions for Rating model.
 *
 * @package admin\controllers
 */
final class RatingController extends AdminController
{
    const PAGE_SIZE = 10;
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => ['delete' => ['POST']]
                ]
            ]
        );
    }

    /**
     * Lists all Rating models.
     *
     * @throws InvalidConfigException
     */
    public function actionIndex(): string
    {
        $searchModel = new RatingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['max_points' => SORT_DESC, 'updated_at' => SORT_ASC];
        $dataProvider->pagination->pageSize = self::PAGE_SIZE;

        return $this->render(
            'index',
            ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]
        );
    }

    /**
     * Displays a single Rating model.
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    /**
     * Creates a new Rating model.
     *
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @param string|null $redirect если нужен иной редирект после успешного создания
     *
     * @throws InvalidConfigException
     */
    /*public function actionCreate(string $redirect = null): Response|string
    {
        $model = new Rating();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Элемент №$model->id создан успешно");
            return match ($redirect) {
                'create' => $this->redirect(['create']),
                'index' => $this->redirect(UserUrl::setFilters(RatingSearch::class)),
                default => $this->redirect(['view', 'id' => $model->id])
            };
        }

        return $this->render('create', ['model' => $model]);
    }*/

    /**
     * Updates an existing Rating model.
     *
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    /*public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Элемент №$model->id изменен успешно");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', ['model' => $model]);
    }*/

    /**
     * Deletes an existing Rating model.
     *
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @throws NotFoundHttpException if the model cannot be found
     * @throws StaleObjectException
     * @throws Throwable
     */
    /*public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', "Элемент №$id удален успешно");
        return $this->redirect(UserUrl::setFilters(RatingSearch::class));
    }*/

    /**
     * Finds the Rating model based on its primary key value.
     *
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel(int $id): Rating
    {
        if (($model = Rating::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'change' => [
                'class' => EditableColumnAction::class,
                'modelClass' => Rating::class
            ]
        ];
    }
}
