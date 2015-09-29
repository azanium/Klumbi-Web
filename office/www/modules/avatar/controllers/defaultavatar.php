<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Defaultavatar extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_avatar");
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Default Avatar","module7",FALSE,TRUE,"home");
    }
    function index()
    {        
        $defaulkelamin = "male";
        $isiform['genderdefault'] = $defaulkelamin;
        $isiform['sizedefault'] = "medium";
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");  
        $isiform['anigenderdefault']=$this->m_avatar->default_item(array("gender" => $defaulkelamin,'tipe' =>"gender"));
        $isiform['facedefault']=$this->m_avatar->default_item(array("gender" => $defaulkelamin,'tipe' =>"face"));
        $isiform['handdefault']=$this->m_avatar->default_item(array("gender" => $defaulkelamin,'tipe' =>"hand"));
        $isiform['legdefault']=$this->m_avatar->default_item(array("gender" => $defaulkelamin,'tipe' =>"leg"));
        $isiform['bodydefault']=$this->m_avatar->default_item(array("gender"=>$defaulkelamin,'tipe' =>"body"));
        $isiform['pantsdefault']=$this->m_avatar->default_item(array("gender"=>$defaulkelamin,'tipe' =>"pants"));
        $isiform['eyesdefault']=$this->m_avatar->default_item(array('tipe'=>"face_part_eyes"));
        $isiform['eyebrowsdefault']=$this->m_avatar->default_item(array('tipe' =>"face_part_eye_brows"));
        $isiform['lipdefault']=$this->m_avatar->default_item(array('tipe' =>"face_part_lip"));
        $isiform['hairdefault']=$this->m_avatar->default_item(array('tipe' =>"hair"));        
        $isiform['hatdefault']=$this->m_avatar->default_item(array('tipe' =>"hat",'payment' =>"Default"));
        $isiform['shoesdefault']=$this->m_avatar->default_item(array('tipe' =>"shoes"));        
        $this->mongo_db->select_collection("SkinColor");
        $isiform['skindefault']=$this->mongo_db->findOne(array());
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
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",            
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
        );
        $this->template_admin->header_web(TRUE,"Default Configurations",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("listavatardefault_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("DefaultAvatar");
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
            $keysearchdt="gender";
        }
        else if($iSortCol_0==3)
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
            $title=(!isset($dt['name'])?"":$dt['name']);
            $gender=(!isset($dt['gender'])?"":$dt['gender']);
            $description=(!isset($dt['description'])?"":$dt['description']);
            $detail="";           
            if($this->m_checking->actions("Default Avatar","module7","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."')","",'Edit',"pencil.png","","linkdetail");
            }
            $output['aaData'][] = array(
                $i,
                $title,
                $gender,
                $description,
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
            redirect('avatar/defaultavatar/index'); 
        }
    }
    function detail_data($id="")
    {
        $this->m_checking->actions("Default Avatar","module7","Edit",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("DefaultAvatar");
        $output['message'] = "Data tidak ditemukan";
        $output['success'] = FALSE;
        $tampung = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($tampung)
        {
            $output['message'] = "<i class='success'>Data ditemukan</i>";
            $output['success'] = TRUE;
            $output['data']['name'] = $tampung['name'];
            $output['data']['gender'] = $tampung['gender'];
            $output['data']['size'] = $tampung['size'];
            $output['data']['description'] = $tampung['description'];
            $tempdtconfigall = $this->m_avatar->avatarconfig($tampung['configuration'],$tampung["size"],"web");
            $tempconfigstring = str_replace('"',"'", json_encode($tempdtconfigall['configurations']));
            if($tempconfigstring == "[[]]")
            {
                $tempconfigstring = "";
            }
            $output['data']['configurations'] = $tempconfigstring;
            $output['data']['dataconf'] = $tempdtconfigall['dataconf'];
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('avatar/defaultavatar/index'); 
        }
    }
    function cruid_avatar()
    {        
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('title','Name Avatar Default','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtgender','Gender','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtskin','Skin','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtsize','Body Size','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('eyes','Eyes','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('eyeBrows','Eyebrow','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('lip','Lip','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('hair','Hair','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('body','Body','trim|required|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('gender','Gender','trim|htmlspecialchars|xss_clean'); 
        $this->form_validation->set_rules('hat','Hat','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('shoes','Shoes','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('pants','Pants','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('hand','Hand','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('leg','Leg','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('face','Face','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('description','Description','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfileimgname','File Image','trim|htmlspecialchars|xss_clean');
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
            $title = $this->input->post('title',TRUE);
            $gender = $this->input->post('txtgender',TRUE);
            $skin = $this->input->post('txtskin',TRUE);
            $size = $this->input->post('txtsize',TRUE);            
            $eyes = $this->input->post('eyes',TRUE);
            $idgender = $this->input->post('gender',TRUE);
            $eyebrows = $this->input->post('eyeBrows',TRUE);
            $lip = $this->input->post('lip',TRUE);
            $hair = $this->input->post('hair',TRUE);
            $body = $this->input->post('body',TRUE);
            $hat = $this->input->post('hat',TRUE);
            $shoes = $this->input->post('shoes',TRUE);
            $pants = $this->input->post('pants',TRUE);
            $hands = $this->input->post('hand',TRUE);
            $legs = $this->input->post('leg',TRUE);
            $face = $this->input->post('face',TRUE);
            $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'hand','gender'=>$gender));
            if($cekdatatemp)
            {
                $hands = (string)$cekdatatemp["_id"];
            }
            $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'face','gender'=>$gender));
            if($cekdatatemp)
            {
                $face = (string)$cekdatatemp["_id"];
            }
            $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'gender','gender'=>$gender));
            if($cekdatatemp)
            {
                $idgender = (string)$cekdatatemp["_id"];
            }
            $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'leg','gender'=>$gender));
            if($cekdatatemp)
            {
                $leg = (string)$cekdatatemp["_id"];
            }
            $description = $this->input->post('description',TRUE);
            $datatinsert=array(
                'name'  =>$title,
                "description" => $description,
                'gender' => $gender,
                'configuration'=>array(
                    array('tipe'=>"Skin","color"=>$skin),
                    array('tipe'=>"Leg","id"=>$legs),
                    array('tipe'=>"Hand","id"=>$hands),
                    array('tipe'=>"Face","id"=>$face),
                    array('tipe'=>"Gender","id"=>$idgender),                    
                    array('tipe'=>"EyeBrows","id"=>(string)$eyebrows),
                    array('tipe'=>"Eyes","id"=>(string)$eyes),
                    array('tipe'=>"Lip","id"=>(string)$lip),
                    array('tipe'=>"Hair","id"=>(string)$hair),
                    array('tipe'=>"Body","id"=>(string)$body),
                    array('tipe'=>"Pants","id"=>(string)$pants),
                    array('tipe'=>"Shoes","id"=>(string)$shoes),
                    array('tipe'=>"Hat","id"=>(string)$hat),
                ),
            );             
            $this->m_checking->actions("Default Avatar","module7","Edit",FALSE,FALSE,"home");
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("DefaultAvatar");
            $this->mongo_db->update(array('gender'=> $gender ),array('$set'=>$datatinsert));
            $this->m_user->tulis_log("Update Default Avatar",$url,$user);
            $output['message'] = "<i class='success'>Data is updated</i>";
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('avatar/defaultavatar/index'); 
        }
    }
}
