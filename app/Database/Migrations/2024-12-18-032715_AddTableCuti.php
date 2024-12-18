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
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'mulai' => [
                'type'    => 'DATETIME',
                'null'       => false,
            ],
            'selesai' => [
                'type'    => 'DATETIME',
                'null'       => false,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'deskripsi' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'tanggal_pengajuan' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        // Menentukan primary key
        $this->forge->addKey('id', true);

        // Menentukan foreign key
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        // Membuat tabel 'users'
        $this->forge->createTable('cuti', true);
    }

    public function down()
    {
        // Menghapus tabel 'users'
        $this->forge->dropTable('cuti', true);
    }
}
