<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Properties User</h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <table width="100%">
                        <tr>
                            <td><label>Full Name</label></td>
                            <td><input type="text" name="fullname" maxlength="255" value="<?php echo $fullname; ?>" placeholder="Full Name" class="inputbox lengkung_4 pading_5" /></td>
                        </tr>
                        <tr>
                            <td><label>Avatar Name</label></td>
                            <td><input type="text" name="avatarname" maxlength="255" value="<?php echo $avatarname; ?>" placeholder="Avatar Name" class="inputbox lengkung_4 pading_5" /></td>
                        </tr>
                        <tr>
                            <td><label>Birthday</label></td>
                            <td><input type="text" name="birthday" maxlength="255" value="<?php echo $birthday; ?>" placeholder="Birthday" class="inputbox lengkung_4 pading_5" /></td>
                        </tr>
                        <tr>
                            <td><label>Gender</label></td>
                            <td><input type="text" name="sex" maxlength="255" value="<?php echo $sex; ?>" placeholder="Sex" class="inputbox lengkung_4 pading_5" /></td>
                        </tr>
                        <tr>
                            <td><label>Website</label></td>
                            <td><input type="text" name="website" maxlength="255" value="<?php echo $website; ?>" placeholder="http://" class="inputbox lengkung_4 pading_5" /></td>
                        </tr>
                        <tr>
                            <td><label>Link</label></td>
                            <td><input type="text" name="link" maxlength="255" value="<?php echo $link; ?>" placeholder="http://" class="inputbox lengkung_4 pading_5" /></td>
                        </tr>
                        <tr>
                            <td><label>About</label></td>
                            <td><textarea cols="10" rows="3" class="inputbox lengkung_4 pading_5" name="about"><?php echo $about; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label>Phone</label></td>
                            <td><input type="text" name="handphone" maxlength="255" value="<?php echo $handphone; ?>" placeholder="Phone" class="inputbox lengkung_4 pading_5" /></td>
                        </tr>
                        <tr>
                            <td><label>Location</label></td>
                            <td><input type="text" name="location" maxlength="255" value="<?php echo $location; ?>" placeholder="Location" class="inputbox lengkung_4 pading_5" /></td>
                        </tr>
                        <tr>
                            <td><label>Body Size</label></td>
                            <td><input type="text" name="bodysize" maxlength="255" value="<?php echo $bodytype; ?>" placeholder="Body Size" class="inputbox lengkung_4 pading_5" /></td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <img src='<?php echo $picture; ?>' alt='Avatar Pic' style='max-width:120px; max-height:120px;' class='img-thumbnail' />&nbsp;&nbsp;
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#modal').modal('show');
});
</script>