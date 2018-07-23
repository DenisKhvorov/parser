<?php

use yii\db\Migration;

/**
 * Handles the creation of table `offices`.
 */
class m180723_194240_create_offices_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('offices', [
            'id' => $this->primaryKey(),
            'office_title' => $this->string()->notNull(),
            'office_price' => $this->integer()->notNull(),
            'office_numbers' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('offices');
    }
}
