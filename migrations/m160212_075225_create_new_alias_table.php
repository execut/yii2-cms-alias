<?php

use yii\db\Schema;
use yii\db\Migration;

class m160212_075225_create_new_alias_table extends Migration
{
    public function safeUp()
    {
        $this->dropTable('{{%alias_lang}}');
        $this->dropTable('{{%alias}}');

        $this->createTable('{{%alias}}', [
            'entity'        => $this->string()->notNull(),
            'entity_id'     => $this->integer()->unsigned()->notNull(),
            'language'      => $this->string(10)->notNull(),
            'url'           => $this->string()->notNull(),
            'type'          => "pages_type NOT NULL DEFAULT 'user-defined'",
            'created_at'    => $this->integer()->unsigned()->notNull(),
            'updated_at'    => $this->integer()->unsigned()->notNull(),
        ]);

        $this->addPrimaryKey('entity_entity_id_language', '{{%alias}}', ['entity', 'entity_id', 'language']);
    }

    public function safeDown()
    {
        $this->dropTable('alias');
    }
}
