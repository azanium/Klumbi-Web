<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Template_admin
{
    private $_ci;
    public function __construct()
    {
            $this->_ci=&get_instance();
    }
    function header_web($writelock=FALSE,$title,$css=array(),$js=array(),$loadhight=TRUE,$bodyadd="")
    {
        $temp_css=array(           
            base_url()."resources/css/font-awesome/css/font-awesome.min.css",
            base_url()."resources/css/glyphicons/css/glyphicons.min.css",
            base_url()."resources/css/styles.min.css",
        );
        $css = array_merge($temp_css,$css);
        $js_pluging =array(
            base_url()."resources/js/bootstrap.min.js", 
            base_url()."resources/js/enquire.js", 
            base_url()."resources/js/jquery.cookie.js", 
            base_url()."resources/js/jquery.touchSwipe.min.js", 
            base_url()."resources/js/jquery.nicescroll.min.js", 
            base_url()."resources/plugin/codeprettifier/prettify.js", 
            base_url()."resources/plugin/easypiechart/jquery.easypiechart.min.js", 
            base_url()."resources/plugin/sparklines/jquery.sparklines.min.js", 
            base_url()."resources/plugin/form-toggle/toggle.min.js", 
            base_url()."resources/js/placeholdr.js",
        );
        $temp_js=array(
//            "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js",
//            "http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js",
            base_url()."resources/js/jquery-1.10.2.min.js",
            base_url()."resources/js/jqueryui-1.10.3.min.js",   
        );
        if($loadhight)
        {
            $temp_js = array_merge($temp_js,$js_pluging);
        }
        $js = array_merge($temp_js,$js);            
        if($writelock)
        {
            $urlaktive=current_url();
            $this->_ci->session->set_userdata('urlsebelumnya', $urlaktive);
        }
        $dataheader['title']=$title;
        $dataheader['css']=$css;	
        $dataheader['js']=$js;
        $dataheader['addbody']=$bodyadd;
        $this->_ci->load->view('panel/header',$dataheader);
    }
    function link($location="",$param="")
    {
        return base_url().$location.$this->_ci->config->item('url_suffix').$param;
    }
    function top_menu($left=TRUE)
    {
        $propertipage['leftmenu'] = $left;
        $this->_ci->load->view('panel/topmenu',$propertipage);
    } 
    function big_top_menu($moduleforadmin=FALSE,$currentmenu="")
    {
        $propertipage['islogin'] = $moduleforadmin;
        $propertipage['curentmenu'] = $currentmenu;
        $this->_ci->load->view('panel/big_top_menu',$propertipage);
    }
    function panel_menu()
    {	
        $this->_ci->load->view('panel/menu_database');
    }
    function sub_menutop($menu=TRUE)
    {
        $addpage['menutop'] = $menu;
        $this->_ci->load->view('panel/sub_menutop',$addpage);
    }
    function footer()
    {
        if($this->_ci->session->userdata('login'))
        {
            $this->_ci->load->view('panel/loaderauto');
        }
        $this->_ci->load->view('panel/footer');
    } 
    function addresbar()
    {
        $this->_ci->load->view('panel/address');
    } 
    function headerbar()
    {            
        $this->_ci->load->view('panel/headerbar');
    }
}