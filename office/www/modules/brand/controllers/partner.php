<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Partner extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Partner","module2",FALSE,TRUE,"home");
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
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"Partner",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("listdata_partner");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Website");
        $this->mongo_db->select_collection("Partner");
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
            $keysearchdt="partner_id";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="name";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="email";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="website";
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
            $partner_id=(!isset($dt['partner_id'])?"":$dt['partner_id']);
            $detail="";
            $delete="";
            $state="";
            $textstate = "New";
            if($this->m_checking->actions("Partner","module2","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."', '".$partner_id."', '".$dt['name']."', '".$dt['PIC']."','".$dt['phone']."','".$dt['mobile']."','".$dt['email']."','".$dt['website']."','".$dt['address']."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            }
            if($this->m_checking->actions("Partner","module2","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Partner ".$dt['name']."')","",'Delete',"delete.png","","linkdelete");
            }
            if($this->m_checking->actions("Partner","module2","State",TRUE,FALSE,"home"))
            {
                $replaceto = "Approved";
                if(isset($dt['state']))
                {
                    $textstate = $dt['state'];
                    if($dt['state']=="Approved")
                    {
                        $replaceto = "New";
                    }
                }
                $state=$this->template_icon->detail_onclick("changestate('".$dt['_id']."','".$replaceto."')","",'State',"rosette.png","","linkdelete");
            }
            $output['aaData'][] = array(
                $i,
                $partner_id,
                $dt['name'],
                $dt['email'],
                $dt['website'],
                $textstate,
                $state.$detail.$delete,
            );
            $i++;           
        }  
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('brand/partner/index'); 
        }
    }
    function cruid_partner()
    {        
        $this->form_validation->set_rules('partner_id','ID','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('name','Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('address','Address','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('pic','PIC','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('phone','Phone Number','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('mobile','HandPhone Number','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('email','Email','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('website','Website','trim|htmlspecialchars|xss_clean');
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
            $partner_id = $this->input->post('partner_id',TRUE);
            $name = $this->input->post('name',TRUE);
            $address = $this->input->post('address',TRUE);
            $pic = $this->input->post('pic',TRUE);
            $phone = $this->input->post('phone',TRUE);
            $mobile = $this->input->post('mobile',TRUE);
            $email = $this->input->post('email',TRUE);
            $website = $this->input->post('website',TRUE);
            $datatinsert=array(
                'partner_id'  =>$partner_id,
                'name'  =>$name,
                'address'  =>$address,
                'PIC'  =>$pic,
                'phone'  =>$phone,
                'mobile'  =>$mobile,
                'email'  =>$email,
                'website'  =>$website,
            );
            if($id=='')
            {
                $this->m_checking->actions("Partner","module2","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Website");
                $this->mongo_db->select_collection("Partner");
                $this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add Partner",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {
                $this->m_checking->actions("Partner","module2","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Website");
                $this->mongo_db->select_collection("Partner");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Partner",$url,$user);
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
            redirect('brand/partner/index'); 
        }
    }
    function changestate($id="",$state="New")
    {
        $this->m_checking->actions("Partner","module2","State",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Website");
        $this->mongo_db->select_collection("Partner");
        $dataupdate=array(
            'state'  =>$state,
        );
        $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$dataupdate));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Update State Partner to ".$state,$url,$user);
        $output = array(
            "message" =>"Data state is update",
            "success" =>TRUE,
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('brand/partner/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Partner","module2","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Website");
        $this->mongo_db->select_collection("Partner");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Partner",$url,$user);
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
            redirect('brand/partner/index'); 
        }
    }
}


