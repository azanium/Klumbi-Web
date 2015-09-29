<?php
include_once('libraries/LiloMongo.php');
/*
 * API for get Quest Data
 * Methode: GET
 * Urutan Parameter
 * 1. Quest ID
 * Example : [server host]/[server path]/api/unity/query/quest/[quest id]
 * Return : JSON
 */
function unity_query_quest() {
    $id = func_arg(0);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Game');
    $lilo_mongo->selectCollection('Quest');
    $data = $lilo_mongo->findOne(array("ID" => $id));
    if ($data) {
        $output['IsActive'] = strtolower($data['IsActive']) == 'true' ? TRUE : FALSE;
        $output['IsDone'] = strtolower($data['IsDone']) == 'true' ? TRUE : FALSE;
        $output['IsReturn'] = strtolower($data['IsReturn']) == 'true' ? TRUE : FALSE;
        $output['ID'] = intval($data['ID']);
        $output['RequiredEnergy'] = intval($data['RequiredEnergy']);
        $output['Requirement'] = intval($data['Requirement']);
        $output['Rewards'] = $data['Rewards'];
        $output['RequiredItem'] = $data['RequiredItem'];
        $output['Description'] = $data['Description'];
        $output['DescriptionNormal'] = $data['DescriptionNormal'];
        $output['DescriptionActive'] = $data['DescriptionActive'];
        $output['DescriptionDone'] = $data['DescriptionDone'];
        $output['StartDate'] = "";
        $output['EndDate'] = "";
        if ($data['StartDate'] != "") {
            $output['StartDate'] = date('Y-m-d', $data['StartDate']->sec);
        }
        if ($data['EndDate'] != "") {
            $output['EndDate'] = date('Y-m-d', $data['EndDate']->sec);
        }
        return json_encode($output);
    }
}
/*
 * API for get Dialog Story Data
 * Methode: GET
 * Urutan Parameter
 * 1. Dialog Story Name
 * Example : [server host]/[server path]/api/unity/query/dialogstory/[Dialogstory name]
 * Return : JSON
 */
function unity_query_dialogstory() {
    $name = func_arg(0);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Game');
    $lilo_mongo->selectCollection('DialogStory');
    $data = $lilo_mongo->findOne(array("name" => $name));
    if ($data) {
        $output['Name'] = $data['name'];
        $output['Dialogs'] = array();
        if ($data['dialogs']) {
            foreach ($data['dialogs'] as $key => $val) {
                unset($dtoption);
                $dtoption = array();
                foreach ($val['options'] as $nilai) {
                    $dtoption[] = array(
                        'Tipe' => (int) $nilai['option_type'],
                        'Content' => $nilai['description'],
                        'Next' => (int) $nilai['next_id'],
                    );
                }
                $output['Dialogs'][] = array(
                    'ID' => (int) $val['id'],
                    'Description' => $val['description'],
                    'Options' => $dtoption
                );
            }
        }
        return json_encode($output);
    }
}
/*
 * API for get Inventory Data
 * Methode: GET
 * Example : [server host]/[server path]/api/unity/query/inventory
 * Return : JSON
 */
function unity_query_inventory() {
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('Inventory');
    $data = $lilo_mongo->find();
    $output = array();
    foreach ($data as $dt) {
        $output[] = array(
            'name' => $dt['name'],
            'file' => isset($dt['file']) ? $dt['file'] : "",
        );
    }
    return json_encode($output);
}
/*
 * API for get Lobby Setting Data
 * Methode: GET
 * Example : [server host]/[server path]/api/unity/query/lobby
 * Return : JSON
 */
function unity_query_lobby() {
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Game');
    $lilo_mongo->selectCollection('LobbySetting');
    $data = $lilo_mongo->findOne();
    return $data['ip'] . ":" . $data['port'];
}
/*
 * API for get Animations Data
 * Methode: GET
 * Urutan Parameter
 * 1. Gender is oprion: [male] or [female]
 * Example : [server host]/[server path]/api/unity/query/editor_animation/[gender]
 * Return : JSON
 */
function unity_query_editor_animation() {
    $gender = func_arg(0);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('Animation');
    $data = $lilo_mongo->command_values(array("distinct" => "Animation", "key" => "animation_file", "query" => array("gender" => $gender, "permission" => "default")));
    $output = array();
    foreach ($data['values'] as $dt) {
        $output[] = str_replace(".unity3d", "", $dt);
    }
    return json_encode($output);
}
/*
 * API for get Avatar Configurations Data
 * Methode: GET
 * Urutan Parameter
 * 1. User ID
 * Example : [server host]/[server path]/api/unity/query/avatar_configuration/[user ID]
 * Return : JSON
 */
function unity_query_avatar_configuration() {
    $user_id = func_arg(0);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Avatar');
    $data2 = $lilo_mongo->findOne(array("user_id" => $user_id));
    $output = "";
    if ($data2) {
        $output = json_encode($data2['configuration']);
    } else {
        $gender = __cek_gender($user_id);
        $lilo_mongo2 = new LiloMongo();
        $lilo_mongo2->selectDB('Assets');
        $lilo_mongo2->selectCollection('DefaultAvatar');
        $data4 = $lilo_mongo2->findOne(array('gender' => $gender, 'size' => 'medium'));
        if ($data4) {
            $output = json_encode($data4['configuration']);
        }
    }
    return str_replace("\"", "", $output);
}
/*
 * Hidden API Get Gender
 */
function __cek_gender() {
    $user_id = func_arg(0);
    $gender = "male";
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Avatar');
    $data = $lilo_mongo->findOne(array('user_id' => $user_id));
    if ($data) {
        $conf_ = json_decode(str_replace("'", '"', $data['configuration']));
        if (is_array($conf_)) {
            foreach ($conf_ as $dt) {
                if ($dt->tipe == 'gender') {
                    return str_replace("_base", "", $dt->element);
                }
            }
        }
    } else {
        $lilo_mongo->selectCollection('Avatar');
        $data2 = $lilo_mongo->findOne(array('lilo_id' => $user_id));
        if (!isset($data2['sex'])) {
            $gender = "male";
        } else {
            $gender = $data2['sex'];
        }
    }
    return $gender;
}
/*
 * API for get Gesticon Data
 * Methode: GET
 * Urutan Parameter
 * 1. name
 * Example : [server host]/[server path]/api/unity/query/gesticon/[name]
 * Return : JSON
 */
function unity_query_gesticon() {
    $name = func_arg(0);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Game');
    $lilo_mongo->selectCollection('Gesticon');
    $data = $lilo_mongo->find();
    if ($data) {
        $output = array();
        foreach ($data as $val) {
            $output[] = array(
                'command' => $val['command'],
                'animation' => $val['animation'],
            );
        }
        return json_encode($output);
    }
}
/*
 * API for get Random Data
 * Methode: GET
 * Urutan Parameter
 * 1. length : harus number.
 * Example : [server host]/[server path]/api/unity/query/k_gen/[length]
 * Return : JSON
 */
function unity_query_k_gen() {
    $length = func_arg(0);
    $key = get_random($length);
    return "{'key':'" . $key . "'}";
}
/*
 * API for get Quiz Data
 * Methode: GET
 * Urutan Parameter
 * 1. Quiz ID.
 * Example : [server host]/[server path]/api/unity/query/quiz/[quiz id]
 * Return : JSON
 */
function unity_query_quiz()
{
    $quiz_id = func_arg(0);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Game');
    $lilo_mongo->selectCollection('Quiz');
    $data = $lilo_mongo->findOne(array('ID' => $quiz_id));
    $output=array();
    $datebegintemp="";
    $dateendtemp="";
    if($data)
    {
        $output['ID'] = $data['ID'];
        $output['Title'] = $data['Title'];
        $output['Description'] = $data['Description'];
        $output['BrandId'] = $data['BrandId'];
        $output['State'] = $data['State'];
        $output['StartTime'] = $data['StartTime'];
        $output['EndTime'] = $data['EndTime'];
        $output['IsRandom'] = (bool)$data['IsRandom'];
        $output['Number'] = (int)$data['Number'];
        $output['Count'] = (int)$data['Count'];
        $output['RequiredQuiz'] = (int)$data['RequiredQuiz'];
        $output['RequiredQuest'] = (int)$data['RequiredQuest'];            
        $output['RequiredItem'] = $data['RequiredItem'];
        $output['Reward'] = $data['Reward'];
        $temp_dt_question=array();
        foreach( $data['Questions'] as $dtquestion)
        {
            $options=array();
            foreach( $dtquestion['Options'] as $dtpilihan)
            {
                $options[]=array(
                    'Answer'=>$dtpilihan['Answer'],
                    'IsCorrect'=>(bool)$dtpilihan['IsCorrect'],
                );
            }
            $temp_dt_question[]=array(
                'QuestionId'=>$dtquestion['QuestionId'],
                'Question'=>$dtquestion['Question'],
                'Difficulty'=>$dtquestion['Difficulty'],
                'QuestionTime'=>$dtquestion['QuestionTime'],
                'Tipe'=>$dtquestion['Tipe'],
                'Image'=>(($dtquestion['Image']=="")?"":$dtquestion['Image']),
                'Options'=>$options,
            );
        }        
        $output['Questions'] = $temp_dt_question;
        if($data['EndDate']!='')
        {
            $dateendtemp=date('Y-m-d', $data['EndDate']->sec);
        }
        if($data['StartDate']!='')
        {
            $datebegintemp=date('Y-m-d', $data['StartDate']->sec);
        }
        $output['EndDate'] = $dateendtemp;
        $output['StartDate'] = $datebegintemp;
    }
    return json_encode($output);
}
/*
 * API Hidden Generate Redeem Avatar
 */
function generate_redeem_avatar($avatar_item_code, $count) {
    $code = get_random(10);
    $lilo_mongo = new LiloMongo();
    $id=$lilo_mongo->mongoid($code.date('YmdHis'));
    $lilo_mongo->selectDB('Assets');
    
    // Create Redeem
    $lilo_mongo->selectCollection('Redeem');
    $generatetime = date("Y-m-d H:i:s");
    $time_start = strtotime($generatetime);
    $datatinsert = array(
        'code' => $code,
        'count' => $count,
        'expire' => '',
        'create' => $lilo_mongo->time($time_start),
        '_id' => $id
    );
    $result = $lilo_mongo->insert($datatinsert);
    $redeemId = (string)$id;
    // Create Mapping Avatar
    $lilo_mongo->selectCollection('Avatar');
    $data=$lilo_mongo->find(array("code"=>$avatar_item_code));
    $datareturn=array();
    if($data)
    {
        $lilo_mongo->selectCollection('RedeemAvatar');
        foreach($data as $tampung)
        {
            $dataaddnew = array(
                'avatar_id' => (string)$tampung['_id'],
                'code_id' => $redeemId
            );
            $lilo_mongo->insert($dataaddnew);
            $datareturn[]=$dataaddnew;
        }
    }
    $ret = array(
        'id' => $datareturn,
        'code' => $code
    );
    
    return $ret;
}
/*
 * API for Set Redeem Mapping Data
 * Methode: GET
 * Urutan Parameter
 * 1. avatar CodeID.
 * note:
 * Avatar Code bisa lebih dari satu pemisah tiap code gunakan tanda "."
 * Example : [server host]/[server path]/api/unity/query/r_gen/[Avatar Code]
 * Return : JSON
 */
function unity_query_r_gen() {
    $count = 1;
    $encoded_avatars = func_arg(0);

    $pairs = explode(".", $encoded_avatars);
   
    $result = array();
    
    foreach ($pairs as $avatar) {
        $ret = generate_redeem_avatar($avatar, $count);
        $result[] = $ret;
    }
    
    return json_encode($result);
}
/*
 * API for get Avatar Animations Data
 * Methode: GET
 * Urutan Parameter
 * 1. User ID
 * Example : [server host]/[server path]/api/unity/query/avatar_animation/[user ID]
 * Return : JSON
 */
function unity_query_avatar_animation() {
    $user_id = func_arg(0);
    $gender = "male";
    $lilo_mongo = new LiloMongo();
	
    /*
     //CODE BELOW COMMENTED, BECAUSE USERS DOESN'T HAVE SPECIFIC ANIMATIONS EMO ATM
	
	$lilo_mongo->selectDB('Users');
        $lilo_mongo->selectCollection('Animation');
        $data2 = $lilo_mongo->findOne(array("user_id" => $user_id));
        $output = "";
    if ($data2) 
    {
        $temp = array();
        foreach ($data2['configuration'] as $dt) {
            $temp[] = $dt;
        }
        $output = json_encode($temp);
    } 
    else 
    {*/
        $gender = __cek_gender($user_id);
        $lilo_mongo2 = new LiloMongo();
        $lilo_mongo2->selectDB('Assets');
        $lilo_mongo2->selectCollection('Animation');

        // Get the default animation
        $data4 = $lilo_mongo2->command_values(array("distinct" => "Animation", "key" => "animation_file", "query" => array("gender" => $gender, "permission" => "default")));

        // Sometimes the default animation is named Default, get that too
        $data5 = $lilo_mongo2->command_values(array("distinct" => "Animation", "key" => "animation_file", "query" => array("gender" => $gender, "permission" => "Default")));

        $list_temp = array(
            $gender . '@bye',
            $gender . '@happy',
            $gender . '@idle1',
            $gender . '@idle2',
            $gender . '@jump',
            $gender . '@pickup',
            $gender . '@run',
            $gender . '@walk'
        );
        if ($data4) {
            $temp = "";
            foreach ($data4['values'] as $dt2) {
                $temp = str_replace(".unity3d", "", $dt2);

                if (!in_array($temp, $list_temp)) {
                    $list_temp[] = $temp;
                }
            }

            foreach ($data5['values'] as $dt2) {
                $temp = str_replace(".unity3d", "", $dt2);

                if (!in_array($temp, $list_temp)) {
                    $list_temp[] = $temp;
                }
            }

            $output = json_encode($list_temp);
        }
    //}
    return $output;
}
?>