<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="modal<?php echo $classword; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Form Edit FAQ, Term &amp; Conditions</h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("setting/article/cruid_article"); ?>">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid2" value="<?php echo $txtid; ?>" />
                                <label for="title2">Title</label>
                            </td>
                            <td><input type="text" name="title" id="title2" value="<?php echo $title; ?>" maxlength="255" placeholder="Title" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="alias2">Alias</label></td>
                            <td><input type="text" name="alias" id="alias2" value="<?php echo $alias; ?>" maxlength="255" placeholder="Alias" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="state2">State</label></td>
                            <td>
                                <select id="state2" name="state" class="form-control">
                                <?php 
                                foreach($this->tambahan_fungsi->document_state() as $dt=>$value)
                                {
                                    echo "<option value='".$dt."'>".$value."</option>";
                                }
                                ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="descmobile2">Content Mobile</label></td>
                            <td><textarea name="descmobile" id="descmobile2" class="form-control autosize {required:true}" cols="55" rows="3"><?php echo $mobiledesc; ?></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="2"><textarea name="contentarticle" id="<?php echo $classword; ?>" class="<?php echo $classword; ?>" cols="45" rows="10"><?php echo $contentarticle; ?></textarea></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('textarea.autosize').autosize({append: "\n"});
<?php
if($state!="")
{
?>
    $('#state2').val('<?php echo $state; ?>');
<?php
}
?>
    $('#modal<?php echo $classword; ?>').modal('show');
});
//<![CDATA[
    CKEDITOR.replace('<?php echo $classword; ?>',{
        fullPage : true
    });

//]]>
</script>