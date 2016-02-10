<?php

use yii\db\Schema;
use yii\db\Migration;

class m160205_135337_move_entity_to_lang extends Migration
{
    public function up()
    {
        $this->dropIndex('entity_entity_id', '{{%alias}}');
        $this->dropColumn('{{%alias}}', 'entity');
        $this->dropColumn('{{%alias}}', 'entity_id');

        $this->addColumn('{{%alias_lang}}', 'entity', Schema::TYPE_STRING . '(255) NOT NULL');
        $this->addColumn('{{%alias_lang}}', 'entity_id', Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL');

        $this->createIndex('language_url_entity_entity_id', '{{%alias_lang}}', ['language', 'url', 'entity', 'entity_id'], true);
    }

    public function down()
    {
        echo "m160205_135337_move_entity_to_lang cannot be reverted.\n";

        return false;
    }


}
