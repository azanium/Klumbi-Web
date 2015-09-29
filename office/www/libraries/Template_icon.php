<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Template_icon
{
    private $_ci;
    public function __construct()
    {
            $this->_ci=&get_instance();
    }
    function detail($confirm=FALSE,$url="",$alt='Detail',$src="grid.png",$pesantext="",$class="linkaction",$scriptadd="")
    {
        if($confirm)
        {
            return "<a onclick=\"return confirm('".$pesantext."');\" title=\"".$alt."\" href=\"".$url."\" class=\"".$class."\" ".$scriptadd."><img alt=\"".$alt."\" src=\"".base_url()."resources/image/icon/".$src."\" /></a>&nbsp;";
        }
        return "<a title=\"".$alt."\" href=\"".$url."\" class=\"".$class."\" ".$scriptadd."><img alt=\"".$alt."\" src=\"".base_url()."resources/image/icon/".$src."\"/></a>&nbsp;";
    }
    function detail_onclick($confirm="",$url="",$alt='Detail',$src="grid.png",$pesantext="",$class="linkaction",$propertiadd="")
    {
        return "<a onclick=\"".$confirm.";return false;\" title=\"".$alt."\" href=\"".$url."\" class=\"".$class."\" ".$propertiadd."><img alt=\"".$alt."\" src=\"".base_url()."resources/image/icon/".$src."\" /></a>&nbsp;";
    }
    function link_text($url="",$alt='Detail',$src="grid.png",$class="linkaction")
    {
        return "<a title=\"".$alt."\" href=\"".base_url().$url.$this->_ci->config->item('url_suffix')."\" class=\"".$class."\"><img alt=\"".$alt."\" src=\"".base_url()."resources/image/icon/".$src."\"/>&nbsp;&nbsp;".$alt."</a>";
    }
    function link_icon($url="",$alt='EXCELL',$src="grid.png",$attr="")
    {
        return "<a title=\"".$alt."\" href=\"".base_url().$url.$this->_ci->config->item('url_suffix')."\" ".$attr."><img alt=\"".$alt."\" src=\"".base_url()."resources/image/icon/".$src."\"/>".$alt."</a>";
    }
    function link_icon2($url="",$alt='EXCELL',$src="grid.png",$attr="")
    {
        return "<a title=\"".$alt."\" href=\"".base_url().$url."\" ".$attr."><img alt=\"".$alt."\" src=\"".base_url()."resources/image/icon/".$src."\"/>".$alt."</a>";
    }
    function link_icon3($url="",$alt='EXCELL',$src="grid.png",$attr="")
    {
        return "<a title=\"".$alt."\" href=\"".base_url().$url."\" ".$attr."><img alt=\"".$alt."\" src=\"".base_url()."resources/image/icon/".$src."\"/></a>&nbsp;";
    }
    function link_icon_notext($url="",$alt='EXCELL',$src="grid.png")
    {
        return "<a title=\"".$alt."\" href=\"".base_url().$url.$this->_ci->config->item('url_suffix')."\"><img alt=\"".$alt."\" src=\"".base_url()."resources/image/icon/".$src."\"/></a>&nbsp;";
    }
    function img_icon($alt="",$src="grid.png")
    {
        return "<img alt=\"".$alt."\" src=\"".base_url()."resources/image/icon/".$src."\"/>&nbsp;";
    }
    function link_icon_tag($confirm="",$url="",$alt='Detail',$pesantext="",$propertiadd="")
    {
        return "<a onclick=\"".$confirm."\" title=\"".$alt."\" href=\"".$url."\" ".$propertiadd.">".$pesantext."</a>";
    }
}