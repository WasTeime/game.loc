<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rating}}`.
 */
class m240529_142607_create_rating_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    final public function safeUp()
    {
        $this->createTable('{{%rating}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'max_points' => $this->bigInteger()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->notNull()->comment('Дата изменения'),
        ]);

        $this->createIndex(
            'idx-rating-user_id',
            '{{%rating}}',
            'user_id'
        );

        // creates index for column `author_id`
        $this->createIndex(
            'idx-rating-max_points-updated_at',
            '{{%rating}}',
            ['max_points', 'updated_at']
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-rating-user_id',
            'rating',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    final public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-post-author_id',
            'post'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-rating-max_points-updated_at',
            '{{%rating}}',
        );


        $this->dropIndex(
            'idx-rating-user_id',
            '{{%rating}}',
        );


        $this->dropTable('{{%rating}}');
    }
}
