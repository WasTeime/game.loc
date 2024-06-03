<?php

use yii\db\Migration;

/**
 * Class m240530_120928_add_uid_columns_to_table_user
 */
class m240530_120928_add_uid_columns_to_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'uid', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'uid');
    }
}
