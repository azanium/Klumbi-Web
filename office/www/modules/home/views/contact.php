<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() { 
    $("#formdata").validate({
        submitHandler: function(form) {
           var url=$("#formdata").attr('action');
           var datapost=$("#formdata").serialize();
                $.ajax({
                    type: "POST",
                    url: url,
                    data:datapost,
                    dataType: "json",
                    beforeSend: function ( xhr ) {
                        $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
						'<strong>Warning!</strong> ' +
                                                '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                                '<i class="process">Wait a minute, Your request being processed</i>' +
						'</div>').slideDown(100);
                    },
                    error: function (xhr, textStatus, errorThrown) {
                           $("#loadingprocess").slideUp(100);
                           $.pnotify({
                                title: textStatus + " " + xhr.status,
                                text: (errorThrown ? errorThrown : xhr.status),
                                type: 'error'
                            });
                    },
                    success: function (data, textStatus) {
                        $("#loadingprocess").slideUp(100);
                        var message = "";
                        if(data['success']==true)
                        {                        
                            $.pnotify({
                                title: "Success",
                                text: data['message'],
                                type: 'success'
                            });
                            $("input, textarea").val("");
                        }
                        else
                        {
                            $.pnotify({
                                title: "Fail",
                                text: data['message'],
                                type: 'info'
                            });
                        }  
                        Recaptcha.reload();
                    }
                });
            return false;
        }
    });
});
</script>
<div class="container">
    <div class="row">
        <form class="form-horizontal" method="POST" id="formdata" action="<?php echo $this->template_admin->link("home/contact/send"); ?>">
        <div class="col-md-3">&nbsp;</div>
        <div class="col-md-6 panel">
            <div class="panel-body">
                <h4 class="text-center" style="margin-bottom: 25px;">Contact Us</h4>                
                    <div class="form-group">
                        <label for="txtemail" class="control-label col-sm-4" style="text-align: left;">Email</label>
                        <div class="col-sm-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-user"></i></span>
                                <input type="text" class="form-control {required:true,email:true}" id="txtemail" name="txtemail" placeholder="Email" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="txtname" class="control-label col-sm-4" style="text-align: left;">Name</label>
                        <div class="col-sm-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-hand-right"></i></span>
                                <input type="text" class="form-control {required:true}" id="txtname" name="txtname" placeholder="Name" />
                            </div>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="txtsubject" class="control-label col-sm-4" style="text-align: left;">Subject</label>
                        <div class="col-sm-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-book"></i></span>
                                <input type="text" class="form-control {required:true}" id="txtsubject" name="txtsubject" placeholder="Subject" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="txtmessage" class="control-label col-sm-4" style="text-align: left;">Message</label>
                        <div class="col-sm-12">
                            <div class="input-group">
                                <textarea name="txtmessage" id="txtmessage" cols="50" rows="4" class="form-control {required:true}"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" style="text-align: left;">Captcha</label>
                        <div class="col-sm-12">
                            <div class="input-group">
                            <?php
                            $this->load->view("plugins/captcha");
                            ?>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-4">
                        <div class="btn-toolbar">
                            <button type="submit" class="btn-primary btn">Send</button>
                            <button type="reset" class="btn-default btn resetform">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">&nbsp;</div>
        </form>
    </div>	
</div>	