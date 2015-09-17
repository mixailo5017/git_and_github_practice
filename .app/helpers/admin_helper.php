<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

//check if variable exists and return it's result
if (! function_exists('get_value'))
{
    function get_value($variable)
    {
        return isset($variable) ? $variable : '';
    }
}

function membergrouplist()
{
    $CI =& get_instance();

    $result_group = array();
    $CI->db->where(array("status"=>"1"));
    $query_group = $CI->db->get('exp_member_type');
    if ($query_group->num_rows() > 0)
    {
        foreach($query_group->result_array() as $row)
        {
            $result_group[$row['typename']]	=	$row['typename'];
        }
    }
    return $result_group;
}

function membergrouplist_Add()
{
    $CI =& get_instance();

    $result_group = array();
    $CI->db->where(array("status"=>"1",'typeid !='=>'8'));
    $query_group = $CI->db->get('exp_member_type');
    if ($query_group->num_rows() > 0)
    {
        foreach($query_group->result_array() as $row)
        {
            $result_group[$row['typeid']]	=	$row['typename'];
        }
    }
    return $result_group;
}

function get_project_userinfo($uid)
{
    $userarr = array();
    $CI =& get_instance();

    $qryuser = $CI->db->get_where("exp_members",array("uid"=>$uid));
    $userarr = $qryuser->row_array();

    return $userarr;

}

function get_onlineuser()
{
    $userarr = array();
    $CI =& get_instance();

    $CI->db->select("uid,firstname,lastname,forum_attendee,userphoto,lastlogin,lastlogout,membertype,organization");
    $strwhere = "lastlogin > lastlogout AND lastlogout != '0'";
    $CI->db->where($strwhere);
    $CI->db->order_by("lastlogin", "desc");
    $qryuser = $CI->db->get("exp_members");
    $userarr = $qryuser->result_array();
    return $userarr;
}

function get_project_owner_dropdown()
{
    $userarr = array();
    $CI =& get_instance();

    $CI->db->select("uid");
    $qryproj = $CI->db->get("exp_projects");
    $uidarr = $qryproj->result_array();
    foreach($qryproj->result_array() as $row)
    {
        $uid[]	=	$row['uid'];
    }

    $CI->db->select("uid,firstname,lastname");
    $CI->db->where_in("uid",$uid);
    $CI->db->order_by("firstname", "asc");
    $qryuser = $CI->db->get("exp_members");
    foreach($qryuser->result_array() as $row2)
    {
        $userarray[$row2['firstname'].' '.$row2['lastname']]	=	$row2['firstname'].' '.$row2['lastname'];
    }

    return $userarray;
}

function get_all_users_dropdown()
{
    $userarr = array();
    $CI =& get_instance();
    $CI->db->select("uid,firstname,lastname");
    if(sess_var("admin_uid"))
    {
        $CI->db->where_not_in("uid",sess_var("admin_uid"));
    }
    $CI->db->order_by("firstname", "asc");
    $qryuser = $CI->db->get("exp_members");

    $userarray[''] = '- Select One -';

    foreach($qryuser->result_array() as $row2)
    {
        $userarray[$row2['uid']]	=	$row2['firstname'].' '.$row2['lastname'];
    }

    return $userarray;
}

function get_all_projusers_dropdown()
{

    $userarr = array();
    $CI =& get_instance();
    $CI->db->select("uid,firstname,lastname");
    if(sess_var("admin_uid"))
    {
        $CI->db->where_not_in("uid",sess_var("admin_uid"));
        $CI->db->where_not_in("membertype",'8');
    }
    $CI->db->order_by("firstname", "asc");
    $qryuser = $CI->db->get("exp_members");

    $userarray[''] = '- Select One -';

    foreach($qryuser->result_array() as $row2)
    {
        $userarray[$row2['uid']]	=	$row2['firstname'].' '.$row2['lastname'];
    }

    return $userarray;
}

// TODO: replace with time_ago() from lib_helper
function DateDiffernece($date1,$date2,$suffix='')
{
    if( !$date1 || !$date2 || $date2 == '1969-12-31 19:00:00' ) return '&nbsp;';

    $diff = abs(strtotime($date2) - strtotime($date1));

    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

    $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
    $minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
    $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60));

    $returnstr = "";

    if($years > 0) {
        $returnstr = $years." years";
    } elseif($months > 0) {
        $returnstr = $months." months";
    } elseif($days > 0) {
        $returnstr = $days." days";
    } elseif($hours > 0) {
        $returnstr = $hours." hours";
    } elseif($minuts> 0){
        $returnstr = $minuts." minutes";
    } elseif($seconds > 0) {
        $returnstr = $seconds." seconds";
    }

    return $returnstr.$suffix;
}

