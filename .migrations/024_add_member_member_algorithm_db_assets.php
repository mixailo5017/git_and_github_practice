<?php

class Migration_Add_member_member_algorithm_db_assets extends CI_Migration
{

  private $table = "exp_member_member_scores";
  private $timestamp_increment_function = PSQL_FUNC_TIMESTAMP_UPDATE_NAME;
  private $timestamp_update_trigger_name = "update_timestamp";

  public function up()
  {
    if(! $this->db->table_exists($this->table)){
      $fields = array(
        'id'              => array(
          'type'            => 'SERIAL',
          'auto_increment'  => TRUE,
          'null'            => FALSE,
        ),
        'member_id_1'    => array(
          'type'    => 'bigint',
          'null'    => FALSE,
        ),
        'member_id_2'     => array(
          'type'    => 'bigint',
          'null'    => FALSE,
        ),
        'score_sum'     => array(
          'type'    => 'int',
          'default' => 0,
        ),
        'sector_score' => array(
           'type'    => 'smallint',
           'default' => 0,
        ),
        'subsector_score' => array(
           'type'    => 'smallint',
           'default' => 0,
        ),
        'location' => array(
          'type'    => 'smallint',
          'default' => 0,
        ),
        'country' => array(
           'type'    => 'smallint',
           'default' => 0,
        ),
        'area_of_focus' => array(
           'type'    => 'smallint',
           'default' => 0,
        ),
        'discipline' => array(
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
      $this->dbforge->add_key('id',TRUE);
      $this->dbforge->create_table($this->table);

      //add foreign key constraints
      $this->db->query("ALTER TABLE {$this->table} ADD CONSTRAINT {$this->table}_member_id_1_fkey FOREIGN KEY (member_id_1) REFERENCES public.exp_members (uid)");
      $this->db->query("ALTER TABLE {$this->table} ADD CONSTRAINT {$this->table}_member_id_2_fkey FOREIGN KEY (member_id_2) REFERENCES public.exp_members (uid)");

      //add indexes on columns
      $this->db->query("CREATE INDEX {$this->table}_created_at_idx ON {$this->table} (created_at)");
      $this->db->query("CREATE INDEX {$this->table}_updated_at_idx ON {$this->table} (updated_at)");
      $this->db->query("CREATE INDEX {$this->table}_score_sum_idx ON {$this->table} (score_sum)");

      $trigger_definition = "
        CREATE TRIGGER $this->timestamp_update_trigger_name
        BEFORE UPDATE
        ON public.$this->table
        FOR EACH ROW
        EXECUTE PROCEDURE public.$this->timestamp_increment_function();
        COMMENT ON TRIGGER $this->timestamp_update_trigger_name ON public.$this->table IS 'Updates the update_at timestamp on row update';
      ";

      $this->db->query($trigger_definition);
    }
  }


  public function down()
  {

    //remove table
    $this->dbforge->drop_table($this->table);

  }
}