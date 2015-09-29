<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Avatar extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_avatar");
    }
    /*
     * Methode : GET
     * API SET Active Configurations Avatar from List Mix User
     * Parameter :
     * 1. iduser (parameter _id field from database Users collection Account(required))
     * 2. idmix (field _id from Database Users dan collection AvatarMix (this param is required))
     * Return JSON
     */
    function setactive_frommix($userid="",$idmix="")
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['message'] = "Error load data";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("AvatarMix"); 
            $tempdata = $this->mongo_db->findOne(array("_id"=>$this->mongo_db->mongoid($idmix)));
            if($tempdata)
            {
                if($tempdata["user_id"] === $userid)
                {
                    $this->mongo_db->select_db("Users");
                    $this->mongo_db->select_collection("Avatar"); 
                    $datatinsert = array(
                        'gender'  => $tempdata["gender"],
                        'user_id' => $userid,
                        'bodytype'=> $tempdata["bodytype"],
                        'configuration'=> $tempdata["configuration"],
                        'date'=>$this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
                    );
                    $this->mongo_db->update(array('user_id'=> $userid),array('$set'=>$datatinsert),array('upsert' => TRUE));
                    $url = current_url();
                    $this->m_user->tulis_log("Update User Avatar Active Configurations from list mix user",$url,"API Unity");
                    $output['success'] = TRUE;
                    $output['message'] = "Configuration is Overwrited";
                }
                else
                {
                    $output['message'] = "This User not Owner of mix";
                }
            }
            else
            {
                $output['message'] = "Mix Not Found";
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API SET Active Avatar Configurations
     * Parameter :
     * 1. iduser (parameter _id field from database Users collection Account(required))
     * 2. gender
     * 3. bodysize
     * 4. configurations
     * Return JSON
     */
    function setactive_configurations()
    {
        $userid = isset($_POST['iduser'])?$_POST['iduser']:"";
        $gender = isset($_POST['gender'])?$_POST['gender']:"";
        $bodysize = isset($_POST['bodysize'])?$_POST['bodysize']:"";
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");        
        $skin = "1"; 
        $hair = "";
        $eyes = "";
        $eyebrows = "";
        $lip = "";  
        $body = "";
        $hat = "";
        $shoes = "";
        $pants = "";
        $hands = "";
        $face = "";
        $idgender = "";
        $leg = "";
        $configurations = isset($_POST['configurations'])?$_POST['configurations']:"";
        $configurations = str_replace("'",'"',$configurations);
        $dataconf = json_decode($configurations);
        if($dataconf)
        {
            foreach ($dataconf as $dt=>$temp)
            {
                if($temp->tipe==="Skin")
                {
                    $skin = isset($temp->color)?$temp->color:"1";
                }
                else if($temp->tipe==="Hair")
                {
                    if(isset($temp->id))
                    {
                        $hair = $temp->id;
                    }
                    else
                    {
                        $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'hair'));
                        if($cekdatatemp)
                        {
                            $hair = (string)$cekdatatemp["_id"];
                        }
                    }
                }
                else if($temp->tipe==="Body")
                {
                    if(isset($temp->id))
                    {
                        $body = $temp->id;
                    }
                    else
                    {
                        $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'body','gender'=>$gender));
                        if($cekdatatemp)
                        {
                            $body = (string)$cekdatatemp["_id"];
                        }
                    }
                }
                else if($temp->tipe==="Pants")
                {
                    if(isset($temp->id))
                    {
                        $pants = $temp->id;
                    }
                    else
                    {
                        $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'pants','gender'=>$gender));
                        if($cekdatatemp)
                        {
                            $pants = (string)$cekdatatemp["_id"];
                        }
                    }
                }
                else if($temp->tipe==="Shoes")
                {
                    if(isset($temp->id))
                    {
                        $shoes = $temp->id;
                    }
                    else
                    {
                        $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'shoes'));
                        if($cekdatatemp)
                        {
                            $shoes = (string)$cekdatatemp["_id"];
                        }
                    }
                }
                else if($temp->tipe==="Face")
                {
                    if(isset($temp->id_EyeBrows))
                    {
                        $eyebrows = $temp->id_EyeBrows;
                    }
                    else
                    {
                        $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'face_part_eye_brows'));
                        if($cekdatatemp)
                        {
                            $eyebrows = (string)$cekdatatemp["_id"];
                        }
                    }
                    if(isset($temp->id_Eyes))
                    {
                        $eyes = $temp->id_Eyes;
                    }
                    else
                    {
                        $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'face_part_eyes'));
                        if($cekdatatemp)
                        {
                            $eyes = (string)$cekdatatemp["_id"];
                        }
                    }
                    if(isset($temp->id_Lip))
                    {
                        $lip = $temp->id_Lip;
                    }
                    else
                    {
                        $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'face_part_lip'));
                        if($cekdatatemp)
                        {
                            $lip = (string)$cekdatatemp["_id"];
                        }
                    }
                }
                else if($temp->tipe==="Hat")
                {
                    if(isset($temp->id))
                    {
                        $hat = $temp->id;
                    }
                }
            }
        }
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
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Avatar"); 
            $datatinsert = array(
                'gender'  =>$gender,
                'user_id' => $userid,
                'bodytype'=>$bodysize,
                'configuration'=>array(
                    array('tipe'=>"Skin","color"=>$skin),
                    array('tipe'=>"Leg","id"=>$leg),
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
                'date'=>$this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            );
            $this->mongo_db->update(array('user_id'=> $userid),array('$set'=>$datatinsert),array('upsert' => TRUE));
            $url = current_url();
            $this->m_user->tulis_log("Update User Avatar Active Configurations",$url,"API Unity");
            $output['success'] = TRUE;
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get Active Avatar Configurations
     * Parameter :
     * 1. userid (parameter _id field from database Users collection Account(required))
     * 2. tipedata [web,iOS,Android]
     * Return JSON
     */
    function active_configurations($userid="",$bodysize="medium",$tipedata="Android")
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['user_id'] = $userid;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Avatar");  
            $tempdata = $this->mongo_db->findOne(array("user_id"=>$userid));
            if($tempdata)
            {
                $output['success'] = TRUE;
                if($bodysize === "")
                {
                    $bodysize = $tempdata["bodytype"];
                }
                $tempdtconfigall = $this->m_avatar->avatarconfig($tempdata['configuration'],$bodysize,$tipedata);
                $tempconfigstring = str_replace('"',"'", json_encode($tempdtconfigall['configurations']));
                $tempdataconf = $tempdata['configuration'];
                if($tempconfigstring == "[[]]")
                {
                    $tempconfigstring = "";
                    $tempdataconf = "";
                }
                $output['configurations'] = $tempconfigstring;
//                $output['dataconf'] = $tempdataconf;
            }
            else
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("Properties");
                $gender = "male";
                $size = "medium";
                $temp_properties = $this->mongo_db->findOne(array("lilo_id"=>(string)$userid));                
                if($temp_properties)
                {
                    $gender = isset($temp_properties["sex"])?$temp_properties["sex"]:$gender;
                    $size = isset($temp_properties["bodytype"])?$temp_properties["bodytype"]:$size;
                }                
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("DefaultAvatar");
                $tempdata2 = $this->mongo_db->findOne(array("gender"=>$gender));
                $tempdtconfigall = $this->m_avatar->avatarconfig($tempdata2['configuration'],$size,$tipedata);
                $tempconfigstring = str_replace('"',"'", json_encode($tempdtconfigall['configurations']));
                $tempdataconf = $tempdata2['configuration'];
                if($tempconfigstring == "[[]]")
                {
                    $tempconfigstring = "";
                    $tempdataconf = "";
                }
                $output['configurations'] = $tempconfigstring;
//                $output['dataconf'] = $tempdataconf;
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get Default Avatar Configurations
     * Parameter :
     * 1. bodysize [thin,medium,fat]
     * 2. gender [male,female]
     * 3. tipedata [web,iOS,Android]
     * Return JSON
     */
    function default_configurations($bodysize="medium",$gender="male",$tipedata="Android")
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $output['success'] = TRUE;             
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("DefaultAvatar");
            $tempdata2 = $this->mongo_db->findOne(array("gender"=>$gender));
            $tempdtconfigall = $this->m_avatar->avatarconfig($tempdata2['configuration'],$bodysize,$tipedata);
            $tempconfigstring = str_replace('"',"'", json_encode($tempdtconfigall['configurations']));
            $tempdataconf = $tempdata2['configuration'];
            if($tempconfigstring == "[[]]")
            {
                $tempconfigstring = "";
                $tempdataconf = "";
            }
            $output['configurations'] = $tempconfigstring;
            //$output['dataconf'] = $tempdataconf;
        }
        echo json_encode($output);
    }
}
