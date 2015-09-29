<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Achievement extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Achievement","module5",FALSE,TRUE,"home");
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
            base_url()."resources/plugin/jquery-fileupload/css/jquery.fileupload-ui.css",
            base_url()."resources/plugin/form-select2/select2.css",
            base_url()."resources/plugin/fancyBox-master/source/jquery.fancybox.css?v=2.1.5",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/quicksearch/jquery.quicksearch.min.js",
            base_url()."resources/plugin/form-typeahead/typeahead.min.js",
            base_url()."resources/plugin/form-select2/select2.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
            base_url()."resources/plugin/jquery-fileupload/js/vendor/jquery.ui.widget.js",
            base_url()."resources/plugin/jquery-fileupload/js/jquery.fileupload.js",
            base_url()."resources/plugin/fancyBox-master/source/jquery.fancybox.pack.js?v=2.1.5",
        );
        $this->template_admin->header_web(TRUE,"Achievement",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->mongo_db->select_db("Game");        
        $this->mongo_db->select_collection("RequiredRewards");
        $isiform['datareward']=$this->mongo_db->find(array('type'=>'Rewards'),0,0,array('name'=>1));
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Brand");
        $isiform['brand']=$this->mongo_db->find(array(),0,0,array('name'=>1));
        $this->mongo_db->select_collection("AvatarBodyPart");
        $isiform['tipe']=$this->mongo_db->find(array("parent"=>''),0,0,array('name'=>1)); 
        $this->load->view("achievement_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }  
    function list_data()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Achievements");
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
        if($this->session->userdata('brand')!="")
        {
            $pencarian= array_merge($pencarian,array("brand_id"=>$this->session->userdata('brand')));
        }
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array($keysearchdt=>$jns_sorting));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $detail="";
            $delete="";
            if($this->m_checking->actions("Achievement","module5","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            }
            if($this->m_checking->actions("Achievement","module5","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Achievement ".$dt['name']."')","",'Delete',"delete.png","","linkdelete");
            }
            $pathimg = $this->config->item('path_asset_img')."preview_images/";
            $picture = "<a class='fancybox' href='".$pathimg.$dt['icon_default']."'><img src='".$pathimg.$dt['icon_default']."' alt='' class='img-thumbnail' style='max-width:75px; max-height:75px;' /></a><br />";
            $picture .= "<a class='fancybox' href='".$pathimg.$dt['icon_active']."'><img src='".$pathimg.$dt['icon_active']."' alt='' class='img-thumbnail' style='max-width:75px; max-height:75px;' /></a><br />";
            $output['aaData'][] = array(
                $i,
                $dt['name'],
                $dt['description'],
                $dt['point'],
                $dt['brand_id'],
                $dt['state'],
                $picture,
                $detail.$delete,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('contest/achievement/index'); 
        }
    }
    function detail()
    {
        $this->m_checking->actions("Achievement","module5","Edit",FALSE,FALSE,"home");
        $this->form_validation->set_rules('txtid','ID','trim|required|htmlspecialchars|xss_clean');        
        $output['message'] = "";
        $output['success'] = FALSE;
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $id = $this->input->post('txtid',TRUE);
            $output['success'] = TRUE;
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("Achievements");
            $tempdata = $this->mongo_db->findOne(array('_id'=> $this->mongo_db->mongoid($id)));
            if($tempdata)
            {
                $output['id'] = (string)$tempdata['_id'];
                $output['name'] = $tempdata['name'];
                $output['state'] = $tempdata['state'];
                $output['point'] = $tempdata['point'];
                $output['rewards'] = $tempdata['rewards'];
                $output['description'] = $tempdata['description'];
                $output['icon_active'] = $tempdata['icon_active'];
                $output['icon_default'] = $tempdata['icon_default'];
                $output['brand_id'] = $tempdata['brand_id'];
            }
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('contest/achievement/index'); 
        }
    }
    function cruid_achievement()
    {        
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('name','Achievement Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('descriptions','Descriptions','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtpoint','Point','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('brand','Brand Name','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('cmbstate','State Achievement','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txticondefault','Icon Default','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txticonactive','Icon Active','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('rewards','Rewards','trim|htmlspecialchars|xss_clean');
        $output['message'] = "";
        $output['success'] = FALSE;
        $url = current_url();
        $user = $this->session->userdata('username');
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $id = $this->input->post('txtid',TRUE);
            $name = $this->input->post('name',TRUE);
            $descriptions = $this->input->post('descriptions',TRUE);
            $txtpoint = $this->input->post('txtpoint',TRUE);
            $cmbstate = $this->input->post('cmbstate',TRUE);
            $txticondefault = $this->input->post('txticondefault',TRUE);
            $txticonactive = $this->input->post('txticonactive',TRUE);
            $rewards = $this->input->post('rewards',TRUE);
            $tanggalupdate=$this->mongo_db->time(strtotime(date("Y-m-d H:i:s")));
            if($this->session->userdata('brand')=="")
            {
                $dtbrand = $_POST['brand'];
            }
            else
            {
                $dtbrand = $this->session->userdata('brand');
            }
            $datatinsert=array(
                'name'  =>$name,
                'state'  =>$cmbstate,
                'point'  =>$txtpoint,
                'rewards'  =>$rewards,
                'description'  =>$descriptions,
                'icon_active'  =>$txticonactive,
                'icon_default'  =>$txticondefault,
                'brand_id'  =>$dtbrand,
                'dateupdate'=>$tanggalupdate,
            );
            if($id=='')
            {
                $this->m_checking->actions("Achievement","module5","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Achievements");
                $level_id=$this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add Achievement",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {
                $this->m_checking->actions("Achievement","module5","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Achievements");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Achievement",$url,$user);
                $output['message'] = "<i class='success'>Data is updated</i>";
            }  
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('contest/achievement/index'); 
        }
    }    
    function delete($id="")
    {
        $this->m_checking->actions("Achievement","module5","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Achievements");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Achievement",$url,$user);
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
            redirect('contest/achievement/index'); 
        }
    }
}
