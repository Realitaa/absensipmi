<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTableQRCode extends Migration
{
    public function up()
    {
        $this->forge->addField(
            [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uniqueCode' => [
                'type'           => 'VARCHAR',
                'constraint'     => 8,
            ],
            'issuer' => [
                'type'       => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        // Menentukan primary key
        $this->forge->addKey('id', true);
        
        // Menentukan foreign key
        $this->forge->addForeignKey('issuer', 'admins', 'id', 'CASCADE', 'CASCADE');

        // Membuat tabel 'qrcode'
        $this->forge->createTable('qrcode', true);
    }

    public function down()
    {
        $this->forge->dropTable('qrcode ', true);
    }
}
