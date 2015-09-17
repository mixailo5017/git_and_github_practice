<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_activity_tables extends CI_Migration {

	public function up()
	{
		if( ! $this->db->field_exists('pid', 'exp_proj_comment') )
		{
			$fields = array(
				'pid'		=> array('type' => 'text', 'null' => TRUE )
			);
			// ADD pid to Projects Comments Table
			$this->dbforge->add_column('exp_proj_comment', $fields);

			unset($fields);
		}

		// MEMBERS LOG TABLE
		if ( ! $this->db->table_exists('log_members') )
		{
			$fields = array(
				'log_id'		=> array('type' => 'int', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE ),
				'uid'			=> array('type' => 'int', 'constraint' => 5, 'unsigned' => TRUE ),
				'table_name'	=> array('type' => 'text', 'null' => TRUE ),
				'fields'		=> array('type' => 'text', 'null' => TRUE ),
				'last_date'		=> array('type' => 'timestamp'),
				'last_user'		=> array('type' => 'text', 'null' => TRUE )
			);

			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('log_id', TRUE);
			$this->dbforge->create_table('log_members');
		}

		unset($fields);

		// PROJECTS LOG TABLE
		if ( ! $this->db->table_exists('log_projects') )
		{
			$fields = array(
				'log_id'		=> array('type' => 'int', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE ),
				'pid'			=> array('type' => 'int', 'constraint' => 5, 'unsigned' => TRUE ),
				'table_name'	=> array('type' => 'text', 'null' => TRUE ),
				'fields'		=> array('type' => 'text', 'null' => TRUE ),
				'last_date'		=> array('type' => 'timestamp'),
				'last_user'		=> array('type' => 'text', 'null' => TRUE )
			);

			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('log_id', TRUE);
			$this->dbforge->create_table('log_projects');
		}

		unset($fields);

		$create_function_sql_array =  array(
				"CREATE FUNCTION log_members_update() RETURNS trigger AS \$log_members_update\$
					DECLARE
						-- Declare an varchars
						fields VARCHAR(300):= '';
						table_name VARCHAR(50);

					BEGIN

						-- Check that uid is given
						IF NEW.uid IS NULL THEN
							RAISE EXCEPTION 'uid cannot be null';
						END IF;

						-- log table name
						table_name = TG_RELNAME;

						fields = '';
						-- compare all new and old values to get diff fields
						IF NEW.firstname != OLD.firstname THEN
							fields = fields || ' firstname';
						END IF;
						IF NEW.lastname != OLD.lastname THEN
							fields = fields || ' lastname';
						END IF;
						IF NEW.email != OLD.email THEN
							fields = fields || ' email';
						END IF;
						IF NEW.organization != OLD.organization THEN
							fields = fields || ' organization';
						END IF;
						IF NEW.title != OLD.title THEN
							fields = fields || ' title';
						END IF;
						IF NEW.totalemployee != OLD.totalemployee THEN
							fields = fields || ' totalemployee';
						END IF;
						IF NEW.annualrevenue != OLD.annualrevenue THEN
							fields = fields || ' annualrevenue';
						END IF;
						IF NEW.discipline != OLD.discipline THEN
							fields = fields || ' discipline';
						END IF;
						IF NEW.country != OLD.country THEN
							fields = fields || ' country';
						END IF;
						IF NEW.city != OLD.city THEN
							fields = fields || ' city';
						END IF;
						IF NEW.state != OLD.state THEN
							fields = fields || ' state';
						END IF;
						IF NEW.userphoto != OLD.userphoto THEN
							fields = fields || ' userphoto';
						END IF;
						IF NEW.uservideo != OLD.uservideo THEN
							fields = fields || ' uservideo';
						END IF;
						IF NEW.forum_attendee != OLD.forum_attendee THEN
							fields = fields || ' forum_attendee';
						END IF;
						IF NEW.geocode != OLD.geocode THEN
							fields = fields || ' geocode';
						END IF;
						IF NEW.address != OLD.address THEN
							fields = fields || ' address';
						END IF;
						IF NEW.postal_code != OLD.postal_code THEN
							fields = fields || ' postal_code';
						END IF;

						IF fields = '' THEN
							RETURN NULL;
						END IF;

						INSERT INTO log_members (
								uid,
								table_name,
								fields,
								last_date,
								last_user
							) VALUES (
								new.uid,
								table_name,
								fields,
								current_timestamp,
								current_user
							);



						RETURN NULL;

					END;
				\$log_members_update\$ LANGUAGE plpgsql;",
				"CREATE FUNCTION log_members_other() RETURNS trigger AS \$log_members_other\$
					DECLARE
						-- Declare an varchars
						fields VARCHAR(300):= '';
						table_name VARCHAR(50);

					BEGIN

						-- Check that user id
						IF NEW.uid IS NULL THEN
							RAISE EXCEPTION 'uid cannot be null';
						END IF;

						-- log table name
						table_name = TG_RELNAME;
						fields = TG_OP;

						INSERT INTO log_members (
								uid,
								table_name,
								fields,
								last_date,
								last_user
							) VALUES (
								new.uid,
								table_name,
								fields,
								current_timestamp,
								current_user
							);

						RETURN NULL;

					END;
				\$log_members_other\$ LANGUAGE plpgsql;",
				"CREATE FUNCTION log_projects_update() RETURNS trigger AS \$log_projects_update\$
					DECLARE
						-- Declare an varchars
						fields VARCHAR(300):= '';
						table_name VARCHAR(50);

					BEGIN

						-- Check that pid is given
						IF NEW.pid IS NULL THEN
							RAISE EXCEPTION 'pid cannot be null';
						END IF;

						-- log table name
						table_name = TG_RELNAME;


						fields = '';
						-- compare all new and old values to get diff fields
						IF NEW.stage != OLD.stage THEN
							fields = fields || ' stage';
						END IF;
						IF NEW.projectname != OLD.projectname THEN
							fields = fields || ' projectname';
						END IF;
						IF NEW.slug != OLD.slug THEN
							fields = fields || ' slug';
						END IF;
						IF NEW.projectphoto != OLD.projectphoto THEN
							fields = fields || ' projectphoto';
						END IF;
						IF NEW.description != OLD.description THEN
							fields = fields || ' description';
						END IF;
						IF NEW.keywords != OLD.keywords THEN
							fields = fields || ' keywords';
						END IF;
						IF NEW.country != OLD.country THEN
							fields = fields || ' country';
						END IF;
						IF NEW.location != OLD.location THEN
							fields = fields || ' location';
						END IF;
						IF NEW.sector != OLD.sector THEN
							fields = fields || ' sector';
						END IF;
						IF NEW.subsector != OLD.subsector THEN
							fields = fields || ' subsector';
						END IF;
						IF NEW.subsector_other != OLD.subsector_other THEN
							fields = fields || ' subsector_other';
						END IF;
						IF NEW.totalbudget != OLD.totalbudget THEN
							fields = fields || ' totalbudget';
						END IF;
						IF NEW.financialstructure != OLD.financialstructure THEN
							fields = fields || ' financialstructure';
						END IF;
						IF NEW.financialstructure_other != OLD.financialstructure_other THEN
							fields = fields || ' financialstructure_other';
						END IF;
						IF NEW.fundamental_legal != OLD.fundamental_legal THEN
							fields = fields || ' fundamental_legal';
						END IF;
						IF NEW.isforum != OLD.isforum THEN
							fields = fields || ' isforum';
						END IF;
						IF NEW.status != OLD.status THEN
							fields = fields || ' status';
						END IF;
						IF NEW.entry_date != OLD.entry_date THEN
							fields = fields || ' entry_date';
						END IF;
						IF NEW.eststart != OLD.eststart THEN
							fields = fields || ' eststart';
						END IF;
						IF NEW.estcompletion != OLD.estcompletion THEN
							fields = fields || ' estcompletion';
						END IF;
						IF NEW.developer != OLD.developer THEN
							fields = fields || ' developer';
						END IF;
						IF NEW.sponsor != OLD.sponsor THEN
							fields = fields || ' sponsor';
						END IF;
						IF NEW.geocode != OLD.geocode THEN
							fields = fields || ' geocode';
						END IF;

						INSERT INTO log_projects (
								pid,
								table_name,
								fields,
								last_date,
								last_user
							) VALUES (
								new.pid,
								table_name,
								fields,
								current_timestamp,
								current_user
							);

						RETURN NULL;

					END;
				\$log_projects_update\$ LANGUAGE plpgsql;",
				"CREATE FUNCTION log_projects_other_update() RETURNS trigger AS \$log_projects_other_update\$
					DECLARE
						-- Declare an varchars
						fields VARCHAR(300):= '';
						table_name VARCHAR(50);
						project_id integer;

					BEGIN
						IF TG_OP = 'INSERT' THEN
							project_id = NEW.pid;
						ELSE
							project_id = OLD.pid;
						END IF;

						-- Check that empname and salary are given
						IF project_id IS NULL THEN
							RAISE EXCEPTION 'project_id cannot be null';
						END IF;

						-- compare all new and old values to get diff fields
						table_name = TG_RELNAME;
						fields = TG_OP;

						INSERT INTO log_projects (
								pid,
								table_name,
								fields,
								last_date,
								last_user
							) VALUES (
								project_id,
								table_name,
								fields,
								current_timestamp,
								current_user
							);

						RETURN NULL;

					END;
				\$log_projects_other_update\$ LANGUAGE plpgsql;"
			);

		foreach( $create_function_sql_array as $create_function_sql )
		{
			$this->db->query($create_function_sql);
		}

		// CREATE ALT TRIGGERS FOR PROJECT AND MEMBER TABLES
		$create_triggers_array = array(
			'CREATE TRIGGER log_members_update AFTER UPDATE ON exp_members
				FOR EACH ROW EXECUTE PROCEDURE log_members_update();',
			'CREATE TRIGGER log_projects_update AFTER UPDATE OR DELETE ON exp_projects
				FOR EACH ROW EXECUTE PROCEDURE log_projects_update();'
		);


		foreach( $create_triggers_array as $create_triggers )
		{
			$this->db->query($create_triggers);
		}

		// CREATE ALT TRIGGERS FOR OTHER MEMBER TABLES
		$other_tables = array(
			'exp_expertise', 'exp_expertise_sector', 'exp_education'
		);

		foreach( $other_tables as $table )
		{
			$sql_base = "CREATE TRIGGER log_members_other AFTER INSERT OR UPDATE OR DELETE ON {$table}
							FOR EACH ROW EXECUTE PROCEDURE log_members_other();";
			$this->db->query($sql_base);
		}

		unset($other_tables);

		// CREATE ALT TRIGGERS FOR OTHER PROJ TABLES
		$other_tables = array(
			'exp_proj_comment', 'exp_proj_assessment', 'exp_proj_design_issues',
			'exp_proj_engg_fundamental', 'exp_proj_environment', 'exp_proj_executive',
			'exp_proj_files', 'exp_proj_financial', 'exp_proj_fund_sources',
			'exp_proj_investment_return', 'exp_proj_machinery', 'exp_proj_map_points',
			'exp_proj_organization', 'exp_proj_participant_company', 'exp_proj_participant_critical',
			'exp_proj_participant_owner', 'exp_proj_participant_political', 'exp_proj_participant_public',
			'exp_proj_procurement_services', 'exp_proj_procurement_technology', 'exp_proj_regulatory', 'exp_proj_studies' );

		foreach( $other_tables as $table )
		{
			$sql_base = "CREATE TRIGGER log_projects_other_update AFTER INSERT OR UPDATE OR DELETE ON {$table}
							FOR EACH ROW EXECUTE PROCEDURE log_projects_other_update();";
			$this->db->query($sql_base);
		}

		unset($other_tables);

	}

	public function down()
	{
		// REMOVE MEMBERS LOG TABLE
		if ( $this->db->table_exists('log_members') )
		{
			$this->dbforge->drop_table('log_members');
		}

		// REMOVE PROJECTS LOG TABLE
		if ( $this->db->table_exists('log_projects') )
		{
			$this->dbforge->drop_table('log_projects');
		}

		// DROP Function log_members_update and its Triggers
		$sql = 'DROP FUNCTION IF EXISTS log_members_update() CASCADE;';
		$this->db->query($sql);

		// DROP Function log_members_other and its Triggers
		$sql = 'DROP FUNCTION IF EXISTS log_members_other() CASCADE;';
		$this->db->query($sql);

		// DROP Function log_projects_update and its Triggers
		$sql = 'DROP FUNCTION IF EXISTS log_projects_update() CASCADE;';
		$this->db->query($sql);

		// DROP Function log_projects_other_update and its Triggers
		$sql = 'DROP FUNCTION IF EXISTS log_projects_other_update() CASCADE;';
		$this->db->query($sql);

	}

}