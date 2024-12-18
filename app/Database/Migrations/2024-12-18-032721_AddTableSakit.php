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
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'mulai' => [
                'type'    => 'DATE',
                'null'       => false,
            ],
            'selesai' => [
                'type'    => 'DATE',
                'null'       => false,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'deskripsi' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Terima', 'Tolak', 'Menunggu'],
                'default'    => 'Menunggu',
                'null'       => false,
            ],
            'waktu_pengajuan' => [
                'type'    => 'DATETIME',
                'null'    => false,
            ],
        ]);

        // Menentukan primary key
        $this->forge->addKey('id', true);

         // Menentukan foreign key
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        // Membuat tabel 'users'
        $this->forge->createTable('sakit', true);
    }

    public function down()
    {
        // Menghapus tabel 'users'
        $this->forge->dropTable('sakit', true);
    }
}
