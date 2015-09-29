<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<li class="dropdown">
    <a href="#" id="mesagecountdb" class="hasnotifications dropdown-toggle" data-toggle='dropdown'><i class="icon-envelope"></i></a>
    <ul id="listdatamesage" class="dropdown-menu messeges arrow">
        <li><span>You have no new message</span><span><a href="<?php echo $this->template_admin->link("inbox/index"); ?>">Open All Messages</a><span></li>
        <li><a class="dd-viewall" href="<?php echo $this->template_admin->link("inbox/index"); ?>">View All Messages</a></li>
    </ul>
</li>