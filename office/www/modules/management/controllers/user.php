<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->load->model("m_userdata");
        $this->m_checking->module("User Akses","module2",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Group");        
        $isiform['listgroup']=$this->mongo_db->find(array(),0,0,array('Name'=>1));
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Brand");
        $isiform['listbrand']=$this->mongo_db->find(array(),0,0,array('name'=>1));
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
        $this->template_admin->header_web(TRUE,"User Access",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("user_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
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
                'username'=>$filter,
            );
        }
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array('username'=>1));
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
            $priview="";
            if($this->m_checking->actions("User Akses","module2","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete User Access ".$dt['username']."')","",'Revoke User Access',"group_delete.png","","linkdelete");
            }
            if($this->m_checking->actions("User Akses","module2","Edit",TRUE,FALSE,"home"))
            {
                $priview=$this->template_icon->detail_onclick("lihatdetail('".$dt['_id']."','".(isset($dt['access'])?$dt['access']:'')."','".(isset($dt['brand_id'])?$dt['brand_id']:'')."')","#editdata",'Edit',"group_edit.png","","","data-toggle='modal'");
            }
            $group=""; 
            $brand="";
            if(isset($dt['access']))
            {
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Group");
                $checkdata=$this->mongo_db->findOne(array('Code'=>$dt['access']));
                if($checkdata)
                {
                    $group=$checkdata['Name'];
                }                
            }
            if(isset($dt['brand_id']))
            {
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Brand");
                $checkdata=$this->mongo_db->findOne(array('brand_id'=>$dt['brand_id']));
                if($checkdata)
                {
                    $brand=$checkdata['name'];
                }     
                else
                {
                    $brand=$dt['brand_id'];
                }
            }
            $tempdtuser = $this->m_userdata->user_properties((string)$dt["_id"]);
            $output['aaData'][] = array(
                $i,
                $tempdtuser["fullname"],
                $tempdtuser["handphone"],
                $dt['username'],
                $dt['email'],
                $group,
                $brand,
                $priview.$delete,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('management/user/index'); 
        }
    }
    function cruid_user($id="")
    {
        $this->form_validation->set_rules('txtid','User ID','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('group','Group Name','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('brand','Brand Name','trim|htmlspecialchars|xss_clean');
        $output['message'] = "<i class='error'>Sorry, You are not allowed to access this page</i>";
        $output['success'] = FALSE;
        $url = current_url();
        $user = $this->session->userdata('username');
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            if($this->m_checking->actions("User Akses","module2","Delete",TRUE,FALSE,"home") || $this->m_checking->actions("User Akses","module2","Edit",TRUE,FALSE,"home"))
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("Account");
                $id = $this->input->post('txtid',TRUE);
                $group = $this->input->post('group',TRUE);
                $brand = $this->input->post('brand',TRUE);
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>array('access'=>$group,'brand_id'=>$brand)));
                $output['message'] = "<i class='success'>Data is updated</i>";
                $this->m_user->tulis_log("Update User to Group",$url,$user);
                $output['success'] = TRUE;
            }            
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('management/user/index'); 
        }
    }
}
