<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("brand/cruid_brand"); ?>" enctype="multipart/form-data">
    <table width="100%" class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="15%">
                <label for="brand_id2">ID</label>
                <input type="hidden" name="txtid" id="txtid2" value="" />
                <input type="hidden" name="txtfileimgname" id="txtfileimgname2" value="" />
                <input type="hidden" name="txtfileimgnamecontent" id="txtfileimgnamecontent2" value="" />
            </td>
            <td width="85%">
                <div class="col-sm-8">
                  <input type="text" name="brand_id" id="brand_id2" value="" maxlength="255" placeholder="ID" class="form-control {required:true}" />
                </div>
                <div class="col-sm-4">
                    <button id="generate2" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                </div>
            </td>
        </tr>
        <tr>
            <td><label for="name2">Name</label></td>
            <td><input type="text" name="name" id="name2" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" /></td>
        </tr> 
        <tr>
            <td><label for="descriptions2">Descriptions</label></td>
            <td>
                <textarea name="descriptions" id="descriptions2" class="form-control" cols="55" rows="3"></textarea>
            </td>
        </tr>
        <tr>
            <td><label for="fileimage2">Header Picture</label></td>
            <td>
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus"></i>
                    <span>Choose file...</span>
                    <input type="file" name="fileimage" id="fileimage2" />
                </span>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><img id="filepreview2" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" /></td>
        </tr>        
        <tr>
            <td><label for="fileimagebanner2">Poster Picture</label></td>
            <td>
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus"></i>
                    <span>Choose file...</span>
                    <input type="file" name="fileimagebanner" id="fileimagebanner2" />
                </span>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><img id="filepreviewbanner2" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" /></td>
        </tr>                
        <tr>
            <td>&nbsp;</td>
            <td>
            <?php 
            if($this->m_checking->actions("Brand","module2","Edit",TRUE,FALSE,"home"))
            {
                echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Update</span></button>&nbsp;&nbsp;'; 
            }
            ?>
            </td>
        </tr>
    </table>
</form>