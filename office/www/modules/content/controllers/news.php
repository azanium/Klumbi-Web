<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class News extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("News Stream","module9",FALSE,TRUE,"home");
    }
    function index()
    {
        $data['txtid']='';
        $data['title']='';
        $data['alias']='';
        $data['state']='';
        $data['contentpage']='';
        $data['classword']='contentarticle';
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
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
        );
        $this->template_admin->header_web(TRUE,"Stream News",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("news_view",$data);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("ContentNews");
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
        $keysearchdt="title";
        if($iSortCol_0==1)
        {
            $keysearchdt="title";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="alias";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="update";
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
            $delete="";
            $detail="";
            $data_like="";
            $data_comments="";
            $url_link=$this->template_admin->link("home/news/detail/".urlencode($dt['alias']));
            $set_link=$this->template_icon->detail_onclick("getlink('".$url_link."')","",'Get Link',"link.png","","");
            if($this->m_checking->actions("News Stream","module9","Edit",TRUE,FALSE,"home"))
            {
                $url=$this->template_admin->link("content/news/detail/".$dt['_id']);
                $detail=$this->template_icon->detail_onclick("list_detail('".$url."','','html')",$url,'Edit',"pencil.png","","linkdetail");
            }
            if($this->m_checking->actions("News Stream","module9","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete News Stream ".$dt['title']."')","",'Delete',"delete.png","","linkdelete");
            }
            if($this->m_checking->actions("News Stream","module9","View Likes",TRUE,FALSE,"home"))
            {
                $data_like=$this->template_icon->link_icon_notext("content/newssocial/datalike/".$dt['_id'],'Like ',"heart.png");
            }
            if($this->m_checking->actions("News Stream","module9","View Comments",TRUE,FALSE,"home"))
            {
                $data_comments=$this->template_icon->link_icon_notext("content/newssocial/datacomments/".$dt['_id'],'Comment',"comments.png");
            }
            $output['aaData'][] = array(
                $i,
                isset($dt['title'])?$dt['title']:"",
                isset($dt['alias'])?$dt['alias']:"",
                isset($dt['update'])?date('Y-m-d H:i:s',$dt['update']->sec):"",
                isset($dt['state_document'])?$dt['state_document']:"",
                $set_link.$data_comments.$data_like.$detail.$delete,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('content/news/index'); 
        }
    }
    function cruid_news()
    {        
        $this->form_validation->set_rules('title','Title','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('state','State','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('alias','Alias','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
        $isipesan="";
        if($this->form_validation->run()==FALSE)
        {
            $isipesan = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $id = $this->input->post('txtid',TRUE);
            $title = $this->input->post('title',TRUE);
            $alias = $this->input->post('alias',TRUE);
            $state = $this->input->post('state',TRUE);
            $generatetime=date("Y-m-d H:i:s");
            $time_start=  strtotime($generatetime);
            $url = current_url();
            $user = $this->session->userdata('username');
            $datatinsert=array(
                'title'  =>$title,
                'alias'  =>$alias,
                'text'  => $this->tambahan_fungsi->filter_text($_POST['contentarticle']),    
                'mobile'  =>$_POST['descmobile'], 
                'update'=>$this->mongo_db->time($time_start),
                'state_document'  =>$state,
            );
            if($id=='')
            {
                $this->m_checking->actions("News Stream","module9","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Articles");
                $this->mongo_db->select_collection("ContentNews");
                $this->mongo_db->insert($datatinsert);                
                $this->m_user->tulis_log("Add News",$url,$user);
            }
            else
            {
                $this->m_checking->actions("News Stream","module9","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Articles");
                $this->mongo_db->select_collection("ContentNews");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update News",$url,$user);
            }  
        }
        if(IS_AJAX)
        {            
            echo json_encode(array(
                'message'=>$isipesan,
                'success'=>TRUE,
            ));
        }
        else
        {
            redirect('content/news/index'); 
        }
    }
    function detail($id="")
    {
        $this->m_checking->actions("News Stream","module9","Edit",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("ContentNews");
        $article_detail = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        $data['txtid']='';
        $data['title']='';
        $data['alias']='';
        $data['state']='';
        $data['contentpage']='';    
        if($article_detail)
        {
                $data['txtid']=$article_detail['_id'];
                $data['title']=isset($article_detail['title'])?$article_detail['title']:'';
                $data['alias']=isset($article_detail['alias'])?$article_detail['alias']:'';                      
                $data['state']=isset($article_detail['state_document'])?$article_detail['state_document']:'';
                $data['contentpage']=isset($article_detail['text'])?$article_detail['text']:'';
                $data['mobiledesc']=isset($article_detail['mobile'])?$article_detail['mobile']:'';  
        }
        $data['classword']='contentpage'.$this->tambahan_fungsi->global_get_random(10);
        $this->load->view("form_news",$data);
    }
    function delete($id="")
    {
        $this->m_checking->actions("News Stream","module9","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("ContentNews");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete News Stream",$url,$user);
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
            redirect('content/news/index'); 
        }
    }
}
