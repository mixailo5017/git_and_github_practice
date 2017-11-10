<?php

class Projects_model extends CI_Model
{
    /**
     * Returns a list of member's projects that are not deleted
     * @param $member_id
     * @param string|null $select
     * @return mixed
     */
    public function member_projects($member_id, $select = null)
    {
        if (! is_null($select)) {
            $this->db->select($select);
        }

        $rows = $this->db
            ->where('uid', (int) $member_id)
            ->where('isdeleted', '0')
            ->get('exp_projects')
            ->result_array();

        return $rows;
    }

    /**
     * Retrieve a record by id
     *
     * @param $id
     * @param null $select
     * @return mixed
     */
    public function find($id, $select = null)
    {
        if (! is_null($select)) {
            $this->db->select($select);
        }

        $row = $this->db
            ->where('pid', (int) $id)
            ->where('isdeleted', '0')
            ->get('exp_projects')
            ->row_array();

        return $row;
    }

    public function find_from_slug($slug, $select = null)
    {
        $pid = $this->get_pid_from_slug($slug);
        return $this->find($pid, $select);
    }

    /**
     * Add New Project
     *
     * @access    public
     * @return    boolean/string
     */
    public function add_project()
    {
        //create slug for new project
        $slugname = $this->create_slug($this->input->post("title"), "exp_projects");
        $project_users = $this->input->post("project_users");
        $usrdetails = $this->get_user_general($project_users);
        $insertdata = array(
            "uid" => $project_users,
            "projectname" => $this->input->post("title"),
            "slug" => $slugname,
            "isforum" => $usrdetails["forum_attendee"],
            "entry_date" => time()
        );

        //insert into db and return slug value
        if ($this->db->insert("exp_projects", $insertdata)) {
            return $slugname;
        } else {
            return false;
        }
    }

    /**
     * Create slug
     *
     * @access    public
     */
    public function create_slug($string, $table)
    {
        $slug = url_title($string);
        $slug = strtolower($slug);
        $i = 0;
        $params = array();
        $params['slug'] = $slug;

        while ($this->db->where($params)->get($table)->num_rows()) {
            if (!preg_match('/-{1}[0-9]+$/', $slug)) {
                $slug .= '-' . ++$i;
            } else {
                $slug = preg_replace('/[0-9]+$/', ++$i, $slug);
            }
            $params ['slug'] = $slug;
        }
        return $slug;
    }

    /**
     * Get projects
     * (get user projects)
     *
     * @access    public
     *
     * @param    int
     *
     * @return    array
     */
    public function get_projects()
    {

        $this->db->select("pid,uid,projectname,slug,projectphoto,country,sector,stage,fundamental_legal");
        $this->db->order_by("projectname", "asc");
        $qryproj = $this->db->get_where('exp_projects', array('isdeleted' => '0'));


        $totalproj = $qryproj->num_rows();


        if ($totalproj > 0) {
            foreach ($qryproj->result_array() as $row) {
                $imgurl = $row["projectphoto"];
                $row["projectphoto"] = $imgurl;
                $projectdata["proj"][] = $row;
            }
            $projectdata["totalproj"] = $totalproj;
            return $projectdata;
        } else {
            $projectdata["totalproj"] = 0;
            $projectdata["proj"] = array();
            return $projectdata;
        }

    }

    /**
     * Get user General detail
     * (get user projects)
     *
     * @access    public
     *
     * @param    int
     *
     * @return    array
     */
    public function get_user_general($uid)
    {
        $row = $this->db
            ->select('uid,title,firstname,lastname,email,organization,membertype,sector,subsector,subsector_other,country,city,state,userphoto,forum_attendee')
            ->where('uid', (int) $uid)
// TODO: Revisit this
//            ->where('status', STATUS_ACTIVE)
            ->get('exp_members')
            ->row_array();

        return $row;
    }


    /*
 * Check User Project
 * Check whether this project is created by logged in user or not
 *
 * @access 	public
 * @param 	string
 * @return 	projectid
*/
    public function check_user_project($slug, $uid = '')
    {
        $this->db->select("pid");
        $qrycheck = $this->db->get_where("exp_projects", array("slug" => $slug, "isdeleted" => "0"));
        if ($qrycheck->num_rows > 0) {
            $objproject = $qrycheck->row_array();
            $pid = $objproject["pid"];
            return $pid;
        } else {
            return "";
        }
    }


    /*
 * Check User Project existance
 * Check whether this project is created or not
 *
 * @access 	public
 * @param 	string
 * @return 	boolean
*/
    public function check_project($slug)
    {
        $this->db->select("pid");
        $qrycheck = $this->db->get_where("exp_projects", array("slug" => $slug, "isdeleted" => "0"));
        if ($qrycheck->num_rows > 0) {

            return true;
        } else {
            return false;
        }
    }

    /*
 * Check uid from slug
 * 
 *
 * @access 	public
 * @param 	string
 * @return 	boolean
*/
    public function get_uid_from_slug($slug)
    {
        $this->db->select("uid");
        $qrycheck = $this->db->get_where("exp_projects", array("slug" => $slug, "isdeleted" => "0"));
        if ($qrycheck->num_rows > 0) {
            $objproject = $qrycheck->row_array();
            $uid2 = $objproject["uid"];
            return $uid2;
        } else {
            return "";
        }
    }


    /*
 * Update Project
 * Update project data submited from Project Information Tab
 *
 * @access 	public
 * @param 	string
 * @param 	int
 * @return 	array
*/
    public function update_project($slug, $uid)
    {
        // If budget value is empty or equals to 0 set it explicitly to NULL
        // otherwise convert it to int
        $budget = $this->input->post('project_budget_max', TRUE);
        if ($budget == '' || $budget == '0') {
            $budget = null;
        } else {
            $budget = (int) $budget;
        }

        $update = array(
            'projectname' => $this->input->post('title_input', TRUE),
            'description' => $this->input->post('project_overview', TRUE),
            'keywords' => $this->input->post('project_keywords', TRUE),
            'country' => $this->input->post('project_country', TRUE),
            'location' => $this->input->post('project_location', TRUE),
            'sector' => $this->input->post('project_sector_main', TRUE),
            'subsector' => $this->input->post('project_sector_sub', TRUE),
            'subsector_other' => $this->input->post('project_sector_sub_other', TRUE),
            'totalbudget' => $budget,
            'financialstructure' => $this->input->post('project_financial', TRUE),
            'financialstructure_other' => $this->input->post('project_fs_other', TRUE),
            'project_meta_permissions' => $this->input->post('project_meta_permissions', TRUE),
            'stage' => $this->input->post('project_stage', TRUE),
            'eststart' => DateFormat($this->input->post('project_estsrart', TRUE), DATEFORMATDB, false),
            'estcompletion' => DateFormat($this->input->post('project_estcompletion', TRUE), DATEFORMATDB, false),
            'developer' => $this->input->post('project_developer', TRUE),
            'sponsor' => $this->input->post('project_sponsor', TRUE),
            'website' => $this->input->post('website', TRUE),
//            'uid' => $this->input->post('project_owner', TRUE),
        );

        // TODO: Revisit and deal with changing the owner of a project!!!
        $owner = $this->input->post('project_owner', TRUE);
        if ($owner) {
            $update['uid'] = (int) $owner;
        }

        // TODO: This is no longer being used
        $isforum_post = $this->input->post('project_isforum', TRUE);
        if (isset($isforum_post) && $isforum_post == '1') {
            $update['isforum'] = '1';
        } else {
            $update['isforum'] = '0';
        }

        $this->db->where(array('slug' => $slug, 'uid' => $uid));

        if ($this->db->update('exp_projects', $update)) {
            $response['issubmit'] = false;
            $response['status'] = 'success';
            $response['message'] = 'Project Updated Successfully.';
            $response['remove'] = true;
            $response['isload'] = 'no';
            $response['redirect'] = '';
            $response['innermsg'] = '1';

            $this->batch_geocode($slug);
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error while updating Project information.';
            $response['remove'] = true;

        }

        sendResponse($response);
        exit;
    }


    public function delete_projects()
    {
        $delids = $this->input->get("delids");
        if (count($delids) > 0) {

            $update_data = array('isdeleted' => '1');
            $response = array();
            $this->db->where_in("pid", $delids);

            if ($this->db->update('exp_projects', $update_data)) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Project(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }


/*
 * Get Project Data
 * Get Requested Project data when user eneter into edit mode
 *
 * @access 	public
 * @param 	string
 * @param 	int
 * @return 	array
*/
    public function get_project_data($slug)
    {
        $qryproj = $this->db->get_where("exp_projects", array("slug" => $slug));
        $projectarr = $qryproj->row_array();
        $qryproj->free_result();

        $uid = $projectarr['uid'];

        $projectarr["executive"] = $this->get_executives($slug, $uid);
        $projectarr["organization"] = $this->get_organizations($slug, $uid);
        $projectarr["assessment"] = $this->get_assessment_data($slug) == false ? false : $this->get_assessment_data(
            $slug
        );

        return $projectarr;
    }


    /*
 * Get common Project Data
 * Get Requested Project data when user eneter into edit mode
 *
 * @access 	public
 * @param 	string
 * @param 	string,int
 * @return 	array
*/
    public function get_common_data($slug)
    {
        $this->db->select(array('pid', 'stage', 'uid', 'projectname', 'slug', 'fundamental_legal'));
        $qryproj = $this->db->get_where("exp_projects", array("slug" => $slug));
        $projectarr = $qryproj->row_array();
        $qryproj->free_result();

        return $projectarr;
    }


    /**
     * update project picture in Project Details & Edit
     *
     * @return    boolean
     */
    public function upload_photo($file, $slug, $uid)
    {
        $update_data = array(
            'projectphoto' => $file['file_name']

        );

        $this->db->where(array('uid' => $uid, 'slug' => $slug));

        if ($str = $this->db->update('exp_projects', $update_data)) {
            $response = array();
            $response["status"] = "success";
            $response["message"] = "Project picture updated successfully.";
            $response["isload"] = "no";
            $response["imgpath"] = PROJECT_IMAGE_PATH . "150_150_" . $file["file_name"];
            $response["redirect"] = '';


            ////header('Content-type: application/json');
            echo json_encode($response);
        } else {
            $response = array();
            $response["status"] = "error";
            $response["message"] = "Error while updating Project picture.";
            $response["isload"] = "no";
            $response["redirect"] = '';

            ////header('Content-type: application/json');
            echo json_encode($response);
        }

    }


    /**
     * Update Project name
     *
     * @access    public
     *
     * @param    int
     *
     * @return    json
     */

    public function updateprojectname($slug, $uid)
    {
        $response = array();
        $update_data = array(
            'projectname' => $this->input->post("title_input")
        );
        $this->db->where(array('uid' => $uid, 'slug' => $slug));

        if ($this->db->update('exp_projects', $update_data)) {
            $response["issubmit"] = true;
            $response["formname"] = "project_form";
        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * Add Legal
     *
     * @access    public
     *
     * @param    int
     *
     * @return    array
     */
    public function add_legal($slug, $uid)
    {
        $response = array();
        $update_data = array(
            'fundamental_legal' => $this->input->post("project_legal")
        );
        $this->db->where(array('uid' => $uid, 'slug' => $slug));

        if ($this->db->update('exp_projects', $update_data)) {
            $response["issubmit"] = false;
            $response["status"] = "success";
            $response["message"] = "Legal Info update successfully.";
            $response["remove"] = true;
            $response["isload"] = "no";
            $response["redirect"] = "";
            //$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Legal Info.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * Get executive
     * (get executives)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_executives($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_executive", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }


    /**
     * Add executive
     * (add executives)
     *
     * @access    public
     *
     * @param    int
     *
     * @return    array
     */
    public function add_executive($slug, $uid)
    {
        $executivedata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'executivename' => $this->input->post("project_executives_name"),
            'company' => $this->input->post("project_executives_company"),
            'role' => $this->input->post("project_executives_role"),
            'email' => $this->input->post("project_executives_email")
        );
        $response = array();
        if ($this->db->insert("exp_proj_executive", $executivedata)) {
            $response["status"] = "success";
            $response["message"] = "Executive added successfully.";
            $response["remove"] = true;
            $response["redirect"] = '/admin.php/projects/edit/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Executive.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * update executive
     * (update executives)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function update_executive($slug, $uid)
    {
        $executiveid = $this->input->post("hdn_project_executives_id");
        $executivedata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'executivename' => $this->input->post("project_executives_name"),
            'company' => $this->input->post("project_executives_company"),
            'role' => $this->input->post("project_executives_role"),
            'email' => $this->input->post("project_executives_email")
        );

        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $executiveid));

        if ($this->db->update('exp_proj_executive', $executivedata)) {
            $response["status"] = "success";
            $response["msgtype"] = "success";
            $response["message"] = "Executive update successfully.";
            $response["remove"] = true;
            $response["redirect"] = '/admin.php/projects/edit/' . $slug;

        } else {
            $response["status"] = "error";
            $response["message"] = "Error while update Executive.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * delete executive
     * (delete executives)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */

    public function delete_executive()
    {
        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_executive")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Executive(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Get Organization
     * (get organization)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function get_organizations($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryorg = $this->db->get_where("exp_proj_organization", array("slug" => $slug));
        $orgarr = $qryorg->result_array();
        $qryorg->free_result();

        return $orgarr;
    }

    /**
     * add organization
     * (add organization)
     *
     * @access    public
     *
     * @param    slug ,int
     *
     * @return    json
     */
    public function add_organization($slug, $uid)
    {
        $organizationdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'contact' => $this->input->post("project_organizations_contact"),
            'company' => $this->input->post("project_organizations_company"),
            'role' => $this->input->post("project_organizations_role"),
            'email' => $this->input->post("project_organizations_email")
        );
        $response = array();
        if ($this->db->insert("exp_proj_organization", $organizationdata)) {
            $response["status"] = "success";
            $response["message"] = "Organization added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "organization_form";
            $response["redirect"] = '/admin.php/projects/edit/' . $slug;
            //$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Organization.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * update organization
     * (update organization)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function update_organization($slug, $uid)
    {
        $organizationid = $this->input->post("hdn_project_organizations_id");
        $organizationdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'contact' => $this->input->post("project_organizations_contact"),
            'company' => $this->input->post("project_organizations_company"),
            'role' => $this->input->post("project_organizations_role"),
            'email' => $this->input->post("project_organizations_email")
        );

        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $organizationid));

        if ($this->db->update('exp_proj_organization', $organizationdata)) {
            $response["status"] = "success";
            $response["message"] = "Organization update successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "organization_form";
            $response["redirect"] = '/admin.php/projects/edit/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while update Organization.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * delete organization
     * (delete organization)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */

    public function delete_organization()
    {
        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_organization")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Organization(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
     * Get engineering
     * (get engineering)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */

    public function get_engineering($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_engg_fundamental", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * add engineering
     * (add engineering)
     *
     * @access    public
     *
     * @param    int ,int,optional
     *
     * @return    json
     */
    public function add_engineering($slug, $uid, $upload = '')
    {

        $engineeringdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'contactname' => $this->input->post("project_engineering_cname"),
            'company' => $this->input->post("project_engineering_company"),
            'role' => $this->input->post("project_engineering_role"),
            'challenges' => $this->input->post("project_engineering_challenges"),
            'innovations' => $this->input->post("project_engineering_innovations")
        );

        if ($upload['error'] == '') {
            $engineeringdata['schedule'] = $upload['file_name'];
        }

        $response = array();
        if ($this->db->insert("exp_proj_engg_fundamental", $engineeringdata)) {
            $response["status"] = "success";
            $response["message"] = "Engineering Fundamental added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "engineering_form";
            $response["redirect"] = '/admin.php/projects/edit_fundamentals/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Engineering Fundamental.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * update engineering
     * (update engineering)
     *
     * @access    public
     *
     * @param    int ,int,optional
     *
     * @return    json
     */
    public function update_engineering($slug, $uid, $upload = '')
    {
        $engineeringid = $this->input->post("hdn_project_engineering_id");
        $engineeringdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'contactname' => $this->input->post("project_engineering_cname"),
            'company' => $this->input->post("project_engineering_company"),
            'role' => $this->input->post("project_engineering_role"),
            'challenges' => $this->input->post("project_engineering_challenges"),
            'innovations' => $this->input->post("project_engineering_innovations")
        );

        if ($upload['error'] == '') {
            $engineeringdata['schedule'] = $upload['file_name'];
        }

        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $engineeringid));

        if ($this->db->update('exp_proj_engg_fundamental', $engineeringdata)) {
            $response["status"] = "success";
            $response["message"] = "Engineering Fundamental update successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "engineering_form";
            $response["redirect"] = '/admin.php/projects/edit_fundamentals/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while update Engineering Fundamental.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * Delete engineering
     * (delete engineering)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */

    public function delete_engineering()
    {
        //$unlinkstatus = $this->unlink_files($delid,$uid,'schedule','exp_proj_engg_fundamental',PROJECT_IMAGE_PATH);

        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_engg_fundamental")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Engineering Fundamental(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
     * get map points
     * (get map points)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_map_point($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_map_points", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add map points
     * (add map points)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function add_map_point($slug, $uid)
    {
        $mappointdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_map_points_mapname"),
            'latitude' => $this->input->post("project_map_points_latitude"),
            'longitude' => $this->input->post("project_map_points_longitude")
        );
        $response = array();
        if ($this->db->insert("exp_proj_map_points", $mappointdata)) {
            $response["status"] = "success";
            $response["message"] = "Map point added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "map_points_form";
            $response["isreset"] = "yes";
            $response["redirect"] = '/admin.php/projects/edit_fundamentals/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Map point.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * update map points
     * (update map points)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function update_map_point($slug, $uid)
    {
        $mappointid = $this->input->post("hdn_project_map_points_id");
        $mappointdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_map_points_mapname"),
            'latitude' => $this->input->post("project_map_points_latitude"),
            'longitude' => $this->input->post("project_map_points_longitude")
        );

        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $mappointid));

        if ($this->db->update('exp_proj_map_points', $mappointdata)) {
            $response["status"] = "success";
            $response["message"] = "Map Point update successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "map_points_form";
            $response["redirect"] = '/admin.php/projects/edit_fundamentals/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updateing Map Point.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * Delete map points
     * (delete map points)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_map_point()
    {
        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_map_points")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Map Point(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
     * Get design issue
     * (get design issue)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_design_issue($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_design_issues", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add design issue
     * (add design issue)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function add_design_issue($slug, $uid, $upload = '')
    {
        $designissuedata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'title' => $this->input->post("project_design_issues_title"),
            'description' => $this->input->post("project_design_issues_desc"),
            'attachment' => $this->input->post("project_design_issues_attachment"),
            'permission' => $this->input->post("project_design_issues_permissions")
        );
        if ($upload['error'] == '') {
            $designissuedata['attachment'] = $upload['file_name'];
        }
        $response = array();
        if ($this->db->insert("exp_proj_design_issues", $designissuedata)) {
            $response["status"] = "success";
            $response["message"] = "Design issue added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "design_issue_form";
            $response["redirect"] = '/admin.php/projects/edit_fundamentals/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Design issue.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * update design issue
     * (update design issue)
     *
     * @access    public
     *
     * @param    int ,int,optional
     *
     * @return    json
     */
    public function update_design_issue($slug, $uid, $upload = '')
    {
        $designissueid = $this->input->post("hdn_project_design_issues_id");
        $designissuedata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'title' => $this->input->post("project_design_issues_title"),
            'description' => $this->input->post("project_design_issues_desc"),
            'attachment' => $this->input->post("project_design_issues_attachment"),
            'permission' => $this->input->post("project_design_issues_permissions")

        );
        if ($upload['error'] == '') {
            $designissuedata['attachment'] = $upload['file_name'];
        }


        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $designissueid));
        $response = array();
        if ($this->db->update("exp_proj_design_issues", $designissuedata)) {
            $response["status"] = "success";
            $response["message"] = "Design issue updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "design_issue_form";
            $response["redirect"] = '/admin.php/projects/edit_fundamentals/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Design issue.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * Delete design issue
     * (delete design issue)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */

    public function delete_design_issue()
    {
        //$unlinkstatus = $this->unlink_files($delid,$uid,'attachment','exp_proj_design_issues',PROJECT_IMAGE_PATH);

        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_design_issues")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Design Issue(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
     * Get design issue
     * (get design issue)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_environment($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_environment", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add environment
     * (add environment)
     *
     * @access    public
     *
     * @param    int ,int,optional
     *
     * @return    json
     */
    public function add_environment($slug, $uid, $upload = '')
    {
        $environmentdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'title' => $this->input->post("project_environment_title"),
            'description' => $this->input->post("project_environment_desc"),
            'attachment' => $this->input->post("project_environment_attachment"),
            'permission' => $this->input->post("project_environment_permissions")
        );
        if ($upload['error'] == '') {
            $environmentdata['attachment'] = $upload['file_name'];
        }

        $response = array();
        if ($this->db->insert("exp_proj_environment", $environmentdata)) {
            $response["status"] = "success";
            $response["message"] = "Environment file added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "environment_form";
            $response["redirect"] = '/admin.php/projects/edit_fundamentals/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Environment file.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * Update environment
     * (update environment)
     *
     * @access    public
     *
     * @param    int ,int,optional
     *
     * @return    json
     */
    public function update_environment($slug, $uid, $upload = '')
    {
        $environmentid = $this->input->post("hdn_project_environment_id");
        $environmentdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'title' => $this->input->post("project_environment_title"),
            'description' => $this->input->post("project_environment_desc"),
            'attachment' => $this->input->post("project_environment_attachment"),
            'permission' => $this->input->post("project_environment_permissions")

        );
        if ($upload['error'] == '') {
            $environmentdata['attachment'] = $upload['file_name'];
        }

        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $environmentid));
        $response = array();
        if ($this->db->update("exp_proj_environment", $environmentdata)) {
            $response["status"] = "success";
            $response["message"] = "Environment file updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "environment_form";
            $response["redirect"] = '/admin.php/projects/edit_fundamentals/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Environment file.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * delete environment
     * (delete environment)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_environment()
    {
        //$unlinkstatus = $this->unlink_files($delid,$uid,'attachment','exp_proj_environment',PROJECT_IMAGE_PATH);

        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_environment")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Environment(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
     * Get studies
     * (get studies)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_studies($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where("id", $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_studies", array('slug' => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add studies
     * (add studies)
     *
     * @access    public
     *
     * @param    int ,int,optional
     *
     * @return    array
     */
    public function add_studies($slug, $uid, $upload = '')
    {
        $studiesdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'title' => $this->input->post("project_studies_title"),
            'description' => $this->input->post("project_studies_desc"),
            'attachment' => $this->input->post("project_studies_attachment"),
            'permission' => $this->input->post("project_studies_permissions")
        );
        if ($upload['error'] == '') {
            $studiesdata['attachment'] = $upload['file_name'];
        }

        $response = array();
        if ($this->db->insert("exp_proj_studies", $studiesdata)) {
            $response["status"] = "success";
            $response["message"] = "Study file added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "project_studies_form";
            $response["isreset"] = "yes";
            $response["redirect"] = '/admin.php/projects/edit_fundamentals/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Study file.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * Update studies
     * (update studies)
     *
     * @access    public
     *
     * @param    int ,int,optional
     *
     * @return    json
     */
    public function update_studies($slug, $uid, $upload = '')
    {
        $studiesid = $this->input->post("hdn_project_studies_id");
        $studiesdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'title' => $this->input->post("project_studies_title"),
            'description' => $this->input->post("project_studies_desc"),
            'attachment' => $this->input->post("project_studies_attachment"),
            'permission' => $this->input->post("project_studies_permissions")

        );
        if ($upload['error'] == '') {
            $studiesdata['attachment'] = $upload['file_name'];
        }

        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $studiesid));
        $response = array();
        if ($this->db->update("exp_proj_studies", $studiesdata)) {
            $response["status"] = "success";
            $response["message"] = "Study file updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "project_studies_form";
            $response["redirect"] = '/admin.php/projects/edit_fundamentals/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Study files.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * Delete studies
     * (delete studies)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_studies()
    {
        //$unlinkstatus = $this->unlink_files($delid,$uid,'attachment','exp_proj_studies',PROJECT_IMAGE_PATH);

        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_studies")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Study File(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
     * Get finance
     * (get finance)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */

    public function get_financial($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_financial", array("slug" => $slug));
        $execarr = $qryexec->row_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add financial
     * (add financial)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    array
     */
    public function add_financial($slug, $uid)
    {

        $num_financialrec = count($this->get_financial($slug));

        $financialdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_fs_name"),
            'name_privacy' => $this->input->post("project_fs_name_permissions"),
            'contactname' => $this->input->post("project_fs_name"),
            'contactname_privacy' => $this->input->post("project_fs_contact_permissions"),
            'role' => $this->input->post("project_fs_role"),
            'role_others' => $this->input->post("project_fs_other"),
            'role_privacy' => $this->input->post("project_fs_role_permissions"),
            'contactinfo' => $this->input->post("project_fs_info"),
            'contactinfo_privacy' => $this->input->post("project_fs_info_permissions")
        );
        $response = array();

        if ($num_financialrec > 0) {
            $this->db->where(array('uid' => $uid, 'slug' => $slug));
            $financialStatus = $this->db->update("exp_proj_financial", $financialdata);
        } else {
            $financialStatus = $this->db->insert("exp_proj_financial", $financialdata);
        }
        if ($financialStatus) {
            $response["status"] = "success";
            $response["message"] = "Financial Details updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["redirect"] = '/admin.php/projects/edit_financial/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Financial Details.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }


    /**
     * get fund resources
     * (get fund resources)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */

    public function get_fund_sources($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_fund_sources", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add fund resources
     * (add fund resources)
     *
     * @access    public
     *
     * @param    sting ,int
     *
     * @return    json
     */
    public function add_fund_sources($slug, $uid)
    {

        $fundsourcedata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_fund_sources_name"),
            'role' => $this->input->post("project_fund_sources_role"),
            'amount' => $this->input->post("project_fund_sources_amount"),
            'description' => $this->input->post("project_fund_sources_desc"),
        );
        $response = array();

        if ($this->db->insert("exp_proj_fund_sources", $fundsourcedata)) {
            $response["status"] = "success";
            $response["message"] = "Fund sources added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "fund_sources_form";
            $response["redirect"] = '/admin.php/projects/edit_financial/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Fund sources.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * update fund resources
     * (update fund resources)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */

    public function update_fund_sources($slug, $uid)
    {
        $fundsourceid = $this->input->post("hdn_project_fund_sources_id");

        $fundsourcedata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_fund_sources_name"),
            'role' => $this->input->post("project_fund_sources_role"),
            'amount' => $this->input->post("project_fund_sources_amount"),
            'description' => $this->input->post("project_fund_sources_desc"),
        );
        $response = array();
        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $fundsourceid));

        if ($this->db->update("exp_proj_fund_sources", $fundsourcedata)) {
            $response["status"] = "success";
            $response["message"] = "Fund sources updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "fund_sources_form";
            $response["redirect"] = '/admin.php/projects/edit_financial/' . $slug;
            $response['datafatch'] = $fundsourceid;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Fund sources.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * delete fund resources
     * (delete fund resources)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_fund_sources()
    {
        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_fund_sources")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Fund Source(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
     * Get roi
     * (get roi)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_roi($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_investment_return", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add roi
     * (add roi)
     *
     * @access    public
     *
     * @param    string ,int,optional
     *
     * @return    json
     */
    public function add_roi($slug, $uid, $upload = '')
    {
        $roidata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_roi_name"),
            'percent' => $this->input->post("project_roi_percent"),
            'type' => $this->input->post("project_roi_type"),
            'approach' => $this->input->post("project_roi_approach"),
            'keystudy' => $this->input->post("project_roi_keystudy"),
            'permission' => $this->input->post("project_roi_permission")
        );

        if ($upload['error'] == '') {
            $roidata['keystudy'] = $upload['file_name'];
        }


        $response = array();

        if ($this->db->insert("exp_proj_investment_return", $roidata)) {
            $response["status"] = "success";
            $response["message"] = "Return On Investment added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "roi_form";
            $response["redirect"] = '/admin.php/projects/edit_financial/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Return on Investment sources.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * update roi
     * (update roi)
     *
     * @access    public
     *
     * @param    add ,int
     *
     * @return    json
     */
    public function update_roi($slug, $uid, $upload = '')
    {
        $roiid = $this->input->post("hdn_project_roi_id");

        $roidata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_roi_name"),
            'percent' => $this->input->post("project_roi_percent"),
            'type' => $this->input->post("project_roi_type"),
            'approach' => $this->input->post("project_roi_approach"),
            'keystudy' => $this->input->post("project_roi_keystudy"),
            'permission' => $this->input->post("project_roi_permission")
        );

        if ($upload['error'] == '') {
            $roidata['keystudy'] = $upload['file_name'];
        }

        $response = array();
        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $roiid));

        if ($this->db->update("exp_proj_investment_return", $roidata)) {
            $response["status"] = "success";
            $response["message"] = "Return On Investment updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "roi_form";
            $response["redirect"] = '/admin.php/projects/edit_financial/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Return On Investment.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * Delete roi
     * (delete roi)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */

    public function delete_roi()
    {
        //$unlinkstatus = $this->unlink_files($delid,$uid,'keystudy','exp_proj_investment_return',PROJECT_IMAGE_PATH);

        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_investment_return")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "ROI Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Get Cretical participant
     * (Get Cretical participant)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_critical_participants($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_participant_critical", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add Cretical participant
     * (add Cretical participant)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function add_critical_participants($slug, $uid)
    {

        $criticalparticipantdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_critical_participants_name"),
            'role' => $this->input->post("project_critical_participants_role"),
            'description' => $this->input->post("project_critical_participants_desc")
        );
        $response = array();

        if ($this->db->insert("exp_proj_participant_critical", $criticalparticipantdata)) {
            $response["status"] = "success";
            $response["message"] = "Critical Participant added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "critical_participants_form";
            $response["redirect"] = '/admin.php/projects/edit_financial/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Critical Participant.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * update Cretical participant
     * (update Cretical participant)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function update_critical_participants($slug, $uid)
    {
        $criticalparticipantid = $this->input->post("hdn_project_critical_participants_id");

        $criticalparticipantdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_critical_participants_name"),
            'role' => $this->input->post("project_critical_participants_role"),
            'description' => $this->input->post("project_critical_participants_desc")
        );

        $response = array();
        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $criticalparticipantid));

        if ($this->db->update("exp_proj_participant_critical", $criticalparticipantdata)) {
            $response["status"] = "success";
            $response["message"] = "Critical Participant updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "critical_participants_form";
            $response["redirect"] = '/admin.php/projects/edit_financial/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Critical Participant.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * Delete Cretical participant
     * (delete Cretical participant)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_critical_participants()
    {
        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_participant_critical")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Critical Participant(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
     * Get project regulatory
     * (Get project regulatory)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */

    public function get_project_regulatory($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_regulatory", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add project regulatory
     * (add project regulatory)
     *
     * @access    public
     *
     * @param    string ,int,optional
     *
     * @return    json
     */
    public function add_regulatory($slug, $uid, $upload = '')
    {
        $regulatorydata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'description' => $this->input->post("project_regulatory_desc"),
            'permission' => $this->input->post("project_regulatory_permission")
        );
        if ($upload['error'] == '') {
            $regulatorydata['file'] = $upload['file_name'];
        }

        $response = array();

        if ($this->db->insert("exp_proj_regulatory", $regulatorydata)) {
            $response["status"] = "success";
            $response["message"] = "Regulatory added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "regulatory_form";
            $response["redirect"] = '/admin.php/projects/edit_regulatory/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Regulatory.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);


    }

    /**
     * Update project regulatory
     * (Update project regulatory)
     *
     * @access    public
     *
     * @param    string ,int,optional
     *
     * @return    json
     */
    public function update_regulatory($slug, $uid, $upload = '')
    {
        $ragulatoryid = $this->input->post("hdn_project_regulatory_id");
        $regulatorydata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'file' => $this->input->post("project_regulatory_filename"),
            'description' => $this->input->post("project_regulatory_desc"),
            'permission' => $this->input->post("project_regulatory_permission")
        );

        if ($upload['error'] == '') {
            $regulatorydata['file'] = $upload['file_name'];
        }

        $response = array();
        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $ragulatoryid));

        if ($this->db->update("exp_proj_regulatory", $regulatorydata)) {
            $response["status"] = "success";
            $response["message"] = "Regulatory upated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "regulatory_form";
            $response["redirect"] = '/admin.php/projects/edit_financial/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Regulatory.";
            $response["remove"] = true;
        }
        //header('Content-type: application/json');
        echo json_encode($response);

    }

    /**
     * Delete project regulatory
     * (delete project regulatory)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_regulatory()
    {
        //$unlinkstatus = $this->unlink_files($delid,$uid,'file','exp_proj_regulatory',PROJECT_IMAGE_PATH);

        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_regulatory")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Regulatory File(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Get public participants
     * (Get public participants)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_participants_public($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_participant_public", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }


    /**
     * Add public participants
     * (add public participants)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function add_participants_public($slug, $uid)
    {
        $participantspublicdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_participants_public_name"),
            'type' => $this->input->post("project_participants_public_type"),
            'description' => $this->input->post("project_participants_public_desc"),
            'permission' => $this->input->post("project_participants_political_permission")

        );
        $response = array();

        if ($this->db->insert("exp_proj_participant_public", $participantspublicdata)) {
            $response["status"] = "success";
            $response["message"] = "Public Participant added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "participants_public_form";
            $response["redirect"] = '/admin.php/projects/edit_participants/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Public Participant.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);

    }

    /**
     * update public participants
     * (update public participants)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function update_participants_public($slug, $uid)
    {
        $participantspublicid = $this->input->post("hdn_participants_public_id");
        $participantspublicdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_participants_public_name"),
            'type' => $this->input->post("project_participants_public_type"),
            'description' => $this->input->post("project_participants_public_desc"),
            'permission' => $this->input->post("project_participants_political_permission")

        );
        $response = array();
        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $participantspublicid));

        if ($this->db->update("exp_proj_participant_public", $participantspublicdata)) {
            $response["status"] = "success";
            $response["message"] = "Public Participant updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "participants_public_form";
            $response["redirect"] = '/admin.php/projects/edit_participants/' . $slug;;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Public Participant.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * Delete public participants
     * (delete public participants)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_participants_public()
    {
        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_participant_public")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Public Participant(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * get political participants
     * (get political participants)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_participants_political($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_participant_political", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add political participants
     * (add political participants)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function add_participants_political($slug, $uid)
    {
        $participantspoliticaldata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_participants_political_name"),
            'type' => $this->input->post("project_participants_political_type"),
            'description' => $this->input->post("project_participants_political_desc"),
            'permission' => $this->input->post("project_participants_political_permission")


        );
        $response = array();

        if ($this->db->insert("exp_proj_participant_political", $participantspoliticaldata)) {
            $response["status"] = "success";
            $response["message"] = "Political Participant added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "participants_political_form";
            $response["redirect"] = '/admin.php/projects/edit_participants/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Political Participant.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);


    }

    /**
     * update political participants
     * (update political participants)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function update_participants_political($slug, $uid)
    {
        $participantspoliticalid = $this->input->post("hdn_participants_political_id");
        $participantspoliticaldata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_participants_political_name"),
            'type' => $this->input->post("project_participants_political_type"),
            'description' => $this->input->post("project_participants_political_desc"),
            'permission' => $this->input->post("project_participants_political_permission")


        );
        $response = array();

        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $participantspoliticalid));

        if ($this->db->update("exp_proj_participant_political", $participantspoliticaldata)) {
            $response["status"] = "success";
            $response["message"] = "Political Participant updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "participants_political_form";
            $response["redirect"] = '/admin.php/projects/edit_participants/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Political Participant.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);

    }

    /**
     * delete political participants
     * (delete political participants)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_participants_political()
    {
        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_participant_political")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Political Participant(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * get companies
     * (get companies)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_participants_companies($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_participant_company", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add companies
     * (add companies)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function add_participants_companies($slug, $uid)
    {
        $participantscompanydata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_participants_companies_name"),
            'role' => $this->input->post("project_participants_companies_role"),
            'description' => $this->input->post("project_participants_companies_desc"),
            'permission' => $this->input->post("project_participants_companies_permission")


        );
        $response = array();

        if ($this->db->insert("exp_proj_participant_company", $participantscompanydata)) {
            $response["status"] = "success";
            $response["message"] = "Company Participant added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "participants_company_form";
            $response["redirect"] = '/admin.php/projects/edit_participants/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Company Participant.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);


    }

    /**
     * update company
     * (update company)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function update_participants_companies($slug, $uid)
    {
        $participantscompanyid = $this->input->post("hdn_participants_companies_id");
        $participantscompanydata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_participants_companies_name"),
            'role' => $this->input->post("project_participants_companies_role"),
            'description' => $this->input->post("project_participants_companies_desc"),
            'permission' => $this->input->post("project_participants_companies_permission")


        );
        $response = array();
        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $participantscompanyid));
        if ($this->db->update("exp_proj_participant_company", $participantscompanydata)) {
            $response["status"] = "success";
            $response["message"] = "Company Participant updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "participants_company_form";
            $response["redirect"] = '/admin.php/projects/edit_participants/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Company Participant.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);

    }


    /**
     * Delete company
     * (delete company)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_participants_companies()
    {
        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_participant_company")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Participant Companies Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Get owner
     * (Get owner)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_participants_owners($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        //,"membertype !="=>'8'
        $qryexec = $this->db->get_where("exp_proj_participant_owner", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }


    /**
     * add owner
     * (add owner)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function add_participants_owners($slug, $uid)
    {
        $participantsownersdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_participants_owners_name"),
            'type' => $this->input->post("project_participants_owners_type"),
            'description' => $this->input->post("project_participants_owners_desc"),
            'permission' => $this->input->post("project_participants_owners_permission")


        );
        $response = array();

        if ($this->db->insert("exp_proj_participant_owner", $participantsownersdata)) {
            $response["status"] = "success";
            $response["message"] = "Owner Participant added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "participants_owners_form";
            $response["redirect"] = '/admin.php/projects/edit_participants/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Owner Participant.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);


    }

    /**
     * update company
     * (update company)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function update_participants_owners($slug, $uid)
    {
        $participantsownersid = $this->input->post("hdn_participants_owners_id");
        $participantsownersdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_participants_owners_name"),
            'type' => $this->input->post("project_participants_owners_type"),
            'description' => $this->input->post("project_participants_owners_desc"),
            'permission' => $this->input->post("project_participants_owners_permission")
        );
        $response = array();
        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $participantsownersid));
        if ($this->db->update("exp_proj_participant_owner", $participantsownersdata)) {
            $response["status"] = "success";
            $response["message"] = "Owner Participant updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "participants_owners_form";
            $response["redirect"] = '/admin.php/projects/edit_participants/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Company Participant.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
        $response = array();

    }

    /**
     * Delete owners
     * (update owners)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_participants_owners()
    {
        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_participant_owner")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Participant Owner(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Get machinery
     * (get machinery)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_machinery($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_machinery", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add machinery
     * (add machinery)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function add_machinery($slug, $uid)
    {
        $machinerydata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_machinery_name"),
            'procurementprocess' => $this->input->post("project_machinery_process"),
            'financialinfo' => $this->input->post("project_machinery_financial_info"),
            'permission' => $this->input->post("project_machinery_permission")


        );
        $response = array();

        if ($this->db->insert("exp_proj_machinery", $machinerydata)) {
            $response["status"] = "success";
            $response["message"] = "Machinery added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "machinery_form";
            $response["redirect"] = '/admin.php/projects/edit_participants/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Machinery.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * Update machinery
     * (update machinery)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function update_machinery($slug, $uid)
    {
        $machineryid = $this->input->post("hdn_project_machinery_id");
        $machinerydata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_machinery_name"),
            'procurementprocess' => $this->input->post("project_machinery_process"),
            'financialinfo' => $this->input->post("project_machinery_financial_info"),
            'permission' => $this->input->post("project_machinery_permission")


        );

        $response = array();
        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $machineryid));
        if ($this->db->update("exp_proj_machinery", $machinerydata)) {
            $response["status"] = "success";
            $response["message"] = "Machinery updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "machinery_form";
            $response["redirect"] = '/admin.php/projects/edit_procurement/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Machinery.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);

    }

    /**
     * Delete machinery
     * (delete machinery)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_machinery()
    {
        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_machinery")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Machinery Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Get Procurement technology
     * (Get Procurement technology)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_procurement_technology($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_procurement_technology", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add Procurement technology
     * (add Procurement technology)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function add_procurement_technology($slug, $uid)
    {
        $protechdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_procurement_technology_name"),
            'procurementprocess' => $this->input->post("project_procurement_technology_process"),
            'financialinfo' => $this->input->post("project_procurement_technology_financial_info"),
            'permission' => $this->input->post("project_procurement_technology_permission")


        );
        $response = array();

        if ($this->db->insert("exp_proj_procurement_technology", $protechdata)) {
            $response["status"] = "success";
            $response["message"] = "Procurement Technology added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "procurement_technology_form";
            $response["redirect"] = '/admin.php/projects/edit_procurement/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Procurement Technology.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);

    }

    /**
     * Update Procurement technology
     * (update Procurement technology)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */

    public function update_procurement_technology($slug, $uid)
    {
        $protechid = $this->input->post("hdn_procurement_technology_id");

        $protechdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_procurement_technology_name"),
            'procurementprocess' => $this->input->post("project_procurement_technology_process"),
            'financialinfo' => $this->input->post("project_procurement_technology_financial_info"),
            'permission' => $this->input->post("project_procurement_technology_permission")


        );
        $response = array();

        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $protechid));

        if ($this->db->update("exp_proj_procurement_technology", $protechdata)) {
            $response["status"] = "success";
            $response["message"] = "Procurement Technology updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "procurement_technology_form";
            $response["redirect"] = '/admin.php/projects/edit_procurement/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Procurement Technology.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);

    }

    /**
     * Delete Procurement technology
     * (delete Procurement technology)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_procurement_technology()
    {

        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_procurement_technology")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Procurement Technology Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Get Procurement services
     * (get Procurement services)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_procurement_services($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_procurement_services", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add Procurement services
     * (add Procurement services)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function add_procurement_services($slug, $uid)
    {
        $proservicesdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_procurement_services_name"),
            'type' => $this->input->post("project_procurement_services_type"),
            'procurementprocess' => $this->input->post("project_procurement_services_process"),
            'financialinfo' => $this->input->post("project_procurement_services_financial_info"),
            'permission' => $this->input->post("project_procurement_services_permission")


        );
        $response = array();

        if ($this->db->insert("exp_proj_procurement_services", $proservicesdata)) {
            $response["status"] = "success";
            $response["message"] = "Procurement Service added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "procurement_services_form";
            $response["redirect"] = '/admin.php/projects/edit_procurement/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Procurement Services.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);

    }

    /**
     * Update Procurement services
     * (update Procurement services)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    json
     */
    public function update_procurement_services($slug, $uid)
    {
        $proservicesid = $this->input->post("hdn_procurement_services_id");
        $proservicesdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'name' => $this->input->post("project_procurement_services_name"),
            'type' => $this->input->post("project_procurement_services_type"),
            'procurementprocess' => $this->input->post("project_procurement_services_process"),
            'financialinfo' => $this->input->post("project_procurement_services_financial_info"),
            'permission' => $this->input->post("project_procurement_services_permission")


        );
        $response = array();

        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $proservicesid));

        if ($this->db->update("exp_proj_procurement_services", $proservicesdata)) {
            $response["status"] = "success";
            $response["message"] = "Procurement Service updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "procurement_services_form";
            $response["redirect"] = '/admin.php/projects/edit_procurement/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating Procurement Services.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     * Delete Procurement services
     * (delete Procurement services)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_procurement_services()
    {
        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_procurement_services")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Procurement Service(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Get Project files
     * (get projct files)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_project_files($slug, $uid = '', $entry_id = '')
    {
        if (isset($entry_id) && $entry_id != '') {
            $this->db->where('id', $entry_id);
        }
        $qryexec = $this->db->get_where("exp_proj_files", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    /**
     * Add Project files
     * (Add projct files)
     *
     * @access    public
     *
     * @param    string ,int,optional
     *
     * @return    json
     */
    public function add_project_files($slug, $uid, $upload = '')
    {
        $filedata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'description' => $this->input->post("project_files_desc"),
            'permission' => $this->input->post("project_files_permission"),
            'dateofuploading' => date('Y-m-d')
        );
        if ($upload['error'] == '') {
            $filedata['file'] = $upload['file_name'];
            $filedata['filesize'] = $upload['file_size'];
        }

        $response = array();

        if ($this->db->insert("exp_proj_files", $filedata)) {
            $response["status"] = "success";
            $response["message"] = "File added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["listdiv"] = "files_form";
            $response["redirect"] = '/admin.php/projects/edit_files/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding this Files.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);

    }

    /**
     * Update Project files
     * (update projct files)
     *
     * @access    public
     *
     * @param    string ,int,optional
     *
     * @return    josn
     */
    public function update_project_files($slug, $uid, $upload = '')
    {
        $fileid = $this->input->post("hdn_project_files_id");
        $filedata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'description' => $this->input->post("project_files_desc"),
            'permission' => $this->input->post("project_files_permission")
        );

        if ($upload['error'] == '') {
            $filedata['file'] = $upload['file_name'];
            if (isset($upload['file_size']) && $upload['file_size'] != '') {
                $filedata['filesize'] = $upload['file_size'];
            }
        }


        $response = array();
        $this->db->where(array('uid' => $uid, 'slug' => $slug, 'id' => $fileid));

        if ($this->db->update("exp_proj_files", $filedata)) {
            $response["status"] = "success";
            $response["message"] = "File updated successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["listdiv"] = "files_form";
            $response["redirect"] = '/admin.php/projects/edit_participants/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating this Files.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);

    }

    /**
     * Delete Project files
     * (Delete projct files)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_project_files()
    {
        //$unlinkstatus = $this->unlink_files($delid,$uid,'file','exp_proj_files',PROJECT_IMAGE_PATH);

        $delids = $this->input->get("delids");
        if (count($delids) > 0) {
            $response = array();
            $this->db->where_in("id", $delids);
            if ($this->db->delete("exp_proj_files")) {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "File(s) Deleted Successfully";
            }

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /*********************************************************************************************************
     * Load functions collect each table data from database and prepare array for GET FUNCTION for ajax call
     * (Load functions for listing above each add forms dynamically)
     *********************************************************************************************************/


    public function load_executive($formname, $entryid, $slug, $uid)
    {
        $array_load['executive_data'] = $this->get_executives($slug, $uid, $entryid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_organization($formname, $entryid, $slug, $uid)
    {
        $array_load['organization_data'] = $this->get_organizations($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_engineering($formname, $entryid, $slug, $uid)
    {
        $array_load['engineering_data'] = $this->get_engineering($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_map_point($formname, $entryid, $slug, $uid)
    {
        $array_load['map_point_data'] = $this->get_map_point($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_design_issue($formname, $entryid, $slug, $uid)
    {
        $array_load['design_issue_data'] = $this->get_design_issue($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_environment($formname, $entryid, $slug, $uid)
    {
        $array_load['environment_data'] = $this->get_environment($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_studies($formname, $entryid, $slug, $uid)
    {
        $array_load['studies_data'] = $this->get_studies($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_fund_sources($formname, $entryid, $slug, $uid)
    {
        $array_load['fund_sources_data'] = $this->get_fund_sources($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_roi($formname, $entryid, $slug, $uid)
    {
        $array_load['roi_data'] = $this->get_roi($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_critical_participants($formname, $entryid, $slug, $uid)
    {
        $array_load['critical_participants_data'] = $this->get_critical_participants($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_project_regulatory($formname, $entryid, $slug, $uid)
    {
        $array_load['regulatory_data'] = $this->get_project_regulatory($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_participants_public($formname, $entryid, $slug, $uid)
    {
        $array_load['participants_public_data'] = $this->get_participants_public($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_participants_political($formname, $entryid, $slug, $uid)
    {
        $array_load['participants_political_data'] = $this->get_participants_political($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_participants_companies($formname, $entryid, $slug, $uid)
    {
        $array_load['participants_companies_data'] = $this->get_participants_companies($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_participants_owners($formname, $entryid, $slug, $uid)
    {
        $array_load['participants_owners_data'] = $this->get_participants_owners($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_project_machinery($formname, $entryid, $slug, $uid)
    {
        $array_load['machinery_data'] = $this->get_machinery($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_procurement_technology($formname, $entryid, $slug, $uid)
    {
        $array_load['procurement_technology_data'] = $this->get_procurement_technology($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_procurement_services($formname, $entryid, $slug, $uid)
    {
        $array_load['procurement_services_data'] = $this->get_procurement_services($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_project_files($formname, $entryid, $slug, $uid)
    {
        $array_load['project_files_data'] = $this->get_project_files($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_project_comment($formname, $entryid, $slug, $uid)
    {
        $array_load['project_comment_data'] = $this->get_project_comment($slug, $uid, $entryid);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    public function load_project_assessment($formname, $entryid, $slug, $uid)
    {
        $array_load['project_assessment'] = $this->get_assessment_data($slug);
        $array_load['loadtype'] = $formname;
        $array_load['slug'] = $slug;

        return $array_load;
    }

    /*****************************************************************************************************
     * GET Functions collect each array set define above and sent to the loder.php in view directory
     * (Load functions for listing above each add forms dynamically)
     *****************************************************************************************************/

    public function get_fundamental_data($slug)
    {
        $fundamental_data = array();
        $this->db->select(array('pid', 'stage', 'uid', 'projectname', 'slug', 'fundamental_legal'));
        $qryproj = $this->db->get_where("exp_projects", array("slug" => $slug));
        $fundamental_data = $qryproj->row_array();
        $qryproj->free_result();
        $fundamental_data['engineering'] = $this->get_engineering($slug);
        $fundamental_data['map_point'] = $this->get_map_point($slug);
        $fundamental_data['design_issue'] = $this->get_design_issue($slug);
        $fundamental_data['environment'] = $this->get_environment($slug);
        $fundamental_data['studies'] = $this->get_studies($slug);
        $fundamental_data['totalfundamental'] = (count($fundamental_data['engineering']) + count(
                $fundamental_data['map_point']
            ) + count($fundamental_data['design_issue']) + count($fundamental_data['environment']) + count(
                $fundamental_data['studies']
            ));

        return $fundamental_data;
    }

    public function get_financial_data($slug)
    {
        $financial_data = array();
        $this->db->select(array('pid', 'stage', 'uid', 'projectname', 'slug'));
        $qryproj = $this->db->get_where("exp_projects", array("slug" => $slug));
        $financial_data = $qryproj->row_array();
        $qryproj->free_result();
        $financial_data['financial'] = $this->get_financial($slug);
        $financial_data['fund_sources'] = $this->get_fund_sources($slug);
        $financial_data['roi'] = $this->get_roi($slug);
        $financial_data['critical_participants'] = $this->get_critical_participants($slug);
        $financial_data['totalfinancial'] = (count($financial_data['financial']) + count(
                $financial_data['fund_sources']
            ) + count($financial_data['roi']) + count($financial_data['critical_participants']));

        return $financial_data;

    }

    public function get_regulatory_data($slug)
    {
        $regulatory_data = array();

        $this->db->select(array('pid', 'stage', 'uid', 'projectname', 'slug'));
        $qryproj = $this->db->get_where("exp_projects", array("slug" => $slug));
        $regulatory_data = $qryproj->row_array();
        $qryproj->free_result();
        $regulatory_data['regulatory'] = $this->get_project_regulatory($slug);
        $regulatory_data['totalregulatory'] = (count($regulatory_data['regulatory']));


        return $regulatory_data;

    }

    public function get_participants_data($slug)
    {
        $participants_data = array();

        $this->db->select(array('pid', 'stage', 'uid', 'projectname', 'slug'));
        $qryproj = $this->db->get_where("exp_projects", array("slug" => $slug));
        $participants_data = $qryproj->row_array();
        $qryproj->free_result();
        $participants_data['public'] = $this->get_participants_public($slug);
        $participants_data['political'] = $this->get_participants_political($slug);
        $participants_data['companies'] = $this->get_participants_companies($slug);
        $participants_data['owners'] = $this->get_participants_owners($slug);
        $participants_data['totalparticipants'] = (count($participants_data['public']) + count(
                $participants_data['political']
            ) + count($participants_data['companies']) + count($participants_data['owners']));

        return $participants_data;
    }

    public function get_procurement_data($slug)
    {
        $procurement_data = array();

        $this->db->select(array('pid', 'stage', 'uid', 'projectname', 'slug'));
        $qryproj = $this->db->get_where("exp_projects", array("slug" => $slug));
        $procurement_data = $qryproj->row_array();
        $qryproj->free_result();
        $procurement_data['machinery'] = $this->get_machinery($slug);
        $procurement_data['procurement_technology'] = $this->get_procurement_technology($slug);
        $procurement_data['procurement_services'] = $this->get_procurement_services($slug);
        $procurement_data['totalprocurement'] = (count($procurement_data['machinery']) + count(
                $procurement_data['procurement_technology']
            ) + count($procurement_data['procurement_services']));

        return $procurement_data;

    }

    public function get_files_data($slug)
    {
        $files_data = array();

        $this->db->select(array('pid', 'stage', 'uid', 'projectname', 'slug'));
        $qryproj = $this->db->get_where("exp_projects", array("slug" => $slug));
        $files_data = $qryproj->row_array();
        $qryproj->free_result();
        $files_data['files'] = $this->get_project_files($slug);
        $files_data['totalfiles'] = count($files_data['files']);


        return $files_data;
    }


    /**
     * function for unlink
     *
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function unlink_files($dbid, $uid, $dbfield, $dbtable, $path)
    {
        $this->db->select($dbfield);
        $qryfile = $this->db->get_where($dbtable, array("id" => $dbid, "uid" => $uid));
        $files = $qryfile->row_array();
        $qryfile->free_result();

        if (isset($files[$dbfield]) && $files[$dbfield] != '') {
            $unlink_path = "./" . $path . $files[$dbfield];
            if (file_exists($unlink_path)) {
                $unlink_file = unlink($unlink_path);
                return $unlink_file;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * Get Ads
     * (get list of ads added in db)
     *
     * @access    public
     * @return    array
     */
    public function get_ad_data()
    {
        $adarr = array();
        $this->db->select(array("adimage", "adurl"));
        $qryad = $this->db->get_where("exp_advertisement", array("status" => "1"));
        $totalad = $qryad->num_rows();
        $adarr["data"] = $qryad->result_array();

        $adarr["totalad"] = $totalad;

        return $adarr;
    }

    /**
     * Add comment
     * (Add comment to project )
     *
     * @access    public
     *
     * @param strin
     *
     * @return    json
     */
    public function add_comment($slug)
    {
        $response = array();
        $insertdata = array(
            "uid" => sess_var("uid"),
            "comment" => $this->input->post("comment"),
            "slug" => $slug,
            "commentdate" => date("Y-m-d H:i:s")
        );


        if ($this->db->insert("exp_proj_comment", $insertdata)) {
            $response["status"] = "success";
            $response["message"] = "Comment added successfully.";
            $response["remove"] = true;
            $response["isload"] = "yes";
            $response["isreset"] = "yes";
            $response["loadurl"] = "/projects/form_load/project_comment/view/" . $slug . "";
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Comment.";
            $response["remove"] = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);

    }

    /**
     * Get Project comment
     * (get projct comments)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_project_comment($slug)
    {
        $this->db->order_by("commentdate", "desc");
        $qryexec = $this->db->get_where("exp_proj_comment", array("slug" => $slug));
        $execarr = $qryexec->result_array();
        $qryexec->free_result();

        return $execarr;
    }

    public function delete_comment($id)
    {
        $response = array();
        $this->db->delete("exp_proj_comment", array("id" => $id));

        $response["status"] = "success";
        $response["remove"] = true;

        //header('Content-type: application/json');
        echo json_encode($response);
    }


    /**
     * Get top experts
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_top_experts($slug)
    {
        $prd_sector = $this->get_sector_from_project($slug);

        /*$this->db->join('exp_members as m','m.uid = es.uid', 'inner');
		$where1 = "(es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')";
		$this->db->where($where1);
		$where2 = "(es.sector='".$prd_sector['sector']."')";
		$this->db->or_where($where2);
		$where3 = "(es.subsector='".$prd_sector['subsector']."')";
		$this->db->or_where($where3);
		$this->db->group_by("es.uid");
		$this->db->order_by("es.sector", "desc");
		$this->db->order_by("es.subsector", "desc");
		

		$query_userlist = $this->db->get('exp_expertise_sector as es');*/
        $query_userlist = $this->db->query(
            "select  * from (
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid 
		WHERE 
		(es.sector='" . $prd_sector['sector'] . "' AND es.subsector='" . $prd_sector['subsector'] . "') 
		AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.membertype = '5' 
		union
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid 
		WHERE 
		es.sector='" . $prd_sector['sector'] . "' AND es.subsector !='" . $prd_sector['subsector'] . "' 
		AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.membertype = '5'
		union
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid 
		WHERE 
		es.sector !='" . $prd_sector['sector'] . "' AND es.subsector ='" . $prd_sector['subsector'] . "'
		AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.membertype = '5' 
		)
		as s
		"
        );
        $execarr = $query_userlist->result_array();
        $query_userlist->free_result();
        return $execarr;
    }


    /**
     * Get sector/subsector of given project
     *
     *
     * @access    public
     *
     * @param    int
     *
     * @return    array
     */
    public function get_sector_from_project($slug)
    {
        $this->db->where('slug', $slug);
        $this->db->where('isdeleted', '0');
        $this->db->select("sector,subsector");
        $query_sector_qryproj = $this->db->get('exp_projects');
        $result_sector_qryproj = $query_sector_qryproj->row_array();
        return $result_sector_qryproj;
    }

    /**
     * Add CG/LA Assessment
     * Add new CG/LA Assessment for selected project
     *
     * @access    public
     *
     * @param    int
     *
     * @return    array
     */
    public function add_assessment($slug, $uid)
    {

        $t = 'exp_proj_assessment';
        $where = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid
        );
        $assessmentdata = array(
            'competitors' => $this->input->post("project_assessment_competitors"),
            'drivers' => $this->input->post("project_assessment_drivers"),
            'analysis' => $this->input->post("project_assessment_analysis")
        );

        $response = array();

        if ($this->db->where($where)->get($t)->num_rows() > 0) {
            $this->db->where($where);
            $save = $this->db->update($t, $assessmentdata);
        } else {
            $new = array_merge($where, $assessmentdata);
            $save = $this->db->insert($t, $new);
        }


        if ($save) {
            $response["status"] = "success";
            $response["message"] = "Assessment added successfully.";
            $response["remove"] = true;
            $response["redirect"] = '/admin.php/projects/edit/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Assessment.";
            $response["remove"] = true;

        }

        //header('Content-type: application/json');
        echo json_encode($response);

    }

    /**
     * Update assessment
     * (update assessment)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function update_assessment($slug, $uid)
    {

        $assessmentdata = array(
            'pid' => $this->check_user_project($slug, $uid),
            'slug' => $slug,
            'uid' => $uid,
            'competitors' => $this->input->post("project_assessment_competitors"),
            'drivers' => $this->input->post("project_assessment_drivers"),
            'analysis' => $this->input->post("project_assessment_analysis")
        );

        $response = array();
        if ($this->db->update("exp_proj_assessment", $assessmentdata)) {
            $response["status"] = "success";
            $response["message"] = "Assessment added successfully.";
            $response["remove"] = true;
            $response["redirect"] = '/admin.php/projects/edit/' . $slug;
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while adding Assessment.";
            $response["remove"] = true;

        }

        //header('Content-type: application/json');
        echo json_encode($response);

    }

    /**
     * delete assessment
     * (delete assessment)
     *
     * @access    public
     *
     * @param    int ,int
     *
     * @return    json
     */
    public function delete_assessment($id)
    {
        $response = array();
        $this->db->delete("exp_proj_assessment", array("id" => $id));

        $response["status"] = "success";
        $response["remove"] = true;

        //header('Content-type: application/json');
        echo json_encode($response);

    }


    /**
     * Get Project assessment
     * (get projct assessment)
     *
     * @access    public
     *
     * @param    string ,int
     *
     * @return    array
     */
    public function get_assessment_data($slug)
    {
        //$this->db->order_by("assessmentdate", "desc"); 
        $qryexec = $this->db->order_by("id", "desc")->get_where("exp_proj_assessment", array("slug" => $slug));
        $execarr = $qryexec->row_array(0);
        $qryexec->free_result();

        return $execarr;
    }

    /*
         * Find pid from slug
         *
         *
         * @access 	public
         * @param 	string
         * @return 	boolean
        */
    public function get_pid_from_slug($slug)
    {
        $this->db->select("pid");
        $qrycheck = $this->db->get_where("exp_projects", array("slug" => $slug, "isdeleted" => "0"));
        if ($qrycheck->num_rows > 0) {
            $objproject = $qrycheck->row_array();
            $pid = $objproject["pid"];
            return $pid;
        } else {
            return "";
        }
    }

    /**
     * batch_geocode
     *
     * @access    public
     * @return    boolean/string
     */
    public function batch_geocode($slug = false)
    {


        //$qry = $this->db->update('exp_projects',array('geocode' => NULL, 'lat' => NULL, 'lng' => NULL) );
        //echo "<pre>"; var_dump( $qry ); exit;

        $this->load->library('mapquest');

        if ($slug) {
            $qry = $this->db->where('slug', $slug)->get('exp_projects', 50);
        } else {
            $qry = $this->db->where("geocode IS NULL")->get('exp_projects', 50);
        }


        foreach ($qry->result() as $i => $row) {

            $location = trim($row->location . ' ' . $row->country);

            if ($location == '') {
                $this->db->where('slug', $row->slug)->update('exp_projects', array('geocode' => '[]'));
                continue;
            }

            $location = urlencode($location);

            $data = $this->mapquest->geocode($location)->json_raw;

            // create insert obj
            $insert_data = array();
            $insert_data['geocode'] = $data;

            $json = $this->mapquest->geocode($location)->json_obj;

            if ($json && count($json->results) > 0 && count($json->results[0]->locations) > 0) {
                //echo "<pre>"; var_dump( $json ); exit;
                $loc1 = $json->results[0]->locations[0];
                $insert_data['lat'] = $loc1->latLng->lat;
                $insert_data['lng'] = $loc1->latLng->lng;

            }
            //echo "<pre>"; var_dump( $row->pid, $insert_data ); exit;

            $update = $this->db->where('slug', $row->slug)->update('exp_projects', $insert_data);


            sleep(2);
        }

    }
}
