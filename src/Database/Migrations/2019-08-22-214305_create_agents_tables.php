<?php namespace Tatter\Agents\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAgentsTables extends Migration
{
	public function up()
	{
		// Agents
		$fields = [
			'name'           => ['type' => 'varchar', 'constraint' => 31],
			'uid'            => ['type' => 'varchar', 'constraint' => 31],
			'class'          => ['type' => 'varchar', 'constraint' => 63],
			'icon'           => ['type' => 'varchar', 'constraint' => 31],
			'summary'        => ['type' => 'varchar', 'constraint' => 255],
			'created_at'     => ['type' => 'datetime', 'null' => true],
			'updated_at'     => ['type' => 'datetime', 'null' => true],
			'deleted_at'     => ['type' => 'datetime', 'null' => true],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addKey('name');
		$this->forge->addKey('uid');
		$this->forge->addKey(['deleted_at', 'id']);
		$this->forge->addKey('created_at');
		
		$this->forge->createTable('agents');
		
		// Results
		$fields = [
			'server_id'     => ['type' => 'int', 'unsigned' => true, 'null' => true],
			'agent_id'      => ['type' => 'int', 'unsigned' => true],
			'batch'         => ['type' => 'int', 'unsigned' => true],
			'level'         => ['type' => 'int', 'null' => true],
			'metric'        => ['type' => 'varchar', 'constraint' => 63],
			'format'        => ['type' => 'varchar', 'constraint' => 15],
			'hash'          => ['type' => 'varchar', 'constraint' => 32, 'null' => true],
			'content'       => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
			'created_at'    => ['type' => 'datetime', 'null' => true],
			'updated_at'    => ['type' => 'datetime', 'null' => true],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addKey(['agent_id', 'metric']);
		$this->forge->addKey(['metric', 'agent_id']);
		$this->forge->addKey(['batch', 'agent_id']);
		$this->forge->addKey('created_at');
		
		$this->forge->createTable('agents_results');
		
		// Hashes
		$fields = [
			'hash'          => ['type' => 'varchar', 'constraint' => 32],
			'content'       => ['type' => 'text'],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);
		$this->forge->addUniqueKey('hash');		

		$this->forge->createTable('agents_hashes');
	}

	public function down()
	{
		$this->forge->dropTable('agents');
		$this->forge->dropTable('agents_results');
		$this->forge->dropTable('agents_hashes');
	}
}
