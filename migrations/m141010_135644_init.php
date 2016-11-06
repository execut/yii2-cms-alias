<?php

use yii\db\Schema;
use yii\db\Migration;

class m141010_135644_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        if ($this->db->driverName === 'pgsql') {
            $this->execute('CREATE TYPE alias_type AS ENUM (\'system\',\'user-defined\')');
        }
        
        // Create '' table
        $this->createTable('{{%alias}}', [
            'id'            => $this->primaryKey(),
            'type'          => "alias_type NOT NULL DEFAULT 'user-defined'",
            'entity'        => $this->string(50)->notNull(),
            'entity_id'     => $this->integer()->unsigned()->notNull(),
            'created_at'    => $this->integer()->unsigned()->notNull(),
            'updated_at'    => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        
        $this->createIndex('alias_entity_entity_id_i', '{{%alias}}', ['entity', 'entity_id'], true);
        $this->createIndex('alias_entity_id_i', '{{%alias}}', 'entity_id');

        // Create 'alias_lang' table
        $this->createTable('{{%alias_lang}}', [
            'alias_id'      => $this->integer()->notNull(),
            'language'      => $this->string(10)->notNull(),
            'url'           => $this->string()->notNull(),
            'created_at'    => $this->integer()->unsigned()->notNull(),
            'updated_at'    => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('alias_id_language', '{{%alias_lang}}', ['alias_id', 'language']);
        //$this->createIndex('language_url', '{{%alias_lang}}', ['language', 'url'], true);
        $this->createIndex('alias_lang_language_i', '{{%alias_lang}}', 'language');
        $this->createIndex('alias_lang_url_i', '{{%alias_lang}}', 'url');
        $this->addForeignKey('FK_ALIAS_LANG_ALIAS_ID', '{{%alias_lang}}', 'alias_id', '{{%alias}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('alias_lang');
        $this->dropTable('alias');
    }
}
