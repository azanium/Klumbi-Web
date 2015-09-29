<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() { 
    $("#emailverification").click(function(){
        $.ajax({
            type: "GET",
            url: "<?php echo $this->template_admin->link("api/pm/email/verification/".$id); ?>",
            data:{user_id:"<?php echo $id; ?>"},
            dataType: "json",
            beforeSend: function ( xhr ) {
                $("#notifyform").html('<div class="alert alert-dismissable alert-warning">' +
                                        '<strong>Warning!</strong> ' +
                                        '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                        '<i class="process">Wait a minute, Your request being processed</i>' +
                                        '</div>').slideDown(100);
            },
            error: function (xhr, textStatus, errorThrown) {
                   $("#notifyform").slideUp(100);
                   $.pnotify({
                        title: textStatus + " " + xhr.status,
                        text: (errorThrown ? errorThrown : xhr.status),
                        type: 'error'
                    });
            },
            success: function (data, textStatus) {
                $("#notifyform").slideUp(100);
                if(data['success']==true)
                {                        
                    $.pnotify({
                        title: "Success",
                        text: data['message'],
                        type: 'success'
                    });
                }
                else
                {
                    $.pnotify({
                        title: "Fail",
                        text: data['message'],
                        type: 'info'
                    });
                }
            }
        });
        return false;
    });
    $("#formdata").validate({
        submitHandler: function(form) {
           var url=$("#formdata").attr('action');
           var datapost=$("#formdata").serialize();
           $.ajax({
                type: "GET",
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
                    var pesandata = "";
                    if(data["success"])
                    {
                        pesandata = '<div class="alert alert-dismissable alert-warning">' +
                                    "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                                    data["message"] +
                                    '</div>';
                    }
                    else
                    {
                        pesandata = '<div class="alert alert-dismissable alert-danger">' +
                                    "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                                    data["message"] +
                                    '</div>';
                    }
                    $("#notifyform").html(pesandata);
                }
            });
            return false;
        }
    });
    $("#formdatauser").validate({
        submitHandler: function(form) {
           var url=$("#formdatauser").attr('action');
           var datapost=$("#formdatauser").serialize();
           $.ajax({
                type: "GET",
                url: url,
                data:datapost,
                dataType: "json",
                beforeSend: function ( xhr ) {
                    $("#notifyformusername").html('<div class="alert alert-dismissable alert-warning">' +
                                            '<strong>Warning!</strong> ' +
                                            '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                            '<i class="process">Wait a minute, Your request being processed</i>' +
                                            '</div>').slideDown(100);
                },
                error: function (xhr, textStatus, errorThrown) {
                    $("#notifyformusername").html(
                        "<div class='alert alert-dismissable alert-danger'>" +
                        "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                        textStatus + " " + xhr.status + ": " + (errorThrown ? errorThrown : xhr.status) +
                        "</div>"
                    );
                },
                success: function (data, textStatus) {
                    var pesandata = "";
                    if(data["success"])
                    {
                        pesandata = '<div class="alert alert-dismissable alert-warning">' +
                                    "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                                    data["message"] +
                                    '</div>';
                    }
                    else
                    {
                        pesandata = '<div class="alert alert-dismissable alert-danger">' +
                                    "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                                    data["message"] +
                                    '</div>';
                    }
                    $("#notifyformusername").html(pesandata);
                }
            });
            return false;
        }
    });
    $("#formdatapassword").validate({
        submitHandler: function(form) {
           var url=$("#formdatapassword").attr('action');
           var datapost=$("#formdatapassword").serialize();
           $.ajax({
                type: "GET",
                url: url,
                data:datapost,
                dataType: "json",
                beforeSend: function ( xhr ) {
                    $("#notifyforpassword").html('<div class="alert alert-dismissable alert-warning">' +
                                            '<strong>Warning!</strong> ' +
                                            '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                            '<i class="process">Wait a minute, Your request being processed</i>' +
                                            '</div>').slideDown(100);
                },
                error: function (xhr, textStatus, errorThrown) {
                    $("#notifyforpassword").html(
                        "<div class='alert alert-dismissable alert-danger'>" +
                        "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                        textStatus + " " + xhr.status + ": " + (errorThrown ? errorThrown : xhr.status) +
                        "</div>"
                    );
                },
                success: function (data, textStatus) {
                    var pesandata = "";
                    if(data["success"])
                    {
                        pesandata = '<div class="alert alert-dismissable alert-warning">' +
                                    "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                                    data["message"] +
                                    '</div>';
                    }
                    else
                    {
                        pesandata = '<div class="alert alert-dismissable alert-danger">' +
                                    "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                                    data["message"] +
                                    '</div>';
                    }
                    $("#notifyforpassword").html(pesandata);
                }
            });
            return false;
        }
    });
});
</script>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-body">
                <form class="form-horizontal" method="POST" id="formdata" action="<?php echo $this->template_admin->link("api/user/change_email"); ?>">
                    <div id="notifyform">&nbsp;</div>
                    <div class="form-group">
                        <label for="email" class="control-label col-sm-2" style="text-align: left;">Email</label>
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-envelope"></i></span>
                                <input type="text" value="<?php echo $email; ?>" class="form-control {required:true, email:true}" name="email" id="email" placeholder="Email" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>
                        </div>
                    </div>                    
                    <button type="button" id="emailverification" class="btn-danger btn btn-block"> <i class="icon-signin"></i> <span>Send Email Verification</span></button>
                </form>
                <hr />
                <form class="form-horizontal" method="POST" id="formdatauser" action="<?php echo $this->template_admin->link("api/user/change_username"); ?>">
                    <div id="notifyformusername">&nbsp;</div>
                    <div class="form-group">
                        <label for="username" class="control-label col-sm-2" style="text-align: left;">Username</label>
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">@</span>
                                <input type="text" value="<?php echo $username; ?>" class="form-control {required:true, minlength:3, maxlength:15}" name="username" id="username" placeholder="Username" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>
                        </div>
                    </div>
                </form>
                <hr />
                <form class="form-horizontal" method="POST" id="formdatapassword" action="<?php echo $this->template_admin->link("api/user/change_password"); ?>">
                    <div id="notifyforpassword">&nbsp;</div>
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <label for="txtpassword" class="control-label col-sm-4" style="text-align: left;">Password</label>
                        <div class="col-sm-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-lock"></i></span>
                                <input type="password" class="form-control {required:true,minlength: 6}" id="txtpassword" name="txtpassword" placeholder="Password" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-sm-4" style="text-align: left;">Retype Password</label>
                        <div class="col-sm-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-lock"></i></span>
                                <input type="password" class="form-control {required:true, equalTo:txtpassword}" id="password" name="password" placeholder="Password" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">&nbsp;</div>
                        <div class="col-sm-8">
                            <button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>
                        </div>
                    </div>                    
                </form>
            </div>
        </div>
    </div>
</div>