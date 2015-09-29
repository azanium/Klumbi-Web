<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Tambahan_fungsi
{
    function list_tag()
    {
        return array(
            "Game", 
            "Android", 
            "iOS", 
            "Virtual", 
            "Mobile"
        );
    }
    function list_hastag()
    {
        return array(
            "#gaya", 
            "#trendi", 
            "#style", 
            "#baju", 
            "#celana"
        );
    }
    function list_gender()
    {
        return array(
            'male'=>'Male',
            'female'=>'Female',
//            'unisex'=>'Unisex',
       );
    }
    function state_contest()
    {
        return array(
            'commingsoon'=>'Comming Soon',
            'openforentry'=>'Open for Entry',
            'entryclose'=>'Entry Close',
            'close'=>'Contest Close',
       );
    }
    function state_achievement()
    {
        return array(
            'hidden '=>'Hidden ',
            'revealed '=>'Revealed ',
            'unlocked '=>'Unlocked ',
       );
    }
    function state_cmbwinner()
    {
        return array(
            'auto'=>'Order by Auto',
            'manual'=>'Order Manual',
       );
    }
    function ucapkan_salam()
    {
        $waktu=date('H');
        if($waktu >=0 && $waktu<11 )
        {
            $salam="Good Morning";
        }
        else if($waktu>=11 && $waktu<18 )
        {
            $salam="Good Afternoon";
        }
        else if($waktu>=18)
        {
            $salam="Good Night";
        }
        else
        {
            $salam="Welcome";
        }
        return $salam;
    }
    function list_level()
    {
        return array(
            'easy'=>'Easy',
            'medium'=>'Medium',
            'difficult'=>'Difficult',
        );
    }
    function list_size()
    {
        return array(
            ''=>'No Size',
            'thin'=>'Thin',
            'medium'=>'Medium',
            'fat'=>'Fat',
        );
    }
    function document_state()
    {
        return array(
            'draft'=>'Draft',
            'publish'=>'Publish',
            //'not publish'=>'Not Publish',
            //'pending'=>'Pending',
        );
    }
    function global_get_random($panjang)
    {
            $kalimat="ZA1QWSX2CDER3FVB9G4T0YHN5MJ7UI6KL8OP";
            $pnj=strlen($kalimat);
            $unikhasil="";
            for($i=1;$i<=$panjang;$i++)
            {
                    $mulai=rand(0,$pnj);
                    $unikhasil .=substr($kalimat,$mulai,1);
            }
            return $unikhasil;
    }
    function global_get_numeric($panjang)
    {
            $kalimat="0123456789";
            $pnj=strlen($kalimat);
            $unikhasil="";
            for($i=1;$i<=$panjang;$i++)
            {
                    $mulai=rand(0,$pnj);
                    $unikhasil .=substr($kalimat,$mulai,1);
            }
            return $unikhasil;
    }
    function unitytounixtime($unitytime = NULL)
    {
	if(!isset($unitytime))
        {
            return "";
	}
	$ut_expl = explode(' ', $unitytime);	
	$date_ = $ut_expl[0];
	$time_ = isset($ut_expl[1])?$ut_expl[1]:"00:00:00";
	$ampm_ = isset($ut_expl[2])?$ut_expl[2]:"PM";	
	$date_expl = explode('/', $date_);
	$time_expl = explode(':', $time_);
	$ampm_ = strtoupper(trim($ampm_));	
	$time_expl[0] = ($ampm_ == 'PM') ? $time_expl[0] + 12 : $time_expl[0];	
	return mktime(intval($time_expl[0]), intval($time_expl[1]), intval($time_expl[2]), intval($date_expl[0]), intval($date_expl[1]), intval($date_expl[2]));
    }
    function sec2hms ($sec, $padHours = false) 
    {
            $hms = "";
            $hours = intval(intval($sec) / 3600); 
            $hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":" : $hours. ":";
            $minutes = intval(($sec / 60) % 60); 
            $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";
            $seconds = intval($sec % 60); 
            $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
            return $hms;
    }
    function debug($arrayold=array())
    {
        echo "<pre>";
        var_dump($arrayold);
        echo "</pre>";
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
    function filter_text($text)
    {
        return str_replace(array('<html>','<head>','<title>','<body>','</html>','</head>','</title>','</body>'), '', $text);
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
    function replace_response($string="")
    {
        $textreturn = $string;
        $textreturn = str_replace('{', "{\r\n", $textreturn);
        $textreturn = str_replace('}', "}\r\n", $textreturn);
        $textreturn = str_replace(',', ",\r\n", $textreturn);        
        $textreturn = str_replace('[', "[\r\n", $textreturn);
        $textreturn = str_replace(']', "]\r\n", $textreturn);
        return $textreturn;
    }
}