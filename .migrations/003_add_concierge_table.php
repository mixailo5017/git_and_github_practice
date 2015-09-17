<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_concierge_table extends CI_Migration {

	public function up()
	{
		if ( ! $this->db->table_exists('exp_concierge') )
		{

			$fields = array(
				'id'		=> array('type' => 'int', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE ),
				'uid'		=> array('type' => 'int', 'constraint' => 5, 'unsigned' => TRUE ),
				'name'		=> array('type' => 'varchar', 'constraint' => 50, 'null' => TRUE ),
				'email'		=> array('type' => 'varchar', 'constraint' => 50, 'null' => TRUE ),
				'message'	=> array('type' => 'text', 'null' => TRUE ),
				'date'		=> array('type' => 'int', 'constraint' => 10,  'unsigned' => TRUE ),
				'archive'	=> array('type' => 'int', 'unsigned' => TRUE, 'default' => 0 ),
				'read'		=> array('type' => 'int', 'unsigned' => TRUE, 'default' => 0 ),
				'updated'	=> array('type' => 'int', 'constraint' => 10,  'unsigned' => TRUE )
			);

			$this->dbforge->add_field($fields);

			$this->dbforge->add_key('id', TRUE);

			$this->dbforge->create_table('exp_concierge');

		}
	}

	public function down()
	{
		if ( $this->db->table_exists('exp_concierge') )
		{
			$this->dbforge->drop_table('exp_concierge');
		}
	}

}