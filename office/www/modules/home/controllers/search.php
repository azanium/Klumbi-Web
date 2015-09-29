<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Search extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->library(array('pagination'));
        $this->load->helper('text');
        $this->load->model("m_userdata");
    }
    function index($numberpage=0)
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Properties");
        $sSearch=(isset($_GET['q']))?$_GET['q']:"";
        $pencarian=array();
        if($sSearch!="")
        {
            $sSearch=(string) trim($sSearch);
            $sSearch = quotemeta($sSearch);
            $regex = "/$sSearch/i";
            $filter = $this->mongo_db->regex($regex);
            $pencarian= array(
                'fullname'=>$filter,
            );
        }
        $config['base_url'] = base_url()."home/search";
        $config['total_rows'] = $this->mongo_db->count2($pencarian);
        $config['uri_segment'] = 4;
        $config['per_page'] = 10;
        $config['full_tag_open'] = '<div><ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_link'] = '<i class="icon-long-arrow-left"></i> Newer';
        $config['first_tag_open'] = '<li class="previous">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Older <i class="icon-long-arrow-right"></i>';
        $config['last_tag_open'] = '<li class="next">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="icon-double-angle-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="icon-double-angle-left"></i>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="disabled active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $datapage['paging']= $this->pagination->create_links();
        $datapage['count']= $config['total_rows'];
        $datapage['datalist'] = $this->mongo_db->find($pencarian,0,0,array('join_date'=>1));        
        $css=array(
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
        );
        $js=array(
            base_url()."resources/plugin/charts-chartjs/Chart.min.js"
        );
        $this->template_admin->header_web(TRUE,"Search Page",$css,$js,TRUE,"");
        $this->template_admin->headerbar();
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");  
        $this->load->view("search_view",$datapage);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
}
