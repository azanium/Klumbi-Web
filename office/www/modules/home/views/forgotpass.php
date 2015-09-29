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
                        $("#notifyform").html("");
                    },
                    error: function (xhr, textStatus, errorThrown) {
                           $("#loadingprocess").slideUp(100);
                           $("#notifyform").html(
                                "<div class='alert alert-dismissable alert-danger'>" +
                                "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                                textStatus + " " + xhr.status + ": " + (errorThrown ? errorThrown : xhr.status) +
                                "</div>"
                            );
                    },
                    success: function (data, textStatus) {
                        $("#loadingprocess").slideUp(100);
                        $("input, textarea").val("");
                        var message = "";
                        if(data['success']==true)
                        {                            
                            message += "<div class='alert alert-dismissable alert-success'>" +
                                        "<strong>Success!</strong> ";
                        }
                        else
                        {                            
                            message += "<div class='alert alert-dismissable alert-danger'>" +
                                        "<strong>Wrong!</strong> ";
                        }  
                        message += data['message'] +
					"<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
					"</div>";
                        $("#notifyform").html(message);
                    }
                });
            return false;
        }
    });
});
</script>
<div class="verticalcenter">
    <img src="<?php echo base_url(); ?>resources/image/logo/page-login.png" alt="Logo" class="brand" />
    <div id="notifyform">&nbsp;</div>
    <div class="panel panel-primary">
        <div class="panel-body">
            <h4 class="text-center" style="margin-bottom: 25px;">Request new password</h4>
            <form class="form-horizontal" method="POST" id="formdata" action="<?php echo $this->template_admin->link("home/sendemailpass"); ?>">
                <div class="form-group">
                    <label for="txtemail" class="control-label col-sm-4" style="text-align: left;">Your Email</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-user"></i></span>
                            <input type="text" class="form-control {required:true, email:true}" id="txtemail" name="txtemail" placeholder="Email" />
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Send</button>
            </form>
        </div>
        <div class="panel-footer">
            <a href="<?php echo $this->template_admin->link("home/login"); ?>" class="pull-left btn btn-link" style="padding-left:0">Login?</a>
            <div class="pull-right">
                <a href="#" class="btn btn-default resetform">Reset</a>
            </div>
        </div>
    </div>
 </div>