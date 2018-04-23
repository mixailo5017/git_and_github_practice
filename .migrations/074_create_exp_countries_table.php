<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_exp_countries_table extends CI_Migration
{
    protected $table = 'exp_countries';

    public function up()
    {
        $this->create_table();
        $this->insert_data();
    }

    public function down()
    {
        if ($this->db->table_exists($this->table)) {
            $this->dbforge->drop_table($this->table);
        }
    }

    private function execute($sql)
    {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }

    private function create_table()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = [
                'countryname' => [
                    'type'       => 'varchar',
                    'constraint' => 255,
                    'null'       => false
                ],
                'devlevel'    => [
                    'type'       => 'varchar',
                    'constraint' => 32,
                    'null'       => false
                ],
            ];
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('countryname', true);
            $this->dbforge->create_table($this->table);

            $sql = array(
                "COMMENT ON COLUMN {$this->table}.devlevel IS 'Denotes whether country is an emerging market (EM) or developed economy/rest of world (RoW)'"
            );

            $this->execute($sql);
        }
    }

    private function insert_data()
    {
        $data = [
            new Country('Afghanistan', 'EM'),
            new Country('Albania', 'RoW'),
            new Country('Algeria', 'EM'),
            new Country('Andorra', 'RoW'),
            new Country('Angola', 'EM'),
            new Country('Antigua & Deps', 'RoW'),
            new Country('Argentina', 'EM'),
            new Country('Armenia', 'EM'),
            new Country('Australia', 'RoW'),
            new Country('Austria', 'RoW'),
            new Country('Azerbaijan', 'EM'),
            new Country('Bahamas', 'EM'),
            new Country('Bahrain', 'EM'),
            new Country('Bangladesh', 'EM'),
            new Country('Barbados', 'EM'),
            new Country('Belarus', 'EM'),
            new Country('Belgium', 'RoW'),
            new Country('Belize', 'EM'),
            new Country('Benin', 'EM'),
            new Country('Bhutan', 'EM'),
            new Country('Bolivia', 'EM'),
            new Country('Bosnia Herzegovina', 'RoW'),
            new Country('Botswana', 'EM'),
            new Country('Brazil', 'EM'),
            new Country('Brunei', 'EM'),
            new Country('Bulgaria', 'RoW'),
            new Country('Burkina', 'EM'),
            new Country('Burundi', 'EM'),
            new Country('Cambodia', 'EM'),
            new Country('Cameroon', 'EM'),
            new Country('Canada', 'RoW'),
            new Country('Cape Verde', 'EM'),
            new Country('Central African Rep', 'RoW'),
            new Country('Chad', 'EM'),
            new Country('Chile', 'EM'),
            new Country('China', 'EM'),
            new Country('Colombia', 'EM'),
            new Country('Comoros', 'EM'),
            new Country('Congo {Democratic Rep}', 'EM'),
            new Country('Congo', 'EM'),
            new Country('Costa Rica', 'EM'),
            new Country('Croatia', 'RoW'),
            new Country('Cuba', 'EM'),
            new Country('Cyprus', 'RoW'),
            new Country('Czech Republic', 'RoW'),
            new Country('Denmark', 'RoW'),
            new Country('Djibouti', 'EM'),
            new Country('Dominica', 'EM'),
            new Country('Dominican Republic', 'EM'),
            new Country('East Timor', 'RoW'),
            new Country('Ecuador', 'EM'),
            new Country('Egypt', 'EM'),
            new Country('El Salvador', 'EM'),
            new Country('Equatorial Guinea', 'EM'),
            new Country('Eritrea', 'EM'),
            new Country('Estonia', 'RoW'),
            new Country('Ethiopia', 'EM'),
            new Country('Fiji', 'EM'),
            new Country('Finland', 'RoW'),
            new Country('France', 'RoW'),
            new Country('Gabon', 'EM'),
            new Country('Gambia', 'EM'),
            new Country('Georgia', 'EM'),
            new Country('Germany', 'RoW'),
            new Country('Ghana', 'EM'),
            new Country('Greece', 'RoW'),
            new Country('Grenada', 'EM'),
            new Country('Guatemala', 'EM'),
            new Country('Guinea', 'EM'),
            new Country('Guinea-Bissau', 'EM'),
            new Country('Guyana', 'EM'),
            new Country('Haiti', 'EM'),
            new Country('Honduras', 'EM'),
            new Country('Hungary', 'RoW'),
            new Country('Iceland', 'RoW'),
            new Country('India', 'EM'),
            new Country('Indonesia', 'EM'),
            new Country('Iran', 'EM'),
            new Country('Iraq', 'EM'),
            new Country('Ireland {Republic}', 'RoW'),
            new Country('Israel', 'RoW'),
            new Country('Italy', 'RoW'),
            new Country('Ivory Coast', 'EM'),
            new Country('Jamaica', 'EM'),
            new Country('Japan', 'RoW'),
            new Country('Jordan', 'EM'),
            new Country('Kazakhstan', 'EM'),
            new Country('Kenya', 'EM'),
            new Country('Kiribati', 'EM'),
            new Country('Korea North', 'RoW'),
            new Country('Korea South', 'RoW'),
            new Country('Kosovo', 'RoW'),
            new Country('Kuwait', 'EM'),
            new Country('Kyrgyzstan', 'EM'),
            new Country('Laos', 'RoW'),
            new Country('Latvia', 'RoW'),
            new Country('Lebanon', 'EM'),
            new Country('Lesotho', 'EM'),
            new Country('Liberia', 'EM'),
            new Country('Libya', 'EM'),
            new Country('Liechtenstein', 'RoW'),
            new Country('Lithuania', 'RoW'),
            new Country('Luxembourg', 'RoW'),
            new Country('Macedonia', 'RoW'),
            new Country('Madagascar', 'EM'),
            new Country('Malawi', 'EM'),
            new Country('Malaysia', 'EM'),
            new Country('Maldives', 'EM'),
            new Country('Mali', 'EM'),
            new Country('Malta', 'RoW'),
            new Country('Marshall Islands', 'EM'),
            new Country('Mauritania', 'EM'),
            new Country('Mauritius', 'EM'),
            new Country('Mexico', 'EM'),
            new Country('Micronesia', 'RoW'),
            new Country('Moldova', 'RoW'),
            new Country('Monaco', 'RoW'),
            new Country('Mongolia', 'EM'),
            new Country('Montenegro', 'RoW'),
            new Country('Morocco', 'EM'),
            new Country('Mozambique', 'EM'),
            new Country('Myanmar, {Burma}', 'RoW'),
            new Country('Namibia', 'EM'),
            new Country('Nauru', 'EM'),
            new Country('Nepal', 'EM'),
            new Country('Netherlands', 'RoW'),
            new Country('New Zealand', 'RoW'),
            new Country('Nicaragua', 'EM'),
            new Country('Niger', 'EM'),
            new Country('Nigeria', 'EM'),
            new Country('Norway', 'RoW'),
            new Country('Oman', 'EM'),
            new Country('Pakistan', 'EM'),
            new Country('Palau', 'EM'),
            new Country('Panama', 'EM'),
            new Country('Papua New Guinea', 'EM'),
            new Country('Paraguay', 'EM'),
            new Country('Peru', 'EM'),
            new Country('Philippines', 'EM'),
            new Country('Poland', 'RoW'),
            new Country('Portugal', 'RoW'),
            new Country('Qatar', 'EM'),
            new Country('Romania', 'RoW'),
            new Country('Russian Federation', 'RoW'),
            new Country('Rwanda', 'EM'),
            new Country('Saint Vincent & the Grenadines', 'RoW'),
            new Country('Samoa', 'EM'),
            new Country('San Marino', 'RoW'),
            new Country('Sao Tome & Principe', 'EM'),
            new Country('Saudi Arabia', 'EM'),
            new Country('Senegal', 'EM'),
            new Country('Serbia', 'RoW'),
            new Country('Seychelles', 'EM'),
            new Country('Sierra Leone', 'EM'),
            new Country('Singapore', 'RoW'),
            new Country('Slovakia', 'RoW'),
            new Country('Slovenia', 'RoW'),
            new Country('Solomon Islands', 'EM'),
            new Country('Somalia', 'EM'),
            new Country('South Africa', 'EM'),
            new Country('South Sudan', 'EM'),
            new Country('Spain', 'RoW'),
            new Country('Sri Lanka', 'EM'),
            new Country('St Kitts & Nevis', 'RoW'),
            new Country('St Lucia', 'EM'),
            new Country('Sudan', 'EM'),
            new Country('Suriname', 'EM'),
            new Country('Swaziland', 'EM'),
            new Country('Sweden', 'RoW'),
            new Country('Switzerland', 'RoW'),
            new Country('Syria', 'EM'),
            new Country('Taiwan', 'RoW'),
            new Country('Tajikistan', 'EM'),
            new Country('Tanzania', 'EM'),
            new Country('Thailand', 'EM'),
            new Country('Togo', 'EM'),
            new Country('Tonga', 'EM'),
            new Country('Trinidad & Tobago', 'EM'),
            new Country('Tunisia', 'EM'),
            new Country('Turkey', 'RoW'),
            new Country('Turkmenistan', 'EM'),
            new Country('Tuvalu', 'EM'),
            new Country('Uganda', 'EM'),
            new Country('Ukraine', 'EM'),
            new Country('United Arab Emirates', 'EM'),
            new Country('United Kingdom', 'RoW'),
            new Country('United States', 'RoW'),
            new Country('Uruguay', 'EM'),
            new Country('Uzbekistan', 'EM'),
            new Country('Vanuatu', 'EM'),
            new Country('Vatican City', 'RoW'),
            new Country('Venezuela', 'EM'),
            new Country('Vietnam', 'EM'),
            new Country('Yemen', 'EM'),
            new Country('Zambia', 'EM'),
            new Country('Zimbabwe', 'EM')
        ];

        foreach ($data as $record) {
            $this->db->insert($this->table, $record);
        }
    }
}

class Country
{
    public $countryname;
    public $devlevel;

    public function __construct($countryname, $devlevel)
    {
        $this->countryname = $countryname;
        $this->devlevel    = $devlevel;
    }
}
