<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Current_player extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Current Player","module13",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Concurrent");
        $dataadd['count']=$this->mongo_db->count();
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
        );
        $js=array(
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"Current Player",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("current_player",$dataadd);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function count()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Concurrent");
        $jmldt = $this->mongo_db->count();
        $output = array(
            "message" =>"Data Fail to load",
            "success" =>TRUE,
            "count" =>$jmldt,
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('user/current_player/index'); 
        }
    }
    function list_data()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Concurrent");
        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
        $data=$this->mongo_db->find(array(),$awal,$limit,array('username'=>1));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count(),
		"iTotalDisplayRecords" => $this->mongo_db->count(),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Properties");
            $datadetail=$this->mongo_db->findOne(array('lilo_id' => $dt['user_id']));
            $fullname="";
            $birthday="";
            $sex="";
            if($datadetail)
            {
                $fullname=isset($datadetail['fullname'])?$datadetail['fullname']:"";
                $birthday=isset($datadetail['birthday'])?$datadetail['birthday']:"";
                $sex=isset($datadetail['sex'])?$datadetail['sex']:"";
            }
            $output['aaData'][] = array(
                $i,
                $fullname,
                $birthday,
                $sex,
                $dt['room'],
                $dt['datetime'],
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('user/current_player/index'); 
        }
    }
}
