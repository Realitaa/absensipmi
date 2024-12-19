<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTableSakit extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'absensi_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'deskripsi' => [
                'type'       => 'TEXT',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Terima', 'Tolak', 'Menunggu'],
                'default'    => 'Menunggu',
                'null'       => false,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        // Menentukan primary key
        $this->forge->addKey('id', true);
        
        // Menentukan foreign key
        $this->forge->addForeignKey('absensi_id', 'absensi', 'id', 'CASCADE', 'CASCADE');

        // Membuat tabel 'users'
        $this->forge->createTable('sakit', true);
    }

    public function down()
    {
        $this->forge->dropTable('sakit', true);
    }
}
