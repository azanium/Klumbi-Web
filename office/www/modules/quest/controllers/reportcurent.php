<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reportcurent extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Report Current Quest","module5",FALSE,TRUE,"home");
    }
    function index()
    {
        $dataadd['countperactive']=$this->__list_datadractive();
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"Quest Current",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("report_curent",$dataadd);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function __list_datadractive()
    {
        $this->mongo_db->select_db("Game");
        $data=$this->mongo_db->command_values(array('distinct' => "QuestActive", 'key' => "userid"));
        $output = array();
        foreach($data['values'] as $dt)
        {
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("QuestActive");
            $count=$this->mongo_db->count(array('userid' => $dt));
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Properties");
            $dataproperties=$this->mongo_db->findOne(array('lilo_id' => (string)$dt));
            $this->mongo_db->select_collection("Account");
            $datamember=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($dt)));
            $fullname="";
            $twitter="";
            $phone="";
            if($dataproperties)
            {
                $fullname=isset($dataproperties['fullname'])?$dataproperties['fullname']:"";
                $twitter=isset($dataproperties['twitter'])?$dataproperties['twitter']:"";
                $phone=isset($dataproperties['handphone'])?$dataproperties['handphone']:"";;
            }
            $output[] = array(
                '_id'=>$dt,
                'fullname'=>$fullname,
                'email'=>$datamember['email'],
                'twitter'=>$twitter,
                'phone'=>$phone,
                'count'=>"Player yang masih memainkan ".$count." Quest",
            );         
        }  
	return  $output;
    }
}



