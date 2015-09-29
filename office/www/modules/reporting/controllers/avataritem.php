<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Avataritem extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('pagination','form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Avatar Items Report","module13",FALSE,TRUE,"home");
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
            base_url()."resources/plugin/datatables/dataTables.css",
        );
        $js=array(
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/Highcharts-2.3.3/js/highcharts.js", 
            base_url()."resources/plugin/Highcharts-2.3.3/js/modules/exporting.js", 
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
        );
        $this->template_admin->header_web(TRUE,"Avatar Item Report",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");        
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");
        $config['base_url'] = $this->template_admin->link("reporting/avataritem/index","?page=avataritem&dt=list");
        $config['total_rows'] = $this->mongo_db->count2(array());
        $config['uri_segment'] = 4;
        $config['page_query_string'] = TRUE;
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
        $isiform['paging']= $this->pagination->create_links();
        $isiform['count']= $config['total_rows'];
        $isiform['listconfig']=$this->mongo_db->find(array(),(int)$start,$config['per_page'],array('name'=>1));
        $isiform['startfrom'] = (int)$start;
        $this->load->view("avataritem_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer(); 
    }
    function list_data()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");
        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
        $sSortDir_0=(isset($_GET['sSortDir_0']))?$_GET['sSortDir_0']:"desc";
        $iSortCol_0=(isset($_GET['iSortCol_0']))?(int)$_GET['iSortCol_0']:0;
        $sSearch=(isset($_GET['sSearch']))?$_GET['sSearch']:"";
        $pencarian=array();
        if($sSearch!="")
        {
            $sSearch=(string) trim($sSearch);
            $sSearch = quotemeta($sSearch);
            $regex = "/$sSearch/i";
            $filter=$this->mongo_db->regex($regex);
            $pencarian=array(
                "name"=>$filter,
            );
        }
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array('name'=>1));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $childname=(!isset($dt['name'])?"":$dt['name']);
            $childtipe=(!isset($dt['tipe'])?"":$dt['tipe']);
            $childgender=(!isset($dt['gender'])?"":$dt['gender']);
            $childcategory=(!isset($dt['category'])?"":$dt['category']);
            $childpayment=(!isset($dt['payment'])?"":$dt['payment']);
            $childbrand=(!isset($dt['brand_id'])?"":$dt['brand_id']);            
            $childcolor= (!isset($dt['color'])?"":$dt['color']);
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");
            $totallike = (int)$this->mongo_db->count(array('avatar_id'=>(string)$dt['_id'],'type'=>'AvatarItemLove'));  
            $totalcomment = (int)$this->mongo_db->count(array('avatar_id'=>(string)$dt['_id'],'type'=>'AvatarItemComment')); 
            $output['aaData'][] = array(
                $i,
                $childname, 
                $childtipe,
                $childgender,
                $childcategory,                
                $childpayment,                
                $childbrand,
                $totallike,
                $totalcomment,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('reporting/avataritem/index'); 
        }
    }
}

