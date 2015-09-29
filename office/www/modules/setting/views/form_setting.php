<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() {
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    $("#frmsetting").validate({
        submitHandler: function(form) {
            var url=$("#frmsetting").attr('action');
            var datapost=$("#frmsetting").serialize();
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
                success: function (data, textStatus) {
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
                    $("#loadingprocess").slideUp(100);
                },
                error: function (xhr, textStatus, errorThrown) {
                    $("#loadingprocess").slideUp(100);
                    $.pnotify({
                        title: textStatus + " " + xhr.status,
                        text: (errorThrown ? errorThrown : xhr.status),
                        type: 'error'
                    });
                }
            });
            return false;
        }
    });
});
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">  
            <form method="POST" id="frmsetting" action="<?php echo $this->template_admin->link("setting/cruid_setting"); ?>">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4>Setting</h4>
                        <div class="options">
                            <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                        </div>
                    </div>
                    <div class="panel-body collapse in">                    
                        <fieldset>
                            <legend>Setting Lobby</legend>
                            <div class="form-group">                                
                                <label for="ip" class="col-sm-3 control-label">IP</label>
                                <div class="col-sm-9">
                                    <input type="hidden" name="txtid" id="txtid" value="<?php echo $txtid; ?>" />
                                    <input type="text" name="ip" id="ip" value="<?php echo $ip; ?>" maxlength="255" placeholder="IP" class="form-control input-sm {required:true}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="port" class="col-sm-3 control-label">Port</label>
                                <div class="col-sm-9">
                                    <input type="number" name="port" id="port" value="<?php echo $port; ?>" maxlength="255" step="1" min="0" max="1000000" placeholder="Port" class="form-control input-sm {number:true}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="room_history" class="col-sm-3 control-label">Enable Room History</label>
                                <div class="col-sm-9">
                                    <select id="room_history" name="room_history" class="form-control">
                                        <?php
                                        $stateactive="selected='selected'";
                                        $statenotactive="";
                                        if(!$room_history)
                                        {
                                            $stateactive="";
                                            $statenotactive="selected='selected'";
                                        }
                                        ?>
                                        <option value='1' <?php echo $stateactive; ?>>Enable</option>
                                        <option value='0' <?php echo $statenotactive; ?>>Disable</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>                                      
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-9 col-sm-offset-3">
                                <div class="btn-toolbar">
                                    <?php 
                                    if($this->m_checking->actions("Lobby Setting","module2","Edit",TRUE,FALSE,"home"))
                                    {
                                        echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Update</span></button>&nbsp;&nbsp;'; 
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>