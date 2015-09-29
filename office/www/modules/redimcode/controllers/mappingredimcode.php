<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mappingredimcode extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Avatar Redeem Code","module5",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("RedeemAvatar");
        $listid=$this->mongo_db->find(array(),0,0,array());
        $temp_search=array();
        $search=array();
        if($listid)
        {
            foreach($listid as $dt)
            {
                $temp_search[]=$this->mongo_db->mongoid($dt['code_id']);
            }
            $search=array('_id'=>array('$nin'=>$temp_search));
        }
        $this->mongo_db->select_collection("Redeem");
        $data['isiredimcode']=$this->mongo_db->find($search,0,0,array('create'=>-1));
        $this->mongo_db->select_collection("AvatarBodyPart");
        $data['tipe']=$this->mongo_db->find(array("parent"=>''),0,0,array('name'=>1));
        $this->mongo_db->select_collection("Brand");
        $data['brand']=$this->mongo_db->find(array(),0,0,array('name'=>1));
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
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
            base_url()."resources/plugin/fancyBox-master/source/jquery.fancybox.pack.js?v=2.1.5",
        );
        $this->template_admin->header_web(TRUE,"Redemption Code Mapping",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("mappingredimcode_view",$data);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }    
    function list_data()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("RedeemAvatar");
        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
        $data=$this->mongo_db->find(array(),$awal,$limit,array());
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count(),
		"iTotalDisplayRecords" => $this->mongo_db->count(),
		"aaData" => array()
	);
        $tempresult=array();        
        foreach($data as $dt)
        {
            $tempresult[]=array(
                '_id'=>$dt['_id'],
                'code'=>$dt['code_id'],
                'avatar'=>$dt['avatar_id'],
            );          
        }
        $i=$awal+1;
        foreach($tempresult as $result)
        {
            $this->mongo_db->select_collection("Avatar");
            $dataimg=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($result['avatar'])));       
            $detailavatar="Name : ".$dataimg['name']."<br />Gender : ".$dataimg['gender']."<br />Type : ".$dataimg['tipe']."<br />Category : ".$dataimg['category']."<br /><a rel='shadowbox' href='".$this->config->item('path_asset_img')."preview_images/".$dataimg['preview_image']."' class='fancybox'><img src='".$this->config->item('path_asset_img')."preview_images/".$dataimg['preview_image']."' width='70' height='90' alt='".$dataimg['name']."' class='img-thumbnail' /></a>";
            $delete="";
            if($this->m_checking->actions("Avatar Redeem Code","module5","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$result['_id']."','Are you sure want to delete Redeem Avatar ".$result['code']." with Avatar name : ".$dataimg['name']."')","",'Delete',"delete.png","","linkdelete");
            }
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Redeem");
            $datareedim=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($result['code']))); 
            $tglexpire="";
            $code_result=(!isset($result['code'])?"":(string)$result['code']);
            $code_reedem=(!isset($datareedim['code'])?"":(string)$datareedim['code']);
            $count_reedem=(!isset($datareedim['count'])?"":(string)$datareedim['count']);
            $createDate=(!isset($datareedim['create'])?"":date('Y-M-d', $datareedim['create']->sec));
            if($datareedim['expire']!="")
            {
                $tglexpire=date('Y-m-d', $datareedim['expire']->sec);
            }
            $detailreedim= "ID : ".$code_result." <br />Code : ".$code_reedem."<br />Count : ".$count_reedem."<br />Expire Date : ".$tglexpire."<br />Created : ".$createDate;
            $output['aaData'][] = array(
                $i,
                $detailreedim,
                $detailavatar,
                $delete,
            );
            $i++;     
        }
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('redimcode/mappingredimcode/index'); 
        }
    }    
    function cruid_mappingredimcode()
    {        
        $this->form_validation->set_rules('redimcode','Redeem Code','trim|required|htmlspecialchars|xss_clean');
        $output['message'] = "Fail add data";
        $output['success'] = FALSE;
        $url = current_url();
        $user = $this->session->userdata('username');
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $this->m_checking->actions("Avatar Redeem Code","module5","Add",FALSE,FALSE,"home");
            $id = $this->input->post('redimcode',TRUE);
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("RedeemAvatar");
            if(isset($_POST['avatarid']))
            {
                if(count($_POST['avatarid'])>0)
                {
                    for($i=0; $i< count($_POST['avatarid']); $i++)
                    {
                        $datatinsert=array(
                            'code_id'  =>$id,
                            'avatar_id'  =>$_POST['avatarid'][$i],
                        );
                        $this->mongo_db->update(array('avatar_id'=>$_POST['avatarid'][$i]),array('$set'=>$datatinsert),array('upsert' => TRUE)); 
                    }                
                }
                $output['success'] = TRUE;
                $this->m_user->tulis_log("Add Avatar Redeem Map",$url,$user);
                $output['message'] = "<i class='success'>Data is updated</i>";
            }
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('redimcode/mappingredimcode/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Avatar Redeem Code","module5","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("RedeemAvatar");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Avatar Redeem Map",$url,$user);
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
            redirect('redimcode/mappingredimcode/index'); 
        }
    }
}
