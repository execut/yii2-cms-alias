<?php

use yii\db\Schema;
use yii\db\Migration;

class m141010_135709_add_default_permissions extends Migration
{
    public function up()
    {
        // Create the auth items
        $this->insert('{{%auth_item}}', [
            'name'          => 'showAliasModule',
            'type'          => 2,
            'description'   => 'Show Alias module icon in main-menu',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
        
        // Create the auth item relation
        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showAliasModule'
        ]);
    }

    public function down()
    {
        // Delete the auth item relation
        $this->delete('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showAliasModule'
        ]);
        
        // Delete the auth items
        $this->delete('{{%auth_item}}', [
            'name'          => 'showAliasModule',
            'type'          => 2,
        ]);
    }
}
