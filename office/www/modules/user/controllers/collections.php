<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Collections extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->load->model(array("m_avatar","m_userdata"));
        //$this->m_checking->actions("User","module4","View Avatar Collection",FALSE,TRUE,"home");
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
            base_url()."resources/plugin/fancyBox-master/source/jquery.fancybox.css?v=2.1.5",
            base_url()."resources/css/avatareditor.css",
        );
        $js=array(
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/fancyBox-master/source/jquery.fancybox.pack.js?v=2.1.5",
        );
        $this->template_admin->header_web(TRUE,"Mix Collections User",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("collections_view",$add_properties);
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
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social");
        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
        $sSortDir_0=(isset($_GET['sSortDir_0']))?$_GET['sSortDir_0']:"desc";
        $iSortCol_0=(isset($_GET['iSortCol_0']))?(int)$_GET['iSortCol_0']:0;
        $pencarian = array(
                'type'=>'AvatarCollection',
                "user_id"=>(string)$iduser,
            );
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
            $tempdtuserowner = $this->m_userdata->user_properties($dt["user_owner"]);
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("AvatarMix");
            $tempdata = $this->mongo_db->findOne(array("_id"=>$this->mongo_db->mongoid($dt["id"])));
            $title = "";
            $gender = "";
            $size = "";
            $brand = "";
            $descriptions = "";
            $picture = "";
            if($tempdata)
            {
                $title = (!isset($tempdata['name'])?"":$tempdata['name']);
                $gender = (!isset($tempdata['gender'])?"":$tempdata['gender']);
                $size = (!isset($tempdata['bodytype'])?"":$tempdata['bodytype']);
                $brand = (!isset($tempdata['brand_id'])?"":$tempdata['brand_id']);
                $descriptions = (!isset($tempdata['description'])?"":$tempdata['description']);
                $picture = (!isset($tempdata['picture'])?"": "<a class='fancybox' href='".$this->config->item('path_asset_img') . "preview_images/" .$tempdata['picture']."'><img src='". $this->config->item('path_asset_img') . "preview_images/" . $tempdata['picture']. "' alt='' class='img-thumbnail' style='max-width:75px; max-height:75px;' /></a>");
            }
            $detail=$this->template_icon->detail_onclick("lihatconfigurasi('".$dt['_id']."')","",'Preview',"zoom.png","","linkdetail");
            $output['aaData'][] = array(
                $i,
                $tempdtuserowner["fullname"],
                $title,
                $gender,
                $size,
                $brand,
                $descriptions,
                $picture,
                $detail,
            );
            $i++;           
        }  
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('user/collections/index/'.$iduser); 
        }
    } 
}


