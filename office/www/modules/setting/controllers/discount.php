<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Discount extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Promo","module5",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Brand");
        $isiform['listbrand']=$this->mongo_db->find(array(),0,0,array('name'=>1));
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/form-select2/select2.css",
            base_url()."resources/plugin/form-multiselect/css/multi-select.css", 
            base_url()."resources/plugin/jqueryui-timepicker/jquery.ui.timepicker.css",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker-bs3.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/jqueryui-timepicker/jquery.ui.timepicker.min.js",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker.min.js",
            base_url()."resources/plugin/form-daterangepicker/moment.min.js",
            base_url()."resources/plugin/form-multiselect/js/jquery.multi-select.min.js",
            base_url()."resources/plugin/quicksearch/jquery.quicksearch.min.js",
            base_url()."resources/plugin/form-typeahead/typeahead.min.js",
            base_url()."resources/plugin/form-select2/select2.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
        );
        $this->template_admin->header_web(TRUE,"Promo",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("discount_list",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("Discount");
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
        $keysearchdt="brand_id";
        if($iSortCol_0==1)
        {
            $keysearchdt="code";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="brand_id";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="Descriptions";
        }
        else if($iSortCol_0==5)
        {
            $keysearchdt="StartDate";
        }
        else if($iSortCol_0==6)
        {
            $keysearchdt="EndDate";
        }
        else if($iSortCol_0==7)
        {
            $keysearchdt="Type";
        }
        else if($iSortCol_0==8)
        {
            $keysearchdt="Price";
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
            $brand="";
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
            $tglmulai = "";
            $tglakhir = "";
            $jammulai = "";
            $jamakhir = "";
            if($dt['StartDate']!="")
            {
                $tglmulai=date('Y-m-d', $dt['StartDate']->sec);
            }
            if($dt['EndDate']!="")
            {
                $tglakhir=date('Y-m-d', $dt['EndDate']->sec);
            }
            if($dt['StartDate']!="")
            {
                $jammulai=date('H:i:s', $dt['StartDate']->sec);
            }
            if($dt['EndDate']!="")
            {
                $jamakhir=date('H:i:s', $dt['EndDate']->sec);
            }
            if($this->m_checking->actions("Promo","module5","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."','".$dt['code']."','".$dt['brand_id']."','".$dt['Descriptions']."','".$tglmulai."','".$jammulai."','".$tglakhir."','".$jamakhir."','".$dt['Type']."','".$dt['Price']."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            } 
            if($this->m_checking->actions("Promo","module5","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete this Discount ? ')","",'Delete',"delete.png","","linkdelete");
            }             
            $output['aaData'][] = array(
                $i,
                $dt['code'],
                $brand,
                $dt['Descriptions'],
                $tglmulai." ".$jammulai,
                $tglakhir." ".$jamakhir,
                $dt['Type'],
                $dt['Price'],
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
            redirect('setting/discount/index'); 
        }
    }
    function cruid_discount()
    {        
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('code','Code','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('description','Description','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtdatebegin','Date Begin','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txttimebegin','Time Begin','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtdateend','Date End','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txttimeend','Time End','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('type','Type','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtprice','Price','trim|numeric|htmlspecialchars|xss_clean');
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
            $code = $this->input->post('code',TRUE);
            $description = $this->input->post('description',TRUE);
            $datebegin = $this->input->post('txtdatebegin',TRUE);
            $timebegin = $this->input->post('txttimebegin',TRUE);
            $dateend = $this->input->post('txtdateend',TRUE);
            $timeend = $this->input->post('txttimeend',TRUE);
            $type = $this->input->post('type',TRUE);
            $price = $this->input->post('txtprice',TRUE);
            if($this->session->userdata('brand')=="")
            {
                $brand = $_POST['brand_id'];
            }
            else
            {
                $brand = $this->session->userdata('brand');
            }
            $datatinsert=array(
                'brand_id'  =>$brand,
                'code'  =>$code,
                'Descriptions'  =>$description,
                'StartDate'  =>$this->mongo_db->time(strtotime($datebegin." ".$timebegin)),
                'EndDate'  =>$this->mongo_db->time(strtotime($dateend." ".$timeend)),
                'Type'  =>$type,
                'Price'  =>$price,              
            );
            if($id==='')
            {
                $this->m_checking->actions("Promo","module5","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Articles");
                $this->mongo_db->select_collection("Discount");
                $this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add New Discount Data",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {
                $this->m_checking->actions("Promo","module5","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Articles");
                $this->mongo_db->select_collection("Discount");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Discount Data",$url,$user);
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
            redirect('setting/discount/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Promo","module5","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("Discount");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Discount",$url,$user);
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
            redirect('setting/discount/index'); 
        }
    }
}




