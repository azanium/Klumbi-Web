<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Unlock extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Generate Redeem Avatar
     * Parameter :
     * 1. code
     * 2. jns -> data can use [av] (this param is required)
     * Return JSON
     */
    function redeem_avatar()
    {
        $jns = isset($_GET['jns'])?$_GET['jns']:""; 
        $code = isset($_GET['code'])?$_GET['code']:""; 
        $output['success'] = FALSE;
        $output['message'] = "Type Item is undefined";
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Avatar");
            $output['success'] = TRUE;
            switch ($jns)
            {
                case "av":
                    $this->mongo_db->select_db("Assets");
                    $this->mongo_db->select_collection("Avatar");
                    $tamp = $this->mongo_db->find(array("code"=>$code, "payment"=>array('$in'=>array('Unlock','Paid'))), 0, 0, array());
                    if($tamp)
                    {
                        $count = 1;
                        $generatorcode = $this->tambahan_fungsi->global_get_random(10);
                        $this->mongo_db->select_collection("Redeem");
                        $datatinsert=array(
                            'code'  =>$generatorcode,
                            'count'  =>$count,
                            'expire'  =>'',
                            'create'  =>$this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
                        );
                        $_idinsert = $this->mongo_db->insert($datatinsert);
                        $this->mongo_db->select_collection("RedeemAvatar");
                        foreach($tamp as $dtlistrd)
                        {
                            $datatinsertsub = array(
                                'avatar_id'  => (string)$dtlistrd['_id'],
                                'code_id'  =>(string)$_idinsert,
                            );
                            $this->mongo_db->update($datatinsertsub,array('$set'=>$datatinsertsub),array('upsert' => TRUE)); 
                        }
                        $output['message'] = "Avatar Item And Redeem are generated and code is ".$generatorcode;
                    }
                    else
                    {
                        $output['message'] = "Avatar ID is not Found";
                    }
                break;
                default : null;
            }
        }
        echo json_encode($output);
    }
//    function send_redeem_email($token="",$redeemcode="")
//    {
//        $this->load->library('email');
//        $dataretun['success']=FALSE;
//        $dataretun['message']="Token is Empty";
//        if(!empty($token) && !empty($redeemcode))
//        {
//            $this->mongo_db->select_db("Users");
//            $this->mongo_db->select_collection("Session");
//            $data=$this->mongo_db->findOne(array("session_id"=>$token));
//            if($data)
//            {
//                $this->mongo_db->select_collection("Account");
//                $data2=$this->mongo_db->findOne(array("_id"=>$this->mongo_db->mongoid($data['user_id'])));
//                if($data2)
//                {
//                    $config['protocol'] = 'smtp';
//                    $config['mailpath'] = '/usr/sbin/sendmail';
//                    $config['smtp_user'] = 'spb';
//                    $config['smtp_pass'] = 'spb2012';
//                    $config['smtp_port'] = 25;
//                    $config['smtp_host'] = 'mail.m-stars.net';
//                    $config['charset'] = 'utf-8';
//                    $config['wordwrap'] = TRUE;
//                    $config['mailtype'] = "html";
//                    $this->email->initialize($config);
//                    $this->email->from('support@popbloop.com', 'PopBloop Support');
//                    $this->email->to($data2['email']);
//                    $pesan ="<p>Dear <b>".$data2['username']."</b>,<br /><br /></p>";
//                    $pesan .="<p>This is your Redeem Code : <br /></p>";
//                    $pesan .="<p><ul>";
//                    $arrayredeemcode=  explode(".", $redeemcode);
//                    for($i=0;$i<count($arrayredeemcode);$i++)
//                    {
//                        $pesan .="<li>".$arrayredeemcode[$i]."</li>";
//                    }                    
//                    $pesan .="</ul /><br /></p>";
//                    $pesan .="<p>Please do not reply to this email, you received this email because you are playing at PopBloop.</p>";
//                    $this->email->subject('Your Redeem Code');
//                    $this->email->message($pesan);
//
//                    if($this->email->send())
//                    {
//                        $dataretun['success']=TRUE;
//                        $dataretun['message']="Email is send";
//                    }
//                    else
//                    {
//                        $dataretun['message']=$this->email->print_debugger();
//                    }
//                }
//                else 
//                {
//                    $dataretun['message']="Email is Empty";
//                }
//            }
//            else
//            {
//                $dataretun['message']="Token is not found";
//            }
//        }
//        echo json_encode($dataretun);
//    }
}
