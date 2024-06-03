<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%setting}}`.
 */
class m240530_124441_insert_attempts_batch_to_setting_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%setting}}', ['parameter', 'value', 'description'], [
            ['attempts', '100', 'Сколько попыток выдаётся пользователю'],
        ]);
    }

    public function safeDown()
    {
        $this->delete('{{%setting}}', ['parameter' => 'attempts']);
    }
}
