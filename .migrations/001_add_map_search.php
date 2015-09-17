<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_map_search extends CI_Migration {

	public function up()
	{
		
		$fields = array(
			'lat'		=> array('null' => 'y', 'type' => 'double precision'),
			'lng'		=> array('null' => 'y', 'type' => 'double precision'),
			'geocode'	=> array('null' => 'y', 'type' => 'text')
		);
		$this->dbforge->add_column('exp_projects', $fields);
		$this->dbforge->add_column('exp_members', $fields);

	}

	public function down()
	{
		
		//DROP PROJECT COLUMNS
		$this->dbforge->drop_column('exp_projects', 'lat');
		$this->dbforge->drop_column('exp_projects', 'lng');
		$this->dbforge->drop_column('exp_projects', 'geocode');

		// DROP EXPERT COLUMNS
		$this->dbforge->drop_column('exp_members', 'lat');
		$this->dbforge->drop_column('exp_members', 'lng');
		$this->dbforge->drop_column('exp_members', 'geocode');
	}

}