<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Avatar extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");
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
        if($iSortCol_0==2)
        {
            $keysearchdt="code";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="name";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="tipe";
        }
        else if($iSortCol_0==5)
        {
            $keysearchdt="gender";
        }
        else if($iSortCol_0==6)
        {
            $keysearchdt="category";
        }
        else if($iSortCol_0==7)
        {
            $keysearchdt="payment";
        }
        else if($iSortCol_0==8)
        {
            $keysearchdt="brand_id";
        }
        else if($iSortCol_0==9)
        {
            $keysearchdt="size";
        }
        else if($iSortCol_0==10)
        {
            $keysearchdt="color";
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
            $childcode=(!isset($dt['code'])?"":$dt['code']);
            $childname=(!isset($dt['name'])?"":$dt['name']);
            $childtipe=(!isset($dt['tipe'])?"":$dt['tipe']);
            $childgender=(!isset($dt['gender'])?"":$dt['gender']);
            $childcategory=(!isset($dt['category'])?"":$dt['category']);
            $childpayment=(!isset($dt['payment'])?"":$dt['payment']); 
            $brandname="";
            if(isset($dt['brand_id']))
            {
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Brand");
                $checkdata=$this->mongo_db->findOne(array('brand_id'=>$dt['brand_id']));
                if($checkdata)
                {
                    $brandname = $checkdata['name'];
                } 
                else
                {
                    $brandname = $dt['brand_id'];
                }
            }
            $childsize=(!isset($dt['size'])?"":$dt['size']); 
            $childcolor=(!isset($dt['color'])?"":$dt['color']);            
            $warna= "<span style='width: 15px;height: 15px;display:block;margin:20px 3px 3px 3px;background-color: ".$childcolor.";'></span>";
            $actionmenu=$this->template_icon->detail_onclick("getavataritemval('".(string)$dt['_id']."')","",'Set Value',"bullet_go.png","","linkdetail");
            $output['aaData'][] = array(
                $i,
                $childcode,
                $childname,
                $childtipe,
                $childgender,
                $childcategory,
                $childpayment,
                $brandname,
                $childsize,
                $warna,
                $actionmenu,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect($this->session->userdata('urlsebelumnya')); 
        }
    }
    function loaddtavatar()
    {
        $this->load->library('form_validation');
        $this->load->model("m_avatar");
        $this->form_validation->set_rules('id','ID','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('size','Size','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('gender','Gender','trim|required|htmlspecialchars|xss_clean');
        $output['message'] = "";
        $output['success'] = FALSE;
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $id_ava_item = $this->input->post('id',TRUE);
            $size = $this->input->post('size',TRUE);
            $gender = $this->input->post('gender',TRUE);
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Avatar");
            $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id_ava_item)));
            if($avaitem_tamp)
            {
                if($avaitem_tamp["tipe"] === "face_part_eyes" || $avaitem_tamp["tipe"] === "face_part_eye_brows" || $avaitem_tamp["tipe"] === "face_part_lip" || $avaitem_tamp["tipe"] === "EyeBrows" || $avaitem_tamp["tipe"] === "Eyes" || $avaitem_tamp["tipe"] === "Lip")
                {
                    $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp["tipe"],"medium","web");
                    $output['configurations'] = "{'tipe':'" . $cekdtavaitem["tipe"] . "','element':'" .$cekdtavaitem["material"]. "'}";
                }
                else
                {
                    $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp["tipe"],$size,"web");
                    $output['configurations'] = "{'tipe':'" . $cekdtavaitem["tipe"] . "','element':'" .$cekdtavaitem["element"]. "','material':'" .$cekdtavaitem["material"]. "'}";
                }
            }
            $output['message'] = "<i class='success'>Data is loaded</i>";
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect($this->session->userdata('urlsebelumnya')); 
        }
    }
    function cekconf()
    {
        $this->load->library('form_validation');
        $this->load->model("m_avatar");
        $this->form_validation->set_rules('txtgender','Gender','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtskin','Skin','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtsize','Size','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('hand','Hand','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('leg','Leg','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('eyes','Eyes','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('eyeBrows','EyeBrows','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('lip','Lip','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('hair','Hair','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('body','Body','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('hat','Hat','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('shoes','Shoes','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('pants','Pants','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('gender','Gender','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('face','Face','trim|htmlspecialchars|xss_clean');
        $output['message'] = "";
        $output['success'] = FALSE;
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $gender = $this->input->post('txtgender',TRUE);
            $skin = $this->input->post('txtskin',TRUE);
            $size = $this->input->post('txtsize',TRUE);
            $hand = $this->input->post('hand',TRUE);
            $leg = $this->input->post('leg',TRUE);
            $eyes = $this->input->post('eyes',TRUE);
            $eyeBrows = $this->input->post('eyeBrows',TRUE);
            $lip = $this->input->post('lip',TRUE);
            $hair = $this->input->post('hair',TRUE);
            $body = $this->input->post('body',TRUE);
            $hat = $this->input->post('hat',TRUE);
            $shoes = $this->input->post('shoes',TRUE);
            $pants = $this->input->post('pants',TRUE);
            $idgender = $this->input->post('gender',TRUE);
            $face = $this->input->post('face',TRUE);
            $configava = array();
            $subconfigava = array();
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Avatar");
            $configava[]= array('tipe'=>"Skin","color"=>($skin=="")?"1":$skin);
            $gender = ($gender=="")?"male":$gender;
            $size = ($size=="")?"medium":$size;
            //hand
            $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($hand)));
            if($avaitem_tamp)
            {
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");                                             
            }
            else
            {
                $avaitem_tamp = $this->mongo_db->findOne(array("gender" => $gender,'tipe' =>"hand"));
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");
            }
            if(isset($avaitem_tamp))
            {
                $configava[]= array('tipe'=>$cekdtavaitem["tipe"], "element" => $cekdtavaitem["element"], "material" => $cekdtavaitem["material"],"id"=>(string)$avaitem_tamp["_id"]);
            }
            //leg
            $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($leg)));
            if($avaitem_tamp)
            {
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");                                             
            }
            else
            {
                $avaitem_tamp = $this->mongo_db->findOne(array("gender" => $gender,'tipe' =>"leg"));
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");
            }
            if(isset($avaitem_tamp))
            {
                $configava[]= array('tipe' => $cekdtavaitem["tipe"], "element" => $cekdtavaitem["element"], "material" => $cekdtavaitem["material"],"id"=>(string)$avaitem_tamp["_id"]);
            }
            //Hair
            $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($hair)));
            if($avaitem_tamp)
            {
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");                                             
            }
            else
            {
                $avaitem_tamp = $this->mongo_db->findOne(array('tipe' =>"hair"));
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");
            }
            if(isset($avaitem_tamp))
            {
                $configava[]= array('tipe' => $cekdtavaitem["tipe"], "element" => $cekdtavaitem["element"], "material" => $cekdtavaitem["material"],"id"=>(string)$avaitem_tamp["_id"]);
            }
            //Body
            $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($body)));
            if($avaitem_tamp)
            {
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],$size,"web");                                             
            }
            else
            {
                $avaitem_tamp = $this->mongo_db->findOne(array("gender"=>$gender,'tipe' =>"body"));
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],$size,"web");
            }
            if(isset($avaitem_tamp))
            {
                $configava[]= array('tipe'=>$cekdtavaitem["tipe"],"element"=>$cekdtavaitem["element"],"material"=>$cekdtavaitem["material"],"id"=>(string)$avaitem_tamp["_id"]);
            }
            //Hat
            $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($hat)));
            if($avaitem_tamp)
            {
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");                                             
            }
            else
            {
                $avaitem_tamp = $this->mongo_db->findOne(array('tipe' =>"hat",'payment' =>"Default"));
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");
            }
            if(isset($avaitem_tamp))
            {
                $configava[]= array('tipe'=>$cekdtavaitem["tipe"],"element"=>$cekdtavaitem["element"],"material"=>$cekdtavaitem["material"],"id"=>(string)$avaitem_tamp["_id"]);
            }
            //Shoes
            $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($shoes)));
            if($avaitem_tamp)
            {
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");                                             
            }
            else
            {
                $avaitem_tamp = $this->mongo_db->findOne(array('tipe' =>"shoes"));
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");
            }
            if(isset($avaitem_tamp))
            {
                $configava[]= array('tipe'=>$cekdtavaitem["tipe"],"element"=>$cekdtavaitem["element"],"material"=>$cekdtavaitem["material"],"id"=>(string)$avaitem_tamp["_id"]);
            }
            //Pants
            $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($pants)));
            if($avaitem_tamp)
            {
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],$size,"web");                                             
            }
            else
            {
                $avaitem_tamp = $this->mongo_db->findOne(array("gender"=>$gender,'tipe' =>"pants"));
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],$size,"web");
            }
            if(isset($avaitem_tamp))
            {
                $configava[]= array('tipe'=>$cekdtavaitem["tipe"],"element"=>$cekdtavaitem["element"],"material"=>$cekdtavaitem["material"],"id"=>(string)$avaitem_tamp["_id"]);
            }
            //Gender
            $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($idgender)));
            if($avaitem_tamp)
            {
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");                                             
            }
            else
            {
                $avaitem_tamp = $this->mongo_db->findOne(array("gender" => $gender,'tipe' =>"gender"));
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");
            }
            if(isset($avaitem_tamp))
            {
                $configava[]= array('tipe'=>$cekdtavaitem["tipe"],"element"=>$cekdtavaitem["element"],"material"=>$cekdtavaitem["material"],"id"=>(string)$avaitem_tamp["_id"]);
            }
            //Eyes
            $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($eyes)));
            if($avaitem_tamp)
            {
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");                                             
            }
            else
            {
                $avaitem_tamp = $this->mongo_db->findOne(array('tipe'=>"face_part_eyes"));
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");
            }
            if(isset($avaitem_tamp))
            {
                $subconfigava[$cekdtavaitem["tipe"]]= $cekdtavaitem["material"];
                $subconfigava["id_".$cekdtavaitem['tipe']]= (string)$avaitem_tamp["_id"];
            }
            //eyeBrows
            $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($eyeBrows)));
            if($avaitem_tamp)
            {
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");                                             
            }
            else
            {
                $avaitem_tamp = $this->mongo_db->findOne(array('tipe'=>"face_part_eye_brows"));
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");
            }
            if(isset($avaitem_tamp))
            {
                $subconfigava[$cekdtavaitem["tipe"]]= $cekdtavaitem["material"];
                $subconfigava["id_".$cekdtavaitem['tipe']]= (string)$avaitem_tamp["_id"];
            }
            //Lip
            $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($lip)));
            if($avaitem_tamp)
            {
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");                                             
            }
            else
            {
                $avaitem_tamp = $this->mongo_db->findOne(array('tipe'=>"face_part_lip"));
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");
            }
            if(isset($avaitem_tamp))
            {
                $subconfigava[$cekdtavaitem["tipe"]]= $cekdtavaitem["material"];
                $subconfigava["id_".$cekdtavaitem['tipe']]= (string)$avaitem_tamp["_id"];
            }
            //Face
            $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($face)));
            if($avaitem_tamp)
            {
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");                                             
            }
            else
            {
                $avaitem_tamp = $this->mongo_db->findOne(array("gender" => $gender,'tipe' =>"face"));
                $cekdtavaitem = $this->m_avatar->data_unity_desc($avaitem_tamp,$avaitem_tamp['tipe'],"medium","web");
            }
            if(isset($avaitem_tamp))
            {
                $subconfigava["tipe"]= $cekdtavaitem["tipe"];
                $subconfigava["element"]= $cekdtavaitem["element"];
                $subconfigava["material"]= $cekdtavaitem["material"];
                $subconfigava["id"]= (string)$avaitem_tamp["_id"];
            }
            $configava[] = $subconfigava;
            $output['message'] = "<i class='success'>Data is loaded</i>";
            $output['success'] = TRUE;
            $tempconfigstring = str_replace('"', "'", json_encode($configava));
            if($tempconfigstring == "[[]]")
            {
                $tempconfigstring = "";
            }
            $output['configurations'] = $tempconfigstring;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect($this->session->userdata('urlsebelumnya')); 
        }
    }
}