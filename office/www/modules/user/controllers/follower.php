<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Follower extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->actions("User","module2","View Follower",FALSE,TRUE,"home");
    }
    function index($iduser="")
    {        
        $datapage['iduser']=$iduser;
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
        $this->template_admin->header_web(TRUE,"View User Follower",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("userfollower_view",$datapage);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data($iduser="")
    {
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social");
        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
        $sSearch=(isset($_GET['sSearch']))?$_GET['sSearch']:"";
        $sSortDir_0=(isset($_GET['sSortDir_0']))?$_GET['sSortDir_0']:"asc";
        $pencarian=array('user_id'=>$iduser,'type'=>'Follower');
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array('datetime'=>-1));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $tgl="";
            if(isset($dt['datetime']) && $dt['datetime']!="")
            {
                $tgl=date('Y-m-d H:i:s', $dt['datetime']->sec);
            }
            $email="";
            $username="";
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");
            $temp_user=$this->mongo_db->findOne(array('_id'=>$this->mongo_db->mongoid((string)$dt['friend_id'])));
            if($temp_user)
            {
                $email=(!isset($temp_user['email'])?"":$temp_user['email']); 
                $username=(!isset($temp_user['username'])?"":$temp_user['username']); 
            }
            $output['aaData'][] = array(
                $i,
                "<a href='#' onclick='lihatdetail(\"".$dt['friend_id']."\");return false;'>".$email." / ".$username."</a>",
                $tgl,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('user/follower/index/'.$iduser); 
        }
    }
}