<?php

use yii\db\Schema;
use yii\db\Migration;

class m160212_075225_create_new_alias_table extends Migration
{
    public function up()
    {
        $this->dropTable('{{%alias_lang}}');
        $this->dropTable('{{%alias}}');

        $this->createTable('{{%alias}}', [
            'entity'        => Schema::TYPE_STRING . '(255) NOT NULL',
            'entity_id'     => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'language'      => Schema::TYPE_STRING . '(10) NOT NULL',
            'url'           => Schema::TYPE_STRING . '(255) NOT NULL',
            'type'          => "ENUM('system','user-defined') NOT NULL DEFAULT 'user-defined'",
            'created_at'    => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ]);

        $this->addPrimaryKey('entity_entity_id_language', '{{%alias}}', ['entity', 'entity_id', 'language']);
    }

    public function down()
    {
        $this->dropTable('alias');
    }
}
