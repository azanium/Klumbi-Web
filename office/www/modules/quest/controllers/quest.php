<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Quest extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('reporting','form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Quests","module5",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Brand");
        $adddata['brand']=$this->mongo_db->find(array(),0,0,array('name'=>1));
        $this->mongo_db->select_collection("AvatarBodyPart");
        $adddata['tipe']=$this->mongo_db->find(array("parent"=>''),0,0,array('name'=>1));        
        $this->mongo_db->select_db("Game");        
        $this->mongo_db->select_collection("RequiredRewards");
        $adddata['data']=$this->mongo_db->find(array('type'=>'Required'),0,0,array('name'=>1));
        $adddata['datareward']=$this->mongo_db->find(array('type'=>'Rewards'),0,0,array('name'=>1));
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/form-select2/select2.css",
            base_url()."resources/plugin/form-multiselect/css/multi-select.css", 
            base_url()."resources/plugin/jqueryui-timepicker/jquery.ui.timepicker.css",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker-bs3.css",
            base_url()."resources/plugin/jquery-fileupload/css/jquery.fileupload-ui.css",
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
        $this->template_admin->header_web(TRUE,"Quest",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("quest_view",$adddata);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function generatecode()
    {
        $output = array(
            "message" =>"Data Code is Generated",
            "success" =>TRUE,
            "data" =>(int)$this->tambahan_fungsi->global_get_numeric(5),
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quest/index'); 
        }
    }
    function list_data()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Quest");
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
        $keysearchdt="ID";
        if($iSortCol_0==2)
        {
            $keysearchdt="ID";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="Description";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="DescriptionNormal";
        }
        else if($iSortCol_0==5)
        {
            $keysearchdt="DescriptionActive";
        }
        else if($iSortCol_0==6)
        {
            $keysearchdt="DescriptionDone";
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
            $import="";
            $copy="";
            $detail="";
            $delete="";
            if($this->m_checking->actions("Quests","module5","Import Txt",TRUE,FALSE,"home"))
            {
                $import=$this->template_icon->link_icon3("quest/processexport/".$dt['ID'],"Import to file","database_go.png"," target=\"_blank\" ");
            }
            if($this->m_checking->actions("Quests","module5","Duplicate",TRUE,FALSE,"home"))
            {
                $copy=$this->template_icon->detail_onclick("duplikat('".$dt['_id']."')","",'Create Duplicat',"application_side_contract.png","","linkdetail");
            }
            if($this->m_checking->actions("Quests","module5","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            }
            if($this->m_checking->actions("Quests","module5","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Quest with ID ".$dt['ID']."')","",'Delete',"delete.png","","linkdelete");
            }
            $output['aaData'][] = array(
                $i,
                "<label><input type='checkbox' name='id_export[]' value='".$dt['ID']."'/></label>",
                $dt['ID'],
                $dt['Description'],
                $dt['DescriptionNormal'],
                $dt['DescriptionActive'],                
                $dt['DescriptionDone'],                
                $dt['IsActive'],
                $dt['IsDone'],
                $dt['IsReturn'],
                $import.$copy.$detail.$delete,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quest/index'); 
        }
    }
    function duplicate()
    {
        $this->m_checking->actions("Quests","module5","Duplicate",FALSE,FALSE,"home");
        $this->form_validation->set_rules('txtidduplikat','ID','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('countduplikat','Count','trim|numeric|required|htmlspecialchars|xss_clean');
        $url = current_url();
        $user = $this->session->userdata('username');
        $output['message'] = "";
        $output['success'] = FALSE;
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $kodeid = $this->input->post('txtidduplikat',TRUE);
            $jmlcount = $this->input->post('countduplikat',TRUE);
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("Quest");
            $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($kodeid)));
            $datebegintemp="";
            $dateendtemp="";
            if($tampung)
            {
                $output['message'] = "<i class='success'>Success Duplicat data</i>"; 
                $output['success'] = TRUE;
                $temp = $tampung['ID'];
                $return['Description'] = $tampung['Description'];
                $return['DescriptionNormal'] = $tampung['DescriptionNormal'];
                $return['DescriptionActive'] = $tampung['DescriptionActive'];
                $return['DescriptionDone'] = $tampung['DescriptionDone'];
                $return['Requirement'] = $tampung['Requirement'];
                $return['RequiredEnergy'] = $tampung['RequiredEnergy'];
                $return['RequiredItem'] = $tampung['RequiredItem'];
                $return['Rewards'] = $tampung['Rewards'];
                $return['IsDone'] = $tampung['IsDone'];
                $return['IsActive'] = $tampung['IsActive'];
                $return['IsReturn'] = $tampung['IsReturn'];
                if($tampung['EndDate']!='')
                {
                    $dateendtemp = $this->mongo_db->time(strtotime(date('Y-m-d H:i:s', $tampung['EndDate']->sec)));
                }
                if($tampung['StartDate']!='')
                {
                    $datebegintemp = $this->mongo_db->time(strtotime(date('Y-m-d H:i:s', $tampung['StartDate']->sec)));
                }
                $return['EndDate'] = $dateendtemp;
                $return['StartDate'] = $datebegintemp;
                for($i=0;$i<(int)$jmlcount;$i++)
                {
                    $return['ID'] = $temp.$i;
                    $this->mongo_db->update(array('_id' => $this->mongo_db->mongoid()), array('$set'=> $return ),array('upsert' => TRUE));
                }
                $this->m_user->tulis_log("Duplicate Quest",$url,$user);
            }            
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quest/index'); 
        }
    }
    function list_data_child()
    {        
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Quest");
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
        $keysearchdt="ID";
        if($iSortCol_0==1)
        {
            $keysearchdt="ID";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="Description";
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
		"iTotalRecords" => $this->mongo_db->count(),
		"iTotalDisplayRecords" => $this->mongo_db->count(),
		"aaData" => array()
	);
        $i=1;
        foreach($data as $dt)
        {
            $selected=$this->template_icon->detail_onclick("selectthisitem('".$dt['ID']."')","",'Detail',"accept.png","","linkdetail");
            $output['aaData'][] = array(
                $i,
                $dt['ID'],
                $dt['Description'],
                $selected,
            );
            $i++;           
        } 
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quest/index'); 
        }
    }
    function detail($id="")
    {
        $this->m_checking->actions("Quests","module5","Edit",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Quest");
        $return=array();
        $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        $datebegintemp="";
        $timebegintemp="";
        $dateendtemp="";
        $timeendtemp="";
        if($tampung)
        {
            $return['_id'] = (string)$tampung['_id'];
            $return['ID'] = $tampung['ID'];
            $return['Description'] = $tampung['Description'];
            $return['DescriptionNormal'] = $tampung['DescriptionNormal'];
            $return['DescriptionActive'] = $tampung['DescriptionActive'];
            $return['DescriptionDone'] = $tampung['DescriptionDone'];
            $return['Requirement'] = $tampung['Requirement'];
            $return['RequiredEnergy'] = $tampung['RequiredEnergy'];
            $return['RequiredItem'] = $tampung['RequiredItem'];
            $return['Rewards'] = $tampung['Rewards'];
            $return['IsDone'] = $tampung['IsDone'];
            $return['IsActive'] = $tampung['IsActive'];
            $return['IsReturn'] = $tampung['IsReturn'];
            if($tampung['EndDate']!='')
            {
                $dateendtemp=date('Y-m-d', $tampung['EndDate']->sec);
                $timeendtemp=date('H:i:s', $tampung['EndDate']->sec);
            }
            if($tampung['StartDate']!='')
            {
                $datebegintemp=date('Y-m-d', $tampung['StartDate']->sec);
                $timebegintemp=date('H:i:s', $tampung['StartDate']->sec);
            }
            $return['EndDate'] = $dateendtemp;
            $return['EndTime'] = $timeendtemp;
            $return['StartDate'] = $datebegintemp;
            $return['StartTime'] = $timebegintemp;
        }
        echo json_encode($return);
    }
    function cruid_quest()
    {        
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('code','ID Quest','trim|numeric|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('title','Title','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('descriptionnormal','Description Normal','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('descriptionactive','Description Active','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('descriptiondone','Description Done','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtdatebegin','Start Date','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txttimebegin','Start Time','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtdateend','End Date','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txttimeend','End Time','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('requirementquest','Requirement (Quest ID)','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('energyrequirement','Energy Requirement','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('requireditem','Required Item','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('rewards','Rewards','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('radiodone','Is Done','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('radioactive','Is Active','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('radioreturn','Is Return','trim|htmlspecialchars|xss_clean');  
        $url = current_url();
        $user = $this->session->userdata('username');
        $output['message'] = "";
        $output['success'] = FALSE;
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $id = $this->input->post('txtid',TRUE);
            $kodequest = $this->input->post('code',TRUE);
            $description = $this->input->post('title',TRUE);
            $descriptionnormal = $this->input->post('descriptionnormal',TRUE);
            $descriptionactive = $this->input->post('descriptionactive',TRUE);
            $descriptiondone = $this->input->post('descriptiondone',TRUE);
            $startdate = $this->input->post('txtdatebegin',TRUE);
            $starttime = $this->input->post('txttimebegin',TRUE);
            $enddate = $this->input->post('txtdateend',TRUE);
            $endtime = $this->input->post('txttimeend',TRUE);
            $requirementquest = $this->input->post('requirementquest',TRUE);
            $energyrequirement = $this->input->post('energyrequirement',TRUE);
            $requireditem = $this->input->post('requireditem',TRUE);
            $rewards = $this->input->post('rewards',TRUE);
            $radiodone = $this->input->post('radiodone',TRUE);
            $radioactive = $this->input->post('radioactive',TRUE);
            $radioreturn = $this->input->post('radioreturn',TRUE);
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("Quest");
            $cekada_data = $this->mongo_db->findOne(array('ID' => $kodequest));
            $tanggalawal="";
            $tanggalakhir="";
            if($startdate!='')
            {
                $tanggalawal=$this->mongo_db->time(strtotime($startdate." ".(($starttime=='')?"00:00:00":$starttime)));
            }
            if($enddate!='')
            {
                $tanggalakhir=$this->mongo_db->time(strtotime($enddate." ".(($endtime=='')?"23:59:59":$endtime)));
            }
            $datatinsert=array(
                'Description'  =>$description,
                'DescriptionNormal'  =>$descriptionnormal,
                'DescriptionActive'  =>$descriptionactive,
                'DescriptionDone'  =>$descriptiondone,
                'EndDate'  =>$tanggalakhir,
                'StartDate'  =>$tanggalawal,
                'Requirement'  =>$requirementquest,
                'RequiredEnergy'  =>$energyrequirement,
                'RequiredItem'  =>$requireditem,
                'Rewards'  =>$rewards,
                'IsDone'  =>$radiodone,
                'IsActive'  =>$radioactive,
                'IsReturn'  =>$radioreturn,                    
            );
            if($id=='')
            {
                $this->m_checking->actions("Quests","module5","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Quest");
                if($cekada_data)
                {
                    $output['message'] = "<i class='error'>Duplicate ID, Data failed to be saved</i>";
                }
                else
                {
                    $datatinsert=  array_merge($datatinsert, array('ID'  =>$kodequest));
                    $output['message'] = "<i class='success'>New Data is added</i>";
                    $this->mongo_db->insert($datatinsert);
                    $this->m_user->tulis_log("Add Quest",$url,$user);
                    $output['success'] = TRUE;
                }
                
            }
            else
            {
                $this->m_checking->actions("Quests","module5","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Quest");
                if(!$cekada_data)
                {
                    $datatinsert=  array_merge($datatinsert, array('ID'  =>$kodequest));                    
                }
                $this->mongo_db->update(array('_id' => $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Quest",$url,$user);
                $output['message'] = "<i class='success'>Data is updated</i>";
                $output['success'] = TRUE;
            }              
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quest/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Quests","module5","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Quest");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Quest",$url,$user);
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
            redirect('quest/index'); 
        }
    }    
    function get_required_id($id="")
    {
        $this->mongo_db->select_db("Game");        
        $this->mongo_db->select_collection("RequiredRewards");
        $data=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($data)
        {
            if($data['self_value']=="Yes")
            {
                foreach($data['options'] as $dt)
                {
                    echo "<option value='".$data['code'].":".$dt."'>".$dt."</option>";
                }
            }
            else
            {
                $this->mongo_db->select_db("Assets");        
                $this->mongo_db->select_collection($data['table']);
                $data2=$this->mongo_db->find(array(),0,0,array());
                if($data2)
                {
                    foreach($data2 as $dt)
                    {
                        echo "<option value='".$data['code'].":".$dt[$data['options'][0]]."'>".$dt[$data['options'][1]]."</option>";
                    }
                }
            }
        }
    }
    function processimport()
    {
        $this->m_checking->actions("Quests","module5","Export",FALSE,TRUE,"home");
        $this->load->helper('file');
        if($_FILES['fileimport']['name']!="")        
        {
            $uploaddir = $this->config->item('path_upload');
            $config['upload_path'] = $uploaddir;
            $config['allowed_types'] = 'txt';
            $config['max_size']	= '10000';
            $config['max_width']  = '1024';
            $config['max_height']  = '768';
            $config['max_filename']  = 0;
            $config['overwrite']  = FALSE;
            $config['encrypt_name']  = TRUE;
            $config['remove_spaces']  = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('fileimport'))
            {
                $data['warning'] = array('error' => $this->upload->display_errors());
            }
            else
            {
                $data['warning'] = array('upload_data' => $this->upload->data());
                $hasil = $this->upload->data();
                $fileimg = $hasil['file_name'];
                $string = read_file($uploaddir.$fileimg);
                @unlink($uploaddir.$fileimg);
                $dataprepare=array();
                $dataprepare=  json_decode($string);
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Quest");
                foreach($dataprepare as $dt)
                {
                    $this->mongo_db->update(array('ID'=>$dt->ID),array('$set'=>$dt),array('upsert' => TRUE)); 
                }                
            }
        }
        redirect("quest/index");
    }
    function processexport($id="")
    {
        $this->m_checking->actions("Quests","module5","Import Txt",FALSE,TRUE,"home");
        $this->load->helper('file');
        $this->load->helper('download');
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Quest");
        $isifile="";
        $tempfile=array();
        $cari=array();
        if(isset($_GET['id_export']))
        {            
            for($i=0;$i<count($_GET['id_export']);$i++)
            {
                $cari[]=$_GET['id_export'][$i];
            }       
        }
        else 
        {
            $cari[]=$id;
        }
        $data = $this->mongo_db->find(array('ID'=>array('$in'=>$cari)),0,0,array());            
        foreach($data as $dt)
        {
            $tempfile[]=array(
                'EndDate'=>isset($dt['EndDate'])?$dt['EndDate']:"",
                'StartDate'=>isset($dt['StartDate'])?$dt['StartDate']:"",
                'ID'=>isset($dt['ID'])?$dt['ID']:"",
                'Description'=>isset($dt['Description'])?$dt['Description']:"",
                'DescriptionNormal'=>isset($dt['DescriptionNormal'])?$dt['DescriptionNormal']:"",
                'DescriptionActive'=>isset($dt['DescriptionActive'])?$dt['DescriptionActive']:"",
                'DescriptionDone'=>isset($dt['DescriptionDone'])?$dt['DescriptionDone']:"",
                'Requirement'=>isset($dt['Requirement'])?$dt['Requirement']:";",
                'RequiredEnergy'=>isset($dt['RequiredEnergy'])?$dt['RequiredEnergy']:"",
                'RequiredItem'=>isset($dt['RequiredItem'])?$dt['RequiredItem']:"",
                'Rewards'=>isset($dt['Rewards'])?$dt['Rewards']:"",
                'IsDone'=>isset($dt['IsDone'])?$dt['IsDone']:"",
                'IsActive'=>isset($dt['IsActive'])?$dt['IsActive']:"",
                'IsReturn'=>isset($dt['IsReturn'])?$dt['IsReturn']:"",
            );       
        }
        $uploaddir = $this->config->item('path_upload');
        $downloaddir = $this->config->item('path_asset_img');
        $isifile=  json_encode($tempfile);
        $namafile='quest_import_'.date('Y-m-d');
        $this->reporting->header_txt($namafile);
        echo $isifile;
        /*write_file($uploaddir.$namafile, $isifile);
        @unlink($uploaddir.$namafile);*/
    }
    function excell()
    {	
        $this->m_checking->actions("Quests","module5","Import Exl",FALSE,TRUE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Quest");
        $data=$this->mongo_db->find(array(),0,0,array());
        $namafile='quest_'.date('Y-m-d');
        $this->reporting->header_xls($namafile);
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>No</td>";
        echo "<td>ID</td>";
        echo "<td>Quest Title</td>";
        echo "<td>Description Normal</td>";
        echo "<td>Description Active</td>";
        echo "<td>Description Done</td>";
        echo "<td>Start Date</td>";
        echo "<td>Start Time</td>";
        echo "<td>End Date</td>";
        echo "<td>End Time</td>";
        echo "<td>Requirement (Quest ID)</td>";
        echo "<td>Energy Requirement</td>";
        echo "<td>Required Item</td>";
        echo "<td>Rewards</td>";
        echo "<td>Is Done</td>";
        echo "<td>Is Active</td>";
        echo "<td>Is Return</td>";
        echo "</tr>";
        $i=1;
        foreach($data as $dt)
        {
            $datebegintemp="&nbsp;";
            $timebegintemp="&nbsp;";
            $dateendtemp="&nbsp;";
            $timeendtemp="&nbsp;";
            if($dt['EndDate']!='')
            {
                $dateendtemp=date('Y-m-d', $dt['EndDate']->sec);
                $timeendtemp=date('H:i:s', $dt['EndDate']->sec);
            }
            if($dt['StartDate']!='')
            {
                $datebegintemp=date('Y-m-d', $dt['StartDate']->sec);
                $timebegintemp=date('H:i:s', $dt['StartDate']->sec);
            }
            echo "<tr>";
            echo "<td>".$i."</td>";
            echo "<td>".(isset($dt['ID'])?$dt['ID']:"&nbsp;")."</td>";
            echo "<td>".(isset($dt['Description'])?$dt['Description']:"&nbsp;")."</td>";
            echo "<td>".(isset($dt['DescriptionNormal'])?$dt['DescriptionNormal']:"&nbsp;")."</td>";
            echo "<td>".(isset($dt['DescriptionActive'])?$dt['DescriptionActive']:"&nbsp;")."</td>";
            echo "<td>".(isset($dt['DescriptionDone'])?$dt['DescriptionDone']:"&nbsp;")."</td>";
            echo "<td>".$datebegintemp."</td>";
            echo "<td>".$timebegintemp."</td>";
            echo "<td>".$dateendtemp."</td>";
            echo "<td>".$timeendtemp."</td>";
            echo "<td>".(isset($dt['Requirement'])?$dt['Requirement']:"&nbsp;")."</td>";
            echo "<td>".(isset($dt['RequiredEnergy'])?$dt['RequiredEnergy']:"&nbsp;")."</td>";
            echo "<td>".(isset($dt['RequiredItem'])?$dt['RequiredItem']:"&nbsp;")."</td>";
            echo "<td>".(isset($dt['Rewards'])?$dt['Rewards']:"&nbsp;")."</td>";
            echo "<td>".(isset($dt['IsDone'])?$dt['IsDone']:"&nbsp;")."</td>";
            echo "<td>".(isset($dt['IsActive'])?$dt['IsActive']:"&nbsp;")."</td>";
            echo "<td>".(isset($dt['IsReturn'])?$dt['IsReturn']:"&nbsp;")."</td>";
            echo "</tr>";
            $i++;           
        }  
        echo "</table>";
    }
}

