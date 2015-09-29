<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Avatarconfig extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('pagination','form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Avatar Conf Report","module13",FALSE,TRUE,"home");
    }
    function index()
    {
        $start = (isset($_GET["per_page"])?$_GET["per_page"]:0);
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/css/according.css",
        );
        $js=array(
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/Highcharts-2.3.3/js/highcharts.js", 
            base_url()."resources/plugin/Highcharts-2.3.3/js/modules/exporting.js", 
        );
        $this->template_admin->header_web(TRUE,"Avatar Mix Report",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");        
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("AvatarMix");
        $config['base_url'] = $this->template_admin->link("reporting/avatarconfig/index","?page=avatarmix&dt=list");
        $config['total_rows'] = $this->mongo_db->count2(array());
        $config['uri_segment'] = 4;
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
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
        $isiform['paging']= $this->pagination->create_links();
        $isiform['count']= $config['total_rows'];
        $isiform['listconfig']=$this->mongo_db->find(array(),(int)$start,$config['per_page'],array('last_update'=>1));
        $isiform['startfrom'] = (int)$start;
        $this->load->view("avatarconfig_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
}

