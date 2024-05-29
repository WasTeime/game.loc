<?php

namespace common\modules\user\migrations;

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m240529_143617_add_attempt_columns_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'attempts', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'attempts');
    }
}
