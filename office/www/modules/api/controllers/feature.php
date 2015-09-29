<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Feature extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
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
        $func = isset($_GET['func'])?$_GET['func']:"";
        $email = isset($_GET['email'])?$_GET['email']:"";
        $start = isset($_GET['start'])?$_GET['start']:0; 
        $keysearch = isset($_GET['keysearch'])?$_GET['keysearch']:""; 
        $sort = isset($_GET['sort'])?$_GET['sort']:"asc"; 
        $tipedata= isset($_GET['tipedata'])?$_GET['tipedata']:"Android"; 
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $output['success'] = TRUE;
            if ($func === "root") 
            {
                $temproot = $this->__feature_root();
                $output['data'] = $temproot;
                $output['count'] = count($temproot);
            }
            else if ($func == "allcolors") 
            {
                $output['data'][] = array(
                    'tipe' => 'skincolor',
                    'title' => 'Skin Color',
                    'picture' => 'skincolor.png',
                    'action' => '/Feature?f=skincolors',
                    'value' => ''
                );
            }
            else if ($func == "skincolors") 
            {
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("SkinColor");
                $tamp = $this->mongo_db->find(array(),0,0,array("name"=>1));
                $index=0;
                foreach($tamp as $dttemp)
                {
                    $output['data'][] = array(
                        'tipe' => 'color'.$index,
                        'title' => 'Color #'.$index,
                        'picture' => $index.'.png',
                        'action' => '/AvatarPreview?f=color&v='.$index,
                        'value' => ''
                    );
                    $index++;
                }                
                $output['count'] = $index;
            }
            else 
            {
                $tempavataritem = $this->__part($email,$func,$start,$keysearch,$sort,$tipedata);
                $output['count'] = $tempavataritem["count"];
                $output['data'] = $tempavataritem["data"];
            }
        }
        echo json_encode($output);
    }
    function __feature_root()
    {
        $output[] = array(
            'tipe' => 'face_part_eyes',
            'title' => 'Eyes Cool',
            'picture' => 'Eyes.png',
            'action' => '/Feature?f=face_part_eyes',
            'value' => ''
        );
        $output[] = array(
            'tipe' => 'face_part_lip',
            'title' => 'Mouth',
            'picture' => 'mouth.png',
            'action' => '/Feature?f=face_part_lip',
            'value' => ''
        );
        $output[] = array(
            'tipe' => 'hair',
            'title' => 'Hair',
            'picture' => 'hair.png',
            'action' => '/Feature?f=hair',
            'value' => ''
        );
        $output[] = array(
            'tipe' => 'face_part_eye_brows',
            'title' => 'Eyebrows',
            'picture' => 'eyebrows.png',
            'action' => '/Feature?f=face_part_eye_brows',
            'value' => ''
        );
        $output[] = array(
            'tipe' => 'color',
            'title' => 'Color',
            'picture' => 'color.png',
            'action' => '/Feature?f=allcolors',
            'value' => ''
        );
        $output[] = array(
            'tipe' => 'gender',
            'title' => 'Gender',
            'picture' => 'gender.png',
            'action' => '/Gender?f=male',
            'value' => ''
        );
        $output[] = array(
            'tipe' => 'bodytype',
            'title' => 'Body Type',
            'picture' => 'bodytype.png',
            'action' => '/BodyType?f=average',
            'value' => ''
        );        
        return $output;
    }
    function __part($email, $type, $start=0, $sort="", $asc="asc",$tipedata="Android")
    {
        $templimit = $this->m_userdata->settingvalue("limitavitem");
        $userdata = $this->m_userdata->user_account($email);
        $output['data'] = array();
        $tampcount = 0;
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
            $filtering = array('tipe' => $type, 'gender' =>array('$in'=>array($gender,'unisex')) );
            $tamp = $this->mongo_db->find($filtering, (int)$start, (int)$limit, $orderby);
            $tampcount = $this->mongo_db->count($filtering);
            $newtype = str_replace("face_part_", "", $type);
            foreach($tamp as $dtitem)
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
                if($newtype === $type)
                {
                    $element = array(
                        'gender' => $gender,
                        'tipe' => $newtype,
                        'element' => str_replace(".unity3d", "", $dataelement),
                        'material' => str_replace(".unity3d", "", $datamaterial)
                    );
                }
                else
                {
                    $element = array(
                        'tipe' => $newtype,
                        'element' => str_replace(".unity3d", "", $datamaterial)
                    );
                }
                $json = str_replace("\"", "'", json_encode($element));
                $output['data'][] = array(
                    'tipe' => $type,
                    'title' => $dtitem['name'],
                    'picture' => $dtitem['preview_image'],
                    'action' => '/AvatarPreview?f='. $type . "&id=". (string)$dtitem['_id'] . "&v=".$json,
                    'value' => ''
                );
            }            
        }
        $output['count'] = $tampcount;
        return $output;
    }
}
