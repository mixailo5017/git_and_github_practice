<?php
/**
 * Created by PhpStorm.
 * User: goce
 * Date: 4/2/14
 * Time: 10:48
 */

class Migration_add_queue_table extends CI_Migration
{

  private $queue_table = QUEUE_TABLE;

  public function up()
  {

    if(! $this->db->table_exists($this->queue_table)){
      $fields = array(
        'id'          =>  array(
          'type'            => 'SERIAL',
          'auto_increment'  => TRUE,
          'null'            => FALSE,
        ),//end id
        'queue'  => array(
          'type'            => 'int',
        ),//end queue_name
        'data'        => array(
          'type'            => 'text',//holds serialized data
        ),//end data
        'created_at'  => array(
          'type'            => 'timestamp',
          'null'            => FALSE,
          'default'         => 'now',
        ),
      );

      $this->dbforge->add_field($fields);
      $this->dbforge->add_key('id',TRUE);
      $this->dbforge->create_table($this->queue_table);

      //add index to the craeted_at column
      //index the created_at column
      $created_index_name = $this->queue_table . "_created_at_idx";
      $queue_index_name = $this->queue_table . "_queue_idx";
      $this->db->query("DROP INDEX IF EXISTS $created_index_name");
      $this->db->query("CREATE INDEX $created_index_name ON {$this->queue_table} (created_at)");

      $this->db->query("DROP INDEX IF EXISTS $queue_index_name");
      $this->db->query("CREATE INDEX $queue_index_name ON {$this->queue_table} (queue)");
    }
  }

  public function down()
  {
    //remove the queue table
    $this->dbforge->drop_table($this->queue_table);
  }
}