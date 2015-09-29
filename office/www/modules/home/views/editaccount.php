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
                        $("#notifyform").html('<div class="alert alert-dismissable alert-warning">' +
						'<strong>Warning!</strong> ' +
                                                '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                                '<i class="process">Wait a minute, Your request being processed</i>' +
						'</div>').slideDown(100);
                    },
                    error: function (xhr, textStatus, errorThrown) {
                           $("#notifyform").html(
                                "<div class='alert alert-dismissable alert-danger'>" +
                                "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                                textStatus + " " + xhr.status + ": " + (errorThrown ? errorThrown : xhr.status) +
                                "</div>"
                            );
                    },
                    success: function (data, textStatus) {
                        var message = "";
                        if(data['login']==true)
                        {                            
                            message = "<div class='alert alert-dismissable alert-success'>" +
                                        "<strong>Success!</strong> You successfully Login." +
					"<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
					"</div>";
                            setTimeout(function(){
                                $("#iframeeditaccount").html(data['htmlopen']);
                            },1000);                            
                        }
                        else
                        {
                            message = "<div class='alert alert-dismissable alert-danger'>" +
                                        "<strong>Wrong!</strong> " + data['message'] +
					"<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
					"</div>";
                        }  
                        $("#notifyform").html(message);
                    }
            });
            return false;
        }
    });
});
</script>
<div class="panel panel-primary">
    <div class="panel-body">
        <h4 class="text-center">Please Login before change your account</h4>
        <form class="form-horizontal" method="POST" id="formdata" action="<?php echo $this->template_admin->link("home/eaccount/cek_login"); ?>">
            <div class="form-group">
                <label for="txtemail" class="control-label col-sm-4" style="text-align: left;">Email</label>
                <div class="col-sm-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-user"></i></span>
                        <input type="text" class="form-control {required:true}" id="txtemail" name="txtemail" placeholder="Email" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="txtpassword" class="control-label col-sm-4" style="text-align: left;">Password</label>
                <div class="col-sm-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-lock"></i></span>
                        <input type="password" class="form-control {required:true}" id="txtpassword" name="txtpassword" placeholder="Password" />
                    </div>
                </div>
            </div>
            <div id="notifyform">&nbsp;</div>
            <button type="submit" class="btn btn-green btn-block"> Login</button>
        </form>
    </div>
</div>