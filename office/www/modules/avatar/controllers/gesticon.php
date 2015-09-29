<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gesticon extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("EMO","module6",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Animation");
        $isiform['animation']=$this->mongo_db->find(array('gender'=>'male'),0,0,array('name'=>1));
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/form-select2/select2.css",
            base_url()."resources/plugin/form-multiselect/css/multi-select.css",   
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/form-multiselect/js/jquery.multi-select.min.js",
            base_url()."resources/plugin/quicksearch/jquery.quicksearch.min.js",
            base_url()."resources/plugin/form-typeahead/typeahead.min.js",
            base_url()."resources/plugin/form-select2/select2.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
        );
        $this->template_admin->header_web(TRUE,"Gesticon",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("gesticon_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function get_combo($gender="male")
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Animation");
        $animation=$this->mongo_db->find(array('gender'=>$gender),0,0,array('name'=>1));
        foreach($animation as $dt=>$value)
        {
            $tempdt=  explode("@",str_replace(".unity3d", "", $value['animation_file']));                                    
            if(isset($tempdt[1]))
            {
                $nilai=$tempdt[1];
                if(substr($nilai, 0, 1)!="_")
                {
                    echo "<option value='".$nilai."'>".$value['name']."</option>";
                }                                        
            }
        }
    }
    function list_data()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Gesticon");
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
        $keysearchdt="command";
        if($iSortCol_0==1)
        {
            $keysearchdt="command";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="animation";
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
            $check="false";
            $dtanimation=$dt['animation'];
            if(substr($dt['animation'], 0, 1)=="@")
            {
                $check="true";
                $dtanimation= str_replace("@", "", $dt['animation']);
            }
            $detail="";
            $delete="";
            if($this->m_checking->actions("EMO","module6","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."','".$dt['command']."','".$dt['gender']."','".$dtanimation."',".$check.")","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            }
            if($this->m_checking->actions("EMO","module6","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete EMO ".$dt['command']."')","",'Delete',"delete.png","","linkdelete");
            }
            $output['aaData'][] = array(
                $i,
                $dt['command'],
                $dt['animation'],
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
            redirect('avatar/gesticon/index'); 
        }
    }
    function cruid_gesticon()
    {        
        $this->form_validation->set_rules('command','Command','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('gender','Gender','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('animation','Animation','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('radioloop','IsLoop','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
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
            $command = $this->input->post('command',TRUE);
            $gender = $this->input->post('gender',TRUE);
            if($gender=="")
            {
                $gender="unisex";
            }
            $animation = $this->input->post('animation',TRUE);
            $radioloop = $this->input->post('radioloop',TRUE);
            $datatinsert=array(
                'command'  =>$command,
                'gender'  =>$gender,
                'animation'  =>($radioloop=="true")?"@".$animation:$animation,
            );
            if($id=='')
            {
                $this->m_checking->actions("EMO","module6","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Gesticon");
                $level_id=$this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add EMO",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {
                $this->m_checking->actions("EMO","module6","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Gesticon");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update EMO",$url,$user);
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
            redirect('avatar/gesticon/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("EMO","module6","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Gesticon");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete EMO",$url,$user);
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
            redirect('avatar/gesticon/index'); 
        }
    }
}

