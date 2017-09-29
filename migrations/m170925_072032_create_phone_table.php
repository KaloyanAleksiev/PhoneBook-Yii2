<?php

use yii\db\Migration;

/**
 * Handles the creation of table `phone`.
 */
class m170925_072032_create_phone_table extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $this->createTable('phone', [
            'id' => $this->primaryKey(),
            'number' => $this->integer(64)->notNull(),
            'type' => $this->integer(1)->defaultValue(0)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'contact_id' => $this->integer()->notNull()
        ]);

        $this->createIndex(
                'phone_index', 
                'phone', 
                'contact_id'
        );
        
        $this->addForeignKey(
                'fk_phone_contact', 
                'phone', 
                'contact_id', 
                'contact', 
                'id', 
                'CASCADE', 
                'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropForeignKey('fk_phone_contact', 'phone');
        $this->dropTable('phone');
    }

}
