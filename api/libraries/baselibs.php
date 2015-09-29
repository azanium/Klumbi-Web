<?php
function arg($idx)
{
    $arg = $_REQUEST['q'];
    $arg_expl = explode("/", $arg);
    return $arg_expl[$idx];
}

function arg_str()
{
    return $_REQUEST['q'];
}

function func_arg($idx)
{
    $arg = $_REQUEST['q'];
    $arg_expl = explode("/", $arg);
    $idx += 3;
    return $arg_expl[$idx];
}

function get_random($panjang)
{
        $kalimat="ZA1QWSX2CDER3FVB856JHGEHJIJ654897123UYGFYTJKHVJJFYTRTRSFV468453JHBGIK9G4T0YHN5MJ7UI6KL8OP";
        $pnj=strlen($kalimat);
        $unikhasil="";
        for($i=1;$i<=$panjang;$i++)
        {
                $mulai=rand(0,$pnj);
                $unikhasil .=substr($kalimat,$mulai,1);
        }
        return $unikhasil;
}

function replace_text_content($string="")
{
    $search = array(
		'/\n/',	
                '/\r/',
                '/\t/',
	  ); 
	 $replace = array(
		'<br />',
                ' ',
                '<br />',
	  ); 
	return preg_replace($search, $replace, $string);
}

function filter_text($text)
{
    return str_replace(array('<html>','<head>','<title>','<body>','</html>','</head>','</title>','</body>'), '', $text);
}

function return_xml($parameter=array())
{
    $string="<MSTARS>";
    $string .="<MSISDN>".(isset($parameter['msisdn'])?$parameter['msisdn']:"")."</MSISDN>";
    $string .="<RESPONSE>".(isset($parameter['response'])?$parameter['response']:"1")."</RESPONSE>";
    $string .="<OPTION>".(isset($parameter['option'])?$parameter['option']:"0")."</OPTION>";
    $string .="<CHARGE>".(isset($parameter['charge'])?$parameter['charge']:"0")."</CHARGE>";
    $string .="<APPID>".(isset($parameter['appid'])?$parameter['appid']:"")."</APPID>";
    $string .="<PARTNERID>".(isset($parameter['partnerid'])?$parameter['partnerid']:"")."</PARTNERID>";
    $string .="<MEDIAID>".(isset($parameter['mediaid'])?$parameter['mediaid']:"")."</MEDIAID>";
    $string .="<TRXID>".(isset($parameter['trxid'])?$parameter['trxid']:"")."</TRXID>";
    $string .="<HPTYPE>".(isset($parameter['hptype'])?$parameter['hptype']:"ALL")."</HPTYPE>";
    $string .="<SHORTNAME>".(isset($parameter['shortname'])?$parameter['shortname']:"")."</SHORTNAME>";
    $string .="<CONTENTTYPE>".(isset($parameter['contenttype'])?$parameter['contenttype']:"1")."</CONTENTTYPE>";
    $string .="<PRIORITY>".(isset($parameter['priority'])?$parameter['priority']:"1")."</PRIORITY>";
    $string .="<CONTENTID>".(isset($parameter['contentid'])?$parameter['contentid']:"")."</CONTENTID>";
    $string .="<DESC>".(isset($parameter['desc'])?$parameter['desc']:"")."</DESC>";
    $string .="<CONTENT>";
    $string .="<SMS NUMBER='1'>".(isset($parameter['mesage'])?$parameter['mesage']:"")."</SMS>";      
    $string .="</CONTENT>";
    $string .="</MSTARS>";
    return $string;
}

function __getUserDetails($email) {
    $mongo = new LiloMongo();
    $mongo->selectDB("Users");
    $mongo->selectCollection("Account");
    
    $id = "";
    $username = $email;
    $isValid = false;
    $gender = "male";
    $bodytype = "medium";
    $avatarname = "";
    
    $queryEmail = $mongo->findOne(array('email' => $email));
    if ($queryEmail) {
        $id = (string)$queryEmail['_id'];
        $username = $queryEmail['username'];
        
        $mongo->selectCollection("Properties");
        $queryProp = $mongo->findOne(array('lilo_id' => $id));
        
        if ($queryProp) {
            $gender = $queryProp['sex'];
            $bodytype = $queryProp['body_size'];
            $avatarname = $queryProp['avatarname'];
        }
        else {
            $datatinsert = array( 
                    'lilo_id' => (string)$id,
                    'avatarname' => $username,
                    'fullname' => $username,
                    'sex' => $gender,       
                    'body_size' => $bodytype,
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
            $mongo->insert($datatinsert);
        }
        $isValid = true;
    }
    
    $output = array(
        'id' => $id,
        'username' => $username,
        'avatarname' => $avatarname,
        'gender' => $gender == "" || !isset($gender) ? "male" : $gender,
        'bodytype' => $bodytype == "" || !isset($bodytype) ? "medium" : $bodytype
    );
    
    return $output;
}

?>