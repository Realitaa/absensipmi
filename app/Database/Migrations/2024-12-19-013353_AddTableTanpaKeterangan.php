<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTableTanpaKeterangan extends Migration
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
            'tanggal' => [
                'type'    => 'DATE',
                'null'    => false,
            ],
        ]);

        // Menentukan primary key
        $this->forge->addKey('id', true);

        // Menentukan foreign key
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        // Membuat tabel 'users'
        $this->forge->createTable('tanpaketerangan', true);
    }

    public function down()
    {
        $this->forge->dropTable('tanpaketerangan', true);
    }
}
