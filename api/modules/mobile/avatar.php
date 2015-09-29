<?php 
include_once('libraries/LiloMongo.php');
include_once('libraries/baselibs.php');

function mobile_avatar_defaultbodytype() {
    $email = func_arg(0);
    $bodytype = func_arg(1);
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account');
    
    $query = $lilo_mongo->findOne(array('email' => $email));
    
    $id = "";
    $isValid = false;
    $config = "";
    $gender = "male";
    
    if ($query) {
        $id = (string)$query['_id'];
        
        $lilo_mongo->selectCollection('Properties');
        
        $data = $lilo_mongo->findOne(array('lilo_id' => $id));
        
        if ($data) {
            $gender = $data['sex'];
            if (!isset($gender) || $gender == null) {
                $gender = "male";
            }
        }
        
        $lilo_mongo->selectDB("Assets");
        $lilo_mongo->selectCollection("DefaultAvatar");
        $data2 = $lilo_mongo->findOne(array('gender' => $gender, 'size' => $bodytype));
        if ($data2) {
            $isValid = true;
            $config = $data2['configuration'];
        }
    }
    
    $output = array(
        'id' => $id,
        'success' => $isValid,
        'bodyType' => $bodytype,
        'configuration' => str_replace("\"", "", $config)
    );
    
    return json_encode($output);
}

function mobile_avatar_defaultavatar() {
    $email = func_arg(0);
    $gender = func_arg(1);
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account');
    
    $query = $lilo_mongo->findOne(array('email' => $email));
    
    $id = "";
    $isValid = false;
    $config = "";
    $bodysize = "medium";
    
    if ($query && isset($gender)) {
        $id = (string)$query['_id'];
        $lilo_mongo->selectCollection('Properties');
        
        $data = $lilo_mongo->findOne(array('lilo_id' => $id));
        
        if ($data) {
            $bodysize = $data["body_size"];
            if (!isset($bodysize) || $bodysize == null) {
                $bodysize = "medium";
            }
        }
        
        $lilo_mongo->selectDB("Assets");
        $lilo_mongo->selectCollection("DefaultAvatar");
        $data2 = $lilo_mongo->findOne(array('gender' => $gender, 'size' => $bodysize));
        if ($data2) {
            $isValid = true;
            $config = $data2['configuration'];
        }
    }
    
    $output = array(
        'id' => $id,
        'success' => $isValid,
        'bodyType' => $bodysize,
        'configuration' => str_replace("\"", "", $config)
    );
    
    return json_encode($output);
}

function mobile_avatar_setgender() {
    $email = func_arg(0);
    $gender = func_arg(1);
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account');
    
    $query = $lilo_mongo->findOne(array('email' => $email));
    
    $id = "";
    $isValid = false;
    $config = "";
    $bodysize = "medium";
    
    if ($query && isset($gender)) {
        $id = (string)$query['_id'];
        $username = $query['username'];
        $lilo_mongo->selectCollection('Properties');
        
        $data = $lilo_mongo->findOne(array('lilo_id' => $id));
        
        if ($data) {
            $lilo_mongo->update_set(array('lilo_id' => $id), array('sex' => $gender));

            $bodysize = $data["body_size"];
            
            if (!isset($bodysize) || $bodysize == null) {
                $bodysize = "medium";
            }       
            
        } else {
            $datatinsert = array( 
                    'lilo_id' => (string)$id,
                    'avatarname' => $username,
                    'fullname' => $username,
                    'sex' => $gender,       
                    'body_size' => 'medium',
                    'website' => '',
                    'link' => '',
                    'birthday' =>'',
                    'birthday_dd' => '',
                    'birthday_mm' => '',
                    'birthday_yy' => '',    
                    'state_mind' => '',
                    'about' => '',
                    'picture' => '',
                    'location'=> '',                        
                    'handphone' => '',
                    'twitter' => '',
                );
                $lilo_mongo->insert($datatinsert);
        }
        
        $lilo_mongo->selectDB("Assets");
        $lilo_mongo->selectCollection("DefaultAvatar");
        $data2 = $lilo_mongo->findOne(array('gender' => $gender, 'size' => $bodysize));
        if ($data2) {
            $isValid = true;
            $config = str_replace("\"", "", $data2['configuration']);
            
            $configuration = array(
                'user_id' => $id,
                'size' => $bodysize,
                'configuration' => $config
            );
            
            // Now save our configuration
            $lilo_mongo->selectDB("Users");
            $lilo_mongo->selectCollection('Avatar');
            $data3 = $lilo_mongo->findOne(array('user_id' => $id));
            if (isset($data3)) {
                $lilo_mongo->update_set(array('user_id' => $id), $configuration);
            }
            else {
                $lilo_mongo->insert($configuration);
            }
        }
        
    }
    
    $output = array(
        'id' => $id,
        'success' => $isValid,
        'bodyType' => $bodysize,
        'configuration' => $config
    );
    
    return json_encode($output);
}

function mobile_avatar_setconfig() {
    $email = func_arg(0);
    $details = __getUserDetails($email);
    $id = $details['id'];
    $bodytype = $details['bodytype'];
    
    $config = $_POST['configuration'];
    
    $mongo = new LiloMongo();
    $mongo->selectDB("Users");
    $mongo->selectCollection("Avatar");
    
    $query = $mongo->findOne(array('user_id' => $id));
    if ($query) {
        $mongo->update_set(array("user_id" => $id), array("configuration" => $config, "size" => $bodytype));
    } else {
        $mongo->insert(array("user_id" => $id, "size" => $bodytype, "configuration" => $config));
    }
    
    return json_encode(array(
        'id' => $id,
        'bodytype' => $bodytype,
        'configuration' => $config
    ));
}

function mobile_avatar_setbodytype() {
    $email = func_arg(0);
    $bodysize = func_arg(1);
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account');
    
    $query = $lilo_mongo->findOne(array('email' => $email));
    
    $id = "";
    $isValid = false;
    $config = "";
    $gender = "male";
    
    if ($query) {
        $id = (string)$query['_id'];
        $username = $query['username'];
        $lilo_mongo->selectCollection('Properties');
        
        $data = $lilo_mongo->findOne(array('lilo_id' => $id));
        
        if ($data) {
            $gender = $data['sex'];
            $lilo_mongo->update_set(array('lilo_id' => $id), array('body_size' => $bodysize));
            $isValid = true;
        } else {
            $datatinsert = array( 
                    'lilo_id' => (string)$id,
                    'avatarname' => $username,
                    'fullname' => $username,
                    'sex' => $gender,       
                    'body_size' => 'medium',
                    'website' => '',
                    'link' => '',
                    'birthday' =>'',
                    'birthday_dd' => '',
                    'birthday_mm' => '',
                    'birthday_yy' => '',    
                    'state_mind' => '',
                    'about' => '',
                    'picture' => '',
                    'location'=> '',                        
                    'handphone' => '',
                    'twitter' => '',
                );
                $lilo_mongo->insert($datatinsert);
        }
        
        /// Get and set from the Default Avatar
        $lilo_mongo->selectDB("Assets");
        $lilo_mongo->selectCollection("DefaultAvatar");
        $data2 = $lilo_mongo->findOne(array('gender' => $gender, 'size' => $bodysize));
        if ($data2) {
            $isValid = true;
            $config = str_replace("\"", "", $data2['configuration']);
            
            $configuration = array(
                'user_id' => $id,
                'size' => $bodysize,
                'configuration' => $config
            );
            
            // Now save our configuration
            $lilo_mongo->selectDB("Users");
            $lilo_mongo->selectCollection('Avatar');
            $data3 = $lilo_mongo->findOne(array('user_id' => $id));
            if (isset($data3)) {
                $lilo_mongo->update_set(array('user_id' => $id), $configuration);
            }
            else {
                $lilo_mongo->insert($configuration);
            }
        }
    }
    
    $output = array(
        'id' => $id,
        'success' => $isValid,
        'bodyType' => $bodysize,
        'configuration' => $config
    );
    
    return json_encode($output);
}

function mobile_avatar_bodytype() {
    $email = func_arg(0);

    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account'); 
    
    $query = $lilo_mongo->findOne(array('email' => $email));
    
    $id = "";
    $isValid = false;
    $bodytype = "medium";
    $gender = "gender";
    
    if ($query) {
        $id = (string)$query['_id'];
        $lilo_mongo->selectCollection("Properties");
        $data = $lilo_mongo->findOne(array('lilo_id' => $id));
        if ($data) {
            $bodytype = $data['body_size'];
            $gender = $data['sex'];
            $isValid = true;
        }
    }
    
    $output = array(
        'id' => $id,
        'success' => $isValid,
        'gender' => $gender,
        'bodyType' => $bodytype,
    );
    
    return json_encode($output);
}

function __cek_gender($user_id) {
    $gender = "male";
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Properties');

    $data = $lilo_mongo->findOne(array('lilo_id' => $user_id));
    if ($data) {
        if (!isset($data['sex'])) {
            $gender = "males";
        } else {
            $gender = $data['sex'];
        }
    }
    
    return $gender;
}

function mobile_avatar_getgender() {
    $email = func_arg(0);

    $userDetails = __getUserDetails($email);
    
    $id = $userDetails['id'];
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection("Properties");
    $data = $lilo_mongo->findOne(array('lilo_id' => $id));
    
    $isValid = false;
    $status = "";
    
    if ($data) {
        $isValid = true;
        $status = $data['state_of_mind'];
    }
    
    $output = array(
        'id' => $userDetails['id'],
        'success' => $isValid,
        'gender' => $userDetails['gender'],
        'bodyType' => $userDetails['bodytype'],
        'status' => $status == NULL ? "" : $status
    );
    
    return json_encode($output);
}

function _render_avatar($array_avatar)
{
    $output=array();
    if($array_avatar)
    {
        foreach($array_avatar as $val)
        {
            $childelement=(!isset($val['element'])?"":$val['element']);
            $childmaterial=(!isset($val['material'])?"":$val['material']);
            $childelement_ios=(!isset($val['element_ios'])?"":$val['element_ios']);
            $childmaterial_ios=(!isset($val['material_ios'])?"":$val['material_ios']);
            $childelement_android=(!isset($val['element_android'])?"":$val['element_android']);
            $childmaterial_android=(!isset($val['material_android'])?"":$val['material_android']);
            $childtipe=(!isset($val['tipe'])?"":$val['tipe']);  
            $childcode=(!isset($val['code'])?"":$val['code']);
            $childname=(!isset($val['name'])?"":$val['name']);
            $childsize=(!isset($val['size'])?"":$val['size']);                        
            $childbrand=(!isset($val['brand_id'])?"":$val['brand_id']);
            $childcategory=(!isset($val['category'])?"":$val['category']);
            $childpayment=(!isset($val['payment'])?"":$val['payment']);                        
            $childpreview_image=(!isset($val['preview_image'])?"":$val['preview_image']);
            $childcolor=(!isset($val['color'])?"":$val['color']);
            $childgender=(!isset($val['gender'])?"":$val['gender']);
            $output[] = array(
                '_id' => (string)$val['_id'],
                'element' => $childelement,
                'material' => $childmaterial,
                'element_ios' => $childelement_ios,
                'material_ios' => $childmaterial_ios,
                'element_android' => $childelement_android,
                'material_android' => $childmaterial_android,
                'tipe' => $childtipe,
                'code' => $childcode,
                'name' => $childname,
                'size' => $childsize,
                'brand_id' => $childbrand,
                'category' => $childcategory,
                'payment' => $childpayment,
                'preview_image' => $childpreview_image,
                'color' => $childcolor,
                'gender' => $childgender,
            );
        }
    }
    return $output;
}
/*
 * API for get List All Avatar
 * Methode: GET
 * Urutan Parameter
 * 1. Start Page from
 * 2. Limit Data
 * Example : [server host]/[server path]/api/mobile/avatar/list/0/10
 * Return : JSON
 */
function mobile_avatar_list() 
{
    $start_from = (int)func_arg(0);
    $limit = (int)func_arg(1);
    
    $output=array();
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('Avatar'); 
    $temp_data = $lilo_mongo->find_pagging(array(), $start_from, $limit,array('last_update'=>-1));    
    $output['count']=$lilo_mongo->count();
    $output['data']=_render_avatar($temp_data);
    return json_encode($output);
}
/*
 * API for get List Avatar per Brand
 * Methode: GET
 * Urutan Parameter
 * 1. Brand ID.
 * 2. Gender option : [male], [female], [All]
 * 2. Size in options : [thin], [medium],[fat] or [All]
 * Example : [server host]/[server path]/api/mobile/avatar/perbrand/[brand id]/[gender]/[size]
 * Return : JSON
 */
function mobile_avatar_perbrand() 
{
    $brand_id=(func_arg(0)!="")?func_arg(0):"";
    $gender=(func_arg(1)!="")?strtolower(func_arg(1)):""; 
    $size=(func_arg(2)!="")?strtolower(func_arg(2)):"";
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('Avatar');
    $filter=array('brand_id'=>$brand_id);
    if($gender==="male" || $gender==="female" || $gender==="unisex" )
    {
        $filter= array_merge($filter,array('brand_id'=>$brand_id));
    }
    if($size==="thin" || $size==="medium" || $size==="fat")
    {
        $filter= array_merge($filter,array('size'=>$size));
    }
    $dataavatar = $lilo_mongo->find($filter);
    $output=_render_avatar($dataavatar);
    return json_encode($output);
}
/*
 * API for get List Avatar Update up to 21 days
 * Methode: GET
 * Urutan Parameter
 * 1. Gender in options : [male], [female] or [all].
 * 2. Size in options : [thin], [medium] or [fat],[all]
 * Example : [server host]/[server path]/api/mobile/avatar/threeweek/[gender]/[size]
 * Return : JSON
 */
function mobile_avatar_threeweek() 
{
    $gender=(func_arg(0)!="")?func_arg(0):""; 
    $size=(func_arg(1)!="")?func_arg(1):"";
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('AvatarBodyPart');
    $listtipe= $lilo_mongo->find(array(),0,array('name'=>1));
    $output=array();
    $tanggal=date('Y-m-d H:i:s', strtotime("-21 days"));
    foreach($listtipe as $dt)
    {
        $lilo_mongo->selectCollection('Avatar');        
        $filter = array("tipe"=>$dt['name'],'last_update'=>array('$gte'=>$lilo_mongo->time(strtotime($tanggal))));
        if($gender!="")
        {
            $filter = array_merge($filter, array('gender'=>array('$in'=>array('unisex',$gender))));
        }
        if($size!="")
        {
            $filter = array_merge($filter, array('size'=>array('$in'=>array('',$size))));
        }
        $dataavatar = $lilo_mongo->find($filter,5,array("last_update"=>-1));
        $output=_render_avatar($dataavatar);
    }
    return json_encode($output);
}
/*
 * API for get list Avatar Default
 * Methode: GET
 * Example : [server host]/[server path]/api/mobile/avatar/default
 * Return : JSON
 */
function mobile_avatar_default() 
{
    $start_from = func_arg(0);
    $limit = func_arg(1);  
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('DefaultAvatar');
    $data = $lilo_mongo->find(array());
    $output=array();
    if ($data) 
    {
        foreach($data as $tampung)
        {
            $defconfigurasi=(!isset($tampung['configuration'])?"":$tampung['configuration']);
            $conf_array=json_decode(str_replace("'", '"',$defconfigurasi));
            $output[]=array(
                '_id'=>(!isset($tampung['_id'])?"":(string)$tampung['_id']),
                'name'=>(!isset($tampung['name'])?"":$tampung['name']),
                'gender'=>(!isset($tampung['gender'])?"":$tampung['gender']),
                'size'=>(!isset($tampung['size'])?"":$tampung['size']),
                'configuration'=>$conf_array,
            );
        }        
    }
    return json_encode($output);
}
/*
 * API for get list Avatar Configurations
 * Methode: GET
 * Urutan Parameter
 * 1. Start Page
 * 2. Limit Page
 * Example : [server host]/[server path]/api/mobile/avatar/configuration/0/10
 * Return : JSON
 */
function mobile_avatar_configuration() 
{
    $start_from = func_arg(0);
    $limit = func_arg(1);  
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('AvatarConfigurationsTemplate');
    $data = $lilo_mongo->find_pagging(array(),$start_from,$limit,array());
    $output=array();
    if ($data) 
    {
        foreach($data as $tampung)
        {
            $tgl="";
            if ($tampung['last_update'] != "") 
            {
                $tgl = date('Y-m-d H:i:s', $tampung['last_update']->sec);
            }
            $defconfigurasi=(!isset($tampung['configuration'])?"":$tampung['configuration']);
            $conf_array=json_decode(str_replace("'", '"',$defconfigurasi));
            $output[]=array(
                '_id'=>(!isset($tampung['_id'])?"":(string)$tampung['_id']),
                'name'=>(!isset($tampung['name'])?"":$tampung['name']),
                'gender'=>(!isset($tampung['gender'])?"":$tampung['gender']),
                'size'=>(!isset($tampung['size'])?"":$tampung['size']),
                'configuration'=>$conf_array,
                'last_update'=>$tgl,
            );
        }        
    }
    return json_encode($output);
}
/*
 * API for get list Avatar Collections
 * Methode: GET
 * Urutan Parameter
 * 1. User ID
 * 2. Start Page
 * 3. Limit Page
 * Example : [server host]/[server path]/api/mobile/avatar/collections/[user id]/0/10
 * Return : JSON
 */
function mobile_avatar_collections() 
{
    $user_id = func_arg(0);
    $start_from = func_arg(1);
    $limit = func_arg(2);  
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('AvatarCollection');
    $data = $lilo_mongo->find_pagging(array('user_id'=>$user_id),$start_from,$limit,array());
    $output=array();
    if ($data) 
    {
        foreach($data as $tampung)
        {
            $tgl="";
            if ($tampung['date_add'] != "") 
            {
                $tgl = date('Y-m-d H:i:s', $tampung['date_add']->sec);
            }
            $defconfigurasi=(!isset($tampung['configuration'])?"":$tampung['configuration']);
            $conf_array=json_decode(str_replace("'", '"',$defconfigurasi));
            $output[]=array(
                '_id'=>(!isset($tampung['_id'])?"":(string)$tampung['_id']),
                'subject'=>(!isset($tampung['subject'])?"":$tampung['subject']),
                'descriptions'=>(!isset($tampung['descriptions'])?"":$tampung['descriptions']),
                'configuration'=>$conf_array,
                'date_add'=>$tgl,
            );
        }        
    }
    return json_encode($output);
}
/*
 * API for Add Avatar Collections
 * Methode: GET
 * Urutan Parameter
 * 1. User ID
 * 2. subject
 * 3. description
 * 4. skin
 * 5. gender
 * 6. eyebrows
 * 7. eyes
 * 8. lip
 * 9. element_hair
 * 10. hair
 * 11. element_body
 * 12. body
 * 13. element_pants
 * 14. pants
 * 15. element_shoes
 * 16. shoes
 * 17. element_hat
 * 18. hat
 * Example : [server host]/[server path]/api/mobile/avatar/addcollections/[user id]?subject=&description=&skin=&gender=&eyebrows=&eyes=&lip=&element_hair=&hair=&element_body=&body=&element_pants=&pants=&element_shoes=&shoes=&element_hat=&hat=
 * Return : JSON
 */
function mobile_avatar_addcollections() 
{
    $user_id = func_arg(0);
    $subject = isset($_GET['subject'])?$_GET['subject']:"";
    $descriptions = isset($_GET['description'])?$_GET['description']:"";  
    $skin = isset($_GET['skin'])?$_GET['skin']:"1";
    $gender = isset($_GET['gender'])?$_GET['gender']:"male";
    $eyebrows = isset($_GET['eyebrows'])?$_GET['eyebrows']:"";
    $eyes = isset($_GET['eyes'])?$_GET['eyes']:"";
    $lip = isset($_GET['lip'])?$_GET['lip']:"";
    $element_hair = isset($_GET['element_hair'])?$_GET['element_hair']:"";
    $hair = isset($_GET['hair'])?$_GET['hair']:"";
    $element_body = isset($_GET['element_body'])?$_GET['element_body']:"";
    $body = isset($_GET['body'])?$_GET['body']:"";
    $element_pants = isset($_GET['element_pants'])?$_GET['element_pants']:"";
    $pants = isset($_GET['pants'])?$_GET['pants']:"";
    $element_shoes = isset($_GET['element_shoes'])?$_GET['element_shoes']:"";
    $shoes = isset($_GET['shoes'])?$_GET['shoes']:"";
    $element_hat = isset($_GET['element_hat'])?$_GET['element_hat']:"";
    $hat = isset($_GET['hat'])?$_GET['hat']:"";
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('AvatarCollection');
    $configurations="[{'tipe':'Skin','color':'".$skin."'},{'tipe':'Hand','element':'".$gender."_body_hand','material':'".$gender."_body'},{'tipe':'gender','element':'".$gender."_base'},{'tipe':'Face','element':'".$gender."_head','material':'' ,'eye_brows':'".$eyebrows."','eyes':'".$eyes."','lip':'".$lip."'},{'tipe':'Hair','element':'".$element_hair."','material':'".$hair."'},{'tipe':'Body','element':'".$element_body."','material':'".$body."'},{'tipe':'Pants','element':'".$element_pants."','material':'".$pants."'},{'tipe':'Shoes','element':'".$element_shoes."','material':'".$shoes."'},{'tipe':'Hat','element':'".$element_hat."','material':'".$hat."'}]";
    $datainsert=array(
        "user_id"=>$user_id,
        "configuration"=>$configurations,
        "subject"=>$subject,
        "descriptions"=>$descriptions,
        "gender"=>$gender,
        "date_add"=>$lilo_mongo->time(strtotime(date("Y-m-d H:i:s"))),
    );
    $lilo_mongo->insert($datainsert);
    $output['collectionsCount']=$lilo_mongo->count(array("user_id"=>$user_id));
    return json_encode($output);
}
/*
 * API for Delete Avatar Collections
 * Methode: GET
 * Urutan Parameter
 * 1. User ID
 * 2. Collections ID
 * Example : [server host]/[server path]/api/mobile/avatar/delcollections/[user id]/[_id collections]
 * Return : JSON
 */
function mobile_avatar_delcollections() 
{
    $user_id = func_arg(0);
    $_id = func_arg(1);
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('AvatarCollection');
    $data = $lilo_mongo->delete(array('user_id'=>$user_id,"_id"=>$lilo_mongo->mongoid($_id)));
    $output['collectionsCount']=$lilo_mongo->count(array("user_id"=>$user_id));
    return json_encode($output);
}
?>