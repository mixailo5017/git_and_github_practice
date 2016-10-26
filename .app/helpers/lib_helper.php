<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

    /**
    * _htmlentities()
    *   return htmlentities using global settings
    *
    * @access public
    * @param user object
    * @return array
    */
    if (! function_exists('_htmlentities')) {
        function _htmlentities($str)
        {
            return htmlentities($str, 2 | 0, 'UTF-8');
        }
    }

    /**
    * loc()
    * return if the geocode string has a location
    *
    * @access public
    * @param user object
    * @return array
    */
    if (! function_exists('loc')) {
        function loc($geocode)
        {
            $return = false;

            $data = json_decode($geocode);

            if (! is_object($data)) {
                return false;
            }

            $return = array(
                'location'    => false,
                'provided'    => false,
                'lat'        => false,
                'lng'        => false,
                'string'    => 'Lookup'
                );

            $span = '';
            $addy = '';

            if (isset($data->results[0])) {
                if (isset($data->results[0]->locations) && isset($data->results[0]->locations[0])) {
                    $loc = $data->results[0]->locations[0];

                    $return['location']        = true;

                    if (isset($loc->latLng)) {
                        if (isset($loc->latLng->lat)) {
                            $return['lat'] = $loc->latLng->lat;
                        }
                        if (isset($loc->latLng->lng)) {
                            $return['lng'] = $loc->latLng->lng;
                        }

                        //echo "<pre>"; var_dump( $loc, $data ); exit;

                        $partial_array = array('street','adminArea5', 'postalCode','adminArea3','adminArea1');

                        foreach ($partial_array as $partial) {
                            if (isset($loc->$partial)) {
                                $addy .= $addy == '' ? '' : ' ';
                                $addy .= $loc->$partial;
                            }
                        }

                        //echo "<pre>"; var_dump( $addy ); exit;

                        $span .= "<span title=\"{$addy}\">";
                        $span .= round($return['lat'], 0) .',' . round($return['lng'], 0);
                        $span .= '</span>';
                        $return['string'] = $span ;
                    }
                }
                if (isset($data->results[0]->providedLocation) && isset($data->results[0]->providedLocation->location)) {
                    $return['provided'] = $data->results[0]->providedLocation->location;
                }
            }

            return $return;
        }
    }


    /**
    * fullname()
    * return firstname & lastname or organiztion for a user
    *
    * @access public
    * @param user object
    * @return array
    */
    if (! function_exists('fullname')) {
        function fullname($user)
        {
            $fullname = false;

            if (isset($user['organization'])) {
                $fullname = $user['organization'];
                if ($user["membertype"] != '8') {
                    $fullname = $user['firstname'].' '.$user['lastname'];
                }
            }

            if (isset($user->organization)) {
                $fullname = $user->organization;
                if ($user->membertype != '8') {
                    $fullname = $user->firstname.' '.$user->lastname;
                }
            }

            return ucfirst($fullname);
        }
    }

    /**
    * resp()
    * create response array
    *
    * @access public
    * @param strings
    * @return array
    */
    if (! function_exists('resp')) {
        function resp($status='error', $message='', $reload='no')
        {
            $response            = array();
            $response["status"]  = $status;
            $response["msgtype"] = $status;
            $response["message"] = $message;
            $response["msg"]     = $message;
            $response["isload"]  = $reload;

            return $response;
        }
    }

    /**
    * die_json()
    * set headers to json and encode array/obj
    *
    * @access public
    * @param strings
    */
    if (! function_exists('die_json')) {
        function die_json($response)
        {
            header('Content-type: application/json');
            die(json_encode($response));
        }
    }

    /**
    * Get Value function
    * check if variable exists and return it's result
    *
    * @access public
    * @param string
    * @return string
    */
    if (! function_exists('get_value')) {
        function get_value($variable)
        {
            return isset($variable)?$variable:'';
        }
    }

    /**
     *
     * Checks if user is logged in
     * return bool
     *
     * @access public
     * @return string/array
     */
    function logged_in()
    {
        if (sess_var('logged_in')) {
            return true;
        }
        return false;
    }

    /**
    * Session Variable function
    * return session variable
    *
    * @access public
    * @param string
    * @return string/array
    */
    function sess_var($session_var)
    {
        //logged_in, name, accountid, userid, is_admin
        $CI =& get_instance();
        $CI->load->library('session');
        return $CI->session->userdata($session_var);
    }
    
    /**
    * Year value function
    * returns list of years starting from current to 1992
    *
    * @access public
    * @param string
    * @return array
    */
    function year_dropdown($first)
    {
        $year[''] = $first;
        for ($i=date("Y");$i>1932;$i--) {
            $year[$i] = $i;
        }
        return $year;
    }
    
    /**
    * Education Dropdown function
    * returns list of educations
    *
    * @access public
    * @return array
    */
    function education_dropdown()
    {
        $education = array(''=>lang('selectone'),
                    'BA'=>'BA',
                    'BS'=>'BS',
                    'BFA'=>'BFA',
                    'BBA'=>'BBA',
                    'MA'=>'MA',
                    'MS'=>'MS',
                    'MBA'=>'MBA',
                    'JD'=>'JD',
                    'PhD'=>'PhD',
                    'Other'=>lang('Other')
                    );
        return $education;
    }

    /**
     *  Member/Content types
     * Returns the types of content/members in the system
     *
     * @access public
     * @return array
     */
    function show_members_dropdown()
    {
        $types = array(
            'projects'  => lang('MapContentProjects'),
            'experts'   => lang('MapContentExperts'),
            'companies' => lang('MapContentLightning'),
        );
        return $types;
    }

    /**
     * Member/Content types
     * Returns the types of content/members in the system. Extended version for MyVIP
     *
     * @return array
     */
    function show_members_dropdown2()
    {
        return array_merge(array('myprojects' => lang('MapContentMyProjects')), show_members_dropdown());
    }

    /**
     * Project Stages Dropdown
     * returns list of Stages options for project map serch
     *
     * @param string $first
     * @return array
     */
    function stages_dropdown($first = 'all')
    {
        $stages = array(
            ''                => $first == 'all' ? lang('AllStages') : lang('SelectStage'),
            'conceptual'    => lang('Conceptual'),
            'feasibility'    => lang('Feasibility'),
            'planning'        => lang('Planning'),
            'procurement'    => lang('Procurement'),
            'construction'    => lang('Construction'),
            'om'            => lang('om')
            );

        return $stages;
    }

    /**
    * Expert budget Dropdown
    * returns list of budget options for expert map serch
    *
    * @access public
    * @return array
    */
    function budget_dropdown()
    {
        $budget = array(
            ''     => lang('AnyBudget'),
            '0'    => lang('budget-0-50'),
            '50'   => lang('budget-50-500'),
            '500'  => lang('budget-500-1000'),
            '1000' => lang('budget-1000+'),
        );
        return $budget;
    }

    /**
    * Project revenue Dropdown
    * returns list of revenue options for project map serch
    *
    * @access public
    * @return array
    */
    function revenue_dropdown()
    {
        $budget= array(
            ''        => lang('AnyRevenue'),
            '0'        => lang('budget-0-2.5'),
            '2.5'    => lang('budget-2.5-5'),
            '5'        => lang('budget-5-15'),
            '15'    => lang('budget-15-50'),
            '50'    => lang('budget-50-200'),
            '200'    => lang('budget-200+'),
        );
        return $budget;
    }

    /**
    * Discipline Dropdown function
    * returns list of Discipline rec
    *
    * @access public
    * @return array
    */
    function discipline_dropdown()
    {
        $disciplines = array(
            ''                          => lang('SelectADiscipline') ?: 'Select a discipline',
            'Construction'              => lang('Construction') ?: 'Construction',
            'Consulting'              => lang('Consulting') ?: 'Consulting',
            'Design & Architecture'   => lang('Design_Architecture') ?: 'Design & Architecture',
            'Engineering'              => lang('Engineering') ?: 'Engineering',
            'Finance—Commercial Banks' => lang('FinanceCommercialBanks') ?: 'Finance—Commercial Banks',
            'Finance—Debt Funds'      => lang('FinanceDebtFunds') ?: 'Finance—Debt Funds',
            'Finance—Equity Funds'      => lang('FinanceEquityFunds') ?: 'Finance—Equity Funds',
            'Finance—Pension Funds'      => lang('FinancePensionFunds') ?: 'Finance—Pension Funds',
            'Finance—Insurance Funds' => lang('FinanceInsuranceFunds') ?: 'Finance—Insurance Funds',
            'Finance—Sovereign Wealth Funds' => lang('FinanceSovereignWealthFunds') ?: 'Finance—Sovereign Wealth Funds',
            'Finance—Export Credit Agencies' => lang('FinanceExportCreditAgencies') ?: 'Finance—Export Credit Agencies',
            'Finance—Other'           => lang('FinanceOther') ?: 'Finance—Other',
            'Government'              => lang('Government') ?: 'Government',
            'Insurance'                  => lang('Insurance') ?: 'Insurance',
            'Investment'              => lang('Investment') ?: 'Investment',
            'Legal'                      => lang('Legal') ?: 'Legal',
            'Machinery'                  => lang('Machinery') ?: 'Machinery',
            'Multilateral'              => lang('Multilateral') ?: 'Multilateral',
            'Operation & Maintenance' => lang('Operation_Maintenance') ?: 'Operation & Maintenance',
            'Project Development'      => lang('ProjectDevelopment') ?: 'Project Development',
            'Project Management'      => lang('ProjectManagement') ?: 'Project Management',
            'Technology'              => lang('Technology') ?: 'Technology',
            'Other'                      => lang('Other') ?: 'Other',
        );
        return $disciplines;
    }

    
    /**
    * Country Dropdown function
    * returns list of countries
    *
    * @access public
    * @param string
    * @param array
    * @return array
    */
    function country_dropdown($name="country", $top_countries=array())
    {
        $countries = array(
            ''=>lang('SelectCountry') ? : 'Select a country',
            'United States'=>'United States',
            'Afghanistan'=>'Afghanistan',
            'Albania'=>'Albania',
            'Algeria'=>'Algeria',
            'Andorra'=>'Andorra',
            'Angola'=>'Angola',
            'Antigua & Deps'=>'Antigua &amp; Deps',
            'Argentina'=>'Argentina',
            'Armenia'=>'Armenia',
            'Australia'=>'Australia',
            'Austria'=>'Austria',
            'Azerbaijan'=>'Azerbaijan',
            'Bahamas'=>'Bahamas',
            'Bahrain'=>'Bahrain',
            'Bangladesh'=>'Bangladesh',
            'Barbados'=>'Barbados',
            'Belarus'=>'Belarus',
            'Belgium'=>'Belgium',
            'Belize'=>'Belize',
            'Benin'=>'Benin',
            'Bhutan'=>'Bhutan',
            'Bolivia'=>'Bolivia',
            'Bosnia Herzegovina'=>'Bosnia Herzegovina',
            'Botswana'=>'Botswana',
            'Brazil'=>'Brazil',
            'Brunei'=>'Brunei',
            'Bulgaria'=>'Bulgaria',
            'Burkina'=>'Burkina',
            'Burundi'=>'Burundi',
            'Cambodia'=>'Cambodia',
            'Cameroon'=>'Cameroon',
            'Canada'=>'Canada',
            'Cape Verde'=>'Cape Verde',
            'Central African Rep'=>'Central African Rep',
            'Chad'=>'Chad',
            'Chile'=>'Chile',
            'China'=>'China',
            'Colombia'=>'Colombia',
            'Comoros'=>'Comoros',
            'Congo'=>'Congo',
            'Congo {Democratic Rep}'=>'Congo {Democratic Rep}',
            'Costa Rica'=>'Costa Rica',
            'Croatia'=>'Croatia',
            'Cuba'=>'Cuba',
            'Cyprus'=>'Cyprus',
            'Czech Republic'=>'Czech Republic',
            'Denmark'=>'Denmark',
            'Djibouti'=>'Djibouti',
            'Dominica'=>'Dominica',
            'Dominican Republic'=>'Dominican Republic',
            'East Timor'=>'East Timor',
            'Ecuador'=>'Ecuador',
            'Egypt'=>'Egypt',
            'El Salvador'=>'El Salvador',
            'Equatorial Guinea'=>'Equatorial Guinea',
            'Eritrea'=>'Eritrea',
            'Estonia'=>'Estonia',
            'Ethiopia'=>'Ethiopia',
            'Fiji'=>'Fiji',
            'Finland'=>'Finland',
            'France'=>'France',
            'Gabon'=>'Gabon',
            'Gambia'=>'Gambia',
            'Georgia'=>'Georgia',
            'Germany'=>'Germany',
            'Ghana'=>'Ghana',
            'Greece'=>'Greece',
            'Grenada'=>'Grenada',
            'Guatemala'=>'Guatemala',
            'Guinea'=>'Guinea',
            'Guinea-Bissau'=>'Guinea-Bissau',
            'Guyana'=>'Guyana',
            'Haiti'=>'Haiti',
            'Honduras'=>'Honduras',
            'Hungary'=>'Hungary',
            'Iceland'=>'Iceland',
            'India'=>'India',
            'Indonesia'=>'Indonesia',
            'Iran'=>'Iran',
            'Iraq'=>'Iraq',
            'Ireland {Republic}'=>'Ireland {Republic}',
            'Israel'=>'Israel',
            'Italy'=>'Italy',
            'Ivory Coast'=>'Ivory Coast',
            'Jamaica'=>'Jamaica',
            'Japan'=>'Japan',
            'Jordan'=>'Jordan',
            'Kazakhstan'=>'Kazakhstan',
            'Kenya'=>'Kenya',
            'Kiribati'=>'Kiribati',
            'Korea North'=>'Korea North',
            'Korea South'=>'Korea South',
            'Kosovo'=>'Kosovo',
            'Kuwait'=>'Kuwait',
            'Kyrgyzstan'=>'Kyrgyzstan',
            'Laos'=>'Laos',
            'Latvia'=>'Latvia',
            'Lebanon'=>'Lebanon',
            'Lesotho'=>'Lesotho',
            'Liberia'=>'Liberia',
            'Libya'=>'Libya',
            'Liechtenstein'=>'Liechtenstein',
            'Lithuania'=>'Lithuania',
            'Luxembourg'=>'Luxembourg',
            'Macedonia'=>'Macedonia',
            'Madagascar'=>'Madagascar',
            'Malawi'=>'Malawi',
            'Malaysia'=>'Malaysia',
            'Maldives'=>'Maldives',
            'Mali'=>'Mali',
            'Malta'=>'Malta',
            'Marshall Islands'=>'Marshall Islands',
            'Mauritania'=>'Mauritania',
            'Mauritius'=>'Mauritius',
            'Mexico'=>'Mexico',
            'Micronesia'=>'Micronesia',
            'Moldova'=>'Moldova',
            'Monaco'=>'Monaco',
            'Mongolia'=>'Mongolia',
            'Montenegro'=>'Montenegro',
            'Morocco'=>'Morocco',
            'Mozambique'=>'Mozambique',
            'Myanmar, {Burma}'=>'Myanmar, {Burma}',
            'Namibia'=>'Namibia',
            'Nauru'=>'Nauru',
            'Nepal'=>'Nepal',
            'Netherlands'=>'Netherlands',
            'New Zealand'=>'New Zealand',
            'Nicaragua'=>'Nicaragua',
            'Niger'=>'Niger',
            'Nigeria'=>'Nigeria',
            'Norway'=>'Norway',
            'Oman'=>'Oman',
            'Pakistan'=>'Pakistan',
            'Palau'=>'Palau',
            'Panama'=>'Panama',
            'Papua New Guinea'=>'Papua New Guinea',
            'Paraguay'=>'Paraguay',
            'Peru'=>'Peru',
            'Philippines'=>'Philippines',
            'Poland'=>'Poland',
            'Portugal'=>'Portugal',
            'Qatar'=>'Qatar',
            'Romania'=>'Romania',
            'Russian Federation'=>'Russian Federation',
            'Rwanda'=>'Rwanda',
            'St Kitts & Nevis'=>'St Kitts &amp; Nevis',
            'St Lucia'=>'St Lucia',
            'Saint Vincent & the Grenadines'=>'Saint Vincent &amp; the Grenadines',
            'Samoa'=>'Samoa',
            'San Marino'=>'San Marino',
            'Sao Tome & Principe'=>'Sao Tome &amp; Principe',
            'Saudi Arabia'=>'Saudi Arabia',
            'Senegal'=>'Senegal',
            'Serbia'=>'Serbia',
            'Seychelles'=>'Seychelles',
            'Sierra Leone'=>'Sierra Leone',
            'Singapore'=>'Singapore',
            'Slovakia'=>'Slovakia',
            'Slovenia'=>'Slovenia',
            'Solomon Islands'=>'Solomon Islands',
            'Somalia'=>'Somalia',
            'South Africa'=>'South Africa',
            'South Sudan'=>'South Sudan',
            'Spain'=>'Spain',
            'Sri Lanka'=>'Sri Lanka',
            'Sudan'=>'Sudan',
            'Suriname'=>'Suriname',
            'Swaziland'=>'Swaziland',
            'Sweden'=>'Sweden',
            'Switzerland'=>'Switzerland',
            'Syria'=>'Syria',
            'Taiwan'=>'Taiwan',
            'Tajikistan'=>'Tajikistan',
            'Tanzania'=>'Tanzania',
            'Thailand'=>'Thailand',
            'Togo'=>'Togo',
            'Tonga'=>'Tonga',
            'Trinidad & Tobago'=>'Trinidad &amp; Tobago',
            'Tunisia'=>'Tunisia',
            'Turkey'=>'Turkey',
            'Turkmenistan'=>'Turkmenistan',
            'Tuvalu'=>'Tuvalu',
            'Uganda'=>'Uganda',
            'Ukraine'=>'Ukraine',
            'United Arab Emirates'=>'United Arab Emirates',
            'United Kingdom'=>'United Kingdom',
            'United States'=>'United States',
            'Uruguay'=>'Uruguay',
            'Uzbekistan'=>'Uzbekistan',
            'Vanuatu'=>'Vanuatu',
            'Vatican City'=>'Vatican City',
            'Venezuela'=>'Venezuela',
            'Vietnam'=>'Vietnam',
            'Yemen'=>'Yemen',
            'Zambia'=>'Zambia',
            'Zimbabwe'=>'Zimbabwe'
        );

        return $countries;
    }


    /**
     * Returns options html for dropdown filters on maps
     * @return array
     */
    function map_sector_options()
    {
        $rename = array(
            'Information & Communication Technologies' => 'IT & Communications'
        );

        $html = '';

        foreach (sectors() as $id => $name) {
            $html .= '<option class="map-' . url_title($name, '-', true) . '-circle" value="' . $name . '">';
            if (isset($rename[$name])) {
                $html .= $rename[$name];
            } else {
                $html .= $name;
            }
            $html .= '</option>';
        }

        return $html;
    }

    function subsector_dropdown($sector = null)
    {
        $result = array('' => lang('SelectASub-Sector'));
        if (empty($sector)) {
            return $result;
        }

        foreach (subsectors2($sector) as $id => $value) {
            $result[$value] = $value;
        }

        return $result;
    }

    function sector_dropdown()
    {
        $result = array('' => lang('SelectASector'));

        foreach (sectors() as $id => $value) {
            $result[$value] = $value;
        }

        return $result;
    }

    function subsectors2($sector)
    {
        $CI =& get_instance();

        $rows = $CI->db
            ->select('s.sectorid, s.sectorvalue')
            ->from('exp_sectors AS s')
            ->join('exp_sectors AS p', 's.parentid = p.sectorid')
            ->where('p.sectorvalue', $sector)
            ->order_by('s.sectorvalue', 'asc')
            ->get()->result_array();

        $subsectors = array();
        foreach ($rows as $row) {
            $subsectors[$row['sectorid']] = $row['sectorvalue'];
        }

        return $subsectors;
    }
    /**
    * Sectors function
    * returns list of sectors from database
    *
    * @access public
    * @return array
    */
    function sectors()
    {
        $CI =& get_instance();
        
        $rows = $CI->db
            ->select('sectorid, sectorvalue')
            ->from('exp_sectors')
            ->where('parentid', 0)
            ->order_by('sectorvalue', 'asc')
            ->get()->result_array();

        $sectors = array();

        foreach ($rows as $row) {
            $sectors[$row['sectorid']] = $row['sectorvalue'];
        }

        return $sectors;
    }

    function sector_subsectors()
    {
        $CI =& get_instance();

        $sql = "
        SELECT p.sectorvalue, STRING_AGG(NULLIF(s.sectorvalue, ''), ',' ORDER BY s.sectorvalue) subsectors
          FROM exp_sectors p LEFT JOIN exp_sectors s
            ON p.sectorid = s.parentid
         WHERE p.parentid = 0
         GROUP BY p.sectorvalue
         ORDER BY p.sectorvalue";

        $rows = $CI->db->query($sql)->result_array();

        $first = lang('SelectASub-Sector');
//        $other = lang('Other');

        $subsectors = array('first' => $first, '' => array());
        foreach ($rows as $sector) {
            $subsectors[$sector['sectorvalue']] = is_null($sector['subsectors']) ? array() : explode(',', $sector['subsectors']);
        }
        return $subsectors;
    }

    /**
    * Sub-Sectors function
    * returns list of subsectors from database
    *
    * @access public
    * @return array
    */
    function subsectors()
    {
        $CI =& get_instance();

        $rows = $CI->db
            ->where('parentid !=', 0)
            ->order_by('parentid')
            ->order_by('sectorvalue')
            ->get('exp_sectors')
            ->result_array();

        $subsectors = array();
        foreach ($rows as $row) {
            $subsectors[$row['parentid']][] = $row['sectorvalue'];
        }

        return $subsectors;
    }
    
    
    function getsectorid($sectorname, $subsector=0)
    {
        $result_subsectors = array();
        $CI =& get_instance();
        $CI->db->where("sectorvalue =".$sectorname);
        $query_subsector = $CI->db->get('exp_sectors');
        foreach ($query_subsector->result_array() as $row) {
            if ($subsector == 1) {
                $result_subsectors    =    $row['parentid'];
            } else {
                $result_subsectors    =    $row['sectorid'];
            }
        }
        return $result_subsectors;
    }

if (! function_exists('is_post_msize_exceeded')) {
    /**
     * @return bool|string
     */
    function is_post_msize_exceeded()
    {
        // Check if the post_max_size vaue is exceded
        if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
            empty($_POST) &&
            empty($_POST) &&
            $_SERVER['CONTENT_LENGTH'] > 0) {
            return 'The maximum allowed file size is exceded.';
        }
        return false;
    }
}
    /**
     * Upload file helper
     *
     * @param string $path
     * @param string $filename
     * @param string $allowedtypes
     * @param bool|int $required
     * @return array
     */
    function upload_file($path, $filename, $allowedtypes = '', $required = true)
    {
        if ($allowedtypes == '') {
            $allowed = 'pdf|doc|ppt|xls|jpg|png|jpeg|gif|zip|mp3|docx|txt|rar';
        } else {
            $allowed = implode('|', allowedtypes);
        }

        $config    = array(
            'upload_path' => '.' . $path,
            'allowed_types'    => $allowed,
            'max_size' => '100000',
            'encrypt_name' => true
        );

        $CI =& get_instance();
        $CI->load->library('upload', $config);

        if (! $CI->upload->do_upload($filename)) {
            $error = $CI->upload->display_errors();
            return compact('error');
        } else {
            $data = $CI->upload->data();
            $data['error'] = '';
            return $data;
        }
    }


    /**
     * Upload image helper
     *
     * @param string $path
     * @param string $filename
     * @param bool $isthumb
     * @param string|array $thumbarraythub
     * @param string|array $label
     * @return array
     */
    function upload_image($path, $filename, $isthumb = false, $thumbarraythub = '', $label = '')
    {
        $config = array(
            'upload_path' => '.' . $path,
            'allowed_types'    => 'gif|jpg|png|jpeg',
            'max_size' => '5120',
            // 'max_width'  => '1024',
            // 'max_height' => '768',
            'encrypt_name' => true
        );



        $CI =& get_instance();
        $CI->load->library('VIP_Upload', $config, 'upload');

        if (! $CI->upload->do_upload($filename)) {
            if (is_array($label)) {
                $error = $CI->upload->display_errors($label['open'], $label['close']);
            } else {
                $error = $CI->upload->display_errors();
            }
            return compact('error');
        } else {
            $image_data = $CI->upload->data();

            // Check for image overall dimensions
            if (isset($image_data['image_width']) &&
                isset($image_data['image_height'])) {
                if ($image_data['image_width'] * $image_data['image_height'] > MAX_IMAGE_DIMENSIONS) {
                    $error = lang('ImageDimensionsTooBig');
                    // Fix for admin interface, which doesn't have access to language files
                    if (! $error) {
                        $error = 'Image dimensions are too big.';
                    }

                    if (is_array($label)) {
                        $error = $label['open'] . $error . $label['close'];
                    }
                    return compact('error');
                }
            }

            if ($isthumb) {
                if (count($thumbarraythub)> 0) {
                    $CI->load->library('image_lib');

                    foreach ($thumbarraythub as $key=>$val) {
                        $config2 = array(
                            'source_image' => $image_data['full_path'],
                            'new_image' => '.' . $path . $val['width'] . '_' . $val['height'] . '_' . $image_data['file_name'],
                            'maintain_ratio' => $val['height'] == '284', // Why is it of type string?
                            'width' => $val['width'],
                            'height' => $val['height']
                        );

                        $CI->image_lib->initialize($config2);
                        $CI->image_lib->resize(); //do whatever specified in config
                    }
                }
            }
            $image_data['error'] = '';
            return $image_data;
        }
    }
    

    /**
    * Date Format Function
    * convert passed Date/DateTime to requested Format
    * If input is non-blank, function infers input format from requested output format
    * If input is blank, function returns '1111-11-11'
    * 
    * @access public
    * @param  string  $date           Input date
    * @param  string  $format         Output format
    * @param  boolean $istime         Whether to include time at end of output (requires time to be included in input)
    * @return string                  Output
    */
    function DateFormat($date, $format, $istime=false)
    {
        $formatteddate = null;
        $time = '';
        if ($date != "") {
            if ($format == DATEFORMAT) { // Expects input format YYYY-MM-DD
                $year    = substr($date, 0, 4);
                $month    = substr($date, 5, 2);
                $day    = substr($date, 8, 2);
            } elseif ($format == DATEFORMATDB) { // Expects input format MM/DD/YYYY
                $year    = substr($date, 6, 4);
                $month    = substr($date, 0, 2);
                $day    = substr($date, 3, 2);
            } elseif ($format == DATEFORMATVIEW) { // Expects input format YYYY-MM-DD
                $year    = substr($date, 0, 4);
                $month    = substr($date, 5, 2);
                $day    = substr($date, 8, 2);
            } else {
                return "";
            }
            
            if ($istime) {
                $time    = substr($date, 10);
            }
            
            $formatteddate = str_replace("%d", $day, str_replace("%m", $month, str_replace("%y", $year, $format))).$time;
        } else {
            $formatteddate = '1111-11-11';
        }
        return $formatteddate;
    }
    
    
    /**
    * File Type Function
    * retrive type of file and return with  file type image name
    *
    * @access public
    * @param string
    * @return string
    */
    function filetypeIcon($filename)
    {
        $filetype = explode('.', $filename);
        switch ($filetype[1]) {
            case 'pdf':
            return 'pdf.png';
            
            case 'doc':
            return 'doc.png';
            case 'xls':
            return 'excel.png';
            
            case 'ppt':
            return 'powerpoint.png';
            
            case 'mp3':
            return 'mp3.png';
            
            case 'jpg':
            return 'jpg.png';
            
            case 'jpeg':
            return 'jpg.png';

            
            case 'png':
            return 'png.png';
            
            case 'gif':
            return 'gif.png';
            
            case 'avi':
            return 'avi.png';
            
            case 'csv':
            return 'scv.png';
            
            case 'zip':
            return 'zip.png';

            default:
            return 'file.png';
        }
    }
    

    function get_all_parent_tabs($position='edit_project')
    {
        $tabs = array();
        $CI =& get_instance();

        if ($position=='edit_project') {
            $qryproj =$CI->db->get_where("exp_proj_navigation", array("isdeleted"=>"0", "position_edit"=>"1"));
        } else {
            $qryproj = $CI->db->get_where("exp_proj_navigation", array("isdeleted"=>"0", "position_view"=>"1"));
        }
                
        foreach ($qryproj->result_array() as $row) {
            $tabs[] = $row['navigationname'];
        }
        return $tabs;
    }

    function SendHTMLMail($from = null, $to, $subject, $message, $reply_to = null, $mailtype = 'plain')
    {
        $from = empty($from) ? array(ADMIN_EMAIL, ADMIN_EMAIL_NAME) : $from;

        $headers = $mailtype == 'plain' ? array('Content-Type' => 'text/plain; charset=utf-8') : array();

        return email($to, $subject, $message, $from, $reply_to, $headers);
    }

    // TODO: Eventually get rid of it
    function randomPassword()
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, strlen($alphabet)-1);
            $pass[$i] = $alphabet[$n];
        }
        return implode($pass);
    }

    // TODO: Eventually get rid of it
    function new_encrypt_string($str)
    {
        $key = 'ff02f97e948840393deb7a07efadc6c4';

        $data = mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $str, 'ecb');
        $data = bin2hex($data);

        return $data;
    }

    // TODO: Eventually get rid of it
    function new_decrypt_string($str)
    {
        $key = 'ff02f97e948840393deb7a07efadc6c4';

        $data = pack('H*', $str); // Translate back to binary
        $data = rtrim(mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $data, 'ecb'), "\0");

        return $data;
    }

    // TODO: Eventually get rid of it
    function encryptstring($string)
    {
        return new_encrypt_string($string);

        $key = "6r9qEJg6";
        $result = "";
        for ($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)+ord($keychar));
            $result.=$char;
        }
        return base64_encode($result);
    }

    // TODO: Eventually get rid of it
    function decryptstring($string)
    {
        return new_decrypt_string($string);

        $key = "6r9qEJg6";
        $result = "";
        $string = base64_decode($string);
        for ($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        return $result;
    }

    // TODO: Eventually get rid of it
    // It doen't belong here. Use members_model instead
    function get_logged_userinfo($uid)
    {
        $userarr = array();
        $CI =& get_instance();
        
        $qryuser = $CI->db->get_where("exp_members", array("uid"=>$uid));
        $userarr = $qryuser->row_array();
        
        return $userarr;
    }

    // TODO: Eventually get rid of it
    function encrypt_password($password)
    {
        $algo = "sha512";
        $bytesize = 128;
        $salt = "";
        
        for ($i = 0; $i < $bytesize; $i++) {
            $salt .= chr(mt_rand(33, 126));
        }
        
        return array(
            'salt'        => $salt,
            'password'    => hash($algo, $salt.$password)
        );
    }
    
    function generatethumb($path, $imagefullpath, $imagename, $thumbarraythub)
    {
        $CI =& get_instance();
        $CI->load->library('image_lib');
        
        foreach ($thumbarraythub as $key=>$val) {
            $config2 = array();
            $config2['source_image']    = $imagefullpath;
            $config2['new_image']        =  '.'.$path.$val['width'].'_'.$val['height'].'_'.$imagename;
            if ($val['height'] == "284") {
                $config2['maintain_ratio']  = true;
            } else {
                $config2['maintain_ratio']  = false;
            }
            
            $config2['width']            = $val['width'];
            $config2['height']            = $val['height'];
             //load library
            // print_r($config2);
             $CI->image_lib->initialize($config2);

            $CI->image_lib->resize(); //do whatever specified in config
        }
    }

    // TODO: Eventually get rid of it
    // It doen't belong here. Use members_model instead
    function check_is_topexpert($uid)
    {
        $CI =& get_instance();
        
        $CI->db->select("es.sector,es.subsector,annualrevenue,totalemployee");
        $CI->db->where("es.uid", $uid);
        $CI->db->join('exp_members as m', 'm.uid = es.uid', 'left');
        $qryusersector = $CI->db->get("exp_expertise_sector as es");
        if ($qryusersector->num_rows() > 0) {
            $sector = "";
            $subsector = "";
            $annualrevenue = 0;
            foreach ($qryusersector->result_array() as $row) {
                if ($row["sector"] != "") {
                    if ($sector == "") {
                        $sector = "'".$row["sector"]."'";
                    } else {
                        $sector .= ",'".$row["sector"]."'";
                    }
                }
                if ($row["subsector"] != "") {
                    if ($subsector == "") {
                        $subsector = "'".$row["subsector"]."'";
                    } else {
                        $subsector .= ",'".$row["subsector"]."'";
                    }
                }
                $annualrevenue = $row["annualrevenue"];
                $totalemployee = $row['totalemployee'];
            }
            
            $CI->db->select("pid");
            if ($sector != "") {
                $where = "sector IN (".$sector.")";
            }
            if ($subsector != "") {
                $where .= " OR subsector IN (".$subsector.")";
            }
            if ($where != "") {
                $CI->db->where($where);
            }
            $qryproj = $CI->db->get("exp_projects");
            if ($qryproj->num_rows() > 0 && $annualrevenue >= 15 || $totalemployee != '1-50') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // TODO: Eventually get rid of it
    // It doen't belong here. Use members_model instead
    function get_all_expertAdverts()
    {
        $CI =& get_instance();
        
        $result_expAdv                    = array();
        $result_expAdv['data']            = array();
        $result_expAdv['organization']    = array();
        
        $CI->db->where("membertype ='8'");
        $CI->db->where("status ='1'");
        $query_expAdv = $CI->db->get('exp_members');
        $result_expAdv['organization'][''] = lang('SelectAnOrganization');
        
        if ($query_expAdv->num_rows() > 0) {
            foreach ($query_expAdv->result_array() as $row) {
                $result_expAdv['organization'][$row['uid']]    =    ucfirst($row['organization']);
                $result_expAdv['data']            =    $row;
            }
        }
        return $result_expAdv;
    }


    // TODO: Eventually get rid of it
    // It doen't belong here. Use members_model instead
    function is_organization_member($memberid)
    {
        $CI =& get_instance();
        
        $result_expAdv                    = array();
        $CI->db->where(array("uid"=>$memberid, 'status'=>'1'));
        $query_isExpAdv = $CI->db->get("exp_invite_experts");
        if ($query_isExpAdv->num_rows() > 0) {
            $result = $query_isExpAdv->row();
            return $result->orgid;
        } else {
            return false;
        }
    }

    // TODO: Eventually get rid of it
    // It doen't belong here. Use members_model instead
    function get_organization($orgid)
    {
        $CI =& get_instance();
        
        $result_org    = array();
        $CI->db->where(array("uid"=>$orgid, 'status'=>'1'));
        $query_getOrg = $CI->db->get("exp_members");
        if ($query_getOrg->num_rows() > 0) {
            $result = $query_getOrg->row();
            return $result->organization;
        } else {
            return false;
        }
    }
        
    function get_language_file($language)
    {
        if (empty($language)) {
            $language = 'english';
        }

        $CI    =& get_instance();
        $CI->session->set_userdata(array('lang' => $language));

        // you might want to just autoload these two helpers
        $CI->load->helper('language');
        $CI->load->helper('url');

        // load language file
        $CI->lang->load($language, $language);
        $CI->lang->load('upload', $language);
        $CI->lang->load('form_validation', $language);
        $CI->lang->load('js_form_validation', $language);
        $CI->lang->load('maps', $language);
    }

    function get_js_language_file($languagechange2 = '')
    {
        $CI            =& get_instance();
        
        $currentlang2    = $CI->session->userdata('lang');
        $result2 = array();
        
        if (!$currentlang2) {
            $result2['lang']            = $languagechange2;
            $CI->session->set_userdata($result2);
            $currentlang2    = 'english';
        } else {
            $result2['lang']            = $languagechange2;
            $CI->session->set_userdata($result2);
            $currentlang2    = $languagechange2;
        }

        // you might want to just autoload these two helpers
        $CI->load->helper('language');
        $CI->load->helper('url');
        
        // load language file
        $CI->lang->load('js_form_validation', $currentlang);
    }

    function langGet()
    {
        $CI =& get_instance();
        //$line = $CI->lang->line();
        $line = $CI->lang->language;
        return $line;
    }

    function project_stage_class($stage)
    {
        switch ($stage) {
            case 'om':
                $num = 6; break;
            case 'construction':
                $num = 5; break;
            case 'procurement':
                $num = 4; break;
            case 'planning':
                $num = 3; break;
            case 'feasibility':
                $num = 2; break;
            case 'conceptual':
            default:
                $num = 1;
        }
        return $num;
    }

    function project_sector_class($sector)
    {
        $sector = url_title($sector, '_', true);
        return $sector;
    }

if (! function_exists('sendResponse')) {
    /**
     * Accepts an array of parameters and echo it out as a JSON response
     *
     * @param array $response
     * @return void
     */
    function sendResponse($response)
    {
        if (! is_null($response) && is_array($response)) {
            header('Content-type: application/json');
            echo json_encode($response);
        }
    }
}

if (! function_exists('split_terms2')) {
    /**
     * Splits a string by whitespace (\t,\n etc.) or comma respecting the double quotes
     * changes the case of values to lower and returns a multidimentional array of distict terms in each group
     *
     * @param $input
     * @return array
     */
    function split_terms2($input)
    {
        // Convert commas to space and normalize all whitespace
        $normalized = mb_strtolower(preg_replace('/[\s,]+/u', ' ', $input));
        // Split terms by space respecting double qoutes
        $quoted = array_unique(array_filter(str_getcsv($normalized, ' ')));
        //
        $result = array_map(function ($value) {
            $terms = array_unique(preg_split('/[\s]+/u', $value, -1, PREG_SPLIT_NO_EMPTY));

            if (count($terms) == 1) {
                return $terms[0];
            }

            return $terms;
        }, $quoted);

        return $result;
    }
}

if (! function_exists('split_terms')) {
    /**
     * Splits a string by whitespace (\t, \n) or comma
     * changes the case of values to lower
     * and returns an array of DISTINCT values
     *
     * @param $input
     * @return array
     */
    function split_terms($input)
    {
        // If input value is a closure execute it first by using value() helper function
        return array_unique(array_map('mb_strtolower', preg_split('/[\s,]+/u', $input, -1, PREG_SPLIT_NO_EMPTY)));
    }
}

if (! function_exists('where_like2')) {
    /**
     * Builds WHERE clause in the form LOWER(column1 || ' ' || column2) LIKE '
     *   (LOWER(column1 || ' ' || column2) LIKE '%term1%' OR LOWER(column1 || ' ' || column2) LIKE '%term2%')
     *
     * @param string|array $columns (
     * @param array $terms
     * @return string
     */
    function where_like2($columns, $terms)
    {
        $CI =& get_instance();

        $left = 'LOWER(COALESCE(' . (is_array($columns) ? implode(",'') || ' ' || COALESCE(", $columns) : $columns) . ",'')) LIKE ";

        $subterms = function ($column, $values) use (&$CI) {
            $escaped = array();
            foreach ($values as $value) {
                $escaped[] = $column . "'%" . $CI->db->escape_like_str($value) . "%'";
            }
            return '(' . implode(' AND ', $escaped) . ')';
        };

        $where = array();
        foreach ($terms as $term) {
            if (is_array($term)) {
                $where[] = $subterms($left, $term);
            } else {
                $where[] = $left . "'%" . $CI->db->escape_like_str($term) . "%'";
            }
        }
        return '(' . implode(' OR ', $where) . ')';
    }
}


if (! function_exists('where_like')) {
    /**
     * Builds WHERE clause in the form LOWER(column1 || ' ' || column2) LIKE '
     *   (LOWER(column1 || ' ' || column2) LIKE '%term1%' OR LOWER(column1 || ' ' || column2) LIKE '%term2%')
     *
     * @param string|array $columns (
     * @param array $terms
     * @return string
     */
    function where_like($columns, $terms)
    {
        $CI =& get_instance();

        $left = 'LOWER(COALESCE(' . (is_array($columns) ? implode(",'') || ' ' || COALESCE(", $columns) : $columns) . ",'')) LIKE ";
        $where = array();
        foreach ($terms as $term) {
            $where[] = $left . "'%" . $CI->db->escape_like_str($term) . "%'";
        }
        return '(' . implode(' OR ', $where) . ')';
    }
}

if (! function_exists('decode_iframe')) {
    /**
     * Decodes iframe tags back
     *
     * @param string|Callback $input
     * @return string
     */
    function decode_iframe($input)
    {
        $search  = array('&lt;iframe', '&gt;&lt;/iframe');
        $replace = array('<iframe', '></iframe');
        // If input value is a closure execute it first by using value() helper function
        return str_replace($search, $replace, value($input));
    }
}

if (! function_exists('flatten_assoc')) {
    function flatten_assoc(array $array, $key, $value, $key_prefix = null, $key_suffix = null)
    {
        $result = array();
        foreach ($array as $item) {
            if (is_null($key)) {
                $result[] = $item[$value];
            } else {
                $new_key = (is_null($key_prefix)) ? $item[$key] : $key_prefix . $item[$key];
                $new_key = (is_null($key_suffix)) ? $new_key : $new_key . $key_suffix;
                $result[$new_key] = $item[$value];
            }
        }
        return $result;
    }
}

if (! function_exists('format_date')) {
    function format_date($input, $out_fmt = null, $in_fmt = null)
    {
        if (is_null($out_fmt)) {
            $out_fmt = 'Y-m-d';
        }

        try {
            if (is_null($in_fmt)) {
                $dt = new DateTime($input);
            } else {
                $dt = DateTime::createFromFormat($in_fmt, $input);
            }
            return $dt->format($out_fmt);
        } catch (Exception $e) {
            return false;
        }
    }
}

if (! function_exists('duration')) {
    /**
     * @param $start
     * @param $end
     * @return bool|string
     */
    function duration($start, $end)
    {
        try {
            $dt_start = new DateTime($start);
            $dt_end   = new DateTime($end);

            if ($dt_start > $dt_end) {
                return false;
            }

            switch (true) {
                case $dt_start->format('Ym') == $dt_end->format('Ym'):
                    $result = $dt_start->format('M ') . $dt_start->format('d') . ' - ' . $dt_end->format('d') . $dt_start->format(', Y');
                    break;
                case $dt_start->format('Y') == $dt_end->format('Y'):
                    $result = $dt_start->format('M d') . ' - ' . $dt_end->format('M d') . $dt_start->format(', Y');
                    break;
                default:
                    $result = $dt_start->format('M d, Y') . ' - ' . $dt_end->format('M d, Y');
            }
        } catch (Exception $e) {
            return false;
        }

        return $result;
    }
}

if (! function_exists('forum_dates')) {
    /**
     * @param $start
     * @param $end
     * @return bool|string
     */
    function forum_dates($start, $end)
    {
        $tbd = 'TBD';
        if (is_null($start) || is_null($end)) {
            return $tbd;
        }

        if (! $result = duration($start, $end)) {
            return $tbd;
        }

        return $result;
    }
}

if (! function_exists('time_ago')) {
    /**
     * Given two timestamps (epoch time) returns human representation of elapsed time
     * E.g.: just now; 1m; 15d; Jul 13; Dec 31, 13
     *
     * @param int $timestamp1
     * @param int $timestamp2
     * @param string $suffix E.g. ago, from now
     * @return string
     */
    function time_ago($timestamp1, $timestamp2, $suffix = '')
    {
        $map = array(
            array(60 * 60, 60, 'm'),
            array(60 * 60 * 24, 60 * 60, 'h'),
            array(60 * 60 * 24 * 30, 60 * 60 * 24, 'd'),
        );

        $diff = $timestamp1 - $timestamp2;

        if ($diff < 60) {
            return 'just now';
        }

        foreach ($map as $item) {
            if ($diff < ($item[0])) {
                $result = floor($diff / $item[1]);
                return trim("$result{$item[2]} $suffix");
            }
        }

        if ($diff >= 60 * 60 * 24 * 30) {
            if (date('Y', $timestamp1) == date('Y', $timestamp2)) {
                return date('M j', $timestamp2);
            } else {
                return date('M j, y', $timestamp2);
            }
        }
    }
}

if (! function_exists('format_budget')) {
    /**
     * Formats the budget value
     * If value is null, empty or equals to 0 then returns TBD
     * @param mixed $value
     * @return string
     */
    function format_budget($value)
    {
        if (empty($value) || $value == 0) {
            return 'TBD';
        } else {
            return '$' .$value . 'MM';
        }
    }
}

if (! function_exists('asset_version')) {
    /**
     * Return the query string for the asset or an empty string
     * E.g. ?v=1.0.1
     * @param string $asset
     * @return string
     */
    function asset_version($asset)
    {
        if (empty($asset)) {
            return '';
        }

        $CI =& get_instance();
        $assets = $CI->config->item('assets');

        if (! isset($assets[$asset])) {
            return '';
        }

        return '?v=' . $assets[$asset];
    }
}

if (! function_exists('auth_check')) {
    /**
     * Checks if the current user is logged in
     * If not redirects the user to the login page
     * preserving the intended url including the query string
     * If the user is not logged in for AJAX calls returns the '403 Forbidden' error
     *
     * @return void
     */
    function auth_check()
    {
        $CI =& get_instance();

//      if (! sess_var('logged_in')) {
        if (! $CI->auth->check()) {
            // If it is an AJAX call return 403 error
            if ($CI->input->is_ajax_request()) {
                show_error('Forbidden.', 403);
            }

            // Save intented url and redirect to the home (login) page

            // Get the URI
            $intended = uri_string();

            // If intended URI is empty or is the home page then redirect
            if (empty($intended) || $intended == '/') {
                redirect(index_page(), 'refresh');
            }

            // Check if there are any query parameters
            $query_string = $_SERVER['QUERY_STRING'];
            if (! empty($query_string)) {
                $intended .= '?' . $query_string;
            }

            // Encode the indended URI + query string and redirect to the login page
            $encoded = urlencode(base64_encode($intended));
            redirect('/login?r=' . $encoded, 'refresh');
        }

        // Check if user has been deleted
        $user_id = (int) sess_var('uid');
        $CI->load->model('members_model');
        $user = $CI->members_model->find($user_id, 'status');
        if (empty($user) || $user['status'] != STATUS_ACTIVE) {
            logout(true);
        }
    }
}

if (! function_exists('logout')) {
    /**
     * @param bool $deleted
     */
    function logout($deleted = false)
    {
        $CI =& get_instance();
        // If it is an AJAX call return 403 error
        if ($CI->input->is_ajax_request()) {
            show_error('Forbidden.', 403);
        }

//      $userid = (int) sess_var('uid');
//
//        $update = array('lastlogout' => time());
//        $CI->db
//            ->where('uid', $userid)
//            ->update('exp_members', $update);
//
//        $session_data = array(
//            'logged_in' => '',
//            'uid' => '',
//            'name' => '',
//            'lastlogin' => '',
//            'isforum' =>'',
//            'usertype' =>''
//        );
//        $CI->session->unset_userdata($session_data);

        $CI->auth->logout();

//        if ((sess_var('admin_logged_in') && sess_var('admin_type')) && sess_var('admin_type') == '1') {
//            //$this->session->sess_destroy();
////            redirect('admin.php/members/view_all_members', 'refresh');
//          redirect('/');
//        }

        if ($deleted) {
            $CI->session->sess_destroy();
        }

        redirect(index_page(), 'refresh');
    }
}

if (! function_exists('redirect_after_login')) {
    /**
     * Redirects the user to spesific page after login
     *
     * @return void
     */
    function redirect_after_login()
    {
        $default = 'mygvip';

        $CI =& get_instance();

//      // First timer
//      if (sess_var('lastlogin') == 0) {
//          redirect('profile/account_settings', 'refresh');
//      }

        $intended = $CI->session->flashdata('intended');
        if (! empty($intended)) {
            redirect(base64_decode(urldecode($intended)), 'refresh');
        }

        redirect($default, 'refresh');
    }
}

if (! function_exists('in_array_default')) {
    /**
     * Checks if a value exists in an array and returns it or returns a default value.
     *
     * @param $array
     * @param $value
     * @param null $default
     * @return mixed
     */
    function in_array_default($array, $value, $default = null)
    {
        if (in_array($value, $array)) {
            return $value;
        } else {
            return $default;
        }
    }
}

if (! function_exists('build_title')) {
    /**
     * Builds a title string for a page
     *
     * @param string $subtitle
     * @return string
     */
    function build_title($subtitle = '')
    {
        return empty($subtitle) ? SITE_NAME : $subtitle . ' - ' . SITE_NAME;
    }
}

if (! function_exists('email')) {
    /**
     * An oversimplified wrapper around mb_send_mail that allows to send an email that
     * may contain unicode characters (in To, From, Reply-To, Subject, Message and other fields)
     *
     * Why: CodeIgniter's email library is broken and is not working properly with unicode characters
     *
     * For reacher functionality we can switch to PHPMailer https://github.com/PHPMailer/PHPMailer
     *
     * @param string|array $recipients
     * @param $subject
     * @param $message
     * @param string|array $sender
     * @param null|string|array $reply_to
     * @param array $headers
     * @return bool
     */
    function email($recipients, $subject, $message, $sender, $reply_to = null, $headers = array())
    {
        if (empty($recipients) || empty($sender)) {
            return false;
        }

        $_headers = array();
        $_to = array();
        $_from = '';

        // Date RFC822
        $_headers['Date'] = date('r');

        // Recipient(s)
        if (is_array($recipients)) {
            // Check and convert one dimensional array to multidimensional
            if (count($recipients) == count($recipients, COUNT_RECURSIVE)) {
                $recipients = array($recipients);
            }
            foreach ($recipients as $recipient) {
                $_to[] = mb_encode_mimeheader($recipient[1], 'UTF-8', 'Q') . ' <' . $recipient[0] . '>';
            }
        } else {
            $_to[] = $recipients;
        }

        // Sender
        if (is_array($sender)) {
            $_headers['From'] =  mb_encode_mimeheader($sender[1], 'UTF-8', 'Q') . ' <' . $sender[0] . '>';
            $_from = $sender[0];
        } else {
            $_headers['From'] = $sender;
            $_from = $sender;
        }

        // Reply to
        if (! empty($reply_to)) {
            if (is_array($reply_to)) {
                $_headers['Reply-To'] = mb_encode_mimeheader($reply_to[1], 'UTF-8', 'Q') . ' <' . $reply_to[0] . '>';
            } else {
                $_headers['Reply-To'] = $reply_to;
            }
        }

        // Merge additional headers if they have been provided
        if (! empty($headers)) {
            $_headers = array_merge($_headers, $headers);
        }
        // Set default type to HTML
        if (! in_array('Content-Type', array_keys($_headers))) {
            $_headers['Content-Type'] = 'text/html; charset=utf-8';
        }
        // Transform headers array into a string
        $_header_string = '';
        foreach ($_headers as $key => $value) {
            $_header_string .= $key . ': ' . $value . "\r\n";
        }

        // Use an additional parameter to set Return-Path
        $parameters = "-f$_from";

        mb_language('uni');
//var_dump(implode(',', $_to), $subject, $_header_string, $parameters);
        // Send the message
        return mb_send_mail(implode(',', $_to), $subject, $message, $_header_string, $parameters);
    }
}

if (! function_exists('simple_mail_content')) {
    /**
     * @param $message
     * @return string
     */
    function simple_mail_content($message)
    {
        $CI =& get_instance();

        $message = nl2br($message);

        $content  = $CI->load->view('email/_header', null, true);
        $content .= $CI->load->view('email/_simple', compact('message'), true);
        $content .= $CI->load->view('email/_footer', null, true);

        return $content;
    }
}

if (! function_exists('is_valid_period')) {
    /**
     * Returns true if both $start and $end are valid dates
     * and start_date > end_date
     *
     * @param $start
     * @param $end
     * @param null $format
     * @return bool
     */
    function is_valid_period($start, $end, $format = null)
    {
        if (empty($start) || empty($end)) {
            return false;
        }

        try {
            if (!empty($format)) {
                $dt_start = DateTime::createFromFormat($format, $start);
            } else {
                $dt_start = new DateTime($start);
            }
            if (!empty($format)) {
                $dt_end = DateTime::createFromFormat($format, $end);
            } else {
                $dt_end = new DateTime($end);
            }

            if (!$dt_start || !$dt_end || $dt_start > $dt_end) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

if (! function_exists('reminder_token')) {

    /**
     * Genertes a new password reminder token
     *
     * @param $email
     * @return string
     */
    function reminder_token($email)
    {
        $CI =& get_instance();
        $hash_key = $CI->config->item('encryption_key');

        $value = str_shuffle(sha1($email . spl_object_hash($CI) . microtime(true)));

        return hash_hmac('sha1', $value, $hash_key);
    }
}

/**
 * 503 Page Handler (503 Service Unavailable due to a temporary overloading or maintenance of the server.)
 *
 */
if (! function_exists('show_503')) {
    function show_503()
    {
        $_error =& load_class('Exceptions', 'core');
        $_error->show_503();
        exit;
    }
}
