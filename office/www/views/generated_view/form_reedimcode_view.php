<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="editdata" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Data</h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("redimcode/cruid_redimcode"); ?>">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <label for="code2">Code</label>
                            </td>
                            <td>
                                <div class="col-sm-8">
                                    <input type="text" name="code" id="code2" value="" maxlength="255" placeholder="Code" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-4">
                                    <button id="generate2" type="button" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                </div>                                
                            </td>
                        </tr>
                        <tr>
                            <td><label for="txtkey2">Name</label></td>
                            <td><input type="text" name="txtkey" id="txtkey2" value="" placeholder="Name" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="count2">Count</label></td>
                            <td><input type="number" name="count" id="count2" value="0" step="1" min="0" max="1000" class="form-control {required:true,number:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="dateexpire2">Date Expire</label></td>
                            <td><input type="text" name="dateexpire" id="dateexpire2" value="" class="form-control" /></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("Redeem Code","module5","Edit",TRUE,FALSE,"home"))
                                {
                                    echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Update</span></button>&nbsp;&nbsp;'; 
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>