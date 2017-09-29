<?php

use yii\db\Migration;

/**
 * Handles the creation of table `contact`.
 */
class m170925_071946_create_contact_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('contact', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'fname' => $this->string(64)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('contact');
    }
}
