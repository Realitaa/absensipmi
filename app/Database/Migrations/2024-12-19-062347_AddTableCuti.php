<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTableCuti extends Migration
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
        $this->forge->createTable('cuti', true);
    }

    public function down()
    {
        $this->forge->dropTable('cuti', true);
    }
}
