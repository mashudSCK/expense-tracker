<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('categories');
        
        // Insert default categories
        $db = \Config\Database::connect();
        $db->table('categories')->insertBatch([
            ['name' => 'Food & Dining', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Transportation', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Shopping', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Entertainment', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Bills & Utilities', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Healthcare', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Education', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Others', 'created_at' => date('Y-m-d H:i:s')],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('categories');
    }
}
