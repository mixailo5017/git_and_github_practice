<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_address_field extends CI_Migration {

	public function up()
	{

		$fields = array(
			'address'		=> array('null' => 'y', 'type' => 'varchar', 'constraint' => 150),
			'postal_code'	=> array('null' => 'y', 'type' => 'varchar', 'constraint' => 15)
		);

		$this->dbforge->add_column('exp_members', $fields);

	}

	public function down()
	{

		// DROP EXPERT COLUMNS
		if ($this->db->field_exists('address', 'exp_members'))
		{
			$this->dbforge->drop_column('exp_members', 'address');
		}
		if ($this->db->field_exists('postal_code', 'exp_members'))
		{
			$this->dbforge->drop_column('exp_members', 'postal_code');
		}

	}

}