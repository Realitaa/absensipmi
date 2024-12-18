<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTableHadir extends Migration
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
            'waktu' => [
                'type'    => 'DATETIME',
                'null'    => false,
            ],
        ]);

        // Menentukan primary key
        $this->forge->addKey('id', true);

        // Menentukan foreign key
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        // Membuat tabel 'users'
        $this->forge->createTable('hadir', true);
    }

    public function down()
    {
        // Menghapus tabel 'users'
        $this->forge->dropTable('hadir', true);
    }
}
