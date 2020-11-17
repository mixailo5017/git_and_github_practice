<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class PublicProfiles extends CI_Controller
{
    private $sort_options;

    public function __construct()
    {
        parent::__construct();

        // Load language file
        get_language_file('english');

        $this->sort_options = array(
            1 => lang('SortAlphabetically'),
            2 => lang('SortRecentlyUpdatedFirst'),
            3 => 'Most Liked'
        );

        //Load the default model for this controller
        $this->load->model('projects_model');
    }

    public function projects($slug)
    {
        if (! PROJECT_PROFILES_ENABLED) show_404();

        $this->redirectAuthenticatedUsersToFullProfile($slug);

        // Cache this page for PUBLIC_PROFILE_TTL minutes
        //$this->output->cache(PUBLIC_PROFILE_TTL);

        $project = $this->projects_model->find_public($slug);

        // If a project doesn't exist show 404
        if (empty($project)) show_404();

        $project['photo_src'] = project_image($project['projectphoto'], 198);
        $project_executive['photo_src'] = expert_image('', 198);

        $page = array(
            'view' => 'project',
            'title' => build_title($project['projectname']),
            'header' => array(),
            'content' => compact('project', 'project_executive'),
            'footer' => array()
        );

        $this->load->view('public/layout', $page);
    }

    private function redirectAuthenticatedUsersToFullProfile($slug)
    {
        if ($this->auth->check()) redirect("/projects/$slug");
    }

    /**
     * Listing Method
     * Method call for Project Listing Page
     *
     * @access public
     */
    public function index()
    {
        $limit = view_check_limit($this->input->get_post('limit', true));
        $offset	= $this->input->get_post('per_page', true);
        if (empty($offset)) {
            $offset = 0;
        }
        $sort = $this->check_sort($this->input->get_post('sort', true));

        $filter = array(
            'country' => $this->input->get_post('country', true),
            'sector' => $this->input->get_post('sector', true),
            'subsector' => $this->input->get_post('subsector', true),
            'stage' => $this->input->get_post('stage', true),
            'searchtext' => $this->input->get_post('searchtext', true),
        );
        array_walk($filter, function (&$value, $key) {
            $value = $value ? : '';
        });

        $projects = $this->projects_model->all($limit, $offset, $filter, $sort);
        $total = count($projects) > 0 ? (int) $projects[0]['row_count'] : 0;

        $sector_data = sector_subsectors();
        $subsectors = array();
        if (! empty($subsector)) {
            if (isset($sector_data[$subsector])) {
                $subsectors = $sector_data[$subsector];
            }
        }

        $config = array(
            'base_url' => '/publicprofiles/?' . http_build_query(array_merge($filter, compact('sort', 'limit'))),
            'total_rows' => $total,
            'per_page' => $limit,
            'num_links' => 1,
            'next_link' => lang('Next') . '  ' . '&gt;',
            'prev_link' => '&lt;' . '  ' . lang('Prev'),
            'first_link' => lang('First'),
            'last_link' =>  lang('Last'),
            'page_query_string' => true
        );
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'projects'     => $projects,
            'total'        => $total,
            'subsectors'   => $subsectors,
            'all_subsectors'   => $sector_data,
            'filter'       => $filter,
            'sort_options' => $this->sort_options,
            'sort'         => $sort,
            'limit'        => $limit,
            'paging'       => $this->pagination->create_links(),
            'page_from'    => $offset + 1,
            'page_to'      => ($offset + $limit <= $total) ? $offset + $limit : $total,
        );

        // Analytics
        // Check if we have any serach filters setup
        if (count(array_filter($filter)) > 0) {
            $event_properties = array(
                'Category' => 'Project',
                'Found' => $total
            );
            foreach ($filter as $key => $value) {
                if (! empty($value)) {
                    $event_properties[ucfirst($key)] = $value;
                }
            }

            $this->headerdata['page_analytics'] = array(
                'event' => array(
                    'name' => 'Searched',
                    'properties' => $event_properties
                )
            );
        }

        // Render the page
        $page = array(
            'view' => 'index',
            'title' => build_title('GViP | Infrastructure Project Profiles'),
            'header' => array(),
            'footer' => array(),
            'data'   => $data
        );
        $this->load->view('public/index', $page);
    }

    private function check_sort($value)
    {
        $allowed = array_keys($this->sort_options);
        $default = 2;

        if (in_array($value, $allowed)) {
            return $value;
        } else {
            return $default;
        }
    }
}
