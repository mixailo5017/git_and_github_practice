<?php

class Migration_Add_member_project_algorithm_db_assets extends CI_Migration
{

  private $mem_proj_table = "exp_member_project_scores";
  private $timestamp_increment_function = PSQL_FUNC_TIMESTAMP_UPDATE_NAME;
  private $timestamp_update_trigger_name = "update_timestamp";

  public function up()
  {
    if(! $this->db->table_exists($this->mem_proj_table)){
      $fields = array(
        'project_id'    => array(
          'type'    => 'bigint',
          'null'    => FALSE,
        ),
        'member_id'     => array(
          'type'    => 'bigint',
          'null'    => FALSE,
        ),
        'score_sum'     => array(
          'type'    => 'int',
          'default' => 0,
        ),
        'subsector_score' => array(
          'type'    => 'smallint',
          'default' => 0,
        ),
        'sector_score' => array(
          'type'    => 'smallint',
          'default' => 0,
        ),
        'country_score' => array(
          'type'    => 'smallint',
          'default' => 0,
        ),
        'location_score' => array(
          'type'    => 'smallint',
          'default' => 0,
        ),
        'keywords_aof_score' => array(
          'type'    => 'smallint',
          'default' => 0,
        ),
        'keywords_aoe_score' => array(
          'type'    => 'smallint',
          'default' => 0,
        ),
        'created_at'  => array(
          'type'            => 'timestamp',
          'null'            => FALSE,
          'default'         => 'now',
        ),
        'updated_at'  => array(
          'type'            => 'timestamp',
          'null'            => FALSE,
          'default'         => 'now',
        ),
      );

      //create composite primary key and then table
      $this->dbforge->add_field($fields);
      $this->dbforge->add_key('project_id',TRUE);
      $this->dbforge->add_key('member_id',TRUE);
      $this->dbforge->create_table($this->mem_proj_table);

      //add foreign key constraints
      $this->db->query("ALTER TABLE {$this->mem_proj_table} ADD CONSTRAINT {$this->mem_proj_table}_project_id_fkey FOREIGN KEY (project_id) REFERENCES public.exp_projects (pid)");
      $this->db->query("ALTER TABLE {$this->mem_proj_table} ADD CONSTRAINT {$this->mem_proj_table}_member_id_fkey FOREIGN KEY (member_id) REFERENCES public.exp_members (uid)");

      //add indexes on columns
      $this->db->query("CREATE INDEX {$this->mem_proj_table}_created_at_idx ON {$this->mem_proj_table} (created_at)");
      $this->db->query("CREATE INDEX {$this->mem_proj_table}_updated_at_idx ON {$this->mem_proj_table} (updated_at)");
      $this->db->query("CREATE INDEX {$this->mem_proj_table}_score_sum_idx ON {$this->mem_proj_table} (score_sum)");

      $function_definition = "
        CREATE OR REPLACE FUNCTION public.$this->timestamp_increment_function()
        RETURNS trigger AS
        \$BODY\$
        BEGIN
            IF (TG_OP = 'UPDATE') THEN
                NEW.updated_at = now();
                RETURN NEW;
            END IF;
        END;
        \$BODY\$
        LANGUAGE plpgsql VOLATILE
        COST 100;";

      $this->db->query($function_definition);

      $trigger_definition = "
        CREATE TRIGGER $this->timestamp_update_trigger_name
        BEFORE UPDATE
        ON public.$this->mem_proj_table
        FOR EACH ROW
        EXECUTE PROCEDURE public.$this->timestamp_increment_function();
        COMMENT ON TRIGGER $this->timestamp_update_trigger_name ON public.$this->mem_proj_table IS 'Updates the update_at timestamp on row update';
      ";

      $this->db->query($trigger_definition);
    }
  }


  public function down()
  {
    //remove the trigger function
    $this->db->query("DROP FUNCTION IF EXISTS public.$this->timestamp_increment_function() CASCADE;");
    //remove trigger

    //remove table
    $this->dbforge->drop_table($this->mem_proj_table);

  }
}