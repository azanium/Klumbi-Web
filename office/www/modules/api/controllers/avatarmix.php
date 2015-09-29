<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Avatarmix extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model(array("m_avatar","m_userdata"));
    }
    /*
     * Methode : GET
     * API Get Count Avatar Mix User
     * Parameter :
     * 1. userid (parameter _id field from database Users collection Account(required))
     * Return JSON
     */
    function count($iduser="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("AvatarMix");       
            $tempdata = $this->mongo_db->count2(array("user_id"=>(string)$iduser));
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
     * API Add Avatar Mix User
     * Parameter :
     * 1. iduser (parameter _id field from database Users collection Account(required))
     * 2. title
     * 3. authorname
     * 4. brand_id
     * 5. descriptions
     * 6. gender
     * 7. skin
     * 8. bodysize
     * 9. eyes
     * 10. eyebrows
     * 11. lip
     * 12. hair
     * 13. body
     * 14. hat
     * 15. shoes
     * 16. pants
     * 17. hands
     * 18. leg
     * 19. face
     * 20. idgender
     * 21. picturename
     * Return JSON
     */
    function add_configurations()
    {
        $id = isset($_POST['id'])?$_POST['id']:"";
        $authorname = isset($_POST['authorname'])?$_POST['authorname']:"";
        $brand_id = isset($_POST['brand_id'])?$_POST['brand_id']:"";
        $descriptions = isset($_POST['descriptions'])?$_POST['descriptions']:"";
        $userid = isset($_POST['iduser'])?$_POST['iduser']:"";
        $title = isset($_POST['title'])?$_POST['title']:"";
        $gender = isset($_POST['gender'])?$_POST['gender']:"";
        $bodysize = isset($_POST['bodysize'])?$_POST['bodysize']:"";
        $picturename = isset($_POST['picturename'])?$_POST['picturename']:"";
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
        $output['message'] = "File is empty";
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("AvatarMix"); 
            $datatinsert = array(
                'name'  =>$title,
                'gender'  =>$gender,
                'user_id' => $userid,
                'bodytype'=>$bodysize,
                "author" => $authorname,
                "description" => $descriptions,
                "brand_id" => $brand_id,
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
            if($picturename!="")
            {
                $datatinsert = array_merge($datatinsert, array("picture" => $picturename));
            }
            else
            {
                $uploaddir = $this->config->item('path_upload')."preview_images/";
                if(isset($_FILES['fileimage']['name']) && $_FILES['fileimage']['name']!="")        
                {
                    $config['upload_path'] = $uploaddir;
                    $config['allowed_types'] = 'jpg|jpeg|gif|png';
                    $config['max_size']	= '9000000';
                    $config['max_width']  = '2048';
                    $config['max_height']  = '1600';
                    $config['max_filename']  = 0;
                    $config['overwrite']  = TRUE;
                    $config['encrypt_name']  = TRUE;
                    $config['remove_spaces']  = TRUE;
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload("fileimage"))
                    {
                        $output['message'] = $this->upload->display_errors();
                    }
                    else
                    {
                        $output['message'] = "File uploaded successfully";
                        $hasil = $this->upload->data();
                        $datatinsert = array_merge($datatinsert, array("picture" => $hasil['file_name']));
                    }
                }
                else
                {
                    $datatinsert = array_merge($datatinsert, array("picture" => ""));
                }
            }
            $url = current_url();
            if($id==="")
            {
                $this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add new User Mix",$url,"API Unity");
            }
            else
            {
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id) ),array('$set'=>$datatinsert),array('upsert' => TRUE));
                $this->m_user->tulis_log("Update User Mix",$url,"API Unity");
            }            
            $output['success'] = TRUE;
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get Active Avatar Configurations
     * Parameter :
     * 1. id (parameter _id field from database Users collection AvatarMix(required))
     * 2. tipedata [web,iOS,Android]
     * Return JSON
     */
    function detail_configurations($id="",$tipedata="Android")
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("AvatarMix");  
            $tempdata = $this->mongo_db->findOne(array("_id"=>$this->mongo_db->mongoid($id)));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $tempdtconfigall = $this->m_avatar->avatarconfig($tempdata['configuration'],$tempdata["bodytype"],$tipedata);
                $tempconfigstring = str_replace('"',"'", json_encode($tempdtconfigall['configurations']));
                $tempdataconf = $tempdata['configuration'];
                if($tempconfigstring == "[[]]")
                {
                    $tempconfigstring = "";
                    $tempdataconf = "";
                }
                $output['configurations'] = $tempconfigstring;
//                $output['dataconf'] = $tempdataconf;
                $output['name'] = $tempdata['name'];
                $output['gender'] = $tempdata['gender'];
                $output['bodytype'] = $tempdata['bodytype'];
                $output['user_id'] = $tempdata['user_id'];
                $output['author'] = $tempdata['author'];
                $output['description'] = $tempdata['description'];
                $output['brand_id'] = $tempdata['brand_id'];
                $output['date'] = date('Y-m-d H:i:s', $tempdata['date']->sec);
                $tempdtuser = $this->m_userdata->user_properties($tempdata["user_id"]);
                $output['fullname'] = $tempdtuser['fullname'];
                $output['sex'] = $tempdtuser['sex'];
                $output['picture'] = $tempdtuser['picture'];
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of User Avatar Mix
     * Parameter :
     * 1. page
     * 2. ordering = [az / za ]
     * 3. tipedata [web,iOS,Android]
     * 4. keyorder [name, date, gender, bodytype]
     * 5. name
     * 6. gender [male/female]
     * 7. bodytype [thin, medium, fat]
     * 8. user_id
     * Return JSON
     */
    function list_data($start=0)
    {  
        $ordering = isset($_GET['ordering'])?$_GET['ordering']:"";
        $tipedata = isset($_GET['tipedata'])?$_GET['tipedata']:"Android";
        $order = isset($_GET['keyorder'])?$_GET['keyorder']:"name";
        $user_id = isset($_GET['user_id'])?$_GET['user_id']:"";        
        $name = isset($_GET['name'])?$_GET['name']:"";
        $gender = isset($_GET['gender'])?$_GET['gender']:"";
        $bodytype = isset($_GET['bodytype'])?$_GET['bodytype']:"";        
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");        
            $this->mongo_db->select_collection("Settings");
            $tempdt = $this->mongo_db->findOne(array("code"=>"limitavmix"));
            $limit = 10;
            if($tempdt)
            {
                $limit = $tempdt['value'];
            }   
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("AvatarMix");
            $dtorder = 1;
            if($ordering==="za")
            {
                $dtorder = -1;
            }
            $filtering = array("user_id" => $user_id);
            if($name!="")
            {
                $sSearch=(string) trim($name);
                $sSearch = quotemeta($sSearch);
                $regex = "/$sSearch/i";
                $filter=$this->mongo_db->regex($regex);
                $filtering = array_merge($filtering, array("name" => $filter));
            }
            if($gender!="")
            {
                $filtering = array_merge($filtering, array("gender" => $gender));
            }
            if($bodytype!="")
            {
                $filtering = array_merge($filtering, array("bodytype" => $bodytype));
            }
            $tempdata = $this->mongo_db->find($filtering,(int)$start,(int)$limit,array($order=>$dtorder));
            $output['count'] = (int)$this->mongo_db->count2($filtering);
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $tempdtconfigall = $this->m_avatar->avatarconfig($dt['configuration'],$dt["bodytype"],$tipedata);
                    $tempconfigstring = str_replace('"',"'", json_encode($tempdtconfigall['configurations']));
                    $tempdataconf = $dt['configuration'];
                    if($tempconfigstring == "[[]]")
                    {
                        $tempconfigstring = "";
                        $tempdataconf = "";
                    }
                    $listdata[] = array(
                        "id" => (string)$dt['_id'],
                        "name" => $dt['name'],
                        "brand_id" => $dt['brand_id'],
                        "gender" => $dt['gender'],
                        "user_id" => $dt['user_id'],
                        "bodytype" => $dt['bodytype'],
                        "author" => $dt['author'],
                        "description" => $dt['description'],
                        "configurations" => $tempconfigstring,
//                        "dataconf" => $tempdataconf,
                        "date" => date('Y-m-d H:i:s', $dt['date']->sec),
                        "picture" => $dt['picture'],
                        "url_picture" => $this->config->item('path_asset_img') . "preview_images/" . $dt['picture'],
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}
