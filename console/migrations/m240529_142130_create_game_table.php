<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%game}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m240529_142130_create_game_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    final public function safeUp()
    {
        $this->createTable('{{%game}}', [
            'id' => $this->primaryKey(),
            'start' => $this->integer()->notNull(),
            'end' => $this->integer(),
            'points' => $this->integer(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-game-user_id}}',
            '{{%game}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-game-user_id}}',
            '{{%game}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    final public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-game-user_id}}',
            '{{%game}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-game-user_id}}',
            '{{%game}}'
        );

        $this->dropTable('{{%game}}');
    }
}
