<?php

use admin\modules\modelExportImport\ModelExportImport;
use yii\bootstrap5\Html;
use yii\web\View;

/**
 * @var $this      View
 * @var $remoteUrl string
 */

$this->title = Yii::t(ModelExportImport::MODULE_MESSAGES, 'Move All Content To Remote Server');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="export-page">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (!$remoteUrl) : ?>
        <p>Не настроен адрес сервера для переноса контента</p>
    <?php else : ?>
        <?= Html::a(
            Yii::t(ModelExportImport::MODULE_MESSAGES, 'Move Content'),
            ['export-to-remote'],
            [
                'class' => 'btn btn-primary',
                'data-confirm' => 'Вы уверены что хотите отправить весь контент на удаленный хостинг?'
            ]
        ) . ' ' ?>
        <p style="margin-top: 10px">

        </p>
    <?php endif; ?>
</div>