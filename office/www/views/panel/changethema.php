<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<li class="dropdown hidden-xs">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cogs"></i></a>
    <ul class="dropdown-menu arrow dropdown-menu-form" id="demo-dropdown">
        <li>
            <label for="demo-header-variations" class="control-label">Header Colors</label>
            <ul class="list-inline demo-color-variation" id="demo-header-variations">
                <li><a class="color-black" href="javascript:;" data-headertheme="<?php echo base_url(); ?>resources/css/header-black.css"></a></li>
                <li><a class="color-steel" href="javascript:;" data-headertheme="<?php echo base_url(); ?>resources/css/default_header.css"></a></li>
                <li><a class="color-red" href="javascript:;" data-headertheme="<?php echo base_url(); ?>resources/css/header-red.css"></a></li>
                <li><a class="color-green" href="javascript:;" data-headertheme="<?php echo base_url(); ?>resources/css/header-green.css"></a></li>
                <li><a class="color-blue" href="javascript:;" data-headertheme="<?php echo base_url(); ?>resources/css/header-blue.css"></a></li>
            </ul>
        </li>
        <li class="divider"></li>
        <li>
            <label for="demo-color-variations" class="control-label">Sidebar Colors</label>
            <ul class="list-inline demo-color-variation" id="demo-color-variations">
                <li><a class="color-lite" href="javascript:;" data-theme="<?php echo base_url(); ?>resources/css/default_header.css"></a></li>
                <li><a class="color-steel" href="javascript:;" data-theme="<?php echo base_url(); ?>resources/css/sidebar-steel.css"></a></li>
                <li><a class="color-lavender" href="javascript:;" data-theme="<?php echo base_url(); ?>resources/css/sidebar-lavender.css"></a></li>
                <li><a class="color-green" href="javascript:;" data-theme="<?php echo base_url(); ?>resources/css/sidebar-green.css"></a></li>
            </ul>
        </li>
        <li class="divider"></li>
        <li>
            <label for="fixedheader">Fixed Header</label>
            <div id="fixedheader" style="margin-top:5px;"></div> 
        </li>
    </ul>
</li>