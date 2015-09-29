<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Deactivated extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("User Deactivated","module2",FALSE,TRUE,"home");
    }
    function index()
    {
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
        );
        $js=array(
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"Deactivated User",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("deactivated");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("DeletedUsers");
        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
        $sSearch=(isset($_GET['sSearch']))?$_GET['sSearch']:"";
        $sSortDir_0=(isset($_GET['sSortDir_0']))?$_GET['sSortDir_0']:"asc";
        $pencarian=array();
        if($sSearch!="")
        {
            $sSearch=(string) trim($sSearch);
            $sSearch = quotemeta($sSearch);
            $regex = "/$sSearch/i";
            $filter=$this->mongo_db->regex($regex);
            $pencarian=array(
                'properties.fullname'=>$filter,
            );
        }
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array('properties.avatarname'=>1));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $delete="";
            $redeactive="";
            $priview="";
            $username=isset($dt['username'])?$datatemp['username']:"";            
            $name="";
            $email="";
            $sex="";
            if(isset($dt['account']))
            {
                $datatemp=$dt['account'];
                $email=isset($datatemp['email'])?$datatemp['email']:"";
            }
            if(isset($dt['properties']))
            {
                $datatemp=$dt['properties'];
                $sex=isset($datatemp['sex'])?$datatemp['sex']:"";
            }
            if(isset($dt['properties']))
            {
                $datatemp=$dt['properties'];
                $name=isset($datatemp['fullname'])?$datatemp['fullname']:"";
            }
            if($this->m_checking->actions("User Deactivated","module2","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete User Deactivated ".$name." ? ')","",'Delete',"delete.png","","linkdelete");
            }
            if($this->m_checking->actions("User Deactivated","module2","set Active",TRUE,FALSE,"home"))
            {
                $redeactive = $this->template_icon->detail_onclick("setactive('".$dt['_id']."','Are you sure want to set Active ".$name." ? ')","",'Set Active Account',"book_previous.png","","linkdelete");
            }
            $output['aaData'][] = array(
                $i,
                $name,
                $email,
                $sex,
                $redeactive.$delete,
            );
            $i++;           
        }  
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('user/deactivated/index'); 
        }
    }
    function setactive($id="")
    {
        $this->m_checking->actions("User Deactivated","module2","set Active",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("DeletedUsers");
        $tempdata = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($tempdata)
        {
            $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
            $this->mongo_db->select_collection("Account"); 
            $this->mongo_db->insert($tempdata["account"]);
            $this->mongo_db->select_collection("Properties");
            $this->mongo_db->insert($tempdata["properties"]);            
            $url = current_url();
            $user = $this->session->userdata('username');
            $this->m_user->tulis_log("Set Active User Account Deactivated",$url,$user);
        }
        $output = array(
            "message" =>"Data is set to active",
            "success" =>TRUE,
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('user/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("User Deactivated","module2","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("DeletedUsers");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete User Deactivated Permanently",$url,$user);
        $output = array(
            "message" =>"Data is deleted",
            "success" =>TRUE,
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('user/deactivated/index'); 
        }
    }
}
