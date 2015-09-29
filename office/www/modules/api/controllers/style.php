<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Style extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model(array("m_userdata","m_avatar"));
    }
    /*
     * Methode : GET
     * API List Feature
     * Parameter :
     * 1. email
     * 2. func
     * 3. start
     * 4. keysearch
     * 5. sort[asc/desc]
     * Return JSON
     */
    function index()
    {
        $email = isset($_GET['email'])?$_GET['email']:""; 
        $func = isset($_GET['func'])?$_GET['func']:""; 
        $start = isset($_GET['start'])?$_GET['start']:0;
        $tipedata= isset($_GET['tipedata'])?$_GET['tipedata']:"Android"; 
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
                $temproot = $this->__style_root();
                $output['data'] = $temproot;
                $output['count'] = count($temproot);
            }
            else if ($func == "accessories")
            {
                $tempprop = $this->__style_prop();
                $output['data'] = $tempprop;
                $output['count'] = count($tempprop);
            }
            else if ($func == "mix") 
            {
                $tempmix = $this->__style_mix($email, $start, $keysearch, $sort, $tipedata);
                $output['data'] = $tempmix["data"];
                $output['count'] = $tempmix["count"];
            }
            else
            {
                $datatempava = $this->__style_bodypart($email, $func, $start, $keysearch, $sort, $tipedata);
                $output['data'] = $datatempava["data"];
                $output['count'] = $datatempava["count"];
            }            
        }
        echo json_encode($output);
    }
    function __explode_avatar_items($query, $gender, $bodytype="medium", $tipedata="Android") 
    {
        $output = array();
        foreach ($query as $item) 
        {
            $defaultbody = "_medium";
            if($bodytype ==="thin")
            {
                $defaultbody = "_thin";
            }
            else if($bodytype ==="fat")
            {
                $defaultbody = "_fat";
            }
            $tipedata = strtolower($tipedata);
            if($tipedata ==="ios")
            {
                $dataelement = $dtitem['element_ios'.$defaultbody];
                $datamaterial = $dtitem['material_ios'.$defaultbody];
            }
            else if($tipedata ==="android")
            {
                $dataelement = $dtitem['element_android'.$defaultbody];
                $datamaterial = $dtitem['material_android'.$defaultbody];
            }
            else
            {
                $dataelement = $dtitem['element_web'.$defaultbody];
                $datamaterial = $dtitem['material_web'.$defaultbody];
            }
            $element = array(
                'gender' => $gender,
                'tipe' => $item['tipe'],
                'element' => str_replace(".unity3d", "", $dataelement),
                'material' => str_replace(".unity3d", "", $datamaterial)
            );
            $json = str_replace("\"", "'", json_encode($element));
            $output[] = array(
                'tipe' => $item['tipe'],
                'title' => $item['name'],
                'picture' => $item['preview_image'],
                'action' => '/AvatarPreview?f='.$item['tipe']. "&id=". (string)$item['_id'] . "&v=".$json,
                'value' => ''
            );
        }
        return $output;
    }
    function __style_root() 
    {
        $output[] = array(
            'tipe' => 'top',
            'title' => 'Top',
            'picture' => 'style_top.png',
            'action' => '/Style?f=body',
            'value' => ''
        );
        $output[] = array(
            'tipe' => 'bottom',
            'title' => 'Bottom',
            'picture' => 'style_bottom.png',
            'action' => '/Style?f=pants',
            'value' => ''
        );
        $output[] = array(
            'tipe' => 'footwear',
            'title' => 'Footwear',
            'picture' => 'style_footwear.png',
            'action' => '/Style?f=shoes',
            'value' => ''
        );
        $output[] = array(
            'tipe' => 'accessories',
            'title' => 'Accessories',
            'picture' => 'style_accessories.png',
            'action' => '/Style?f=accessories',
            'value' => ''
        );
        $output[] = array(
            'tipe' => 'mix',
            'title' => 'My Mix',
            'picture' => 'style_mix.png',
            'action' => '/Style?f=mix',
            'value' => ''
        );
        return $output;
    }
    function __style_prop() 
    {
        $output[] = array(
            'tipe' => 'hat',
            'title' => 'Headwear',
            'picture' => 'styl_accessories_headwear.png',
            'action' => '/Style?f=hat',
            'value' => ''
        );
        $output[] = array(
            'tipe' => 'eyesWear',
            'title' => 'Eyewear',
            'picture' => 'styl_accessories_eyewear.png',
            'action' => '/Style?f=eyeswear',
            'value' => ''
        );
        $output[] = array(
            'tipe' => 'other',
            'title' => 'Other',
            'picture' => 'style_accessories_other.png',
            'action' => '/ItemStyle?f=other',
            'value' => ''
        );
        return $output;
    }
    function __style_mix($email, $start=0, $sort="", $asc="asc", $tipedata="Android") 
    {
        $templimit = $this->m_userdata->settingvalue("limitavmix");
        $userdata = $this->m_userdata->user_account($email);
        $output['data'] = array();
        $tampcount = 0;
        if($userdata['success'])
        {
            $userproperties = $this->m_userdata->user_properties($userdata['id']);
            $bodytype = isset($userproperties['bodytype'])?$userproperties['bodytype']:"medium";
            $gender = isset($userproperties['sex'])?$userproperties['sex']:"male";
            $limit = (int)$templimit['value'];
            $orderby = array();
            if($sort !="")
            {
                $orderby = array( $sort => (($asc==="asc")?1:-1));
            }            
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("AvatarMix");
            $filtering = array('gender' =>$gender);
            $tamp = $this->mongo_db->find($filtering, $start, $limit, $orderby);
            $tampcount = $this->mongo_db->count($filtering);
            foreach($tamp as $dtitem)
            {
                $tempdtconfigall = $this->m_avatar->avatarconfig($dtitem['configuration'],$bodytype,$tipedata);
                $json = str_replace('"',"'", json_encode($tempdtconfigall['configurations']));
                $output['data'][] = array(
                    'tipe' => 'Mix',
                    'title' => isset($dtitem['name'])?$dtitem['name']:"",
                    'picture' => isset($dtitem['picture'])?$dtitem['picture']:"",
                    'action' => '/AvatarPreview?f=character'. '&id=' . (string)$dtitem['_id'] ."&v=".$json,
                    'value' => ''
                );
            }
        }
        $output['count'] = $tampcount;
        return $output;
    }
    function __style_bodypart($email, $type, $start = 0, $sort="", $asc="asc", $tipedata="Android") 
    {
        $templimit = $this->m_userdata->settingvalue("limitavitem");
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
            if($type==="shoes" || $type==="hat")
            {
                $filtering = array('tipe' => $type);
            }
            else
            {
                $filtering = array('tipe' => $type, 'gender' =>array('$in'=>array($gender,'unisex')));
            }            
            $tamp = $this->mongo_db->find($filtering, (int)$start, (int)$limit, $orderby);
            $tampcount = $this->mongo_db->count($filtering);
            if($type==="shoes" || $type==="hat")
            {
                $temploadavaitem = $this->__explode_avatar_items($tamp, $gender, "medium", $tipedata);
            }
            else
            {
                $temploadavaitem = $this->__explode_avatar_items($tamp, $gender, $bodytype, $tipedata);
            }  
            $output['data'] = $temploadavaitem;
            $output['count'] = $tampcount;
        }
        return $output;
    }
}
