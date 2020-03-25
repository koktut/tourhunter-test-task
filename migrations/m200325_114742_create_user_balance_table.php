<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_balance}}`.
 */
class m200325_114742_create_user_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_balance}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'value' => $this->getDb()->getSchema()->createColumnSchemaBuilder('numeric')->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
        ]);

        $this->createIndex('user_balance_user_id_unq', 'user_balance', 'user_id', true);
        $this->createIndex('user_balance_value_idx', 'user_balance', 'value');

        $this->addForeignKey(
            'user_balance_user_id_fk',
            'user_balance',
            'user_id',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_balance}}');
    }
}
