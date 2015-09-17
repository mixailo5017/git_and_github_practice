<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_member_pci_table extends CI_Migration {

    protected $table = 'exp_member_pci';

    public function up()
    {
        $fields = array(
            'member_id' => array('type' => 'int', 'null' => FALSE),
            'pci' => array('type' => 'smallint', 'null' => FALSE),
            'created_at' => array('type' => 'timestamp', 'null' => FALSE),
            'dismissed' => array('type' => 'date', 'null' => TRUE),
            'noshow' => array('type' => 'date', 'null' => TRUE),
        );
        $this->dbforge->add_field($fields);

        $this->dbforge->add_key('member_id', TRUE);

        $this->dbforge->create_table($this->table);

        $this->db->query("ALTER TABLE {$this->table}
                          ADD CONSTRAINT {$this->table}_member_id_fkey
                          FOREIGN KEY (member_id)
                          REFERENCES exp_members (uid)
                          ON DELETE CASCADE");
        $this->db->query("ALTER TABLE {$this->table} ALTER created_at SET DEFAULT CURRENT_TIMESTAMP");
    }

    public function down()
    {
        if ($this->db->table_exists($this->table)) {
            $this->dbforge->drop_table($this->table);
        }
    }
}