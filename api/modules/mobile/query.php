<?php 
include_once('libraries/LiloMongo.php');
/*
 * Hidden API for Get _id User
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * Return : String
 */
function _get_id_user($emailuser="")
{
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account');
    $cekada = $lilo_mongo->findOne(array('email'=>(string)$emailuser));
    $userid="";
    if($cekada)
    {
        $userid = (string)$cekada['_id'];
    }
    return $userid;
}
/*
 * API for get User Gender
 * Methode: GET
 * Urutan Parameter
 * 1. User email
 * Example : [server host]/[server path]/api/mobile/query/getgender/[user email]
 * Return : JSON
 */
function mobile_query_getgender()
{
    $user_email = func_arg(0);
    $id_user = _get_id_user($user_email);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Avatar');
    $data = $lilo_mongo->findOne(array("user_id" => $id_user));
    $datareturn['isValidUser']=FALSE;
    $datareturn['gender']="male";
    if($data) 
    {
        $datareturn['isValidUser']=TRUE;
        $conf=  json_decode(str_replace("'", '"', $data['configuration']));
        if(is_array($conf))
        {
            foreach ($conf as $dt)
            {
                if($dt->tipe=="gender")
                {
                    $datareturn['gender']=str_replace("_base", "", $dt->element);
                    break;
                }
            }
        }
    }
    else
    {
        $lilo_mongo->selectCollection('Properties');
        $dataprop=$lilo_mongo->findOne(array("lilo_id" => $id_user));
        if($dataprop)
        {
            $datareturn['gender']=isset($dataprop['sex'])?$dataprop['sex']:"male";
        }
    }
    return json_encode($datareturn);
}
/*
 * API for get User Avatar Configurations
 * Methode: GET
 * Urutan Parameter
 * 1. User email
 * 2. Type is Options : [configurations] or [size]
 * 3. Field is Options : [gender], [Face], [Hair], [Body], [Pants], [Shoes], [Hand], [Skin] or [All]
 * Example : [server host]/[server path]/api/mobile/query/getconfavatar/[user email]/configurations/Skin
 * Return : JSON
 */
function mobile_query_getconfavatar()
{
    $user_email = func_arg(0);
    $id_user = _get_id_user($user_email);
    $get_type = func_arg(1);
    $get_field = func_arg(2);
    $get_field = ($get_field=="all")?"":$get_field;
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Avatar');
    $data = $lilo_mongo->findOne(array("user_id" => $id_user));
    $datareturn['isValidUser']=FALSE;
    if($data) 
    {
        $datareturn['isValidUser']=TRUE;
        if($get_type=="configurations")
        {
            $conf=json_decode(str_replace("'", '"',$data['configuration']));
            if($get_field!="")
            { 
                foreach ($conf as $dt=>$listtemp)
                {
                    if($listtemp->tipe==$get_field)
                    {
                        $datareturn=$listtemp;
                        break;
                    }
                }
            }
            else
            {
                $datareturn=$conf;
            }
        }
        else
        {
            $datareturn['size']=$data['size'];
        }
    }
    else
    {
        $lilo_mongo->selectDB("Assets");
        $lilo_mongo->selectCollection("DefaultAvatar");
        $data2 = $lilo_mongo->findOne(array('gender' => "male", 'size' => "medium"));
        if ($data2) 
        {
            if($get_type=="configurations")
            {
                $conf=json_decode(str_replace("'", '"',$data2['configuration']));
                if($get_field!="")
                { 
                    foreach ($conf as $dt=>$listtemp)
                    {
                        if($listtemp->tipe==$get_field)
                        {
                            $datareturn=$listtemp;
                            break;
                        }
                    }
                }
                else
                {
                    $datareturn=$conf;
                }
            }
            else
            {
                $datareturn['size']=$data2['size'];
            }
        }
    }
    return json_encode($datareturn);
}
/*
 * API for Setting User Avatar Configurations
 * Methode: GET
 * Urutan Parameter
 * 1. User email
 * 2. Type is Options : [configurations] or [size]
 * 3. Field is Options : [gender], [Face], [Hair], [Body], [Pants], [Shoes], [Hand], [Skin] or [All]
 * 4. massage: value of string
 * Example : 
 * 1. Ubah All Message:
 * [server host]/[server path]/api/mobile/query/setconfavatar/[user email]/configurations/All?massage=tipe:gender,element:male_base|tipe:Face,element:male_head,material:,eye_brows:brows_01_01,eyes:eyes_a_01_01,lip:lips_a_01_01|tipe:Hair,element:male_hair_04,material:male_hair_04_1|tipe:Body,element:male_hoodie_medium,material:male_hoodie_01_1|tipe:Pants,element:male_pants_medium,material:male_pants_4|tipe:Shoes,element:male_shoes_01,material:male_shoes_01_2|tipe:Hand,element:male_body_hand,material:male_body|tipe:Skin,color:1
 * 2. Ubah satu nilai konfigurasi
 * [server host]/[server path]/api/mobile/query/setconfavatar/[user email]/configurations/Shoes?massage=tipe:Shoes,element:male_shoes_01,material:male_shoes_01_2
 * 3. Mungubah Size langsung sebutkan Nilai
 * [server host]/[server path]/api/mobile/query/setconfavatar/[user email]/size?massage=medium
 * Note : 
 * 1. Jika Mengubah semua konfigurasi gunakan field [all] sebutkan konfigurasi baru di message
 * 2. Jika mengubah satu konfigurasi sebutkan field perubahan
 * 3. Jika Nilai message value hanya satu langsung tulis nilai value
 * 4. Pemisah nilai message dengan "|"
 * 5. Jika Mesage banyak, Message key dengan value dipisahkan dengan tanda ":" dan pemisah data json dengan ","
 * Return : JSON
 */
function mobile_query_setconfavatar()
{
    $user_email = func_arg(0);
    $id_user = _get_id_user($user_email);
    $get_type = func_arg(1);
    $get_field = func_arg(2);
    $get_field = ($get_field=="all")?"":$get_field;
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Avatar');
    $filter=array("user_id" => $id_user);
    $data = $lilo_mongo->findOne($filter);
    $datareturn['isSuccsesUpdate']=FALSE;
    if($data) 
    {
        $datatinsert=array();
        $datareturn['isSuccsesUpdate']=TRUE;
        if($get_type=="configurations")
        {
            $conf=json_decode(str_replace("'", '"',$data['configuration']));
            if($get_field!="")
            { 
                if(strtolower($get_field)=="all")
                {
                    $datanew="";
                    $temp=isset($_GET['massage'])?$_GET['massage']:"";
                    $datatemparray=  explode("|", $temp);
                    if($datatemparray)
                    {
                        foreach ($datatemparray as $jml_json)
                        {
                            $datanew .="{";                            
                            $datatemp=  explode(",", $jml_json);                    
                            if($datatemp)
                            {
                                foreach ($datatemp as $dttemp)
                                {
                                    $dtinsert=  explode(":", $dttemp);
                                    $key=$dtinsert[0];
                                    $value=isset($dtinsert[1])?$dtinsert[1]:"";
                                    $datanew .="'".$key."':'".$value."',";
                                }
                                $datanew =  substr($datanew, 0, strlen($datanew)-1);
                            } 
                            $datanew .="},";
                        }
                        $datanew=  substr($datanew, 0, strlen($datanew)-1);
                    }
                    $datatinsert=array(
                        "configuration"=>"[".$datanew."]",
                    );
                }
                else
                {
                    foreach ($conf as $dt=>$listtemp)
                    {
                        if($listtemp->tipe==$get_field)
                        {
                            continue;
                        }
                        $datanew .= "{";
                        foreach($listtemp as $dt_json=>$value_key)
                        {
                            $datanew .= "'".$dt_json."':'".$value_key."',";
                        }
                        $datanew =  substr($datanew, 0, strlen($datanew)-1);
                        $datanew .= "},";
                    }
                    $datanew .= "{";
                    $datamessage=isset($_GET['massage'])?$_GET['massage']:"";
                    $datajson=  explode(",",$datamessage);
                    if($datajson)
                    {
                        foreach ($datajson as $dttemp)
                        {
                            $dtinsert=  explode(":", $dttemp);
                            $key=$dtinsert[0];
                            $value=isset($dtinsert[1])?$dtinsert[1]:"";
                            $datanew .="'".$key."':'".$value."',";
                        }
                        $datanew =  substr($datanew, 0, strlen($datanew)-1);
                    }                    
                    $datanew .="}";
                    $datatinsert=array(
                        "configuration"=>"[".$datanew."]",
                    );
                }
            }
        }
        else if($get_type=="size")
        {
            $datasize=isset($_GET['massage'])?$_GET['massage']:"medium";
            $datatinsert=array(
                "size"=>$datasize,
            );
        }
        $lilo_mongo->update_set($filter,$datatinsert);
    }
    else
    {
        $size="medium";
        $gender="female";
        $lilo_mongo->selectDB("Assets");
        $lilo_mongo->selectCollection("DefaultAvatar");
        $data3 = $lilo_mongo->findOne(array('gender' => $gender, 'size' => $size));
        if ($data3) 
        {
            $lilo_mongo->selectDB('Users');
            $lilo_mongo->selectCollection('Avatar');
            $lilo_mongo->update(
                    array(                
                        "size"=>$size,
                        "user_id"=>$id_user,
                    ),
                    array(
                       '$set' => array("configuration"=>$data3['configuration'])
                    ),      
                    array(
                        'upsert' => TRUE
                    )
                    );
        }
    }
    return json_encode($datareturn);
}
?>