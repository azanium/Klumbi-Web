<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() {
    $('textarea.autosize').autosize({append: "\n"});
    $('.select').select2({width: 'resolve',placeholder: "Select User ID",minimumInputLength: 2});
    $("#editbrandfrm").validate();
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
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4>Broadcast Message</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <form method="POST" class="form-horizontal" id="editbrandfrm" action="<?php echo $this->template_admin->link("inbox/sendmsg/send"); ?>">
                        <div class="form-group">
                            <label for="txtsendto" class="col-sm-2 control-label">To</label>
                            <div class="col-sm-8">
                                <select id="txtsendto" name="txtsendto[]" class="select" multiple="true" placeholder="To.." style="width:100%">
                                    <?php 
                                    foreach($datalist as $dt)
                                    {
                                        $tempdtuser = $this->m_userdata->user_properties((string)$dt["_id"]);
                                        echo "<option value='".(string)$dt["_id"]."'>".$tempdtuser['fullname']."</option>";
                                    }
                                    ?>
                               </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="chkagrement"><input type="checkbox" name="chkagrement" id="chkagrement" /> Cheked if the message want to send to the all user</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtsubject" class="col-sm-2 control-label">Subject</label>
                            <div class="col-sm-10">
                                <input type="text" name="txtsubject" id="txtsubject" value="" maxlength="255" placeholder="Subject" class="form-control {required:true}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="<?php echo $tagcontent; ?>" class="col-sm-2 control-label">Message</label>
                            <div class="col-sm-10">
                              <textarea name="txtmessage" id="txtmessage" class="form-control autosize {required:true}" cols="55" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2">&nbsp;</div>
                            <div class="col-sm-10">
                                <button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Send</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>