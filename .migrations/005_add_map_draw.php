<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_map_draw extends CI_Migration {

	public function up()
	{
		
		// $sql = 'CREATE EXTENSION IF NOT EXISTS postgis;';
		// $this->db->query($sql);
		// $sql = 'CREATE EXTENSION IF NOT EXISTS postgis_topology;';
		// $this->db->query($sql);
		// unset($sql);
		
		if ( ! $this->db->table_exists('exp_proj_map_draw') )
		{

			$fields = array(
				'id'		=> array('type' => 'int', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE ),
				'proj_id'	=> array('type' => 'int', 'constraint' => 5, 'unsigned' => TRUE ),
				'poly_name'	=> array('type' => 'varchar', 'constraint' => 15, 'null' => TRUE ),
				'geom'		=> array('type' => 'geometry' ),
				'geojson'	=> array('type' => 'text', 'null' => TRUE ),
				'color'		=> array('type' => 'varchar', 'constraint' => 10, 'null' => TRUE ),
				'extra'		=> array('type' => 'text', 'null' => TRUE ),
			);

			$this->dbforge->add_field($fields);

			$this->dbforge->add_key('id', TRUE);

			$this->dbforge->create_table('exp_proj_map_draw');

		}

	}

	public function down()
	{
		if ( $this->db->table_exists('exp_proj_map_draw') )
		{
			$this->dbforge->drop_table('exp_proj_map_draw');
		}
	}

}