<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() {    
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
});
</script>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4>Trophy</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="row">
                        <div class="col-md-12">                            
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="<?php echo $this->template_admin->link("member/trophy/index"); ?>" class="btn btn-default <?php echo ($world==""?"active":"") ?>"><i class="icon-globe"></i> World</a>
                                    <a href="<?php echo $this->template_admin->link("member/trophy/index/friends"); ?>" class="btn btn-default <?php echo ($world==""?"":"active") ?>"><i class="icon-group"></i> Friend</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="margin-top:10px; margin-bottom:10px;" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="list-group"> 
                                <a href="<?php echo $this->template_admin->link("inbox/index"); ?>" class="list-group-item" style="background-color: red;color: white;"><img src="http://localhost/koffice/resources/image/callcenter-girls-glasses-icon.png" alt="" class="img-circle" /> User 1<span class="pull-right icon-4x">1</span></a>
                                <a href="<?php echo $this->template_admin->link("inbox/index/send"); ?>" class="list-group-item" style="background-color: #1695BD;color: white;"><img src="http://localhost/koffice/resources/image/callcenter-girls-glasses-icon.png" alt="" class="img-circle" />User 2<span class="pull-right icon-4x">2</span></a>
                                <a href="<?php echo $this->template_admin->link("inbox/index/important"); ?>" class="list-group-item" style="background-color: #CCC;"><img src="http://localhost/koffice/resources/image/callcenter-girls-glasses-icon.png" alt="" class="img-circle" /> Anda <span class="pull-right icon-4x">3</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <style type="text/css">
                                .circle {
                                        border-radius: 50%;
                                        -moz-border-radius: 50%;
                                        -webkit-border-radius: 50%;
                                    }
                                    #circle1 {
                                        width: 50px;
                                        height: 50px;
                                        background: red;
                                    }
                                    #circle2 {
                                        width: 50px;
                                        height: 50px;
                                        background: #CCC;
                                    }
                                    #circle3 {
                                        width: 50px;
                                        height: 50px;
                                        background: #1695BD;
                                    }
                                    .circle:after {
                                        content: "";
                                        display: block;
                                        width: 100%;
                                        height:0;
                                        padding-bottom: 100%;
                                        -moz-border-radius: 50%;
                                        -webkit-border-radius: 50%;
                                        border-radius: 50%;
                                    }
                                    .circle div {
                                        float:left;
                                        width:100%;
                                        padding-top:50%;
                                        line-height:1em;
                                        margin-top:-0.5em;
                                        text-align:center;
                                        color:white;
                                    }
                                    @-webkit-keyframes spin {
                                      from { -webkit-transform: rotate(0deg); }
                                      to { -webkit-transform: rotate(360deg); }
                                    }
                                    @-moz-keyframes spin {
                                      from { -moz-transform: rotate(0deg); }
                                      to { -moz-transform: rotate(360deg); }
                                    }
                                    @-ms-keyframes spin {
                                      from { -ms-transform: rotate(0deg); }
                                      to { -ms-transform: rotate(360deg); }
                                    }
                            </style>
                            <div id="circle1" class="circle"><div>1<subscribe>st</subscribe></div></div>
                            <div id="circle2" class="circle"><div>Me</div></div>
                            <div id="circle3" class="circle"><div>Friend</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>