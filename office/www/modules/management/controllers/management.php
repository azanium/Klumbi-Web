<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Menu","module2",FALSE,TRUE,"home");
    }    
    function index()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Module");
        $datapage['listmenu']=$this->mongo_db->find(array(),0,0,array('Order'=>1));
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/form-nestable/jquery.nestable.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
        );
        $js=array(
            base_url()."resources/plugin/form-nestable/jquery.nestable.min.js",
            base_url()."resources/plugin/form-nestable/app.min.js",
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"Edit Menu",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("management_view",$datapage);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function cruid_management()
    {        
        $this->m_checking->actions("Menu","module2","Edit",FALSE,TRUE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Module");
        for($i=0; $i<count($_POST['headmenu']);$i++)
        {
            $datatinsert=array(
                'Name'  =>$_POST['headmenu'][$i],
                'Order'  =>(1+$i),
            );
            $filter=array('Code'=> $_POST['txtid'][$i]);
            $this->mongo_db->update($filter,array('$set'=>$datatinsert));
        }
        $this->mongo_db->select_collection("Menu");
        for($j=0; $j<count($_POST['chilemenu']);$j++)
        {
            $datachild=array(
                'Name'  =>$_POST['chilemenu'][$j],
                'Order'  =>(1+$j),
            );
            $filterchile=array(
                '_id'=> $this->mongo_db->mongoid($_POST['txtchildid'][$j])
            );            
            $this->mongo_db->update($filterchile,array('$set'=>$datachild));
        }
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Update Menu Potision",$url,$user);
        redirect("management/index");
    }
    function dokumentasi()
    {
        if($_GET['key']==KEY_UNLOCK)
        {
            $this->__dokumentasi();
            echo "Dokumentasi Berhasil digenerate";
        }
        else
        {
            echo "Dokumentasi Gagal digenerate";
        }
    }
    function generateaccess()
    {
        if($_GET['key']==KEY_UNLOCK)
        {
            $this->__generatedata();
            $this->__resetdatasetting();
            $this->__dokumentasi();
            echo "Group Berhasil digenerate";
        }
        else
        {
            echo "Group Gagal digenerate";
        }
    }
    function resetdataall()
    {
        if($_GET['key']==KEY_UNLOCK)
        {
            $this->__resetdata();
            echo "Data Berhasil direset";
        }
        else
        {
            echo "Data Gagal direset";
        }
    }
    function resetsetting()
    {
        if($_GET['key']==KEY_UNLOCK)
        {
            $this->__resetdatasetting();
            echo "Setting digenerate";
        }
        else
        {
            echo "Setting digenerate";
        }
    }
    function __resetdatasetting()
    {
        /*Reset Game Skin*/        
        $this->mongo_db->select_db("Assets");        
        $this->mongo_db->select_collection("SkinColor");
        $this->mongo_db->remove(array());
        /* Data Skin*/
        for($i=1;$i<20;$i++)
        {
            $datatinsert=array(
                'name'  =>''.$i,
                'file'  => "skin".$i."_icon.jpg",
                'color'=>'',
            );
            $this->mongo_db->insert($datatinsert);
        }
        /* Data Setting*/
        $this->mongo_db->select_db("Game");        
        $this->mongo_db->select_collection("Settings");
        $this->mongo_db->remove(array());    
        $datatinsert=array(
                'name'  =>'Path Asset',
                'code'  =>'pathasset',
                'type'=>'url',
                'value'=>base_url()."bundles/",
                'self_value'=>base_url()."bundles/",
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting URL Path Asset',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Limit Color',
                'code'  =>'limitcolor',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Color limit load',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Limit Skin Color',
                'code'  =>'limitskincolor',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Skin Color limit load',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Limit Comment Show',
                'code'  =>'limitcomment',
                'type'=>'number',
                'value'=>'50',
                'self_value'=>'50',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting comment limit load',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Limit Brand',
                'code'  =>'limitbrand',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Brand limit load',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Limit Partner',
                'code'  =>'limitpartner',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Partner limit load',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Limit Page Avatar Mix',
                'code'  =>'limitavmix',
                'type'=>'number',
                'value'=>'5',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Avatar Mix and Collection limit load',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Limit Page Message',
                'code'  =>'limitmessage',
                'type'=>'number',
                'value'=>'5',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Message limit load',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Limit Page Avatar Item',
                'code'  =>'limitavitem',
                'type'=>'number',
                'value'=>'100',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Avatar Item limit load',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Limit Page News',
                'code'  =>'limitnews',
                'type'=>'number',
                'value'=>'5',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting News limit load',
            );
        $this->mongo_db->insert($datatinsert);  
        $datatinsert=array(
                'name'  =>'Limit Page Banner',
                'code'  =>'limitbanner',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Banner limit load',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Limit Contest Show',
                'code'  =>'limitcontest',
                'type'=>'number',
                'value'=>'5',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Contest limit load',
            );
        $this->mongo_db->insert($datatinsert); 
        $datatinsert=array(
                'name'  =>'Limit Hallframe show',
                'code'  =>'limithallframe',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Count for Limit Hallframe show',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Limit Contest show',
                'code'  =>'limitcontest',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Count for Limit Contest show',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Limit Store show',
                'code'  =>'limitstore',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Count for Limit Store show',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Limit Caracter Username',
                'code'  =>'limitavatarname',
                'type'=>'number',
                'value'=>'255',
                'self_value'=>'150',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Caracter Count for Avatar',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Text Color',
                'code'  =>'textcolor',
                'type'=>'color',
                'value'=>'#888888',
                'self_value'=>'',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Text Color',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Bagroud Color',
                'code'  =>'bgcollor',
                'type'=>'color',
                'value'=>'#1572df',
                'self_value'=>'',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting Bagroud Color',
            );
        $this->mongo_db->insert($datatinsert);         
        $datatinsert=array(
                'name'  =>'Email for Register Template',
                'code'  =>'regisemtemp',
                'type'=>'html',
                'value'=>"<div style='border: 1px solid #0063DC;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius: 4px;-khtml-border-radius:4px;-o-border-radius: 4px;padding: 0;'>
                    <h3 style='background-color: #0063DC;color: #FFF;padding: 5px;margin-top: 0;'>New User Register</h3>
                    <div style='padding: 10px;'>
                    <p>{greeter}, {name}</p>
                    <p>You success register on our site.</p>
                    <p>to verify your account open this <a href='{verifyemail}'>link</a> or copy this link to your browser {verifyemail}</p>
                    <p>{linksite}</p>
                    </div>
                    </div>",
                'self_value'=>'',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'Template for Send Email User Register. <br />
                    For Greeter use <i class="icon-double-angle-right"> </i> {greeter}<br />
                    For Name use <i class="icon-double-angle-right"> </i> {name}<br />
                    For Link Site use <i class="icon-double-angle-right"> </i> {linksite}<br />
                    For Link Verify Email <i class="icon-double-angle-right"> </i> {verifyemail}<br />',
            );
        $this->mongo_db->insert($datatinsert); 
        $datatinsert=array(
                'name'  =>'Email for Success Change Password Template',
                'code'  =>'changesucessemtemp',
                'type'=>'html',
                'value'=>"<div style='border: 1px solid #0063DC;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius: 4px;-khtml-border-radius:4px;-o-border-radius: 4px;padding: 0;'>
                    <h3 style='background-color: #0063DC;color: #FFF;padding: 5px;margin-top: 0;'>Success change password</h3>
                    <div style='padding: 10px;'>
                    <p>Hi User,</p>
                    <p>You success change your password on our site.</p>
                    <p>You can login by email : <strong>{email}</strong>.</p>
                    <p>And your password : <strong>{password}</strong>.</p>                        
                    <p>{linksite}</p>
                    </div>
                    </div>",
                'self_value'=>'',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'Template for Send Email User Register. <br />
                    For Greeter use <i class="icon-double-angle-right"> </i> {greeter}<br />
                    For Email use <i class="icon-double-angle-right"> </i> {email}<br />
                    For Password use <i class="icon-double-angle-right"> </i> {password}<br />
                    For Link Site use <i class="icon-double-angle-right"> </i> {linksite}<br />',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Email for Forgot Password Template',
                'code'  =>'forpassemtemp',
                'type'=>'html',
                'value'=>"<div style='border: 1px solid #d43f3a;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius: 4px;-khtml-border-radius:4px;-o-border-radius: 4px;padding: 0;'> 
                    <h3 style='background-color: #d43f3a;color: #FFF;padding: 5px;margin-top: 0;'>Change password</h3>
                    <div style='padding: 10px;'>
                    <p>{greeter}, {name}</p>
                    <p>You try to change your password.</p>
                    <p><b style='color:#0099FF;font-size:12px;font-style:italic;'>This is you</b>, please follow this step to change your password.</p>
                    <p>Click <a href='{linkpassword}' target='_blank'>Here</a>.</p>
                    <p>or you can copy this link bellow to your browser.</p>
                    <p>{linkpassword}<br /><br />To get new access go to <a href='{newrequest}' target='_blank'>this</a>.</p>
                    <p><u style='color:#FF3300;font-size:12px;font-style:italic;'>This is not you</u>, Contact {emailadmin} to save your account. </p>
                    <p>{linksite}</p>
                    </div>
                    </div>",
                'self_value'=>'',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'Template for Send Email Forgot Password User. <br />
                    For Greeter use <i class="icon-double-angle-right"> </i> {greeter}<br />
                    For Name use <i class="icon-double-angle-right"> </i> {name}<br />
                    For Link Change Password use <i class="icon-double-angle-right"> </i> {linkpassword}<br />
                    For Link New Token use <i class="icon-double-angle-right"> </i> {newrequest}<br />
                    For Admin Email use <i class="icon-double-angle-right"> </i> {emailadmin}<br />
                    For Link Site use <i class="icon-double-angle-right"> </i> {linksite}<br />
                    ',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Email for Contact Template',
                'code'  =>'kontactemtemp',
                'type'=>'html',
                'value'=>"<div style='border: 1px solid #0063DC;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius: 4px;-khtml-border-radius:4px;-o-border-radius: 4px;padding: 0;'>
                    <h3 style='background-color: #0063DC;color: #FFF;padding: 5px;margin-top: 0;'>Contact User</h3>
                    <div style='padding: 10px;'> <p> {contentdata} </p> 
                    </div>
                    </div>",
                'self_value'=>'',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'Template for Send Email Contact User. <br />
                    For content use <i class="icon-double-angle-right"> </i> {contentdata}<br />',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Email for Invite User Template',
                'code'  =>'inviteemailuser',
                'type'=>'html',
                'value'=>"<div style='border: 1px solid #0063DC;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius: 4px;-khtml-border-radius:4px;-o-border-radius: 4px;padding: 0;'>
                    <h3 style='background-color: #0063DC;color: #FFF;padding: 5px;margin-top: 0;'>Invite User</h3>
                    <div style='padding: 10px;'>
                    <p>{greeter}, <br /> Your Friend {name},</p>
                    <p>invite you to join us.</p>                        
                    <p>{linksite}</p>
                    </div>
                    </div>",
                'self_value'=>'',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'Template for Send Email Invite User. <br />
                    For Greeter use <i class="icon-double-angle-right"> </i> {greeter}<br />
                    For Name User use <i class="icon-double-angle-right"> </i> {name}<br />
                    For Link Site use <i class="icon-double-angle-right"> </i> {linksite}<br />',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Email for User Email Verification',
                'code'  =>'verificationemailuser',
                'type'=>'html',
                'value'=>"<div style='border: 1px solid #0063DC;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius: 4px;-khtml-border-radius:4px;-o-border-radius: 4px;padding: 0;'>
                    <h3 style='background-color: #0063DC;color: #FFF;padding: 5px;margin-top: 0;'>Email Verification</h3>
                    <div style='padding: 10px;'>
                    <p>{greeter} {name},</p>
                    <p>This is link your email verification <a href='{verifyemail}'>klik</a>.</p>     
                    <p>or copy this link to your browser <br /> {verifyemail} </p> 
                    <p>{linksite}</p>
                    </div>
                    </div>",
                'self_value'=>'',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'Template for Send Email Invite User. <br />
                    For Greeter use <i class="icon-double-angle-right"> </i> {greeter}<br />
                    For Name User use <i class="icon-double-angle-right"> </i> {name}<br />
                    For Link Site use <i class="icon-double-angle-right"> </i> {linksite}<br />',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Template for share social',
                'code'  =>'socialsharetemplate',
                'type'=>'textarea',
                'value'=>"Join us on klumbi :D",
                'self_value'=>'',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'',
            );
        $this->mongo_db->insert($datatinsert);
        $this->mongo_db->select_db("Game");        
        $this->mongo_db->select_collection("Point");
        $this->mongo_db->remove(array());        
        $datatinsert=array(
                'name'  =>'Point Register',
                'code'  =>'pointregister',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Open Tutorial',
                'code'  =>'pointopentutorial',
                'type'=>'number',
                'value'=>'5',
                'self_value'=>'5',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Edit Avatar',
                'code'  =>'pointeditavatar',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Captcure And Share',
                'code'  =>'pointcaptureshare',
                'type'=>'number',
                'value'=>'5',
                'self_value'=>'5',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Create Mix First time',
                'code'  =>'pointcreatemixfirst',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Creat Mix each 10',
                'code'  =>'pointcreatemoremix',
                'type'=>'number',
                'value'=>'5',
                'self_value'=>'5',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Collect Mix',
                'code'  =>'pointcollectmix',
                'type'=>'number',
                'value'=>'5',
                'self_value'=>'5',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Share Collect Mix',
                'code'  =>'pointsharecollectmix',
                'type'=>'number',
                'value'=>'15',
                'self_value'=>'15',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Join Contest',
                'code'  =>'pointjoincontest',
                'type'=>'number',
                'value'=>'20',
                'self_value'=>'20',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Submit Mix to Contest',
                'code'  =>'pointsubmitcontest',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Win Contest',
                'code'  =>'pointwincontest',
                'type'=>'number',
                'value'=>'50',
                'self_value'=>'50',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Buy Avatar Item',
                'code'  =>'pointbuyavataritem',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);        
        $datatinsert=array(
                'name'  =>'Point Buy 10 Avatar Item on Store',
                'code'  =>'pointbuy10itemavatar',
                'type'=>'number',
                'value'=>'15',
                'self_value'=>'15',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Buy 20 Avatar Item on Store',
                'code'  =>'pointbuy20itemavatar',
                'type'=>'number',
                'value'=>'25',
                'self_value'=>'25',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Buy 25 Avatar Item on Store',
                'code'  =>'pointbuy25itemavatar',
                'type'=>'number',
                'value'=>'50',
                'self_value'=>'50',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Buy 50 Avatar Item on Store',
                'code'  =>'pointbuy50itemavatar',
                'type'=>'number',
                'value'=>'100',
                'self_value'=>'100',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Love And Comment Store',
                'code'  =>'pointlovecommentstore',
                'type'=>'number',
                'value'=>'10',
                'self_value'=>'10',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Follow Store',
                'code'  =>'pointfolowstore',
                'type'=>'number',
                'value'=>'5',
                'self_value'=>'5',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Point Invite Friend',
                'code'  =>'pointinvitefriend',
                'type'=>'number',
                'value'=>'5',
                'self_value'=>'5',
                'format'=>'',
                'options'  =>array(),
                'descriptions'=>'For Setting User Point',
            );
        $this->mongo_db->insert($datatinsert);        
    }
    function __resetdata()
    {        
        /*$this->mongo_db->dropcollection("Users","Account");
        $this->mongo_db->dropdatabase("Users");*/
        $this->mongo_db->select_db("Users");        
        $this->mongo_db->select_collection("Account");
        $this->mongo_db->remove(array()); 
        $datatinsert=array(
            'email'  =>"klumbi@m-stars.net",
             "valid" => TRUE,
             "artist" => FALSE,
            'password'  =>md5("sahilatua"),
            'username'=>"adminklumbi",
            'join_date'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'activation_key' =>md5("sahilatua"), 
            'token_key' =>md5("sahilatua"), 
            'fb_id'  =>"",
            "twitter_id" =>"",
            'brand_id'=>'',
            'access'=>'menu1',
        );
        $insernew = $this->mongo_db->insert($datatinsert);   
        $datatinsertprope=array(
            'lilo_id'=>(string)$insernew,
            'avatarname'  =>"Admin Klumbi",
            'fullname' =>"Admin Klumbi",
            'sex'=>"male",                        
            'website'=>base_url(),
            'link'=>"",
            'bodytype'=>"medium",
            'birthday'  =>date("Y-m-d"),
            'birthday_dd'=>date("d"),
            'birthday_mm'=>date("m"),
            'birthday_yy' =>date("Y"), 
            'state_of_mind' => '',
            'about'  =>'',
            'picture'  =>"",
            'location'=>"",                        
            'handphone'  =>''
        );
        $this->mongo_db->select_collection("Properties");
        $this->mongo_db->insert($datatinsertprope);
        /*Process Reset Data*/
        $this->mongo_db->select_db("Assets");        
        $this->mongo_db->select_collection("Payment");
        $this->mongo_db->remove(array());
        $this->mongo_db->insert(array('name'  =>'Default'));
        $this->mongo_db->insert(array('name'  =>'Free'));
        $this->mongo_db->insert(array('name'  =>'Paid'));
        $this->mongo_db->insert(array('name'  =>'Unlock'));
        /*Reset Data Body Part Type*/
        $this->mongo_db->select_db("Assets");        
        $this->mongo_db->select_collection("AvatarBodyPart");
        $this->mongo_db->remove(array());
        $this->mongo_db->insert(array('name'  =>'body','title'=>'Body','parent'=>''));
        $this->mongo_db->insert(array('name'  =>'gender','title'=>'Gender','parent'=>''));
        $this->mongo_db->insert(array('name'  =>'face','title'=>'Face','parent'=>''));
        $this->mongo_db->insert(array('name'  =>'head','title'=>'Head','parent'=>''));
        $this->mongo_db->insert(array('name'  =>'face_part_eye_brows','title'=>'Eye Brows','parent'=>'head'));
        $this->mongo_db->insert(array('name'  =>'face_part_eyes','title'=>'Eyes','parent'=>'head'));
        $this->mongo_db->insert(array('name'  =>'face_part_lip','title'=>'Lip','parent'=>'head'));
        $this->mongo_db->insert(array('name'  =>'hair','title'=>'Hair','parent'=>'head'));
        $this->mongo_db->insert(array('name'  =>'hand','title'=>'Hand','parent'=>''));
        $this->mongo_db->insert(array('name'  =>'leg','title'=>'Legs','parent'=>''));
        $this->mongo_db->insert(array('name'  =>'shoes','title'=>'Shoes','parent'=>''));
        $this->mongo_db->insert(array('name'  =>'pants','title'=>'Pants','parent'=>''));
        $this->mongo_db->insert(array('name'  =>'propertie','title'=>'Propertie','parent'=>''));
        $this->mongo_db->insert(array('name'  =>'hat','title'=>'Hat','parent'=>'propertie'));
        $this->mongo_db->insert(array('name'  =>'glass','title'=>'Glass','parent'=>'propertie'));
        $this->mongo_db->insert(array('name'  =>'helmet','title'=>'Helmet','parent'=>'propertie'));
        $this->mongo_db->insert(array('name'  =>'watch','title'=>'Hand Watch','parent'=>'propertie'));
        /*Reset Data Required And Reward*/
        $this->mongo_db->select_db("Game");        
        $this->mongo_db->select_collection("RequiredRewards");
        $this->mongo_db->remove(array());
        /* Data Required*/
        $datatinsert=array(
                'name'  =>'Equipments',
                'code'  =>'e',
                'type'=>'Required',
                'self_value'=>'Yes',
                'table'=>'',
                'options'  =>array('coin','energy'),
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Inventory Items',
                'code'  =>'i',
                'type'=>'Required',
                'self_value'=>'No',
                'table'=>'Inventory',
                'options'  =>array('name','name'),
            );
        $this->mongo_db->insert($datatinsert);
        /* Data Reward*/
        $datatinsert=array(
                'name'  =>'Equipments',
                'code'  =>'e',
                'type'=>'Rewards',
                'self_value'=>'Yes',
                'table'=>'',
                'options'  =>array('coin','energy'),
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Inventory Items',
                'code'  =>'i',
                'type'=>'Rewards',
                'self_value'=>'No',
                'table'=>'Inventory',
                'options'  =>array('name','name'),
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Redeem',
                'code'  =>'r',
                'type'=>'Rewards',
                'self_value'=>'No',
                'table'=>'Redeem',
                'options'  =>array('code','code'),
            );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                'name'  =>'Auto Generate Redeem',
                'code'  =>'x',
                'type'=>'Rewards',
                'self_value'=>'Yes',
                'table'=>'',
                'options'  =>array('generateredeem'),
            );
        $this->mongo_db->insert($datatinsert);         
    }
    function __dokumentasi()
    {
        /*Begin Create Documentation*/
        $this->mongo_db->select_db("Website"); 
        $this->mongo_db->select_collection("Documentation");
        $this->mongo_db->remove(array());
        $datatinsert=array(
            'name'  =>"API Header",
            'descriptions'  =>"",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'name'  =>"Database",
            'descriptions'  =>"",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'name'  =>"Website",
            'descriptions'  =>"",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Mix Cek Love",            
            'descriptions'  =>"Social API for cek user is love or not about Avatar Mix",   
            'url'  => $this->template_admin->link("api/social/avatarmix/cek_like/idmix/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "like" => "bool",
                "message" => "string",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idmix",
                    "descuri"=>"field _id from Database Users dan collection AvatarMix (this param is required)"
                ),
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Mix Button Love and Unlove",            
            'descriptions'  =>"Social API for user give love or not about Avatar Mix",   
            'url'  => $this->template_admin->link("api/social/avatarmix/button_like/idmix/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "like" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idmix",
                    "descuri"=>"field _id from Database Users dan collection AvatarMix (this param is required)"
                ),
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Mix Count User Love",            
            'descriptions'  =>"Social API for count user give love about Avatar Mix",   
            'url'  => $this->template_admin->link("api/social/avatarmix/count_like/idmix"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idmix",
                    "descuri"=>"field _id from Database Users dan collection AvatarMix (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Mix List User Love",            
            'descriptions'  =>"Social API for list user give love about Avatar Mix",   
            'url'  => $this->template_admin->link("api/social/avatarmix/list_like/idmix"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "Integer",
                "data" => array(array(
                    'datetime'=>"string(datetime)",
                    "user_id"=>"string",
                    "fullname"=>"string",
                    "sex"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idmix",
                    "descuri"=>"field _id from Database Users dan collection AvatarMix (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Mix Add Comment",            
            'descriptions'  =>"Social API for user give comment about Avatar Mix",   
            'url'  => $this->template_admin->link("api/social/avatarmix/add_comment"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"mix_id",
                    "descparam"=>"field _id from Database Users dan collection AvatarMix (this param is required)"
                ),
                array(
                    "dtparam"=>"user_id",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dtparam"=>"comment",
                    "descparam"=>"Text user Comment (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Mix Delete Comment",            
            'descriptions'  =>"Social API for user Delete comment about Avatar Mix",   
            'url'  => $this->template_admin->link("api/social/avatarmix/delete_comment/idmix"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idmix",
                    "descuri"=>"field _id from Database Users dan collection AvatarMix (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Mix Count Comment",            
            'descriptions'  =>"Social API for get Count user comment about Avatar Mix",   
            'url'  => $this->template_admin->link("api/social/avatarmix/count_comment/idmix"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idmix",
                    "descuri"=>"field _id from Database Users dan collection AvatarMix (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Mix List User Comment",            
            'descriptions'  =>"Social API for get list user comment about Avatar Mix",   
            'url'  => $this->template_admin->link("api/social/avatarmix/list_comment/idmix/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    '_id'=>"string(field _id from comment)",
                    'datetime'=>"string(datetime)",
                    "comment"=>"string",
                    "user_id"=>"string",
                    "sex"=>"string",
                    "fullname"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idmix",
                    "descuri"=>"field _id from Database Users dan collection AvatarMix (this param is required)"
                ),
                array(
                    "dturi"=>"0",
                    "descuri"=>"pagging data load start from param 0"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);  
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Item Cek Love",            
            'descriptions'  =>"Social API for cek user is love or not about Avatar Item",   
            'url'  => $this->template_admin->link("api/social/avataritem/cek_like/avatarid/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "like" => "bool",
                "message" => "string",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"avatarid",
                    "descuri"=>"field _id from Database Assets dan collection Avatar (this param is required)"
                ),
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ) 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Item Button Love and Unlove",            
            'descriptions'  =>"Social API for user give love or not about Avatar Item",   
            'url'  => $this->template_admin->link("api/social/avataritem/button_like/avatarid/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "love" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"avatarid",
                    "descuri"=>"field _id from Database Assets dan collection Avatar (this param is required)"
                ),
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ) 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Item Count User Love",            
            'descriptions'  =>"Social API for count user give love about Avatar Item",   
            'url'  => $this->template_admin->link("api/social/avataritem/count_like/avatarid"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"avatarid",
                    "descuri"=>"field _id from Database Assets dan collection Avatar (this param is required)"
                ),
            )   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Item List User Love",            
            'descriptions'  =>"Social API for list user give love about Avatar Item",   
            'url'  => $this->template_admin->link("api/social/avataritem/list_like/avatarid"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "Integer",
                "data" => array(array(
                    'datetime'=>"string(datetime)",
                    "user_id"=>"string",
                    "fullname"=>"string",
                    "sex"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"avatarid",
                    "descuri"=>"field _id from Database Assets dan collection Avatar (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Item Add Comment",            
            'descriptions'  =>"Social API for user give comment about Avatar Item",   
            'url'  => $this->template_admin->link("api/social/avataritem/add_comment"), 
            'methode'  =>"POST",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"avatar_id",
                    "descparam"=>"field _id from Database Assets dan collection Avatar (this param is required)"
                ),
                array(
                    "dtparam"=>"user_id",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dtparam"=>"comment",
                    "descparam"=>"Text user Comment (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Item Delete Comment",            
            'descriptions'  =>"Social API for user Delete comment about Avatar Item",   
            'url'  => $this->template_admin->link("api/social/avataritem/delete_comment/id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"id",
                    "descuri"=>"field _id from Database Social dan collection Social (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Item Count Comment",            
            'descriptions'  =>"Social API for get Count user comment about Avatar Item",   
            'url'  => $this->template_admin->link("api/social/avataritem/count_comment/avatar_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"avatar_id",
                    "descuri"=>"field _id from Database Assets dan collection Avatar (this param is required)"
                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Avatar Item List User Comment",            
            'descriptions'  =>"Social API for get list user comment about Avatar Item",   
            'url'  => $this->template_admin->link("api/social/avataritem/list_comment/avatarid/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    '_id'=>"string(field _id from comment)",
                    'datetime'=>"string(datetime)",
                    "comment"=>"string",
                    "user_id"=>"string",
                    "sex"=>"string",
                    "fullname"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"avatarid",
                    "descuri"=>"field _id from Database Assets dan collection Avatar (this param is required)"
                ),
                array(
                    "dturi"=>"0",
                    "descuri"=>"pagging data load start from param 0"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Banner Cek Love",            
            'descriptions'  =>"Social API for cek user is love or not about Banner",   
            'url'  => $this->template_admin->link("api/social/banner/cek_like/bannerid/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "like" => "bool",
                "message" => "string",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"bannerid",
                    "descuri"=>"field _id from Database Assets dan collection Banner (this param is required)"
                ),
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Banner Button Love and Unlove",            
            'descriptions'  =>"Social API for user give love or not about Banner",   
            'url'  => $this->template_admin->link("api/social/banner/button_like/bannerid/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "like" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"bannerid",
                    "descuri"=>"field _id from Database Assets dan collection Banner (this param is required)"
                ),
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Banner Count User Love",            
            'descriptions'  =>"Social API for count user give love about Banner",   
            'url'  => $this->template_admin->link("api/social/banner/count_like/bannerid"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "Integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"bannerid",
                    "descuri"=>"field _id from Database Assets dan collection Banner (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Banner List User Love",            
            'descriptions'  =>"Social API for list user give love about Banner",   
            'url'  => $this->template_admin->link("api/social/banner/list_like/bannerid"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "Integer",
                "data" => array(array(
                    'datetime'=>"string(datetime)",
                    "user_id"=>"string",
                    "fullname"=>"string",
                    "sex"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"bannerid",
                    "descuri"=>"field _id from Database Assets dan collection Banner (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Banner Add Comment",            
            'descriptions'  =>"Social API for user give comment about Banner",   
            'url'  => $this->template_admin->link("api/social/banner/add_comment"), 
            'methode'  =>"POST",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"banner_id",
                    "descparam"=>"field _id from Database Assets dan collection Banner (this param is required)"
                ),
                array(
                    "dtparam"=>"user_id",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dtparam"=>"comment",
                    "descparam"=>"Text user Comment (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Banner Delete Comment",            
            'descriptions'  =>"Social API for user Delete comment about Banner",   
            'url'  => $this->template_admin->link("api/social/banner/delete_comment/idcomment"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idcomment",
                    "descuri"=>"field _id from Database Social dan collection Social (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Banner Count Comment",            
            'descriptions'  =>"Social API for get Count user comment about Banner",   
            'url'  => $this->template_admin->link("api/social/banner/count_comment/bannerid"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"bannerid",
                    "descuri"=>"field _id from Database Assets dan collection Banner (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Banner List User Comment",            
            'descriptions'  =>"Social API for get list user comment about Banner",   
            'url'  => $this->template_admin->link("api/social/banner/list_comment/bannerid/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    '_id'=>"string(field _id from comment)",
                    'datetime'=>"string(datetime)",
                    "comment"=>"string",
                    "user_id"=>"string",
                    "sex"=>"string",
                    "fullname"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"bannerid",
                    "descuri"=>"field _id from Database Assets dan collection Banner (this param is required)"
                ),
                array(
                    "dturi"=>"0",
                    "descuri"=>"pagging data load start from param 0"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert); 
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API News Cek Love",            
            'descriptions'  =>"Social API for cek user is love or not about news",   
            'url'  => $this->template_admin->link("api/social/news/cek_like/idnews/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "like" => "bool",
                "message" => "string",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idnews",
                    "descuri"=>"field _id from Database Articles dan collection ContentNews (this param is required)"
                ),
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ),    
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API News Button Love and Unlove",            
            'descriptions'  =>"Social API for user give love or not about news",   
            'url'  => $this->template_admin->link("api/social/news/button_like/idnews/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "like" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idnews",
                    "descuri"=>"field _id from Database Articles dan collection ContentNews (this param is required)"
                ),
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API News Count User Love",            
            'descriptions'  =>"Social API for count user give love about news",   
            'url'  => $this->template_admin->link("api/social/news/count_like/idnews"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "Integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idnews",
                    "descuri"=>"field _id from Database Articles dan collection ContentNews (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API News List User Love",            
            'descriptions'  =>"Social API for list user love about news",   
            'url'  => $this->template_admin->link("api/social/news/list_like/idnews"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "Integer",
                "data" => array(array(
                    'datetime'=>"string(datetime)",
                    "user_id"=>"string",
                    "fullname"=>"string",
                    "sex"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idnews",
                    "descuri"=>"field _id from Database Articles dan collection ContentNews (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API News Add Comment",            
            'descriptions'  =>"Social API for user give comment about news",   
            'url'  => $this->template_admin->link("api/social/news/add_comment"), 
            'methode'  =>"POST",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"idnews",
                    "descparam"=>"field _id from Database Articles dan collection ContentNews (this param is required)"
                ),
                array(
                    "dtparam"=>"user_id",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dtparam"=>"comment",
                    "descparam"=>"Text user Comment (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API News Delete Comment",            
            'descriptions'  =>"Social API for user Delete comment about news",   
            'url'  => $this->template_admin->link("api/social/news/delete_comment/idcomment"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idcomment",
                    "descuri"=>"field _id from Database Social dan collection Social (this param is required)"
                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API News Count Comment",            
            'descriptions'  =>"Social API for get Count user comment about news",   
            'url'  => $this->template_admin->link("api/social/news/count_comment/idnews"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idnews",
                    "descuri"=>"field _id from Database Articles dan collection ContentNews (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API News List User Comment",            
            'descriptions'  =>"Social API for get list user comment about news",   
            'url'  => $this->template_admin->link("api/social/news/list_comment/idnews/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    '_id'=>"string(field _id from comment)",
                    'datetime'=>"string(datetime)",
                    "comment"=>"string",
                    "user_id"=>"string",
                    "sex"=>"string",
                    "fullname"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idnews",
                    "descuri"=>"field _id from Database Articles dan collection ContentNews (this param is required)"
                ),
                array(
                    "dturi"=>"0",
                    "descuri"=>"pagging data load start from param 0"
                ),
            ),    
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Brand Cek Love",            
            'descriptions'  =>"Social API for cek user is love or not about Brand",   
            'url'  => $this->template_admin->link("api/social/brand/cek_like/idbrand/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "like" => "bool",
                "count" => "integer",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idbrand",
                    "descuri"=>"field _id from Database Assets dan collection Brand (this param is required)"
                ),
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Brand Button Love and Unlove",            
            'descriptions'  =>"Social API for user give love or not about Brand",   
            'url'  => $this->template_admin->link("api/social/brand/button_like/idbrand/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "like" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idbrand",
                    "descuri"=>"field _id from Database Assets dan collection Brand (this param is required)"
                ),
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Brand Count User Love",            
            'descriptions'  =>"Social API for count user give love about Brand",   
            'url'  => $this->template_admin->link("api/social/brand/count_like/idbrand"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idbrand",
                    "descuri"=>"field _id from Database Assets dan collection Brand (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Brand List User Love",            
            'descriptions'  =>"Social API for list user give love about Brand",   
            'url'  => $this->template_admin->link("api/social/brand/list_like/idbrand"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    'datetime'=>"string(datetime)",
                    "user_id"=>"string",
                    "fullname"=>"string",
                    "sex"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......")),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idbrand",
                    "descuri"=>"field _id from Database Assets dan collection Brand (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Brand Add Comment",            
            'descriptions'  =>"Social API for user give comment about Brand",   
            'url'  => $this->template_admin->link("api/social/brand/add_comment"), 
            'methode'  =>"POST",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"brand_id",
                    "descparam"=>"field _id from Database Assets dan collection Brand (this param is required)"
                ),
                array(
                    "dtparam"=>"user_id",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dtparam"=>"comment",
                    "descparam"=>"Text user Comment (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Brand Delete Comment",            
            'descriptions'  =>"Social API for user Delete comment about Brand",   
            'url'  => $this->template_admin->link("api/social/brand/delete_comment/idcomment"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idcomment",
                    "descuri"=>"field _id from Database Social dan collection Social (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Brand Count Comment",            
            'descriptions'  =>"Social API for get Count user comment about Brand",   
            'url'  => $this->template_admin->link("api/social/brand/count_comment/idbrand"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idbrand",
                    "descuri"=>"field _id from Database Assets dan collection Brand (this param is required)"
                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Brand List User Comment",            
            'descriptions'  =>"Social API for get list user comment about Brand",   
            'url'  => $this->template_admin->link("api/social/brand/list_comment/idbrand/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "_id" => "string field id Comment",
                    'datetime'=>"string(datetime)",
                    'comment'=>"string",
                    "user_id"=>"string",
                    "fullname"=>"string",
                    "sex"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......")),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idbrand",
                    "descuri"=>"field _id from Database Assets dan collection Brand (this param is required)"
                ),
                array(
                    "dturi"=>"0",
                    "descuri"=>"pagging data load start from param 0"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Get List Avatar Bodypart Type",            
            'descriptions'  =>"Social API for get list Avatar Bodypart type",   
            'url'  => $this->template_admin->link("api/tipe/index"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "name" => "string",
                    "title" => "string",
                    "parent" => "string",
                ),array("....."=>"......"))
            )
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Uploader File",            
            'descriptions'  =>"API for upload file",   
            'url'  => $this->template_admin->link("api/uploader/index/filename"), 
            'methode'  =>"POST",  
            'return'  =>"JSON",
            "response" => array(
                "message" => "string",
                "success" => "bool",
                "name" => "string",
                "url" => "string(Complete URL)",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"filename",
                    "descuri"=>"Name of variable which post will catch data image"
                ),
            ),  
            'param'  =>array(
                array(
                    "dtparam"=>"filename",
                    "descparam"=>"data file will be uploaded (this param is required) multipartform"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API get Count Skin Color",            
            'descriptions'  =>"API for get count skin color",   
            'url'  => $this->template_admin->link("api/skincolor/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer"
            )
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get One Skin Color Detail",            
            'descriptions'  =>"API for get One Detail skin color",   
            'url'  => $this->template_admin->link("api/skincolor/get_one/_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "id" => "string",
                    "name" => "string",
                    "color" => "string",
                    "picture" => "string",
                    "url_picture" => "string(Complete URL)",
                )
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"_id",
                    "descuri"=>"field _id from Database Assets dan collection SkinColor (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get List Data Skin Color",            
            'descriptions'  =>"API for get List Data skin color",   
            'url'  => $this->template_admin->link("api/skincolor/list_data/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id" => "string",
                    "name" => "string",
                    "color" => "string",
                    "picture" => "string",
                    "url_picture" => "string(Complete URL)",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"0",
                    "descuri"=>"start data show default 0 (must be number)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API get Count Event",            
            'descriptions'  =>"API for get count event",   
            'url'  => $this->template_admin->link("api/event/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer"
            )
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get One Detail of Event",            
            'descriptions'  =>"API for get one Detail of Event",   
            'url'  => $this->template_admin->link("api/event/get_one/_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "id" => "string",
                    "allDay" => "bool",
                    "title" => "string",
                    "color" => "string",
                    "description" => "string",
                    "url" => "string(url)",
                    "start_date" => "string(date)",
                    "start_time" => "string(time)",
                    "end_date" => "string(date)",
                    "end_time" => "string(time)",
                    "picture" => "string",
                    "url_picture" => "string(Complete URL)",
                )
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"_id",
                    "descuri"=>"field _id from Database Articles dan collection Event (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API get List Data Event",            
            'descriptions'  =>"API for get List Data Event",   
            'url'  => $this->template_admin->link("api/event/list_data/start/end"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id" => "string",
                    "allDay" => "bool",
                    "title" => "string",
                    "color" => "string",
                    "description" => "string",
                    "url" => "string(url)",
                    "start_date" => "string(date)",
                    "start_time" => "string(time)",
                    "end_date" => "string(date)",
                    "end_time" => "string(time)",
                    "picture" => "string",
                    "url_picture" => "string(Complete URL)",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"start",
                    "descuri"=>"Start Date load from database format must be yyyy-mm-dd (this param is required)"
                ),
                array(
                    "dturi"=>"end",
                    "descuri"=>"End Date load from database format must be yyyy-mm-dd (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get Count Color",            
            'descriptions'  =>"API for get count color",   
            'url'  => $this->template_admin->link("api/color/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer"
            )
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get One Detail Color",            
            'descriptions'  =>"API for get one Detail color",   
            'url'  => $this->template_admin->link("api/color/get_one/_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "id" => "string",
                    "name" => "string",
                    "color" => "string(RGBA)",
                    "picture" => "string",
                    "url_picture" => "string(Complete URL)",
                )
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"_id",
                    "descuri"=>"field _id from Database Assets dan collection Color (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API get List Data Color",            
            'descriptions'  =>"API for get List Data color",   
            'url'  => $this->template_admin->link("api/color/list_data/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id" => "string",
                    "name" => "string",
                    "color" => "string(RGBA)",
                    "picture" => "string",
                    "url_picture" => "string(Complete URL)",
                ),array("....."=>"......"))
            ),   
            'uri'  =>array(
                array(
                    "dturi"=>"0",
                    "descuri"=>"start data show default 0 (must be number)"
                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get Count Animation",            
            'descriptions'  =>"API for get count Animation",   
            'url'  => $this->template_admin->link("api/animation/count/gender"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer"
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"gender",
                    "descuri"=>"data can use [male/female] or empty (this param is optional)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get One Detail Animation",            
            'descriptions'  =>"API for get one Detail Animation",   
            'url'  => $this->template_admin->link("api/animation/get_one/_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "id" => "string",
                    "name" => "string",
                    "permission" => "string",
                    "gender" => "string",
                    "description" => "string",                  
                    "animation_file_web" => "string",
                    "animation_file_ios" => "string",
                    "animation_file_android" => "string",
                    "picture" => "string",
                    "url_picture" => "string(Complete URL)",
                    "url_file_web" => "string(Complete URL)",
                    "url_file_ios" => "string(Complete URL)",
                    "url_file_android" => "string(Complete URL)",
                )
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"_id",
                    "descuri"=>"field _id from Database Assets dan collection Animation (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API get List Data Animation",            
            'descriptions'  =>"API for get List Data Animation",   
            'url'  => $this->template_admin->link("api/animation/list_data/gender"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id" => "string",
                    "name" => "string",
                    "permission" => "string",
                    "gender" => "string",
                    "description" => "string",                  
                    "animation_file_web" => "string",
                    "animation_file_ios" => "string",
                    "animation_file_android" => "string",
                    "picture" => "string",
                    "url_picture" => "string(Complete URL)",
                    "url_file_web" => "string(Complete URL)",
                    "url_file_ios" => "string(Complete URL)",
                    "url_file_android" => "string(Complete URL)",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"gender",
                    "descuri"=>"data can use [male/female] or empty (this param is optional)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get List Data Animation Command",            
            'descriptions'  =>"API for get List Data Animation Command",   
            'url'  => $this->template_admin->link("api/animation/commad_animation/gender"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array("nam1","name2","name3")
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"gender",
                    "descuri"=>"data can use [male/female] or empty (this param is optional)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get Count News",            
            'descriptions'  =>"API for get Count News",   
            'url'  => $this->template_admin->link("api/news/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer"
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get one Detail News",            
            'descriptions'  =>"API for get one Detail News",   
            'url'  => $this->template_admin->link("api/news/get_one/_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "id" => "string",
                    "title" => "string",
                    "alias" => "string",
                    "text" => "string",
                    "update" => "string(datetime)",
                    "state_document" => "string",
                )
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"_id",
                    "descuri"=>"field _id from database Articles collection ContentNews dan state_document must be on publish (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Data News",            
            'descriptions'  =>"API for get List Data News",   
            'url'  => $this->template_admin->link("api/news/list_data/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id" => "string",
                    "title" => "string",
                    "alias" => "string",
                    "text" => "string",
                    "update" => "string(datetime)",
                    "state_document" => "string",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"0",
                    "descuri"=>"start data show default 0 (must be number)"
                ),
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"search",
                    "descparam"=>"data filter used for key search title of news (this param is optional)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Data Game Server",            
            'descriptions'  =>"API for get List Data Game Server",   
            'url'  => $this->template_admin->link("api/gameserver/index"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id" => "string",
                    "name" => "string",
                    "ip" => "string",
                    "port" => "string",
                    "max_ccu" => "integer",
                ),array("....."=>"......"))
            )
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get Count Brand",            
            'descriptions'  =>"API for get Count Brand",   
            'url'  => $this->template_admin->link("api/brand/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer"
            )
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API one Detail of Brand",            
            'descriptions'  =>"API for get one Detail of Brand",   
            'url'  => $this->template_admin->link("api/brand/get_one/_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "id" => "string",
                    "name" => "string",
                    "brand_id" => "string",
                    "description" => "string",
                    "picture" => "string",
                    "poster" => "string",
                    "url_picture" => "string(Complete URL)",
                    "url_poster" => "string(Complete URL)",
                )
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"_id",
                    "descuri"=>"field _id from database Assets collection Brand (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Data Brand",            
            'descriptions'  =>"API for get List Data Brand",   
            'url'  => $this->template_admin->link("api/brand/list_data/az/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id" => "string",
                    "name" => "string",
                    "brand_id" => "string",
                    "description" => "string",
                    "picture" => "string",
                    "poster" => "string",
                    "url_picture" => "string(Complete URL)",
                    "url_poster" => "string(Complete URL)",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"az",
                    "descuri"=>"parameter can use [az=ascending/za=descending] for order name of brand (this param is optional and default is ascending)"
                ),
                array(
                    "dturi"=>"0",
                    "descuri"=>"pagination start data show default 0 (must be number)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Count Inventory",            
            'descriptions'  =>"API for get Count Inventory",   
            'url'  => $this->template_admin->link("api/inventory/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer"
            )
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Detail Inventory",            
            'descriptions'  =>"API for get Detail Inventory",   
            'url'  => $this->template_admin->link("api/inventory/get_one/_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "id" => "string",
                    "name" => "string",
                    "picture" => "string",
                    "url_picture" => "string(Complete URL)",
                )
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"_id",
                    "descuri"=>"Field _id from database Assets collection Inventory (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Data Inventory",            
            'descriptions'  =>"API for get List Data Inventory",   
            'url'  => $this->template_admin->link("api/inventory/list_data"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id" => "string",
                    "name" => "string",
                    "picture" => "string",
                    "url_picture" => "string(Complete URL)",
                ),array("....."=>"......"))
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Count Content",            
            'descriptions'  =>"API for get Count Content state_document must be publish",   
            'url'  => $this->template_admin->link("api/content/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer"
            )
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Detail Content",            
            'descriptions'  =>"API for get Detail Content state_document must be publish",   
            'url'  => $this->template_admin->link("api/content/get_one/_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "id" => "string",
                    "title" => "string",
                    "alias" => "string",
                    "text" => "string",
                    "update" => "string(datetime)",
                    "state_document" => "string",
                )
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"_id",
                    "descuri"=>"field _id from Database Articles collection ContentPage (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Data Content",            
            'descriptions'  =>"API for get List Data Content state_document must be publish",   
            'url'  => $this->template_admin->link("api/content/list_data/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(array(
                    "id" => "string",
                    "title" => "string",
                    "alias" => "string",
                    "text" => "string",
                    "update" => "string(datetime)",
                    "state_document" => "string",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"0",
                    "descuri"=>"start data show default 0 (must be number)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);  
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Lobby Setting",            
            'descriptions'  =>"API for get Lobby Setting",   
            'url'  => $this->template_admin->link("api/query/lobby"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(array(
                    "IP"=>"string",
                    "PORT"=>"string",
                ),array("....."=>"......"))
            ),
        );
        $this->mongo_db->insert($datatinsert);  
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Add Status Mind",            
            'descriptions'  =>"Social API for Add Status Mind",   
            'url'  => $this->template_admin->link("api/social/user/add_status"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string"
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"user_id",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dtparam"=>"comment",
                    "descparam"=>"Text user Status (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Delete User Status",            
            'descriptions'  =>"Social API for Delete User Status",   
            'url'  => $this->template_admin->link("api/social/user/delete_status/idstatus"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idstatus",
                    "descuri"=>"field _id from Database Social dan collection Social (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Count User Status",            
            'descriptions'  =>"Social API for get Count User Status",   
            'url'  => $this->template_admin->link("api/social/user/count_status/user_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"user_id",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API List Data User Status",            
            'descriptions'  =>"Social API for get List Data User Status",   
            'url'  => $this->template_admin->link("api/social/user/list_data/user_id/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "_id" => "string field ID status from database",
                    'datetime'=>"string(datetime)",
                    'status'=>"string",
                    "user_id"=>"string",
                    "fullname"=>"string",
                    "sex"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......")),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"user_id",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dturi"=>"0",
                    "descuri"=>"start data show default 0 (must be number)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);        
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Cek User Follow Friend or not",            
            'descriptions'  =>"Social API for cek user is Follow Friend or not",   
            'url'  => $this->template_admin->link("api/social/user/cek_follower/user_id/friend_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "follow" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"user_id",
                    "descuri"=>"user field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dturi"=>"friend_id",
                    "descuri"=>"id friend want to check field _id from Database Users dan collection Account (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Button user Follow or Unfollow Friends",            
            'descriptions'  =>"Social API for Button user Follow or Unfollow Friends",   
            'url'  => $this->template_admin->link("api/social/user/button_follower/user_id/friend_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "follow" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"user_id",
                    "descuri"=>"user field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dturi"=>"friend_id",
                    "descuri"=>"id friend want to check field _id from Database Users dan collection Account (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Count User Follower",            
            'descriptions'  =>"Social API for Count User Follower",   
            'url'  => $this->template_admin->link("api/social/user/count_follower/user_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"user_id",
                    "descuri"=>"user field _id from Database Users dan collection Account (this param is required)"
                ),
            ),    
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API List User Follower",            
            'descriptions'  =>"Social API for List User Follower",   
            'url'  => $this->template_admin->link("api/social/user/list_follower/user_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    'datetime'=>"string(datetime)",
                    "user_id"=>"string",
                    "fullname"=>"string",
                    "sex"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"user_id",
                    "descuri"=>"user field _id from Database Users dan collection Account (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Count User Following",            
            'descriptions'  =>"Social API for Count User Following",   
            'url'  => $this->template_admin->link("api/social/user/count_following/user_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"user_id",
                    "descuri"=>"user field _id from Database Users dan collection Account (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API List User Following",            
            'descriptions'  =>"Social API for List User Following",   
            'url'  => $this->template_admin->link("api/social/user/list_following/user_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    'datetime'=>"string(datetime)",
                    "user_id"=>"string",
                    "fullname"=>"string",
                    "sex"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......")),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"user_id",
                    "descuri"=>"user field _id from Database Users dan collection Account (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API User Page Cek Love",            
            'descriptions'  =>"Social API for cek user is love or not Friend Page",   
            'url'  => $this->template_admin->link("api/social/user/cek_pagelike/iduser/idfriend"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "like" => "bool",
                "message" => "string",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idfriend",
                    "descuri"=>"field _id from Database Users dan collection Account <b>Friend Who love the Page</b>(this param is required)"
                ),
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account <b>Owner of Page</b>(this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API give Love and Unlove of Page People",            
            'descriptions'  =>"Social API for user give love or not about Avatar Mix",   
            'url'  => $this->template_admin->link("api/social/user/button_pagelike/iduser/idfriend"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "like" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"idfriend",
                    "descuri"=>"field _id from Database Users dan collection Account <b>Friend Who love the Page</b>(this param is required)"
                ),
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account <b>Owner of Page</b>(this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Count Friend Love User Page",            
            'descriptions'  =>"Social API for count friend give love about user page",   
            'url'  => $this->template_admin->link("api/social/user/count_pagelike/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account <b>Owner of Page</b>(this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Page User List Friend Love",            
            'descriptions'  =>"Social API for list page friend give love about User Page",   
            'url'  => $this->template_admin->link("api/social/user/list_like/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "Integer",
                "data" => array(array(
                    'datetime'=>"string(datetime)",
                    "user_id"=>"string",
                    "fullname"=>"string",
                    "sex"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account <b>Owner of Page</b>(this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Cek Email is used",            
            'descriptions'  =>"API for cek email is used by another user",   
            'url'  => $this->template_admin->link("api/user/check_email/emailuser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "isvalid" => "bool",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"emailuser",
                    "descuri"=>"user email want to check (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Deactivated User Account",            
            'descriptions'  =>"API for Deactivated User Account",   
            'url'  => $this->template_admin->link("api/user/deactiveuser/user_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"user_id",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required, user who want to send email)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Cek Username is used",            
            'descriptions'  =>"API for cek Username is user by another user",   
            'url'  => $this->template_admin->link("api/user/check_username/username"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "isvalid" => "bool",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"username",
                    "descuri"=>"user username want to check (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Change User Password",            
            'descriptions'  =>"API for Change User Password parameter can use _id or email or username.",   
            'url'  => $this->template_admin->link("api/user/change_password"), 
            'methode'  =>"POST",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"password",
                    "descparam"=>"New User Password (this param is required)"
                ),
                array(
                    "dtparam"=>"email",
                    "descparam"=>"Data Find User can use email to find user account (this param is Optional)"
                ),
                array(
                    "dtparam"=>"username",
                    "descparam"=>"Data Find User can use username to find user account (this param is Optional)"
                ),
                array(
                    "dtparam"=>"id",
                    "descparam"=>"Data Find User can use _id field from Database Users collection Account to find user account (this param is Optional)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get Profile User",            
            'descriptions'  =>"API for Get Profile User parameter can user email, username or id from Database Users collection Account",   
            'url'  => $this->template_admin->link("api/user/get_profile"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    'username' => "string",
                    'email' => "string",
                    '_id' => "string",
                    'avatarname' => "string",
                    'fullname' => "string",
                    'sex' => "string",
                    'website' => "string",
                    'link' => "string",
                    'state_of_mind' => "string",
                    'bodytype'=> "string",
                    'artist'=> "bool",
                    'handphone' => "string",
                    'location' => "string",
                    'picture' => "string",
                    'about' => "string",
                    'birthday' => "string",
                    'birthday_dd' => "string",
                    'birthday_mm' => "string",
                    'birthday_yy' => "string",
                ),
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"email",
                    "descparam"=>"Data Find User can use email to find user account (this param is Optional)"
                ),
                array(
                    "dtparam"=>"username",
                    "descparam"=>"Data Find User can use username to find user account (this param is Optional)"
                ),
                array(
                    "dtparam"=>"id",
                    "descparam"=>"Data Find User can use _id field from Database Users collection Account to find user account (this param is Optional)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Change Username",            
            'descriptions'  =>"API for Change Username parameter can use email or id from database Users collection Account",   
            'url'  => $this->template_admin->link("api/user/change_username"), 
            'methode'  =>"POST",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"username",
                    "descparam"=>"new username user (this param is required)"
                ),
                array(
                    "dtparam"=>"email",
                    "descparam"=>"Data Find User can use email to find user account (this param is Optional)"
                ),                
                array(
                    "dtparam"=>"id",
                    "descparam"=>"Data Find User can use _id field from Database Users collection Account to find user account (this param is Optional)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Change User Email",            
            'descriptions'  =>"API for Change User Email parameter can use username or id from database Users collection Account",   
            'url'  => $this->template_admin->link("api/user/change_email"), 
            'methode'  =>"POST",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"email",
                    "descparam"=>"new user email (this param is Required)"
                ),
                array(
                    "dtparam"=>"username",
                    "descparam"=>"Data Find User can use username to find user account (this param is Optional)"
                ),            
                array(
                    "dtparam"=>"id",
                    "descparam"=>"Data Find User can use _id field from Database Users collection Account to find user account (this param is Optional)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Login User",            
            'descriptions'  =>"API for Login User parameter can user email or username",   
            'url'  => $this->template_admin->link("api/user/login"), 
            'methode'  =>"POST",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "id" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"email",
                    "descparam"=>"Data Find User can use email to find user account (this param is Optional)"
                ), 
                array(
                    "dtparam"=>"username",
                    "descparam"=>"Data Find User can use username to find user account (this param is Optional)"
                ),            
                array(
                    "dtparam"=>"password",
                    "descparam"=>"User Password (this param is Required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Random Data",            
            'descriptions'  =>"API for get Random Data",   
            'url'  => $this->template_admin->link("api/random/5"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"5",
                    "descuri"=>"count data random (must be number dan required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get Detail Data Template",            
            'descriptions'  =>"API for get Detail Data Template",   
            'url'  => $this->template_admin->link("api/data_template/templatekey"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "id" => "string",
                    "name" => "string",
                    "type" => "string",
                    "value" => "string",
                    "descriptions" => "string",
                ),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"templatekey",
                    "descuri"=>"templatekey is data field code from Database Game Collection Settings (required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Register User",            
            'descriptions'  =>"API for Register User",   
            'url'  => $this->template_admin->link("api/user/register"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
                "data" => array(
                    'username' => "string",
                    'email' => "string",
                    '_id' => "string",
                    'avatarname' => "string",
                    'fullname' => "string",
                    'sex' => "string",
                    'website' => "string",
                    'link' => "string",
                    'state_of_mind' => "string",
                    'bodytype'=> "string",
                    'artist'=> "bool",
                    'handphone' => "string",
                    'location' => "string",
                    'picture' => "string",
                    'about' => "string",
                    'birthday' => "string",
                    'birthday_dd' => "string",
                    'birthday_mm' => "string",
                    'birthday_yy' => "string",
                ),
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"email",
                    "descparam"=>"Email User (this param is Required)"
                ),
                array(
                    "dtparam"=>"password",
                    "descparam"=>"Password User (this param is Required)"
                ),
                array(
                    "dtparam"=>"username",
                    "descparam"=>"username User (this param is Required)"
                ),
                array(
                    "dtparam"=>"sex",
                    "descparam"=>"Sex User [male/female] (this param is Required)"
                ),
                array(
                    "dtparam"=>"birthday",
                    "descparam"=>"Birtday User (this param is Required, format yyyy-mm-dd)"
                ),
                array(
                    "dtparam"=>"twitterid",
                    "descparam"=>"Twitter ID User (this param is Optional)"
                ),
                array(
                    "dtparam"=>"facebookid",
                    "descparam"=>"Facebook ID User (this param is Optional)"
                ),
                array(
                    "dtparam"=>"avatarname",
                    "descparam"=>"Avatar Name User (this param is Required)"
                ),
                array(
                    "dtparam"=>"fullname",
                    "descparam"=>"Full Username (this param is Required)"
                ),
                array(
                    "dtparam"=>"website",
                    "descparam"=>"Website User (this param is Optional)"
                ),
                array(
                    "dtparam"=>"link",
                    "descparam"=>"Link Site User (this param is Optional)"
                ),
                array(
                    "dtparam"=>"about",
                    "descparam"=>"About User (this param is Optional)"
                ),
                array(
                    "dtparam"=>"location",
                    "descparam"=>"Lokation User (this param is Optional)"
                ),
                array(
                    "dtparam"=>"phone",
                    "descparam"=>"Phone Number User (this param is Optional)"
                ),
                array(
                    "dtparam"=>"picture",
                    "descparam"=>"Link Picture User (this param is Optional)"
                ),
                array(
                    "dtparam"=>"bodytipe",
                    "descparam"=>"Body Type User['thin', 'medium', 'fat'] (this param is Required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Set User Profile",            
            'descriptions'  =>"API for Set User Profile",   
            'url'  => $this->template_admin->link("api/user/change_profile"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"id",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dtparam"=>"sex",
                    "descparam"=>"data can use [male/female] (this param is optional)"
                ),
                array(
                    "dtparam"=>"birthday",
                    "descparam"=>"user birthday (this param is optional, format yyyy-mm-dd)"
                ),
                array(
                    "dtparam"=>"avatarname",
                    "descparam"=>"Avatar User Name (this param is optional)"
                ),
                array(
                    "dtparam"=>"fullname",
                    "descparam"=>"User Full Name (this param is optional)"
                ),
                array(
                    "dtparam"=>"website",
                    "descparam"=>"User Website (this param is optional)"
                ),
                array(
                    "dtparam"=>"link",
                    "descparam"=>"User Link Site (this param is optional)"
                ),
                array(
                    "dtparam"=>"about",
                    "descparam"=>"About User (this param is optional)"
                ),
                array(
                    "dtparam"=>"location",
                    "descparam"=>"User Location (this param is optional)"
                ),
                array(
                    "dtparam"=>"phone",
                    "descparam"=>"User Phone (this param is optional)"
                ),
                array(
                    "dtparam"=>"picture",
                    "descparam"=>"User Picture (this param is optional)"
                ),
                array(
                    "dtparam"=>"bodytipe",
                    "descparam"=>"Body Type User['thin', 'medium', 'fat'] (this param is optional)"
                ),
                array(
                    "dtparam"=>"state_of_mind",
                    "descparam"=>"State Of Mind (this param is optional)"
                ),
                array(
                    "dtparam"=>"newpassword",
                    "descparam"=>"News Password User (this param is optional)"
                ),
                array(
                    "dtparam"=>"star",
                    "descparam"=>"Star Data (this param is optional)"
                ),
                array(
                    "dtparam"=>"diamond",
                    "descparam"=>"Diamond Data (this param is optional)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Change User State Online, Offline, Busy",            
            'descriptions'  =>"API for Change User State Online, Offline, Busy",   
            'url'  => $this->template_admin->link("api/user/change_state"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"id",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dtparam"=>"status",
                    "descparam"=>"status data [online, busy, away, offline] (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get User State Online, Offline, Busy",            
            'descriptions'  =>"API for Get User State Online, Offline, Busy",   
            'url'  => $this->template_admin->link("api/user/get_state"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "state" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"id",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);       
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get Gender User",            
            'descriptions'  =>"API for get Gender User",   
            'url'  => $this->template_admin->link("api/user/gender/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "gender" => "string",
                "id" => "string",
                "bodyType" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get Body Size User",            
            'descriptions'  =>"API for get Body Size User",   
            'url'  => $this->template_admin->link("api/user/bodysize/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "bodysize" => "string",
                "id" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Setting User Account",            
            'descriptions'  =>"API for Setting User Account",   
            'url'  => $this->template_admin->link("api/social/user/setting_account"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"user_id",
                    "descparam"=>"user field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dtparam"=>"chkemail",
                    "descparam"=>"Set User hide Email value must[1=true,0=false] (this param is optional)"
                ),
                array(
                    "dtparam"=>"chksex",
                    "descparam"=>"Set User hide Sex value must[1=true,0=false] (this param is optional)"
                ),
                array(
                    "dtparam"=>"chkphonenumber",
                    "descparam"=>"Set User hide Phone Number value must[1=true,0=false] (this param is optional)"
                ),
                array(
                    "dtparam"=>"optbirthdayshow",
                    "descparam"=>"Set User birthday value must[showall,hideyear,hideall] (this param is optional)"
                ),
                array(
                    "dtparam"=>"chkeventupdate",
                    "descparam"=>"Set User Set Notification Event Update value must[1=true,0=false] (this param is optional)"
                ),
                array(
                    "dtparam"=>"chkfrienreq",
                    "descparam"=>"Set User Set Notification Friend Request value must[1=true,0=false] (this param is optional)"
                ),
                array(
                    "dtparam"=>"chkmention",
                    "descparam"=>"Set User Set Notification Friend Mentions value must[1=true,0=false] (this param is optional)"
                ),
                array(
                    "dtparam"=>"chkpostfriend",
                    "descparam"=>"Set User Set Notification Friend Post data to wall value must[1=true,0=false] (this param is optional)"
                ),
                array(
                    "dtparam"=>"chklove",
                    "descparam"=>"Set User Set Notification Friend love value must[1=true,0=false] (this param is optional)"
                ),
                array(
                    "dtparam"=>"mixcollect",
                    "descparam"=>"Set User Set Notification your Mix Collect by Friend value must[1=true,0=false] (this param is optional)"
                ),
                array(
                    "dtparam"=>"chkcomment",
                    "descparam"=>"Set User Set Notification Friend Comment value must[1=true,0=false] (this param is optional)"
                ),
                array(
                    "dtparam"=>"chkavaitems",
                    "descparam"=>"Set User Set Notification New Avatar Items value must[1=true,0=false] (this param is optional)"
                ),
                array(
                    "dtparam"=>"chkcontest",
                    "descparam"=>"Set User Set Notification Contest Result value must[1=true,0=false] (this param is optional)"
                ),
                array(
                    "dtparam"=>"chkmessage",
                    "descparam"=>"Set User Set Notification Friend Send Message value must[1=true,0=false] (this param is optional)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Data Category",            
            'descriptions'  =>"API for get List Data Category",   
            'url'  => $this->template_admin->link("api/category/index/tipename"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "name" => "string",
                    "image" => "string",
                    "url_picture" => "string(complate url)",
                ),array("....."=>"......")),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"tipename",
                    "descuri"=>"name tipe of category"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Count Gesticon",            
            'descriptions'  =>"API for get Count Gesticon / EMO",   
            'url'  => $this->template_admin->link("api/gesticon/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Data Gesticon",            
            'descriptions'  =>"API for get List Data Gesticon  / EMO",   
            'url'  => $this->template_admin->link("api/gesticon/list_data"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "command" => "string",
                    "gender" => "string",
                    "animation" => "string",
                ),array("....."=>"......")),
            ),
        );
        $this->mongo_db->insert($datatinsert);        
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Send Invite user by Email",            
            'descriptions'  =>"API for Send Invite user by Email",   
            'url'  => $this->template_admin->link("api/pm/email/invite_user"), 
            'methode'  =>"POST",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "command" => "string",
                    "gender" => "string",
                    "animation" => "string",
                ),array("....."=>"......")),
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"email",
                    "descparam"=>"List Array Email User want to invite (this param is required) more email separated by ';' <br />Ext : uda_rido@yahoo.com;ridosaputra2@gmail.com"
                ),
                array(
                    "dtparam"=>"user_id",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required, user who send email)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Send User Email Verification",            
            'descriptions'  =>"API for Send User Email Verification",   
            'url'  => $this->template_admin->link("api/pm/email/verification/user_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"user_id",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required, user who want to send email)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Send Email Template",            
            'descriptions'  =>"API for Send Email Template",   
            'url'  => $this->template_admin->link("api/pm/email/index"), 
            'methode'  =>"POST",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"subject",
                    "descparam"=>"Subbecj of email want to send (this param is required)"
                ),
                array(
                    "dtparam"=>"message",
                    "descparam"=>"text information that user want to read (this param is required)"
                ),
                array(
                    "dtparam"=>"email",
                    "descparam"=>"List Array Email User want to invite (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);              
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Count Poll",            
            'descriptions'  =>"API for get count Poll",   
            'url'  => $this->template_admin->link("api/poll/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Detail Poll",            
            'descriptions'  =>"API for get Detail Poll",   
            'url'  => $this->template_admin->link("api/poll/get_one/id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "id" => "string",
                    "enabled" => "bool",
                    "name" => "string",
                    "question" => "string",
                    "date" => "string(daye)",
                    "options" => array(array(
                        "option" => "string",
                        "values" => "integer",
                    ),array("...."=>"....")),
                    "create" => "string(datetime)",
                ),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"id",
                    "descuri"=>"field _id from Database Assets dan collection Polls (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Poll",            
            'descriptions'  =>"API for get List Poll",   
            'url'  => $this->template_admin->link("api/poll/list_data"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id" => "string",
                    "enabled" => "bool",
                    "name" => "string",
                    "question" => "string",
                    "date" => "string(daye)",
                    "options" => array(array(
                        "option" => "string",
                        "values" => "integer",
                    ),array("...."=>"....")),
                    "create" => "string(datetime)",
                ),array("...."=>"....")),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API set Increment Point Poll",            
            'descriptions'  =>"API for set Increment Point Poll",   
            'url'  => $this->template_admin->link("api/poll/inc_one/id/indexoption"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",  
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"id",
                    "descuri"=>"field _id from Database Assets dan collection Polls (this param is required)"
                ),
                array(
                    "dturi"=>"indexoption",
                    "descuri"=>"field index option from Database Assets dan collection Polls (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Count Vote",            
            'descriptions'  =>"API for get count Vote",   
            'url'  => $this->template_admin->link("api/vote/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Detail Vote",            
            'descriptions'  =>"API for get Detail Vote",   
            'url'  => $this->template_admin->link("api/vote/get_one/id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "id" => "string",
                    "enabled" => "bool",
                    "name" => "string",
                    "question" => "string",
                    "count" => "integer",
                ),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"id",
                    "descuri"=>"field _id from Database Assets dan collection Votes (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Vote",            
            'descriptions'  =>"API for get List Vote",   
            'url'  => $this->template_admin->link("api/vote/list_data"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id" => "string",
                    "enabled" => "bool",
                    "name" => "string",
                    "question" => "string",
                    "count" => "integer",
                ),array("....."=>"......")),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Increment Vote",            
            'descriptions'  =>"API for set Increment Vote",   
            'url'  => $this->template_admin->link("api/vote/inc_one/id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"id",
                    "descuri"=>"field _id from Database Assets dan collection Votes (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Get Count User Avatar Collections",            
            'descriptions'  =>"Social API for get Count User Avatar Collections",   
            'url'  => $this->template_admin->link("api/avatarcollect/count/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Delete User Avatar Collections",            
            'descriptions'  =>"Social API for Delete User Avatar Collections",   
            'url'  => $this->template_admin->link("api/avatarcollect/delete_collection/id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"id",
                    "descuri"=>"field _id from Database Social dan collection Social (this param is required)"
                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Add User Avatar Collections",            
            'descriptions'  =>"Social API for Add User Avatar Collections",   
            'url'  => $this->template_admin->link("api/avatarcollect/add_collection/iduser/id_mix"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dturi"=>"id_mix",
                    "descuri"=>"field _id from Database Users dan collection AvatarMix (this param is required)"
                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Get Detail User Avatar Collection",            
            'descriptions'  =>"Social API for get Detail User Avatar Collection",   
            'url'  => $this->template_admin->link("api/avatarcollect/detail_collections/id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
                "data" => array(
                    "id" => "string",
                    "datetime" => "string(datetime)",
                    "user" => array(
                        "id" => "string",
                        "fullname" => "string",
                        "sex" => "string",
                        "picture" => "string",
                    ),
                    "owner" => array(
                        "id" => "string",
                        "fullname" => "string",
                        "sex" => "string",
                        "picture" => "string",
                    ),
                    "dataconf" => array(array(
                        "id" => "string",
                        "tipe" => "string",
                    ),array("....."=>"......")),
                    "configurations" => "string",
                    "name" => "string",
                    "gender" => "string",
                    "bodytype" => "string",
                    "author" => "string",
                    "description" => "string",
                    "brand_id" => "string",
                    "picture" => "string",
                    "url_picture" => "string(url)",
                ),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"id",
                    "descuri"=>"field _id from Database Social dan collection Social (this param is required)"
                ),
//                array(
//                    "dturi"=>"Android",
//                    "descuri"=>"Tipe data Unity3D return [web,iOS,Android] (this param is required,default is web)"
//                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"Social API Get List User Avatar Collection",            
            'descriptions'  =>"Social API for get List User Avatar Collection",   
            'url'  => $this->template_admin->link("api/avatarcollect/list_data/userid/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id" => "string",
                    "datetime" => "string(datetime)",
                    "user" => array(
                        "id" => "string",
                        "fullname" => "string",
                        "sex" => "string",
                        "picture" => "string",
                    ),
                    "owner" => array(
                        "id" => "string",
                        "fullname" => "string",
                        "sex" => "string",
                        "picture" => "string",
                    ),
                    'name' => "string",
                    'gender' => "string",
                    'bodytype' => "string",
                    'author' => "string",
                    'description' => "string",
                    'brand_id' => "string",
                    'picture' => "string",
                    "url_picture" => "string(url)",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"userid",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dturi"=>"0",
                    "descuri"=>"start data show default 0 (must be number)"
                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Count Banner",            
            'descriptions'  =>"API for get count Banner",   
            'url'  => $this->template_admin->link("api/banner/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Detail Banner",            
            'descriptions'  =>"API for get Detail Banner",   
            'url'  => $this->template_admin->link("api/banner/get_one/ID"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "_id" => "string",
                    "name" => "string",
                    "ID" => "string",
                    "tags" => "string",
                    "Descriptions" => "string",
                    "textcolor" => "string",
                    "type" => "string",
                    "date" => "string(datetime)",
                    "dataValue" => "string",
                    "url_picture" => "string(url)",
                    "picture" => "string",
                    "brand_id" =>  "string",
                ),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"ID",
                    "descuri"=>"field ID from Database Assets dan collection Banner (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Data Banner",            
            'descriptions'  =>"API for get List Data Banner",   
            'url'  => $this->template_admin->link("api/banner/list_data/0"), 
            'methode'  =>"GET",
            'return'  =>"JSON",
            'uri'  =>array(
                array(
                    "dturi"=>"0",
                    "descuri"=>"start data show default 0 (must be number)"
                ),
            ),
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "_id" => "string",
                    "name" => "string",
                    "ID" => "string",
                    "tags" => "string",
                    "Descriptions" => "string",
                    "textcolor" => "string",
                    "type" => "string",
                    "date" => "string(datetime)",
                    "dataValue" => "string",
                    "url_picture" => "string(url)",
                    "picture" => "string",
                    "brand_id" =>  "string",
                ),array("....."=>"......")),
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"name",
                    "descparam"=>"Filtering Banner by name (optional if data want to return by filtering by name)"
                ),
                array(
                    "dtparam"=>"brand",
                    "descparam"=>"Filtering Banner by brand (optional if data want to return by filtering by brand)"
                ),
                array(
                    "dtparam"=>"tag",
                    "descparam"=>"Filtering Banner by tag (optional if data want to return by filtering by tag)"
                ),
                array(
                    "dtparam"=>"keyordering",
                    "descparam"=>"Ordering Data Banner key name [name,date] (optional default data name)"
                ),
                array(
                    "dtparam"=>"ordering",
                    "descparam"=>"Ordering Data Banner by [asc, desc] (optional default data asc, asc for ascending, desc for descending)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);                
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Count Quiz",            
            'descriptions'  =>"API for get Count Quiz state PUBLISH",   
            'url'  => $this->template_admin->link("api/quiz/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer"
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Detail quiz",            
            'descriptions'  =>"API for get Detail quiz",   
            'url'  => $this->template_admin->link("api/quiz/get_one/ID"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "_id" => "string",
                    "ID" => "string",
                    "Title" => "string",
                    "Description" => "string",
                    "Count" => "integer",
                    "BrandId" => "string",
                    "State" => "string",
                    "StartDate" => "string",
                    "StartTime" => "string",
                    "EndDate" => "string",
                    "EndTime" => "string",
                    "IsRandom" => "bool",
                    "RequiredQuiz" => "string",
                    "RequiredQuest" => "string",
                    "RequiredItem" => "string",
                    "Reward" => "string",
                    "Questions" => array(array(
                        "QuestionId" => "string",
                        "Question" => "string",
                        "Tipe" => "string",
                        "Difficulty" => "string",
                        "QuestionTime" => "double",
                        "Image" => "string(url)",
                        "Options" => array(array(
                            "Answer" => "string",
                            "IsCorrect" => "bool",
                        ),array("....."=>"......")),
                    ),array("....."=>"......"))
                ),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"ID",
                    "descuri"=>"field ID from database Game collection Quiz (this param is required)"
                ),
            ), 
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Data Quiz",            
            'descriptions'  =>"API for get List Data Quiz",   
            'url'  => $this->template_admin->link("api/quiz/list_data/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(array(
                    "_id" => "string",
                    "ID" => "string",
                    "Title" => "string",
                    "Description" => "string",
                    "Count" => "integer",
                    "BrandId" => "string",
                    "State" => "string",
                    "StartDate" => "string",
                    "StartTime" => "string",
                    "EndDate" => "string",
                    "EndTime" => "string",
                    "IsRandom" => "bool",
                    "RequiredQuiz" => "string",
                    "RequiredQuest" => "string",
                    "RequiredItem" => "string",
                    "Reward" => "string",
                    "Questions" => array(array(
                        "QuestionId" => "string",
                        "Question" => "string",
                        "Tipe" => "string",
                        "Difficulty" => "string",
                        "QuestionTime" => "double",
                        "Image" => "string(url)",
                        "Options" => array(array(
                            "Answer" => "string",
                            "IsCorrect" => "bool",
                        ),array("....."=>"......")),
                    ),array("....."=>"......"))
                ),array("....."=>"......")),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"0",
                    "descuri"=>"start data show default 0 (must be number)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Count Quest",            
            'descriptions'  =>"API for get Count Quest",   
            'url'  => $this->template_admin->link("api/quest/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer"
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Detail Quest",            
            'descriptions'  =>"API for get Detail Quest",   
            'url'  => $this->template_admin->link("api/quest/get_one/ID"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "_id" => "string",
                    'IsActive' => "bool",
                    'IsDone' => "bool",
                    'IsReturn' => "bool",
                    'ID' => "integer",
                    'RequiredEnergy' => "integer",
                    'Requirement' => "integer",
                    'Rewards' => "string",
                    'RequiredItem' => "string",
                    'Description' => "string",
                    'DescriptionNormal' => "string",
                    'DescriptionActive' => "string",
                    'DescriptionDone' => "string",
                    'StartDate' => "string",
                    'EndDate' => "string",
                ),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"ID",
                    "descuri"=>"field ID from database Game collection Quest (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Data Quest",            
            'descriptions'  =>"API for get List Data Quest",   
            'url'  => $this->template_admin->link("api/quest/list_data/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "_id" => "string",
                    'IsActive' => "bool",
                    'IsDone' => "bool",
                    'IsReturn' => "bool",
                    'ID' => "integer",
                    'RequiredEnergy' => "integer",
                    'Requirement' => "integer",
                    'Rewards' => "string",
                    'RequiredItem' => "string",
                    'Description' => "string",
                    'DescriptionNormal' => "string",
                    'DescriptionActive' => "string",
                    'DescriptionDone' => "string",
                    'StartDate' => "string",
                    'EndDate' => "string",
                ),array("....."=>"......")),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"0",
                    "descuri"=>"start data show default 0 (must be number)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Count Dialog Story",            
            'descriptions'  =>"API for get Count Dialog Story",   
            'url'  => $this->template_admin->link("api/dialog/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer"
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Detail Dialog Story",            
            'descriptions'  =>"API for get Detail Dialog Story",   
            'url'  => $this->template_admin->link("api/dialog/get_one/name"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "_id" => "string",
                    'name' => "string",
                    'description' => "string",
                    'typedialog' => "string",
                    'dialogs' => array(array(
                        'ID' => "integer",
                        'Description' => "string",
                        'Options' => array(array(
                            'Tipe' => "integer",
                            'Content' => "string",
                            'Next' => "integer",
                        ),array("....."=>"......"))
                    ),array("....."=>"......"))
                ),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"name",
                    "descuri"=>"field name from database Game collection DialogStory (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Data Dialog Story",            
            'descriptions'  =>"API for get List Data Dialog Story",   
            'url'  => $this->template_admin->link("api/dialog/list_data/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "_id" => "string",
                    'name' => "string",
                    'description' => "string",
                    'typedialog' => "string",
                    'dialogs' => array(array(
                        'ID' => "integer",
                        'Description' => "string",
                        'Options' => array(array(
                            'Tipe' => "integer",
                            'Content' => "string",
                            'Next' => "integer",
                        ),array("....."=>"......"))
                    ),array("....."=>"......"))
                ),
            ),array("....."=>"......")),
            'uri'  =>array(
                array(
                    "dturi"=>"0",
                    "descuri"=>"start data show default 0 (must be number)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get User Avatar Configurations Active",            
            'descriptions'  =>"API for get User Avatar Configurations Active",   
            'url'  => $this->template_admin->link("api/avatar/active_configurations/iduser/medium"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "user_id" => "string",
                "configurations" => "string",
                "configurations2" => "string",
//                "dataconf" => array(array(
//                    "id" => "string",
//                    'tipe' => "string",
//                ),array("....."=>"......")),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dturi"=>"medium",
                    "descuri"=>"Size of Avatar Body [thin,medium,fat] if not set, data will get param from database (this param is optional)"
                ),
//                array(
//                    "dturi"=>"Android",
//                    "descuri"=>"Tipe data Unity3D return [web,iOS,Android] (this param is required,default is web)"
//                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get User Default Avatar Configurations",            
            'descriptions'  =>"API for get User Default Avatar Configurations",   
            'url'  => $this->template_admin->link("api/avatar/default_configurations/medium/male"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",                
                "configurations" => "string",
                "configurations2" => "string",
//                "dataconf" => array(array(
//                    "id" => "string",
//                    'tipe' => "string",
//                ),array("....."=>"......")),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"medium",
                    "descuri"=>"Size of Avatar Body [thin,medium,fat] (this param is required)"
                ),
                array(
                    "dturi"=>"male",
                    "descuri"=>"Gender of Avatar Configurations [male,female] (this param is required)"
                ),
//                array(
//                    "dturi"=>"Android",
//                    "descuri"=>"Tipe data Unity3D return [web,iOS,Android] (this param is required,default is web)"
//                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Set User Avatar Configurations Active",            
            'descriptions'  =>"API for Set User Avatar Configurations Active",   
            'url'  => $this->template_admin->link("api/avatar/setactive_configurations"), 
            'methode'  =>"POST",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"iduser",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dtparam"=>"gender",
                    "descparam"=>"field gender[male,female] (this param is required)"
                ),
                array(
                    "dtparam"=>"bodysize",
                    "descparam"=>"field Size of Avatar Body [thin,medium,fat] (this param is required)"
                ),
                array(
                    "dtparam"=>"configurations",
                    "descparam"=>"Send All Data Configurations Avatar (this param is required)"
                ),              
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Set User Avatar Active Configurations from User Mix",            
            'descriptions'  =>"API Set User Avatar Active Configurations from User Mix",   
            'url'  => $this->template_admin->link("api/avatar/setactive_frommix/iduser/idmix"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dturi"=>"idmix",
                    "descuri"=>"field _id from Database Users dan collection AvatarMix (this param is required)"
                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API count avatar mix user",            
            'descriptions'  =>"API for get count avatar mix user",   
            'url'  => $this->template_admin->link("api/avatarmix/count/iduser"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);        
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Add new User Avatar Configurations Mix",            
            'descriptions'  =>"API for add new User Avatar Configurations mix",   
            'url'  => $this->template_admin->link("api/avatarmix/add_configurations"), 
            'methode'  =>"POST",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
            ),
            'param'  =>array(                
                array(
                    "dtparam"=>"id",
                    "descparam"=>"field _id from Database Users dan collection AvatarMix (this param is required when process want to update, null will add new)"
                ),
                array(
                    "dtparam"=>"configurations",
                    "descparam"=>"Send All Data Configurations Mix (this param is required)"
                ),
                array(
                    "dtparam"=>"iduser",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dtparam"=>"title",
                    "descparam"=>"Name(Title) Avatar Mix User (this param is required)"
                ),
                array(
                    "dtparam"=>"authorname",
                    "descparam"=>"Name Author of user Avatar (this param is optional default empty)"
                ),
                array(
                    "dtparam"=>"brand_id",
                    "descparam"=>"field _id from Database Assets dan collection Brand (this param is optional)"
                ),
                array(
                    "dtparam"=>"descriptions",
                    "descparam"=>"Descriptions of user AvatarMix (this param is optional default empty)"
                ),
                array(
                    "dtparam"=>"gender",
                    "descparam"=>"field gender[male,female] (this param is required)"
                ),
                array(
                    "dtparam"=>"bodysize",
                    "descparam"=>"field Size of Avatar Body [thin,medium,fat] (this param is required)"
                ),
                array(
                    "dtparam"=>"fileimage",
                    "descparam"=>"Send multipart a file image *.(JPEG,PNG,JPG,GIF) screenshoot avatar Mix (this param is optional)"
                ),
                array(
                    "dtparam"=>"picturename",
                    "descparam"=>"Name file pictuce screenshoot avatar Mix (this param is optional)"
                ),                  
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get Detail User Avatar Mix Configurations",            
            'descriptions'  =>"API for get Detail User Avatar Mix Configurations",   
            'url'  => $this->template_admin->link("api/avatarmix/detail_configurations/id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "message" => "string",
                "logged_in" => "bool",
                "configurations" => "string",
                "configurations2" => "string",
//                "dataconf" => array(array(
//                    "id" => "string",
//                    'tipe' => "string",
//                ),array("....."=>"......")),
                "name" => "string",
                "gender" => "string",
                "bodytype" => "string",
                "user_id" => "string",
                "author" => "string",
                "description" => "string",
                "brand_id" => "string",
                "date" => "string(datetime)",
                "fullname" => "string",
                "sex" => "string",
                "picture" => "string",
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"id",
                    "descuri"=>"filed _id from database Users collection AvatarMix(required)"
                ),
//                array(
//                    "dturi"=>"Android",
//                    "descuri"=>"Tipe data Unity3D return [web,iOS,Android] (this param is required,default is web)"
//                ),
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get List User Avatar Mix",            
            'descriptions'  =>"API for get List User Avatar Mix",   
            'url'  => $this->template_admin->link("api/avatarmix/list_data/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id" => "string",
                    "name" => "string",
                    "brand_id" => "string",
                    "gender" => "string",
                    "user_id" => "string",
                    "bodytype" => "string",
                    "author" => "string",
                    "description" => "string",
                    "configurations" => "string",
                    "configurations2" => "string",
//                    "dataconf" => array(array(
//                        "id" => "string",
//                        'tipe' => "string",
//                    ),array("....."=>"......")),
                    "date" => "string(datetime)",
                    "picture" => "string",
                    "url_picture" => "string(url)",
                ),array("....."=>"......")),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"0",
                    "descuri"=>"start data show default 0 (must be number)"
                ),
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"name",
                    "descparam"=>"Filtering avatar mix by name (this param is optional)"
                ),
                array(
                    "dtparam"=>"gender",
                    "descparam"=>"Filtering avatar mix by gender[name, female] (this param is optional)"
                ),
                array(
                    "dtparam"=>"bodytype",
                    "descparam"=>"Filtering avatar mix by bogy size [thin, medium, fat] (this param is optional)"
                ),
                array(
                    "dtparam"=>"ordering",
                    "descparam"=>"Order avatar mix [az / za ] (this param is optional)"
                ),
                array(
                    "dtparam"=>"user_id",
                    "descparam"=>"user field _id from Database Users dan collection Account (this param is required)"
                ),
//                array(
//                    "dtparam"=>"tipedata",
//                    "descparam"=>"tipe data return avatar mix [web,iOS,Android] (this param is required)"
//                ),
                array(
                    "dtparam"=>"keyorder",
                    "descparam"=>"Ordering data by key [name, date, gender, bodytype] (this param is required)"
                ),             
            ),
        );
        $this->mongo_db->insert($datatinsert);        
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Count Contest",            
            'descriptions'  =>"API for get count Contest",   
            'url'  => $this->template_admin->link("api/contest/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
            ),
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Detail Contest",            
            'descriptions'  =>"API for get Detail Contest",   
            'url'  => $this->template_admin->link("api/contest/get_one/id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "_id" => "string",
                    "votestate" => "string",
                    "valid" => "string",
                    "update" => "string(datetime)",
                    "end" => "string(datetime)",
                    "begin" => "string(datetime)",
                    "tag" => "string",
                    "state" => "string",
                    "rewards" => "string",
                    "name" => "string",
                    "order" => "string",
                    "info" => "string",
                    "gender" => "string",
                    "description" => "string",
                    "brand_id" => "string",
                    "code" => "string",
                    "count" => "integer",
                    "requireds" => array(array(
                        "type" => "string",
                        "value" => "string",
                    ),array("....."=>"......")),                    
                    "url_imageicon" => "string(url)",
                    "imageicon" => "string",
                    "url_imagebanner" => "string(url)",
                    "imagebanner" => "string",
                    "url_imagecontent" => "string(url)",
                    "imagecontent" => "string",
                ),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"id",
                    "descuri"=>"field _id from Database Game dan collection Contest (this param is required)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Contest",            
            'descriptions'  =>"API for get List Contest",   
            'url'  => $this->template_admin->link("api/contest/list_data/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "_id" => "string",
                    "votestate" => "string",
                    "valid" => "string",
                    "update" => "string(datetime)",
                    "end" => "string(datetime)",
                    "begin" => "string(datetime)",
                    "tag" => "string",
                    "state" => "string",
                    "rewards" => "string",
                    "name" => "string",
                    "order" => "string",
                    "info" => "string",
                    "gender" => "string",
                    "description" => "string",
                    "brand_id" => "string",
                    "code" => "string",
                    "count" => "integer",
                    "requireds" => array(array(
                        "type" => "string",
                        "value" => "string",
                    ),array("....."=>"......")),                    
                    "url_imageicon" => "string(url)",
                    "imageicon" => "string",
                    "url_imagebanner" => "string(url)",
                    "imagebanner" => "string",
                    "url_imagecontent" => "string(url)",
                    "imagecontent" => "string",
                ),
            ),array("....."=>"......")),
            'uri'  =>array(
                array(
                    "dturi"=>"0",
                    "descuri"=>"start data show default 0 (must be number)"
                ),
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"name",
                    "descparam"=>"Filtering contest by name (optional if data want to return by filtering by name)"
                ),
                array(
                    "dtparam"=>"gender",
                    "descparam"=>"Filtering contest by gender[male,female] (optional if data want to return by filtering by gender)"
                ),
                array(
                    "dtparam"=>"date",
                    "descparam"=>"Filtering contest by date (optional if data want to return by filtering by date, format yyyy-mm-dd)"
                ),
                array(
                    "dtparam"=>"brand",
                    "descparam"=>"Filtering contest by brand (optional if data want to return by filtering by brand)"
                ),
                array(
                    "dtparam"=>"tag",
                    "descparam"=>"Filtering contest by tag (optional if data want to return by filtering by tag)"
                ),
                array(
                    "dtparam"=>"type",
                    "descparam"=>"Filtering contest by type (optional if data want to return by filtering by type)"
                ),
                array(
                    "dtparam"=>"category",
                    "descparam"=>"Filtering contest by category (optional if data want to return by filtering by category)"
                ),
                array(
                    "dtparam"=>"keyordering",
                    "descparam"=>"Ordering Data contest key name [name,date] (optional default data name)"
                ),
                array(
                    "dtparam"=>"ordering",
                    "descparam"=>"Ordering Data contest by [asc, desc] (optional default data asc, asc for ascending, desc for descending)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Search People",            
            'descriptions'  =>"API for user Search People",   
            'url'  => $this->template_admin->link("api/search/people/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "_id" => "string",
                    "avatarname" => "string",
                    "fullname" => "string",
                    "picture" => "string",
                    "email" => "string",
                    "username" => "string",
                    "fb_id" => "string",
                    "twitter_id" => "string",
                ),
            ),array("....."=>"......")),
            'uri'  =>array(
                array(
                    "dturi"=>"0",
                    "descuri"=>"start data show default 0 (must be number)"
                ),
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"keysearch",
                    "descparam"=>"Key for search people (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Feature",            
            'descriptions'  =>"API for Feature",   
            'url'  => $this->template_admin->link("api/feature/index"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    'tipe' => "string",
                    'title' => "string",
                    'picture' => "string",
                    'action' => "string",
                    'value' => "string",
                ),
            ),array("....."=>"......")),
            'param'  =>array(
                array(
                    "dtparam"=>"func",
                    "descparam"=>"Function use[root,allcolors,skincolors or use one of list avatarbodytype from database ]"
                ),
                array(
                    "dtparam"=>"email",
                    "descparam"=>"user email"
                ),
                array(
                    "dtparam"=>"keysearch",
                    "descparam"=>"Key Sorting data from database"
                ),
                array(
                    "dtparam"=>"sort",
                    "descparam"=>"ordering data [asc,desc]"
                ),
                array(
                    "dtparam"=>"start",
                    "descparam"=>"Start data Pagging default 0"
                ),
//                array(
//                    "dtparam"=>"tipedata",
//                    "descparam"=>"tipe data return avatar mix [web,iOS,Android] (this param is required)"
//                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Style",            
            'descriptions'  =>"API for Style",   
            'url'  => $this->template_admin->link("api/style/index"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    'tipe' => "string",
                    'title' => "string",
                    'picture' => "string",
                    'action' => "string",
                    'value' => "string",
                ),
            ),array("....."=>"......")),
            'param'  =>array(
                array(
                    "dtparam"=>"func",
                    "descparam"=>"Function use[root,accessories,mix or use one of list avatarbodytype from database ]"
                ),
                array(
                    "dtparam"=>"email",
                    "descparam"=>"user email"
                ),
                array(
                    "dtparam"=>"keysearch",
                    "descparam"=>"Key Sorting data from database"
                ),
                array(
                    "dtparam"=>"sort",
                    "descparam"=>"ordering data [asc,desc]"
                ),
                array(
                    "dtparam"=>"start",
                    "descparam"=>"Start data Pagging default 0"
                ),
//                array(
//                    "dtparam"=>"tipedata",
//                    "descparam"=>"tipe data return avatar mix [web,iOS,Android] (this param is required)"
//                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Stream",            
            'descriptions'  =>"API for Stream",   
            'url'  => $this->template_admin->link("api/stream/getdata/iduser/jnsstream/7"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id"=>"string",
                    "email"=>"string",
                    "artist"=>"bool",
                    'datetime'=>"string",                        
                    "fullname"=>"string",
                    "sex"=>"string",
                    "picture"=>"string",
                ),array("....."=>"......")),
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"iduser",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required)"
                ),
                array(
                    "dturi"=>"7",
                    "descuri"=>"Count day want to load from server."
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Get Count Partner",            
            'descriptions'  =>"API for get Count Partner state Approved",   
            'url'  => $this->template_admin->link("api/partner/count"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer"
            )
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API one Detail of Partner",            
            'descriptions'  =>"API for get one Detail of Partner",   
            'url'  => $this->template_admin->link("api/partner/get_one/_id"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "id" => "string",
                    "name" => "string",
                    "partner_id" => "string",
                    "address" => "string",
                    "PIC" => "string",
                    "phone" => "string",
                    "mobile" => "string",
                    "email" => "string",
                    "website" => "string(Complete URL)",
                )
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"_id",
                    "descuri"=>"field _id from database Website collection Partner (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Data Partner",            
            'descriptions'  =>"API for get List Data Partner state Approved",   
            'url'  => $this->template_admin->link("api/partner/list_data/az/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count" => "integer",
                "data" => array(array(
                    "id" => "string",
                    "name" => "string",
                    "partner_id" => "string",
                    "address" => "string",
                    "PIC" => "string",
                    "phone" => "string",
                    "mobile" => "string",
                    "email" => "string",
                    "website" => "string(Complete URL)",
                ),array("....."=>"......"))
            ),
            'uri'  =>array(
                array(
                    "dturi"=>"az",
                    "descuri"=>"parameter can use [az=ascending/za=descending] for order name of partner (this param is optional and default is ascending)"
                ),
                array(
                    "dturi"=>"0",
                    "descuri"=>"pagination start data show default 0 (must be number)"
                ),
            ),  
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Send Message to All User Player",            
            'descriptions'  =>"API for Send Message to All User Player",   
            'url'  => $this->template_admin->link("api/social/message/broadcase"), 
            'methode'  =>"GET",  
            'return'  =>"JSON",
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"user_id",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required) user who do broadcast"
                ),
                array(
                    "dtparam"=>"subject",
                    "descparam"=>"Subject of message want to broadcast (this param is required)"
                ),
                array(
                    "dtparam"=>"message",
                    "descparam"=>"Content of message want to broadcast (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Send Replay Message to another user",            
            'descriptions'  =>"API for Send Replay Message to another user",   
            'url'  => $this->template_admin->link("api/social/message/replay"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'param'  =>array(
                array(
                    "dtparam"=>"friend_id",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required) target user"
                ),
                array(
                    "dtparam"=>"user_id",
                    "descparam"=>"field _id from Database Users dan collection Account (this param is required) user sender"
                ),
                array(
                    "dtparam"=>"subject",
                    "descparam"=>"Subject of message want to broadcash (this param is required)"
                ),
                array(
                    "dtparam"=>"message",
                    "descparam"=>"Content of message want to broadcash (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API set Status Message",            
            'descriptions'  =>"API for set Status Message",   
            'url'  => $this->template_admin->link("api/social/message/setstatus/user_id/status"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(                
                array(
                    "dturi"=>"user_id",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required) user sender"
                ),
                array(
                    "dturi"=>"status",
                    "descuri"=>"status set in[read or unread] (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Delete Message",            
            'descriptions'  =>"API for Delete Message",   
            'url'  => $this->template_admin->link("api/social/message/delete/idmessage"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "message" => "string",
            ),
            'uri'  =>array(                
                array(
                    "dturi"=>"idmessage",
                    "descuri"=>"field _id from Database Users dan collection Inbox (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API Read Message",            
            'descriptions'  =>"API for Read Message",   
            'url'  => $this->template_admin->link("api/social/message/readdt/idmessage"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "data" => array(
                    "user_id" => "string",
                    "nama" => "string",
                    "subject" => "string",
                    "message" => "string",
                    "datetime" => "string(datetime)",
                ),
            ),
            'uri'  =>array(                
                array(
                    "dturi"=>"idmessage",
                    "descuri"=>"field _id from Database Users dan collection Inbox (this param is required)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'type'  =>"detail",
            'doc'  =>"api",
            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'name'  =>"API List Message",            
            'descriptions'  =>"API for List Message",   
            'url'  => $this->template_admin->link("api/social/message/list_message/user_id/0"), 
            'methode'  =>"GET",  
            'return'  =>"JSON", 
            "response" => array(
                "success" => "bool",
                "logged_in" => "bool",
                "count_unread" => "integer",
                "count_read" => "integer",                
                "data" => array(
//                    "user_id" => "string",
//                    "nama" => "string",
//                    "subject" => "string",
//                    "message" => "string",
//                    "datetime" => "string(datetime)",
                ),
            ),
            'uri'  =>array(                
                array(
                    "dturi"=>"user_id",
                    "descuri"=>"field _id from Database Users dan collection Account (this param is required) user sender"
                ),
                array(
                    "dturi"=>"0",
                    "descuri"=>"pagination start data show default 0 (must be number)"
                ),
            ),   
        );
        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array(
//            'type'  =>"detail",
//            'doc'  =>"api",
//            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
//            'name'  =>"API SMS Unlock Avatar Item",            
//            'descriptions'  =>"API for SMS Unlock Avatar Item",   
//            'url'  => $this->template_admin->link("api/pm/sms/index"), 
//            'methode'  =>"GET",  
//            'return'  =>"XML",
//            'param'  =>array(
//                array(
//                    "dtparam"=>"msisdn",
//                    "descparam"=>"data msisdn (this param is optional)"
//                ),
//                array(
//                    "dtparam"=>"contentid",
//                    "descparam"=>"data contentid (this param is optional)"
//                ),
//                array(
//                    "dtparam"=>"realmsisdn",
//                    "descparam"=>"data realmsisdn (this param is optional)"
//                ),
//                array(
//                    "dtparam"=>"message",
//                    "descparam"=>"data message (this param is required) format: <br />
//                        [av] [koderedeem] [idavatar] [phone number] <br />
//                        Description : <br />
//                        <strong>av</strong> -> Data Redeem for Avatar Item <br />
//                        <strong>koderedeem</strong> -> Data ID for Avatar Item <br />
//                        <strong>phone number</strong> -> Data phone number <br />
//                        "
//                ),
//            ),   
//        );
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array(
//            'type'  =>"detail",
//            'doc'  =>"api",
//            'lastupdate'=> $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
//            'name'  =>"API Redeem Avatar",            
//            'descriptions'  =>"API for process Redeem Avatar",   
//            'url'  => $this->template_admin->link("api/unlock/redeem_avatar"), 
//            'methode'  =>"GET",  
//            'return'  =>"JSON",
//            'param'  =>array(
//                array(
//                    "dtparam"=>"code",
//                    "descparam"=>"field code from database Assets collection Avatar (this param is required) and tipe payment must (Unlock,Paid) "
//                ),
//                array(
//                    "dtparam"=>"jns",
//                    "descparam"=>"data can use [av] (this param is required)"
//                ),
//            ),   
//        );
//        $this->mongo_db->insert($datatinsert);
    }
    function __generatedata()
    {
        $this->mongo_db->select_db("Game");
        /*Begin Create Default Group*/
        $this->mongo_db->select_collection("Group");
        $this->mongo_db->remove(array());
        $datatinsert=array(
            'Name'  =>"Administrator",
            'Code'  =>'menu1',
            'Description'  =>"Administrator have full accsess menu Control",
        );
        $this->mongo_db->insert($datatinsert);
        /*Begin Create Default Module*/
        $this->mongo_db->select_collection("Module");
        $this->mongo_db->remove(array());
        $datatinsert=array(
            'Name'  =>"ADMIN TOOLS",
            'Alias'  =>"Menu Manager",
            'Code'  =>'module2',
            'Image'  =>'group_error.png',
            'class'  =>'icon-group',
            'color'  =>'badge-info',
            'Order'  =>3,
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'Name'  =>"TOOLS",
            'Alias'  =>"Redeem",
            'Code'  =>'module5',
            'Image'  =>'time.png',
            'class'  =>'icon-cogs',
            'color'  =>'badge-danger',
            'Order'  =>5,
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'Name'  =>"ASSETS",
            'Alias'  =>"Assets",
            'Code'  =>'module6',
            'Image'  =>'weather_cloudy.png',
            'class'  =>'icon-upload',
            'color'  =>'badge-orange',
            'Order'  =>1,
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'Name'  =>"CONTENTS",
            'Alias'  =>"Article And Slideshow",
            'Code'  =>'module9',
            'Image'  =>'xhtml.png',
            'class'  =>'icon-quote-right',
            'color'  =>'badge-primary',
            'Order'  =>2,
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'Name'  =>"LOG",
            'Alias'  =>"Log Activities",
            'Code'  =>'module11',
            'Image'  =>'newspaper_link.png',
            'class'  =>'icon-bug',
            'color'  =>'badge-primary',
            'Order'  =>7,
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'Name'  =>"MOBILE CONF",
            'Alias'  =>"Mobile",
            'Code'  =>'module7',
            'Image'  =>'telephone.png',
            'class'  =>'icon-mobile-phone',
            'color'  =>'badge-indigo',
            'Order'  =>9,
        );
        $this->mongo_db->insert($datatinsert);        
        $datatinsert=array(
            'Name'  =>"STATISTICS",
            'Alias'  =>"Report",
            'Code'  =>'module13',
            'Image'  =>'chart_bar_link.png',
            'class'  =>'icon-signal',
            'color'  =>'badge-danger',
            'Order'  =>6,
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
            'Name'  =>"DOCUMENTS",
            'Alias'  =>"Documentation",
            'Code'  =>'module16',
            'Image'  =>'application_error.png',
            'class'  =>'icon-list-alt',
            'color'  =>'badge-primary',
            'Order'  =>8,
        );
        $this->mongo_db->insert($datatinsert);
        /*Begin Create Default Menu*/
        $this->mongo_db->select_collection("Menu");
        $this->mongo_db->remove(array());
//        $datatinsert=array('Path'=>'fitureadd/index',
//                    'Name'  =>"Polls",
//                    'Alias'  =>"Polls",
//                    'EditAble'=>TRUE,
//                    'module'=>'module13',
//                    'Code'  =>'Polls',
//                    'Image'  =>'chart_bar.png',
//                    'ListActions'=>array(
//                        'Add'=>TRUE,
//                        'Edit'=>TRUE,
//                        'Delete'=>TRUE,
//                        'Export'=>TRUE,
//                        'Chart Report'=>TRUE,
//                    ),
//                    'Order'  =>1);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'fitureadd/vote/index',
//                    'Name'  =>"Votes",
//                    'Alias'  =>"Votes",
//                    'EditAble'=>TRUE,
//                    'module'=>'module13',
//                    'Code'  =>'Votes',
//                    'Image'  =>'chart_curve_add.png',
//                    'ListActions'=>array(
//                        'Add'=>TRUE,
//                        'Edit'=>TRUE,
//                        'Delete'=>TRUE,
//                        'Export'=>TRUE,
//                        'Chart Report'=>TRUE,
//                    ),
//                    'Order'  =>2);
//        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'management/index',
                    'Name'  =>"Edit Menu",
                    'Alias'  =>"Menu",
                    'EditAble'=>TRUE,
                    'module'=>'module2',
                    'Code'  =>'Menu',
                    'Image'  =>'folder_table.png',
                    'ListActions'=>array(
                                'Edit'=>TRUE,
                            ),
                    'Order'  =>12);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'management/groups/index',
                    'Name'  =>"User Group",
                    'Alias'  =>"Group User",
                    'EditAble'=>TRUE,
                    'module'=>'module2',
                    'Code'  =>'Group User',
                    'Image'  =>'folder_user.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Setting Akses'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>2);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'management/user/index',
                    'Name'  =>"User Access",
                    'Alias'  =>"User Akses",
                    'EditAble'=>TRUE,
                    'module'=>'module2',
                    'Code'  =>'User Akses',
                    'Image'  =>'user_female.gif',
                    'ListActions'=>array(
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>3);
        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'management/menudasboard/index',
//                    'Name'  =>"Menu Frontend",
//                    'Alias'  =>"Menu Dasboard",
//                    'EditAble'=>TRUE,
//                    'module'=>'module2',
//                    'Code'  =>'Menu Dasboard',
//                    'Image'  =>'application_view_tile.png',
//                    'ListActions'=>array(
//                        'Edit'=>TRUE,
//                        'Order'=>TRUE,
//                        'State'=>TRUE,
//                    ),
//                    'Order'  =>11);
//        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'management/dataimage/index',
                    'Name'  =>"File Manager",
                    'Alias'  =>"Filemanager 1",
                    'EditAble'=>TRUE,
                    'module'=>'module6',
                    'Code'  =>'Filemanager 1',
                    'Image'  =>'palette.png',
                    'Order'  =>9);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'user/index',
                    'Name'  =>"User Account",
                    'Alias'  =>"User",
                    'EditAble'=>TRUE,
                    'module'=>'module2',
                    'Code'  =>'User',
                    'Image'  =>'user_orange.png',
                    'ListActions'=>array(
                        'set Artist'=>TRUE,
                        'Delete'=>TRUE,
                        'View Detail'=>TRUE,
                        'Export'=>TRUE,
                        'View Status'=>TRUE,
                        'View Follower'=>TRUE,
                        'View Following'=>TRUE,
                        'View Avatar Collection'=>TRUE,
                        'View Avatar Mix'=>TRUE,
                        'View Avatar Configuration Likes'=>TRUE,
                        'View Avatar Configuration Comments'=>TRUE,
                    ),
                    'Order'  =>4);
        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'user/current_player/index',
//                    'Name'  =>"Current Player",
//                    'Alias'  =>"Current Player",
//                    'EditAble'=>TRUE,
//                    'module'=>'module13',
//                    'Code'  =>'Current Player',
//                    'Image'  =>'award_star_bronze_3.png',
//                    'Order'  =>6);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'user/statistik/index',
//                    'Name'  =>"Player Room Statistic",
//                    'Alias'  =>"Player Room Statistics",
//                    'EditAble'=>TRUE,
//                    'module'=>'module13',
//                    'Code'  =>'Player Room Statistics',
//                    'ListActions'=>array(
//                        'Reporting Graph'=>TRUE,
//                    ),
//                    'Image'  =>'calculator.png',
//                    'Order'  =>7);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'user/bounce/index',
//                    'Name'  =>"Length of Stay",
//                    'Alias'  =>"Length of Stay",
//                    'EditAble'=>TRUE,
//                    'module'=>'module13',
//                    'Code'  =>'Length of Stay',
//                    'Image'  =>'arrow_switch.png',
//                    'Order'  =>8);
//        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'user/deactivated/index',
                    'Name'  =>"Deactivated User",
                    'Alias'  =>"User Deactivated",
                    'EditAble'=>TRUE,
                    'module'=>'module2',
                    'Code'  =>'User Deactivated',
                    'Image'  =>'user_delete.png',
                    'ListActions'=>array(
                                'Delete'=>TRUE,
                                "set Active"=>TRUE,
                            ),
                    'Order'  =>5);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'redimcode/index',
                    'Name'  =>"Redemption Code",
                    'Alias'  =>"Redeem Code",
                    'EditAble'=>TRUE,
                    'module'=>'module5',
                    'Code'  =>'Redeem Code',
                    'Image'  =>'page_white_cd.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>1);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'redimcode/mappingredimcode/index',
                    'Name'  =>"Redemption Code Mapping",
                    'Alias'  =>"Avatar Redeem Code",
                    'EditAble'=>TRUE,
                    'module'=>'module5',
                    'Code'  =>'Avatar Redeem Code',
                    'Image'  =>'film_add.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>2);
        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'level/index',
//                    'Name'  =>"Level",
//                    'Alias'  =>"Level",
//                    'EditAble'=>TRUE,
//                    'module'=>'module6',
//                    'Code'  =>'Level',
//                    'Image'  =>'sport_8ball.png',
//                    'ListActions'=>array(
//                        'Add'=>TRUE,
//                        'Edit'=>TRUE,
//                        'Export'=>TRUE,
//                        'Import'=>TRUE,
//                        'Delete'=>TRUE,
//                    ),
//                    'Order'  =>9);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'inventory/index',
//                    'Name'  =>"Inventory",
//                    'Alias'  =>"Inventory",
//                    'EditAble'=>TRUE,
//                    'module'=>'module6',
//                    'Code'  =>'Inventory',
//                    'Image'  =>'bug_go.png',
//                    'ListActions'=>array(
//                        'Add'=>TRUE,
//                        'Edit'=>TRUE,
//                        'Delete'=>TRUE,
//                    ),
//                    'Order'  =>7);
//        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'avatar/avatar/index',
                    'Name'  =>"Avatar &amp; Upload",
                    'Alias'  =>"Avatar",
                    'EditAble'=>TRUE,
                    'module'=>'module6',
                    'Code'  =>'Avatar',
                    'Image'  =>'user.gif',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Export'=>TRUE,
                        'Import'=>TRUE,
                        'Delete'=>TRUE,
                        'View Comments'=>TRUE,
                        'View Likes'=>TRUE,
                        'Delete Comments'=>TRUE,
                    ),
                    'Order'  =>1);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'brand/tipe/index',
                    'Name'  =>"Avatar Body Part",
                    'Alias'  =>"Avatar Body Part Type",
                    'EditAble'=>TRUE,
                    'module'=>'module6',
                    'Code'  =>'Avatar Body Part Type',
                    'Image'  =>'building.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>2);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'brand/categori/index',
                    'Name'  =>"Items Category",
                    'Alias'  =>"Category",
                    'EditAble'=>TRUE,
                    'module'=>'module6',
                    'Code'  =>'Category',
                    'Image'  =>'chart_organisation.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>3);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'brand/index',
                    'Name'  =>"Store",
                    'Alias'  =>"Brand",
                    'EditAble'=>TRUE,
                    'module'=>'module2',
                    'Code'  =>'Brand',
                    'Image'  =>'newspaper_add.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Export'=>TRUE,
                        'Delete'=>TRUE,
                        "State"=>TRUE,
                        'View Likes'=>TRUE,
                        'View Comments'=>TRUE,
                        'Delete Comments'=>TRUE,
                        'Manage Contract'=>TRUE,
                    ),
                    'Order'  =>6);
        $this->mongo_db->insert($datatinsert); 
        $datatinsert=array('Path'=>'brand/partner/index',
                    'Name'  =>"Partner",
                    'Alias'  =>"Partner",
                    'EditAble'=>TRUE,
                    'module'=>'module2',
                    'Code'  =>'Partner',
                    'Image'  =>'emoticon_smile.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'State'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>8);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'brand/payment/index',
                    'Name'  =>"Payment Method",
                    'Alias'  =>"Payment Method",
                    'EditAble'=>TRUE,
                    'module'=>'module5',
                    'Code'  =>'Payment Method',
                    'Image'  =>'coins.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>5);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'inventory/animation/index',
                    'Name'  =>"Avatar Animation",
                    'Alias'  =>"Animation",
                    'EditAble'=>TRUE,
                    'module'=>'module6',
                    'Code'  =>'Animation',
                    'Image'  =>'emoticon_tongue.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Export'=>TRUE,
                        'Import'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>4);
        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'avatar/gesticon/index',
//                    'Name'  =>"EMO",
//                    'Alias'  =>"EMO",
//                    'EditAble'=>TRUE,
//                    'module'=>'module6',
//                    'Code'  =>'EMO',
//                    'Image'  =>'bomb.png',
//                    'ListActions'=>array(
//                        'Add'=>TRUE,
//                        'Edit'=>TRUE,
//                        'Delete'=>TRUE,
//                    ),
//                    'Order'  =>7);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'inventory/skincolor/index',
//                    'Name'  =>"Skin Color",
//                    'Alias'  =>"Skin Color",
//                    'EditAble'=>TRUE,
//                    'module'=>'module6',
//                    'Code'  =>'Skin Color',
//                    'Image'  =>'flag_red.png',
//                    'ListActions'=>array(
//                        'Add'=>TRUE,
//                        'Edit'=>TRUE,
//                        'Delete'=>TRUE,
//                    ),
//                    'Order'  =>8);
//        $this->mongo_db->insert($datatinsert);                
//        $datatinsert=array('Path'=>'quest/index',
//                    'Name'  =>"Quests",
//                    'Alias'  =>"Quests",
//                    'EditAble'=>TRUE,
//                    'module'=>'module5',
//                    'Code'  =>'Quests',
//                    'Image'  =>'transmit_error.png',            
//                    'ListActions'=>array(
//                        'Add'=>TRUE,
//                        'Edit'=>TRUE,                        
//                        'Duplicate'=>TRUE,
//                        'Import Txt'=>TRUE,
//                        'Import Exl'=>TRUE,
//                        'Export'=>TRUE,
//                        'Delete'=>TRUE,
//                    ),
//                    'Order'  =>7);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'quest/dialog/index',
//                    'Name'  =>"Dialogs",
//                    'Alias'  =>"Dialogs",
//                    'EditAble'=>TRUE,
//                    'module'=>'module5',
//                    'Code'  =>'Dialogs',
//                    'Image'  =>'emoticon_smile.png',
//                    'ListActions'=>array(
//                        'Add'=>TRUE,
//                        'Edit'=>TRUE,                        
//                        'Duplicate'=>TRUE,
//                        'Import Txt'=>TRUE,
//                        'Import Exl'=>TRUE,
//                        'Export'=>TRUE,
//                        'Delete'=>TRUE,
//                    ),
//                    'Order'  =>8);
//        $this->mongo_db->insert($datatinsert); 
//        $datatinsert=array('Path'=>'quiz/index',
//                    'Name'  =>"Quizes",
//                    'Alias'  =>"Quizes",
//                    'EditAble'=>TRUE,
//                    'module'=>'module5',
//                    'Code'  =>'Quizes',
//                    'Image'  =>'ruby_gear.png',
//                    'ListActions'=>array(
//                        'Add'=>TRUE,
//                        'Edit'=>TRUE,                        
//                        'Delete'=>TRUE,
//                    ),
//                    'Order'  =>9);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'quest/reportplayer/index',
//                    'Name'  =>"Report Player",
//                    'Alias'  =>"Report Player",
//                    'EditAble'=>TRUE,
//                    'module'=>'module5',
//                    'Code'  =>'Report Player',
//                    'Image'  =>'award_star_bronze_3.png',
//                    'Order'  =>10);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'quest/reportquest/index',
//                    'Name'  =>"Report Quest",
//                    'Alias'  =>"Report Quest",
//                    'EditAble'=>TRUE,
//                    'module'=>'module5',
//                    'Code'  =>'Report Quest',
//                    'Image'  =>'award_star_bronze_2.png',
//                    'Order'  =>11);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'quest/reportcomplate/index',
//                    'Name'  =>"Report Complate Quest",
//                    'Alias'  =>"Report Complate Quest",
//                    'EditAble'=>TRUE,
//                    'module'=>'module5',
//                    'Code'  =>'Report Complate Quest',
//                    'Image'  =>'award_star_silver_3.png',
//                    'Order'  =>12);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'quest/reportcurent/index',
//                    'Name'  =>"Report Current Quest",
//                    'Alias'  =>"Report Current Quest",
//                    'EditAble'=>TRUE,
//                    'module'=>'module5',
//                    'Code'  =>'Report Current Quest',
//                    'Image'  =>'award_star_silver_1.png',
//                    'Order'  =>13);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'quest/rankquest/index',
//                    'Name'  =>"Report Rank Quest",
//                    'Alias'  =>"Report Rank Quest",
//                    'EditAble'=>TRUE,
//                    'module'=>'module5',
//                    'Code'  =>'Report Rank Quest',
//                    'Image'  =>'award_star_bronze_1.png',
//                    'Order'  =>14);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'quest/report/index',
//                    'Name'  =>"Report Quest",
//                    'Alias'  =>"Report Quest",
//                    'EditAble'=>TRUE,
//                    'module'=>'module5',
//                    'Code'  =>'Report Quest',
//                    'Image'  =>'award_star_bronze_1.png',
//                    'Order'  =>15);
//        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'content/index',
                    'Name'  =>"Pages &amp; Contents",
                    'Alias'  =>"Content Page",
                    'EditAble'=>TRUE,
                    'module'=>'module9',
                    'Code'  =>'Content Page',
                    'Image'  =>'overlays.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>1);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'setting/article/index',
                    'Name'  =>"FAQ, Term &amp Condition",
                    'Alias'  =>"Article",
                    'EditAble'=>TRUE,
                    'module'=>'module9',
                    'Code'  =>'Article',
                    'Image'  =>'application_view_list.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>4);
        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'setting/slideshow/index',
//                    'Name'  =>"Slide Show",
//                    'Alias'  =>"Slideshow",
//                    'EditAble'=>TRUE,
//                    'module'=>'module6',
//                    'Code'  =>'Slideshow',
//                    'Image'  =>'application_view_gallery.png',
//                    'ListActions'=>array(
//                        'Add'=>TRUE,
//                        'Edit'=>TRUE,
//                        'Delete'=>TRUE,
//                    ),
//                    'Order'  =>7);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'setting/gallery/index',
//                    'Name'  =>"Gallery",
//                    'Alias'  =>"Gallery",
//                    'EditAble'=>TRUE,
//                    'module'=>'module6',
//                    'Code'  =>'Gallery',
//                    'Image'  =>'page_white_medal.png',
//                    'ListActions'=>array(
//                        'Add'=>TRUE,
//                        'Edit'=>TRUE,
//                        'Delete'=>TRUE,
//                    ),
//                    'Order'  =>8);
//        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'setting/event/index',
                    'Name'  =>"Calendar Event",
                    'Alias'  =>"Calendar Event",
                    'EditAble'=>TRUE,
                    'module'=>'module9',
                    'Code'  =>'Calendar Event',
                    'Image'  =>'calendar.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>6);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'help/icon/index',
                    'Name'  =>"Icon Help",
                    'Alias'  =>"Icon Help",
                    'EditAble'=>TRUE,
                    'module'=>'module16',
                    'Code'  =>'Icon Help',
                    'Image'  =>'emoticon_happy.png',
                    'Order'  =>8);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'help/element/index',
                    'Name'  =>"HTML Help",
                    'Alias'  =>"Element Help",
                    'EditAble'=>TRUE,
                    'module'=>'module16',
                    'Code'  =>'Element Help',
                    'Image'  =>'html.png',
                    'Order'  =>9);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'help/documentation/index',
                    'Name'  =>"API Docs",
                    'Alias'  =>"Documentation",
                    'EditAble'=>TRUE,
                    'module'=>'module16',
                    'Code'  =>'Documentation',
                    'Image'  =>'book_open.png',
                    'ListActions'=>array(
                                'Preview'=>TRUE,
                            ),
                    'Order'  =>10);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'content/category/index',
                    'Name'  =>"Business Category",
                    'Alias'  =>"Content Category",
                    'EditAble'=>TRUE,
                    'module'=>'module9',
                    'Code'  =>'Content Category',
                    'Image'  =>'drink.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>6);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'content/device/index',
                    'Name'  =>"OS Device",
                    'Alias'  =>"OS Device",
                    'EditAble'=>TRUE,
                    'module'=>'module9',
                    'Code'  =>'OS Device',
                    'Image'  =>'car_add.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>7);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'content/type/index',
                    'Name'  =>"Content Type",
                    'Alias'  =>"Content Type",
                    'EditAble'=>TRUE,
                    'module'=>'module9',
                    'Code'  =>'Content Type',
                    'Image'  =>'images.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>8);
        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'setting/index',
//                    'Name'  =>"Lobby Setting",
//                    'Alias'  =>"Lobby Setting",
//                    'EditAble'=>TRUE,
//                    'module'=>'module2',
//                    'Code'  =>'Lobby Setting',
//                    'Image'  =>'server_uncompressed.png',
//                    'ListActions'=>array(
//                        'Edit'=>TRUE,
//                    ),
//                    'Order'  =>13);
//        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'setting/gserver/index',
//                    'Name'  =>"Game Server",
//                    'Alias'  =>"Game Server",
//                    'EditAble'=>TRUE,
//                    'module'=>'module2',
//                    'Code'  =>'Game Server',
//                    'Image'  =>'joystick.png',
//                    'ListActions'=>array(
//                        'Add'=>TRUE,
//                        'Edit'=>TRUE,
//                        'Delete'=>TRUE,
//                    ),
//                    'Order'  =>14);
//        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'setting/discount/index',
                    'Name'  =>"Promo",
                    'Alias'  =>"Promo",
                    'EditAble'=>TRUE,
                    'module'=>'module5',
                    'Code'  =>'Promo',
                    'Image'  =>'date_magnify.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>6);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'setting/mbsetting/index',
                    'Name'  =>"Templates",
                    'Alias'  =>"Setting",
                    'EditAble'=>TRUE,
                    'module'=>'module2',
                    'Code'  =>'Setting',
                    'Image'  =>'page_white_gear.png',
                    'ListActions'=>array(
                        'Edit'=>TRUE,
                    ),
                    'Order'  =>6);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'setting/point/index',
                    'Name'  =>"Achievement Point",
                    'Alias'  =>"List Point",
                    'EditAble'=>TRUE,
                    'module'=>'module16',
                    'Code'  =>'List Point',
                    'Image'  =>'application_view_list.png',
                    'ListActions'=>array(
                        'Edit'=>TRUE,
                    ),
                    'Order'  =>4);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'inventory/mgcolor/index',
                    'Name'  =>"Color Manager",
                    'Alias'  =>"Color",
                    'EditAble'=>TRUE,
                    'module'=>'module6',
                    'Code'  =>'Color',
                    'Image'  =>'color_wheel.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>5);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'weblog/index',
                    'Name'  =>"User Logs",
                    'Alias'  =>"User Logs",
                    'EditAble'=>TRUE,
                    'module'=>'module11',
                    'Code'  =>'User Logs',
                    'Image'  =>'ruby_gear.png', 
                    'ListActions'=>array(
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>1);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'weblog/error_log/index',
                    'Name'  =>"Error Actions",
                    'Alias'  =>"Error Visits",
                    'EditAble'=>TRUE,
                    'module'=>'module11',
                    'Code'  =>'Error Visits',
                    'Image'  =>'clock_red.png', 
                    'ListActions'=>array(
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>2);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'weblog/visitor_count/index',
                    'Name'  =>"Visits",
                    'Alias'  =>"Log Visits",
                    'EditAble'=>TRUE,
                    'module'=>'module11',
                    'Code'  =>'Log Visits',
                    'Image'  =>'clock.png', 
                    'ListActions'=>array(
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>3);
        $this->mongo_db->insert($datatinsert); 
        $datatinsert=array('Path'=>'content/news/index',
                    'Name'  =>"Stream News",
                    'Alias'  =>"News Stream",
                    'EditAble'=>TRUE,
                    'module'=>'module9',
                    'Code'  =>'News Stream',
                    'Image'  =>'table_sort.png', 
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                        'View Likes'=>TRUE,
                        'View Comments'=>TRUE,
                        'Delete Comments'=>TRUE,
                    ),
                    'Order'  =>1);
        $this->mongo_db->insert($datatinsert);        
        $datatinsert=array('Path'=>'brand/banner/index',
                    'Name'  =>"Home Banner",
                    'Alias'  =>"Banner",
                    'EditAble'=>TRUE,
                    'module'=>'module9',
                    'Code'  =>'Banner',
                    'Image'  =>'film_save.png',
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                        'View Likes'=>TRUE,
                        'View Comments'=>TRUE,
                        'Delete Comments'=>TRUE,
                    ),
                    'Order'  =>3);
        $this->mongo_db->insert($datatinsert);        
        $datatinsert=array('Path'=>'reporting/index',
                    'Name'  =>"Store Report",
                    'Alias'  =>"Brand Report",
                    'EditAble'=>TRUE,
                    'module'=>'module13',
                    'Code'  =>'Brand Report',
                    'Image'  =>'chart_pie.png',
                    'ListActions'=>array(),
                    'Order'  =>5);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'reporting/banner/index',
                    'Name'  =>"Banner Report",
                    'Alias'  =>"Banner Report",
                    'EditAble'=>TRUE,
                    'module'=>'module13',
                    'Code'  =>'Banner Report',
                    'Image'  =>'chart_line.png',
                    'ListActions'=>array(),
                    'Order'  =>6);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'reporting/avatarconfig/index',
                    'Name'  =>"Avatar Mix Report",
                    'Alias'  =>"Avatar Conf Report",
                    'EditAble'=>TRUE,
                    'module'=>'module13',
                    'Code'  =>'Avatar Conf Report',
                    'Image'  =>'chart_bar.png',
                    'ListActions'=>array(),
                    'Order'  =>7);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'reporting/avataritem/index',
                    'Name'  =>"Avatar Item Report",
                    'Alias'  =>"Avatar Items Report",
                    'EditAble'=>TRUE,
                    'module'=>'module13',
                    'Code'  =>'Avatar Items Report',
                    'Image'  =>'chart_curve.png',
                    'ListActions'=>array(),
                    'Order'  =>8);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'reporting/newsstream/index',
                    'Name'  =>"News Report",
                    'Alias'  =>"News Stream Report",
                    'EditAble'=>TRUE,
                    'module'=>'module13',
                    'Code'  =>'News Stream Report',
                    'Image'  =>'map_magnify.png',
                    'ListActions'=>array(),
                    'Order'  =>9);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'inbox/sendemail/index',
                    'Name'  =>"Broadcast Email",
                    'Alias'  =>"Broadcash Email",
                    'EditAble'=>TRUE,
                    'module'=>'module2',
                    'Code'  =>'Broadcash Email',
                    'Image'  =>'book_link.png',
                    'ListActions'=>array(),
                    'Order'  =>1);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'inbox/sendmsg/index',
                    'Name'  =>"Broadcast Message",
                    'Alias'  =>"Broadcash Message",
                    'EditAble'=>TRUE,
                    'module'=>'module2',
                    'Code'  =>'Broadcash Message',
                    'Image'  =>'book_open.png',
                    'ListActions'=>array(),
                    'Order'  =>2);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'avatar/defaultavatar/index',
                    'Name'  =>"Default Avatar",
                    'Alias'  =>"Default Avatar",
                    'EditAble'=>TRUE,
                    'module'=>'module7',
                    'Code'  =>'Default Avatar',
                    'Image'  =>'vcard.png',
                    'ListActions'=>array(
                        'Edit'=>TRUE,
                    ),
                    'Order'  =>1);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'avatar/configurations/index',
                    'Name'  =>"User Mix",
                    'Alias'  =>"Avatar Configurations",
                    'EditAble'=>TRUE,
                    'module'=>'module7',
                    'Code'  =>'Avatar Configurations',
                    'Image'  =>'tux.png', 
                    'ListActions'=>array(
                        'Overwrite to Default Avatar'=>TRUE,
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                        'View Likes'=>TRUE,
                        'View Comments'=>TRUE,
                        'Delete Comments'=>TRUE,
                    ),
                    'Order'  =>2);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'contest/index',
                    'Name'  =>"Contest",
                    'Alias'  =>"Contest",
                    'EditAble'=>TRUE,
                    'module'=>'module5',
                    'Code'  =>'Contest',
                    'Image'  =>'award_star_gold_3.png',            
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Approved'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>3);
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array('Path'=>'contest/achievement/index',
                    'Name'  =>"Achievement",
                    'Alias'  =>"Achievement",
                    'EditAble'=>TRUE,
                    'module'=>'module5',
                    'Code'  =>'Achievement',
                    'Image'  =>'medal_bronze_1.png',            
                    'ListActions'=>array(
                        'Add'=>TRUE,
                        'Edit'=>TRUE,
                        'Delete'=>TRUE,
                    ),
                    'Order'  =>4);
        $this->mongo_db->insert($datatinsert);
//        $datatinsert=array('Path'=>'contest/leaderboard/index',
//                    'Name'  =>"LeaderBoard",
//                    'Alias'  =>"LeaderBoard",
//                    'EditAble'=>TRUE,
//                    'module'=>'module14',
//                    'Code'  =>'LeaderBoard',
//                    'Image'  =>'creditcards.png',            
//                    'ListActions'=>array(
//                        'Preview'=>TRUE,
//                    ),
//                    'Order'  =>3);
//        $this->mongo_db->insert($datatinsert);
        /*Begin Create Default trModule*/
        $this->mongo_db->select_collection("Module");
        $data=$this->mongo_db->find(array(),0,0,array('Order'=>1));
        $this->mongo_db->select_collection("trModule");
        $this->mongo_db->remove(array());
        foreach($data as $dt)
        {
            $datatinsert=array(
                    'Module'  =>$dt["Code"],
                    'Group'  =>"menu1",
                    'IsActive'=>TRUE,
            );
            $this->mongo_db->insert($datatinsert);
        }
        /*Begin Create Default trMenu*/
        $this->mongo_db->select_collection("Menu");
        $data=$this->mongo_db->find(array(),0,0,array('Order'=>1));
        $this->mongo_db->select_collection("trActions");
        $this->mongo_db->remove(array());
        $this->mongo_db->select_collection("trMenu");
        $this->mongo_db->remove(array());
        foreach($data as $dt)
        {
            $this->mongo_db->select_collection("trMenu");
            $datatinsert=array(
                    'Module'  =>$dt["module"],
                    'Menu'  =>$dt["Code"],
                    'Group'  =>"menu1",
                    'IsActive'=>TRUE,
            );
            $this->mongo_db->insert($datatinsert);
            $this->mongo_db->select_collection("trActions");
            if(isset($dt["ListActions"]))
            {
                foreach($dt["ListActions"] as $dtactions=>$value)
                {
                    $datatinsert=array(
                            'Module'  =>$dt["module"],
                            'Menu'  =>$dt["Code"],
                            'Group'  =>"menu1",
                            'Actions'=>  $dtactions,
                            'IsActive'=>TRUE,
                    );
                    $this->mongo_db->insert($datatinsert);
                }
            }
        }
        /*Begin Create Default Menu User*/
        $this->mongo_db->select_db("Website");
        /*Begin Create Default Bigmenu*/
        $this->mongo_db->select_collection("Bigmenu");
        $this->mongo_db->remove(array());
        $datatinsert=array(
                    'Path'=>'home/welcome/index',
                    'Name'  =>"Slider",
                    'Alias'  =>"Slider",
                    'EditAble'=>TRUE,
                    'Admin'=>FALSE,
                    'State'=>TRUE,
                    'class'=>'icon-camera-retro',
                    'Order'  =>1,
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                    'Path'=>'home/term/index/faq',
                    'Name'  =>"Term",
                    'Alias'  =>"Term",
                    'EditAble'=>TRUE,
                    'Admin'=>FALSE,
                    'State'=>TRUE,
                    'class'=>'icon-certificate',
                    'Order'  =>2,
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                    'Path'=>'home/event',
                    'Name'  =>"Event",
                    'Alias'  =>"Event",
                    'EditAble'=>TRUE,
                    'Admin'=>FALSE,
                    'State'=>TRUE,
                    'class'=>'icon-th-large',
                    'Order'  =>3,
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                    'Path'=>'home/maps',
                    'Name'  =>"Maps",
                    'Alias'  =>"Maps",
                    'EditAble'=>TRUE,
                    'Admin'=>FALSE,
                    'State'=>TRUE,
                    'class'=>'icon-globe',
                    'Order'  =>4,
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                    'Path'=>'home/contact',
                    'Name'  =>"Contact",
                    'Alias'  =>"Contact",
                    'EditAble'=>TRUE,
                    'Admin'=>FALSE,
                    'State'=>TRUE,
                    'class'=>'icon-cog',
                    'Order'  =>5,
        );
        $this->mongo_db->insert($datatinsert);
        $datatinsert=array(
                    'Path'=>'home/news/index',
                    'Name'  =>"News",
                    'Alias'  =>"News",
                    'EditAble'=>TRUE,
                    'Admin'=>FALSE,
                    'State'=>TRUE,
                    'class'=>'icon-list-alt',
                    'Order'  =>6,
        );
        $this->mongo_db->insert($datatinsert);       
    }
}




