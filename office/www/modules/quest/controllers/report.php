<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
    }
    function index()
    {
        $dataadd['countperjml']=$this->__list_datadrjumlah();
        $dataadd['countperactive']=$this->__list_datadractive();
        $dataadd['rankactive']=$this->__list_countactive();
        $dataadd['rankjournal']=$this->__list_countjournal();
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
        $this->template_admin->header_web(TRUE,"Quest Reporting",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("report_view",$dataadd);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
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
        $keysearchdt="username";
        if($iSortCol_0==1)
        {
            $keysearchdt="email";
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
        foreach($data as $dt)
        {
            $this->mongo_db->select_collection("Properties");
            $dataproperties=$this->mongo_db->findOne(array('lilo_id' => (string)$dt['_id']));
            $fullname="";
            $twitter="";
            $phone="";
            if($dataproperties)
            {
                $fullname=isset($dataproperties['fullname'])?$dataproperties['fullname']:"";
                $twitter=isset($dataproperties['twitter'])?$dataproperties['twitter']:"";
                $phone=isset($dataproperties['handphone'])?$dataproperties['handphone']:"";;
            }
            $priview_complate=$this->template_icon->detail_onclick("lihatdetail('completed','".$dt['_id']."')","",'Lihat',"accept.png","","linkdelete");
            $priview_current=$this->template_icon->detail_onclick("lihatdetail('onplay','".$dt['_id']."')","",'Lihat',"bullet_error.png","","linkdelete");
            $output['aaData'][] = array(
                $dt['username'],
                $dt['email'],
                $fullname,                
                $twitter,
                $phone,
                $priview_complate,
                $priview_current,
            );         
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quest/reportplayer/index'); 
        }
    }
    function list_dataquest()
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
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        foreach($data as $dt)
        {
            $priview_complate=$this->template_icon->detail_onclick("lihatdetailquest('completed','".$dt['ID']."')","",'Lihat',"accept.png","","linkdelete");
            $priview_current=$this->template_icon->detail_onclick("lihatdetailquest('onplay','".$dt['ID']."')","",'Lihat',"bullet_error.png","","linkdelete");
            $datebegintemp="";
            $dateendtemp="";
            if($dt['EndDate']!='')
            {
                $dateendtemp=date('Y-m-d H:i:s', $dt['EndDate']->sec);
            }
            if($dt['StartDate']!='')
            {
                $datebegintemp=date('Y-m-d H:i:s', $dt['StartDate']->sec);
            }
            $output['aaData'][] = array(
                $dt['ID'],
                $dt['Description'],
                $datebegintemp,
                $dateendtemp,
                $priview_complate,
                $priview_current,
            );         
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quest/reportquest/index'); 
        }
    }
    function __list_datadrjumlah()
    {
        $this->mongo_db->select_db("Game");
        $data=$this->mongo_db->command_values(array('distinct' => "QuestJournal", 'key' => "userid"));
        $output = array();
        foreach($data['values'] as $dt)
        {
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("QuestJournal");
            $count=$this->mongo_db->count(array('userid' => $dt));
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Properties");
            $dataproperties=$this->mongo_db->findOne(array('lilo_id' => (string)$dt));
            $this->mongo_db->select_collection("Account");
            $datamember=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($dt)));
            $fullname="";
            $twitter="";
            $phone="";
            if($dataproperties)
            {
                $fullname=isset($dataproperties['fullname'])?$dataproperties['fullname']:"";
                $twitter=isset($dataproperties['twitter'])?$dataproperties['twitter']:"";
                $phone=isset($dataproperties['handphone'])?$dataproperties['handphone']:"";;
            }
            $output[] = array(
                '_id'=>$dt,
                'fullname'=>$fullname,
                'email'=>$datamember['email'],
                'twitter'=>$twitter,
                'phone'=>$phone,
                'count'=>"Player yang menyelesaikan ".$count." Quest",
            );         
        }  
	return  $output;
    }
    function __list_datadractive()
    {
        $this->mongo_db->select_db("Game");
        $data=$this->mongo_db->command_values(array('distinct' => "QuestActive", 'key' => "userid"));
        $output = array();
        foreach($data['values'] as $dt)
        {
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("QuestActive");
            $count=$this->mongo_db->count(array('userid' => $dt));
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Properties");
            $dataproperties=$this->mongo_db->findOne(array('lilo_id' => (string)$dt));
            $this->mongo_db->select_collection("Account");
            $datamember=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($dt)));
            $fullname="";
            $twitter="";
            $phone="";
            if($dataproperties)
            {
                $fullname=isset($dataproperties['fullname'])?$dataproperties['fullname']:"";
                $twitter=isset($dataproperties['twitter'])?$dataproperties['twitter']:"";
                $phone=isset($dataproperties['handphone'])?$dataproperties['handphone']:"";;
            }
            $output[] = array(
                '_id'=>$dt,
                'fullname'=>$fullname,
                'email'=>$datamember['email'],
                'twitter'=>$twitter,
                'phone'=>$phone,
                'count'=>"Player yang masih memainkan ".$count." Quest",
            );         
        }  
	return  $output;
    }
    function __list_countactive()
    {
        $this->mongo_db->select_db("Game");
        $data=$this->mongo_db->command_values(array('distinct' => "QuestActive", 'key' => "questid"));
        $output = array();
        foreach($data['values'] as $dt)
        {
            $this->mongo_db->select_collection("QuestActive");
            $count=$this->mongo_db->count(array('questid' => (int)$dt));
            $this->mongo_db->select_collection("Quest");
            $dataproperties=$this->mongo_db->findOne(array('ID' => (string)$dt));
            $tglstart="";
            $tglend="";
            if($dataproperties['StartDate']!="")
            {
                $tglstart=date('Y-m-d H:i:s', $dataproperties['StartDate']->sec);
            }
            if($dataproperties['EndDate']!="")
            {
                $tglend=date('Y-m-d H:i:s', $dataproperties['EndDate']->sec);
            }
            $output[] = array(
                'ID'=>isset($dataproperties['ID'])?$dataproperties['ID']:"&nbsp;",
                'Description'=>isset($dataproperties['Description'])?$dataproperties['Description']:"&nbsp;",
                'tglawal'=>$tglstart,
                'tglakhir'=>$tglend,
                'count'=>$count,
            );         
        }  
	return  $output;
    }
    function __list_countjournal()
    {
        $this->mongo_db->select_db("Game");
        $data=$this->mongo_db->command_values(array('distinct' => "QuestJournal", 'key' => "questid"));
        $output = array();
        foreach($data['values'] as $dt)
        {
            $this->mongo_db->select_collection("QuestJournal");
            $count=$this->mongo_db->count(array('questid' => (int)$dt));
            $this->mongo_db->select_collection("Quest");
            $dataproperties=$this->mongo_db->findOne(array('ID' => (string)$dt));
            $tglstart="";
            $tglend="";
            if($dataproperties['StartDate']!="")
            {
                $tglstart=date('Y-m-d H:i:s', $dataproperties['StartDate']->sec);
            }
            if($dataproperties['EndDate']!="")
            {
                $tglend=date('Y-m-d H:i:s', $dataproperties['EndDate']->sec);
            }
            $output[] = array(
                'ID'=>isset($dataproperties['ID'])?$dataproperties['ID']:"&nbsp;",
                'Description'=>isset($dataproperties['Description'])?$dataproperties['Description']:"&nbsp;",
                'tglawal'=>$tglstart,
                'tglakhir'=>$tglend,
                'count'=>$count,
            );         
        }  
	return  $output;
    }
    function quest_player($state="completed",$id_member="")
    {
        $this->mongo_db->select_db("Game");
        $dataadd['id_user']=$id_member;
        if($state=="completed")
        {            
            $this->mongo_db->select_collection("QuestJournal");
            $dataadd['jns']=TRUE;
        }
        else
        {
            $this->mongo_db->select_collection("QuestActive");
            $dataadd['jns']=FALSE;
        }
        $data=$this->mongo_db->find(array('userid'=>$id_member),0,0,array());
        $dataadd['isitable']=array();
        if($data)
        {
            $this->mongo_db->select_collection("Quest");
            foreach($data as $dt)
            {
                $datatemp=$this->mongo_db->findOne(array("ID"=>(string)$dt['questid']));
                $jamawal="";
                if($dataadd['jns'])
                {
                    $jamawal=isset($dt['start_date'])?$dt['start_date']:"";
                }
                else
                {
                    $jamawal=isset($dt['datetime'])?$dt['datetime']:"";
                }
                $dataadd['isitable'][]=array(
                    'ID'=>$datatemp['ID'],
                    'Description'=>$datatemp['Description'],
                    'start_date'=>$jamawal,
                    'end_date'=>isset($dt['end_date'])?$dt['end_date']:"",
                        );
            }
        }
        $this->load->view("inner_report",$dataadd);
    }
    function quest_quest($state="completed",$id_quest="")
    {
        $this->mongo_db->select_db("Game");
        $dataadd['id_quest']=$id_quest;
        if($state=="completed")
        {            
            $this->mongo_db->select_collection("QuestJournal");
            $dataadd['jns']=TRUE;
        }
        else
        {
            $this->mongo_db->select_collection("QuestActive");
            $dataadd['jns']=FALSE;
        }
        $dataadd['isitable']=array();
        $data=$this->mongo_db->find(array('questid'=>(int)$id_quest),0,0,array());
        if($data)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");
            foreach($data as $dt)
            {
                $tgllama="";
                if($dataadd['jns'])
                {
                    $tgllama=isset($dt['start_date'])?$dt['start_date']:"";
                }
                else
                {
                    $tgllama=isset($dt['datetime'])?$dt['datetime']:"";
                }      
                $tempdata=$this->mongo_db->findOne(array("_id"=>$this->mongo_db->mongoid($dt['userid'])));
                $dataadd['isitable'][]=array(
                    '_id'=>$dt['userid'], 
                    'username'=>$tempdata['username'],   
                    'email'=>$tempdata['email'],
                    'start_date'=>$tgllama,
                    'end_date'=>isset($dt['end_date'])?$dt['end_date']:"",
                  );
            }
        }
        $this->load->view("inner_reportquest",$dataadd);
    }
    function quest_journal($idmember="")
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("QuestJournal");
        $dataadd['isitable']=array();
        $data=$this->mongo_db->find(array('userid'=>$idmember),0,0,array());
        if($data)
        {
            $this->mongo_db->select_collection("Quest");
            foreach($data as $dt)
            {
                $tempdata=$this->mongo_db->findOne(array("ID"=>(string)$dt['questid']));
                $dataadd['isitable'][]=array(
                    'ID'=>isset($tempdata['ID'])?$tempdata['ID']:"",
                    'Description'=>isset($tempdata['Description'])?$tempdata['Description']:"",
                    'start_date'=>$dt['start_date'],
                    'end_date'=>$dt['end_date'],
                    );
            }
        }
        $this->load->view("inner_reportjournal",$dataadd);
    }
    function delete($id_user="",$id_quest="")
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("QuestJournal");
        $this->mongo_db->remove(array('questid' => (int)$id_quest, 'userid' => $id_user));
        $this->mongo_db->select_collection("QuestActive");
        $this->mongo_db->remove(array('questid' => (int)$id_quest, 'userid' => $id_user));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Quest Journal",$url,$user);
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
            redirect('quest/reportplayer/index'); 
        }
    }
}



