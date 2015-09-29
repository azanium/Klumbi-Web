<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/plugin/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('textarea.autosize').autosize({append: "\n"});
    $('.select').select2({width: 'resolve',placeholder: "Select email",minimumInputLength: 2});
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
            <div class="panel panel-gray">
                <div class="panel-heading">
                    <h4>Broadcast Email</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <form method="POST"  enctype="multipart/form-data" class="form-horizontal" id="editbrandfrm" action="<?php echo $this->template_admin->link("inbox/sendemail/send"); ?>">
                        <div class="form-group">
                            <label for="txtsendto" class="col-sm-2 control-label">To</label>
                            <div class="col-sm-10">
                                <select id="txtsendto" name="txtsendto[]" class="select {required:true}" multiple="true" placeholder="To.." style="width:100%">
                                    <?php 
                                    foreach($datalist as $dt)
                                    {
                                        $tempdtuser = $this->m_userdata->user_properties((string)$dt["_id"]);
                                        echo "<option value='".$dt["email"]."'>".$tempdtuser['fullname']."</option>";
                                    }
                                    ?>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bccto" class="col-sm-2 control-label">CC</label>
                            <div class="col-sm-6">
                              <input type="text" name="bccto" id="bccto" value="" maxlength="255" placeholder="CC" class="form-control" />
                            </div>
                            <div class="col-sm-4">
                                <p class="help-block">Separated by semicolon ";" Ext:<br /> uda_rido@yahoo.com; ridosaputra2@gmail.com;</p>
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
                              <textarea name="txtmessage" id="<?php echo $tagcontent; ?>" class="<?php echo $tagcontent; ?> form-control autosize {required:true}" cols="55" rows="3"></textarea>
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
<script type="text/javascript">
CKEDITOR.replace('<?php echo $tagcontent; ?>',{
    fullPage : true
});
</script>