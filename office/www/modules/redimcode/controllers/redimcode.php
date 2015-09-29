<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Redimcode extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Redeem Code","module5",FALSE,TRUE,"home");
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
            base_url()."resources/plugin/form-daterangepicker/daterangepicker-bs3.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker.min.js",
            base_url()."resources/plugin/form-daterangepicker/moment.min.js",
        );
        $this->template_admin->header_web(TRUE,"Redemption Code",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("redimcode_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Redeem");
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
        $keysearchdt="create";
        if($iSortCol_0==1)
        {
            $keysearchdt="code";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="name";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="count";
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
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array('create'=>$jns_sorting));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $tglexpire="";
            if($dt['expire']!="")
            {
                $tglexpire=date('Y-m-d', $dt['expire']->sec);
            }
            $detail="";
            $delete="";
            if($this->m_checking->actions("Redeem Code","module5","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Redeem code ".$dt['code']."')","",'Delete',"delete.png","","linkdelete");
            }
            if($this->m_checking->actions("Redeem Code","module5","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."','".$dt['code']."','".$dt['name']."','".$dt['count']."','".$tglexpire."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            }
            $output['aaData'][] = array(
                $i,
                $dt['code'],
                $dt['name'],
                $dt['count'],
                $tglexpire,
                date('Y-m-d', $dt['create']->sec),
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
            redirect('redimcode/index'); 
        }
    }
    function generatecode()
    {
        $output = array(
            "message" =>"Data Code is Generated",
            "success" =>TRUE,
            "data" =>$this->tambahan_fungsi->global_get_random(10),
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('redimcode/index'); 
        }
    }
    function cruid_redimcode()
    {        
        $this->form_validation->set_rules('code','Code','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('count','Count','trim|required|numeric|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtkey','Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('dateexpire','Date Expire','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
        $output['message'] = "";
        $output['success'] = FALSE;
        $url = current_url();
        $user = $this->session->userdata('username');
        $generatetime=date("Y-m-d H:i:s");
        $time_start=  strtotime($generatetime);
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $id = $this->input->post('txtid',TRUE);
            $code = $this->input->post('code',TRUE);
            $count = $this->input->post('count',TRUE);
            $name = $this->input->post('txtkey',TRUE);
            $dateexpire = $this->input->post('dateexpire',TRUE);
            $datatinsert=array(
                'code'  =>$code,
                'count'  =>$count,
                'name' =>$name,
                'expire'  =>($dateexpire=='')?'':$this->mongo_db->time(strtotime($dateexpire." 00:00:00")),
                'create'  =>$this->mongo_db->time($time_start),
            );
            if($id=='')
            {
                $this->m_checking->actions("Redeem Code","module5","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Redeem");
                $id = $this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add Redeem Code",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {
                $this->m_checking->actions("Redeem Code","module5","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Redeem");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Redeem Code",$url,$user);
                $output['message'] = "<i class='success'>Data is updated</i>";
            }
            $output['_id'] = $id;
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('redimcode/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Redeem Code","module5","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Redeem");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $this->mongo_db->select_collection("RedeemAvatar");
        $this->mongo_db->remove(array('code_id' => $id));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Redeem Code",$url,$user);
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
            redirect('redimcode/index'); 
        }
    }
}
