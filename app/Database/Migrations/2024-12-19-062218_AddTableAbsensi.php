<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTableAbsensi extends Migration
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
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'tanggal' => [
                'type'       => 'DATE',
                'null'       => false,
            ],
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['Hadir', 'Sakit', 'Cuti', 'Tanpa Keterangan'],
                'default'    => 'Tanpa Keterangan',
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        // Membuat tabel 'users'
        $this->forge->createTable('absensi', true);
    }

    public function down()
    {
        $this->forge->dropTable('absensi', true);
    }
}
