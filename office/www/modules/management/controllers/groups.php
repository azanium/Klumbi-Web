<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Groups extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','Template_admin','Template_icon','Tambahan_fungsi','Cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Group User","module2",FALSE,TRUE,"home");
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
            base_url()."resources/plugin/treeview/jquery.treeview.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js", 
            base_url()."resources/plugin/treeview/jquery.treeview.async.js",
            base_url()."resources/plugin/treeview/jquery.treeview.js",
        );
        $this->template_admin->header_web(TRUE,"User Group",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("listgroups_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Group");
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
        $keysearchdt="Name";
        if($iSortCol_0==1)
        {
            $keysearchdt="Name";
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
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $config="";
            $detail="";
            $delete="";   
            if($this->m_checking->actions("Group User","module2","Setting Akses",TRUE,FALSE,"home"))
            {
                $config=$this->template_icon->detail_onclick("settingmodule('".$dt['_id']."')","",'Edit Access',"building.png","","linkdetail");
            }
            if($this->m_checking->actions("Group User","module2","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."','".$dt['Name']."','".$dt['Description']."')","#editdata",'Detail',"page_white_edit.png","","","data-toggle='modal'");
            }
            if($this->m_checking->actions("Group User","module2","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Group ".$dt['Name']."')","",'Delete',"delete.png","","linkdelete");
            }
            $output['aaData'][] = array(
                $i,
                $dt['Name'],
                $dt['Description'],
                $config.$detail.$delete,
            );
            $i++;           
        }  
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('management/groups/index'); 
        }
    }    
    function cruid_groups()
    {        
        $this->form_validation->set_rules('name','Group Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('description','Descriptions','trim|htmlspecialchars|xss_clean');
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
            $name = $this->input->post('name',TRUE);
            $description = $this->input->post('description',TRUE);
            if($id=='')
            {
                $this->m_checking->actions("Group User","module2","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Group");
                $codemenu=$this->tambahan_fungsi->global_get_random(10);
                $datatinsert=array(
                    'Name'  =>$name,
                    'Code' =>$codemenu,
                    'Description'  =>$description,
                );
                $this->mongo_db->insert($datatinsert);
//                /*Begin Create Default trModule*/
                $this->mongo_db->select_collection("Module");
                $data=$this->mongo_db->find(array(),0,0,array('Order'=>1));
                $this->mongo_db->select_collection("trModule");
                $this->mongo_db->remove(array('Group'  =>$codemenu));
                foreach($data as $dt)
                {
                    $datatinsert=array(
                            'Module'  =>$dt["Code"],
                            'Group'  =>$codemenu,
                            'IsActive'=>TRUE,
                    );
                    $this->mongo_db->insert($datatinsert);
                }
//                /*Begin Create Default trMenu*/
                $this->mongo_db->select_collection("Menu");
                $data=$this->mongo_db->find(array(),0,0,array('Order'=>1));
                $this->mongo_db->select_collection("trMenu");
                $this->mongo_db->remove(array('Group'  =>$codemenu));
                foreach($data as $dt)
                {
                    $this->mongo_db->select_collection("trMenu");
                    $datatinsert=array(
                            'Module'  =>$dt["module"],
                            'Menu'  =>$dt["Code"],
                            'Group'  =>$codemenu,
                            'IsActive'=>FALSE,
                    );
                    $this->mongo_db->insert($datatinsert);         
                    $this->mongo_db->select_collection("trActions");
                    $this->mongo_db->remove(array('Group'  =>$codemenu,'Module'  =>$dt["module"],'Menu'  =>$dt["Code"],));
                    if(isset($dt["ListActions"]))
                    {
                        foreach($dt["ListActions"] as $dtactions=>$value)
                        {
                            $datatinsert=array(
                                    'Module'  =>$dt["module"],
                                    'Menu'  =>$dt["Code"],
                                    'Group'  =>$codemenu,
                                    'Actions'=>  $dtactions,
                                    'IsActive'=>FALSE,
                            );
                            $this->mongo_db->insert($datatinsert);
                        }
                    }                    
                }
                $this->m_user->tulis_log("Add Group Access",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {
                $this->m_checking->actions("Group User","module2","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Group");
                $datatinsert=array(
                    'Name'  =>$name,
                    'Description'  =>$description,
                );
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Group Access",$url,$user);
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
            redirect('management/groups/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Group User","module2","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Group");
        $codemenu=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));        
        $this->mongo_db->select_collection("trModule");
        $this->mongo_db->remove(array('Group'  =>$codemenu['Code']));
        $this->mongo_db->select_collection("trMenu");
        $this->mongo_db->remove(array('Group'  =>$codemenu['Code'])); 
        $this->mongo_db->select_collection("trActions");
        $this->mongo_db->remove(array('Group'  =>$codemenu['Code'])); 
        $this->mongo_db->select_collection("Group");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Group Access",$url,$user);
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
            redirect('management/groups/index'); 
        }
    }
    function setting_load($id="")
    {
        $this->m_checking->actions("Group User","module2","Setting Akses",FALSE,FALSE,"home");
        $datapage['idgroup']=$id;
        $this->load->view("datamenu_view",$datapage);
    }
    function cruid_menu()
    {
        $this->m_checking->actions("Group User","module2","Setting Akses",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("trModule");
        $filter=array(
            "Group"=>$_POST['group'],
        );
        $this->mongo_db->update($filter,array('$set'=>array("IsActive"=>FALSE)));
        for($i=0;$i<count($_POST['module']);$i++)
        {
            $nilaimodule=  explode("_", $_POST['module'][$i]);
            $filter=array(
                "Module"=>$nilaimodule[1],
                "Group"=>$nilaimodule[0],
            );
            $this->mongo_db->update($filter,array('$set'=>array("IsActive"=>TRUE)));
        }
        $this->mongo_db->select_collection("trMenu");
        $filter=array(
            "Group"=>$_POST['group'],
        );
        $this->mongo_db->update($filter,array('$set'=>array("IsActive"=>FALSE)));
        for($i=0;$i<count($_POST['menu']);$i++)
        {
            $nilaimenu=  explode("_", $_POST['menu'][$i]);
            $filter=array(
                "Module"=>$nilaimenu[1],
                "Group"=>$nilaimenu[0],
                "Menu"=>$nilaimenu[2],
            );
            $this->mongo_db->update($filter,array('$set'=>array("IsActive"=>TRUE)));
        }
        $this->mongo_db->select_collection("trActions");
        $filter=array(
            "Group"=>$_POST['group'],
        );
        $this->mongo_db->update($filter,array('$set'=>array("IsActive"=>FALSE))); 
        for($i=0;$i<count($_POST['actions']);$i++)
        {
            $nilaiaction=  explode("_", $_POST['actions'][$i]);
            $filter=array(
                "Module"=>$nilaiaction[1],
                "Group"=>$nilaiaction[0],
                "Menu"=>$nilaiaction[2],
                "Actions"=>$nilaiaction[3],
            );
            $this->mongo_db->update($filter,array('$set'=>array("IsActive"=>TRUE)));
        }
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Update Menu Action Access Group",$url,$user);
        $output = array(
            "message" =>"Data menu is updated",
            "success" =>TRUE,
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('management/groups/index'); 
        }
    }
}


