<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Store extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
    }
    /*
     * Methode : GET
     * API Root Store /  Brand
     * Parameter :
     * 1. gender (this param is optional if include gender use [male/female])
     * 2. filtering
     * 3. id_brand
     * 4. ordering [date, price, name]
     * 5. sort[asc/desc] (asc for ascending & desc for desending)
     * Return JSON
     */
    function root($gender="",$filtering="",$id_brand="",$ordering="",$sortdata="asc") 
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $datafiltering = $this->__filtering($filtering,$id_brand);
            if($gender==="male")
            {
                $datafiltering = array_merge($datafiltering,array("gender"=>"male"));
            }
            else if($gender==="female")
            {
                $datafiltering = array_merge($datafiltering,array("gender"=>"female"));
            }
            $dataordering = $this->__ordering($ordering,$sortdata);
            $output['data'][] = array(
                'tipe' => 'top',                
                'title' => 'Top',                
                'picture' => 'top.png',
                'action' => '/Store?f=body&id='.$id_brand,
                'value' => '',
                'order' => $dataordering,
                'filter'=> $datafiltering
            );    
            $output['data'][] = array(
                'tipe' => 'bottom',
                'title' => 'Bottom',
                'picture' => 'bottom.png',
                'action' => '/Store?f=pants&id='.$id_brand,
                'value' => '',
                'order' => $dataordering,
                'filter'=> $datafiltering
            );    
            $output['data'][] = array(
                'tipe' => 'footwear',
                'title' => 'Footwear',
                'picture' => 'footwear.png',
                'action' => '/Store?f=shoes&id='.$id_brand,
                'value' => '',
                'order' => $dataordering,
                'filter'=> $datafiltering
            );    
            $output['data'][] = array(
                'tipe' => 'prop',
                'title' => 'Prop',
                'picture' => 'prop.png',
                'action' => '/Store?f=prop&id='.$id_brand,
                'value' => '',
                'order' => $dataordering,
                'filter'=> $datafiltering
            );    
            $output['data'][] = array(
                'tipe' => 'mix',
                'title' => 'My Mix',
                'picture' => 'mix.png',
                'action' => '/Store?f=mix&id='.$id_brand,
                'value' => '',
                'order' => $dataordering,
                'filter'=> $datafiltering
            );
            $output['count'] =count($output['data']);
        }
        echo json_encode($output);
    }
    function __filtering($keyfiltering="",$id_brand = "")
    {
        $datareturn = array("brand_id" => $id_brand);
        
        return $datareturn;
    }
    function __ordering($ordering="",$sortby="asc")
    {
        $keyorder = "name";
        if($ordering=="date")
        {
            $keyorder = "last_update";
        }
        else if($ordering=="price")
        {
            $keyorder = "price";
        }
        else if($ordering=="name")
        {
            $keyorder = "name";
        }        
        $dataorder = 1;
        if($sortby==="desc")
        {
            $dataorder = -1;
        }
        $datareturn = array( $keyorder => $dataorder);
        return $datareturn;
    }
    
    
    
    
    
    
    
    
    
    
    
    /*
     * Methode : GET
     * API List Store /  Brand
     * Parameter :
     * 1. email
     * 2. func
     * 3. settipe
     * 4. setsize
     * 5. brand
     * 6. start
     * 7. keysearch
     * 8. sort[asc/desc]
     * Return JSON
     */
    function index()
    {
        $email = isset($_GET['email'])?$_GET['email']:""; 
        $func = isset($_GET['func'])?$_GET['func']:""; 
        $settipe = isset($_GET['settipe'])?(bool)$_GET['settipe']:FALSE;
        $setsize = isset($_GET['setsize'])?(bool)$_GET['setsize']:FALSE;
        $brand = isset($_GET['brand'])?$_GET['brand']:"";
        $start = isset($_GET['start'])?$_GET['start']:0; 
        $keysearch = isset($_GET['keysearch'])?$_GET['keysearch']:""; 
        $sort = isset($_GET['sort'])?$_GET['sort']:"asc"; 
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $output['success'] = TRUE;
            if ($func === "root") 
            {
                $output['data'] = $this->__root();
            }
            else if ($func == "prop") 
            {
                $output['data'] = $this->__prop($keysearch,$brand);
            }
            else if ($func == "mix") 
            {
                $output['data'] = $this->__mix($email, $keysearch,$brand,$start, $keysearch, $sort);
            }
            else 
            {
                $output['data'] = $this->__bodypart($email, $func, $setsize, $settipe, $brand, $start, $keysearch, $sort);
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get Count Brand
     * Parameter :
     * Return JSON
     */
    function count()
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Brand");        
            $tempdata = $this->mongo_db->count2(array());
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['count'] = (int)$tempdata;
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get One Detail Brand
     * Parameter :
     * 1. brand_id
     * Return JSON
     */
    function get_one_byid()
    {  
        $brand_id = isset($_GET['brand_id'])?$_GET['brand_id']:""; 
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Brand");        
            $tempdata = $this->mongo_db->findOne(array("brand_id"=>$brand_id));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['data'] = array(
                    "id" => (string)$tempdata['_id'],
                    "name" => $tempdata['name'],
                    "brand_id" => $tempdata['brand_id'],
                    "website" => $tempdata['website'],
                    "backgroundcolor" => $tempdata['backgroundcolor'],
                    "textcolor" => $tempdata['textcolor'],
                    "description" => $tempdata['description'],
                    "picture" => $tempdata['picture'],
                    "banner" => $tempdata['banner']
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Brand
     * Parameter :
     * 1. page
     * Return JSON
     */
    function list_data($start=0)
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");        
            $this->mongo_db->select_collection("Settings");
            $tempdt = $this->mongo_db->findOne(array("code"=>"limitstore"));
            $limit = 10;
            if($tempdt)
            {
                $limit = $tempdt['value'];
            }
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Brand");        
            $tempdata = $this->mongo_db->find(array(),(int)$start,(int)$limit,array("name"=>1));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $listdata[] = array(
                        "id" => (string)$dt['_id'],
                        "title" => $dt['name'],
                        "brand_id" => $dt['brand_id'],
                        "website" => $dt['website'],
                        "backgroundcolor" => $dt['backgroundcolor'],
                        "textcolor" => $dt['textcolor'],
                        "description" => $dt['description'],
                        "picture" => $dt['picture'],
                        "banner" => $dt['banner'],
                        'action' => '/Store?f=home',
                        'value' => ''
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
    function __bodypart($email, $tipe, $setsize=FALSE, $setgender=FALSE, $brand_id="",$start=0, $sort="last_update", $asc="desc") 
    {
        $templimit = $this->m_userdata->settingvalue("limitstore");
        $userdata = $this->m_userdata->user_account($email);
        $output = array();
        if($userdata['success'])
        {
            $userproperties = $this->m_userdata->user_properties($userdata['id']);
            $bodytype = $userproperties['bodytype'];
            $gender = $userproperties['sex'];
            $limit = (int)$templimit['value'];
            $orderby = array();
            if($sort !="")
            {
                $orderby = array( $sort => (($asc==="asc")?1:-1));
            }
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Avatar");
            $filtering = array('tipe' =>$tipe, 'brand_id' => $brand_id);
            if($setgender)
            {
                $filtering = array_merge($filtering, array('gender' =>$gender));
            }
            if($setsize)
            {
                $filtering = array_merge($filtering, array('size' =>$bodytype));
            }
            $tamp = $this->mongo_db->find($filtering, $start, $limit, $orderby);
            $tampcount = $this->mongo_db->count($filtering);
            $output['data'] = $this->__avatar_items($tamp, $gender);
            $output['count'] = $tampcount;
        }
        return $output;
    }
    function __avatar_items($query, $gender) 
    {
        $output = array();
        foreach ($query as $item) 
        {
            $element = array(
                'gender' => $gender,
                'tipe' => $item['tipe'],
                'element' => str_replace(".unity3d", "", $item['element']),
                'material' => str_replace(".unity3d", "", $item['material'])
            );
            $json = str_replace("\"", "'", json_encode($element));
            $output['data'][] = array(
                'tipe' => $item['tipe'],
                'title' => $item['name'],
                'picture' => $item['preview_image'],
                'action' => '/AvatarPreview?f='.$item['tipe']. "&id=". (string)$item['_id'] . "&v=".$json,
                'value' => ''
            );
        }
        return $output;
    }
    function __mix($email, $filter="newest",$brand_id="",$start=0, $sort="", $asc="asc") 
    {
        $templimit = $this->m_userdata->settingvalue("limitstore");
        $userdata = $this->m_userdata->user_account($email);
        $output = array();
        if($userdata['success'])
        {
            $userproperties = $this->m_userdata->user_properties($userdata['id']);
            $bodytype = $userproperties['bodytype'];
            $gender = $userproperties['sex'];
            $limit = (int)$templimit['value'];
            $orderby = array();
            if($sort !="")
            {
                $orderby = array( $sort => (($asc==="asc")?1:-1));
            }
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("AvatarMix");
            $filtering = array('gender' =>$gender, 'bodytype' => $bodytype, 'brand_id' => $brand_id);
            $tamp = $this->mongo_db->find($filtering, $start, $limit, $orderby);
            $tampcount = $this->mongo_db->count($filtering);
            $output['count'] = $tampcount;
            foreach($tamp as $dttemp)
            {
                $json = $dttemp['configuration'];
                $output['data'][] = array(
                    'tipe' => 'Mix',
                    'title' => $dttemp['name'],
                    'picture' => $dttemp['picture'],
                    'action' => '/AvatarPreview?f=character'. '&id=' . (string)$dttemp['_id'] ."&v=".$json,
                    'value' => ''
                );
            }
        }
        return $output;
    }
    function __prop($tipe="newest",$id_brand="") 
    {
        $output['data'][] = array(
            'tipe' => 'hat',
            'filter'=>$tipe,
            'title' => 'Headwear',
            'picture' => 'headwear.png',
            'action' => '/Store?f=hat&id='.$id_brand,
            'value' => ''
        );
        $output['count'] =(int) count($output['data']);
        return $output;
    }
}
