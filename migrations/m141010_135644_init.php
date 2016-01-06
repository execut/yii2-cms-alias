<?php

use yii\db\Schema;
use yii\db\Migration;

class m141010_135644_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        // Create '' table
        $this->createTable('{{%alias}}', [
            'id'            => Schema::TYPE_PK,
            'type'          => "ENUM('system','user-defined') NOT NULL DEFAULT 'user-defined'",
            'entity'        => Schema::TYPE_STRING . '(50) NOT NULL',
            'entity_id'     => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'created_at'    => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);
        
        $this->createIndex('entity_entity_id', '{{%alias}}', ['entity', 'entity_id'], true);
        $this->createIndex('entity_id', '{{%alias}}', 'entity_id');

        // Create 'alias_lang' table
        $this->createTable('{{%alias_lang}}', [
            'alias_id'      => Schema::TYPE_INTEGER . ' NOT NULL',
            'language'      => Schema::TYPE_STRING . '(10) NOT NULL',
            'url'           => Schema::TYPE_STRING . '(255) NOT NULL',
            'created_at'    => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('alias_id_language', '{{%alias_lang}}', ['alias_id', 'language']);
        //$this->createIndex('language_url', '{{%alias_lang}}', ['language', 'url'], true);
        $this->createIndex('language', '{{%alias_lang}}', 'language');
        $this->createIndex('url', '{{%alias_lang}}', 'url');
        $this->addForeignKey('FK_ALIAS_LANG_ALIAS_ID', '{{%alias_lang}}', 'alias_id', '{{%alias}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('alias_lang');
        $this->dropTable('alias');
    }
}
