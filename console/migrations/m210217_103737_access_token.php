<?php

use yii\db\Migration;

/**
 * Таблица для хранения токенов доступа (bearer).
 */
class m210217_103737_access_token extends Migration
{

    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->createTable('access_token', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'value' => $this->char(64)->notNull(),
        ]);
        $this->createIndex('value_unique', 'access_token', 'value');

        $this->addForeignKey(
            'access_token__user_id__fk',
            'access_token', 'user_id',
            'user', 'id',
            'RESTRICT', 'RESTRICT',
        );
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropTable('access_token');
    }

}
