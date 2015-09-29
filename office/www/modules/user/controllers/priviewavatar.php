<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Priviewavatar extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->load->model("m_avatar");
        $this->m_checking->actions("User","module2","View Avatar Mix",FALSE,TRUE,"home");
    }
    function index($iduser="")
    {        
        if($iduser==="")
        {
            $iduser = (string)$this->session->userdata('user_id');
        }
        $add_properties['id_user']=$iduser;
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/css/avatareditor.css",
        );
        $js=array(
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"User Mix",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("priviewavatar_view",$add_properties);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data($iduser="")
    {
        if($iduser==="")
        {
            $iduser = (string)$this->session->userdata('user_id');
        }
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("AvatarMix");
        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
        $sSortDir_0=(isset($_GET['sSortDir_0']))?$_GET['sSortDir_0']:"desc";
        $iSortCol_0=(isset($_GET['iSortCol_0']))?(int)$_GET['iSortCol_0']:0;
        $jns_sorting=-1;
        if($sSortDir_0=="asc")
        {
            $jns_sorting=1;
        }
        $keysearchdt="name";
        if($iSortCol_0==1)
        {
            $keysearchdt="name";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="description";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="date";
        }
        $sSearch=(isset($_GET['sSearch']))?$_GET['sSearch']:"";
        $pencarian=array();
        if($sSearch!="")
        {
            $sSearch=(string) trim($sSearch);
            $sSearch = quotemeta($sSearch);
            $regex = "/$sSearch/i";
            $filter=$this->mongo_db->regex($regex);
            $pencarian=array(
                $keysearchdt=>$filter,
            );
        }
        $pencarian = array_merge($pencarian,array("user_id"=>$iduser));
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array($keysearchdt=>$jns_sorting,'date'=>-1));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $subject=(!isset($dt['name'])?"":$dt['name']);
            $descriptions=(!isset($dt['description'])?"":$dt['description']);
            $gender=(!isset($dt['gender'])?"":$dt['gender']);
            $detail=$this->template_icon->detail_onclick("lihatconfigurasi('".$dt['_id']."')","",'Preview',"zoom.png","","linkdetail");
            $data_like="";
            $data_comments="";
            if($this->m_checking->actions("User","module2","View Avatar Configuration Likes",TRUE,FALSE,"home"))
            {
                $data_like=$this->template_icon->link_icon_notext("user/avatarcollection/datalike/".$iduser."/".$dt['_id'],'Like ',"heart.png");
            }
            if($this->m_checking->actions("User","module2","View Avatar Configuration Comments",TRUE,FALSE,"home"))
            {
                $data_comments=$this->template_icon->link_icon_notext("user/avatarcollection/datacomments/".$iduser."/".$dt['_id'],'Comment',"comments.png");
            }
            $output['aaData'][] = array(
                $i,
                $subject,
                $descriptions,
                $gender,
                $data_comments.$data_like.$detail,
            );
            $i++;           
        }  
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('user/priviewavatar/index/'.$iduser); 
        }
    }
}


