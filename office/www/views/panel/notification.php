<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<li class="dropdown">
    <a href="#" id="notificationpanel" class="hasnotifications dropdown-toggle" data-toggle='dropdown'><i class="icon-bell-alt"></i></a>
    <ul id="listdatanotification" class="dropdown-menu notifications arrow">
        <li>
            <span>You have no new notification</span>
            <span><a href="<?php echo $this->template_admin->link("member/timeline/index"); ?>">Open All Notifications</a></span>
        </li>
        <li><a href="<?php echo $this->template_admin->link("member/timeline/index"); ?>" class="dd-viewall">View All Notifications</a></li>
    </ul>
</li>