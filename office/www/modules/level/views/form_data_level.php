<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form method="POST" action="<?php echo $this->template_admin->link("level/update"); ?>" id="editbrandfrm" enctype="multipart/form-data">
    <table width="100%" class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="15%">
                <label for="lvlname2">Level Name</label>
                <input type="hidden" name="txtid" id="txtid2" value="<?php echo $txtid; ?>" />
            </td>
            <td width="85%"><input type="text" name="lvlname" id="lvlname2" value="<?php echo $lvlname; ?>" maxlength="255" placeholder="Level Name" class="form-control {required:true}" /></td>
        </tr>
        <?php
        if($this->session->userdata('brand')=="")
        {
        ?>
        <tr>
            <td><label for="brand2">Store</label></td>
            <td>
                <select id="brand2" name="brand" class="form-control">
                    <option value="">&nbsp;</option>
                    <?php 
                    foreach($brand_id as $dt)
                    {
                        $terpilih="";
                        if($dt['brand_id']==$selected_brand)
                        {
                            $terpilih="selected='selected'";
                        }
                        echo "<option value='".$dt['brand_id']."' ".$terpilih.">".$dt['name']."</option>";
                    }
                    ?>
               </select>
            </td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <td><label for="txtcategory2">Category</label></td>
            <td>
                <select id="txtcategory2" name="txtcategory" class="form-control">
                    <option value="">&nbsp;</option>
                    <?php 
                    foreach($category_id as $dt)
                    {
                        $terpilih="";
                        if($dt['name']==$selected_category)
                        {
                            $terpilih="selected='selected'";
                        }
                        echo "<option value='".$dt['name']."' ".$terpilih.">".$dt['name']."</option>";
                    }
                    ?>
               </select>
            </td>
        </tr>
        <tr>
            <td><label for="fileskybox2">File Skybox Web</label></td>
            <td><input type="file" name="fileskybox" id="fileskybox2"/><?php echo $fileskybox; ?></td>
        </tr>
        <tr>
            <td><label for="fileskybox_ios2">File Skybox iOS</label></td>
            <td><input type="file" name="fileskybox_ios" id="fileskybox_ios2"/><?php echo $fileskybox_ios; ?></td>
        </tr>
        <tr>
            <td><label for="fileskybox_android2">File Skybox Android</label></td>
            <td><input type="file" name="fileskybox_android" id="fileskybox_android2"/><?php echo $fileskybox_android; ?></td>
        </tr>
        <tr>
            <td><label for="fileaudio2">File Audio Web</label></td>
            <td><input type="file" name="fileaudio" id="fileaudio2"/><?php echo $fileaudio; ?></td>
        </tr>
        <tr>
            <td><label for="fileaudio_ios2">File Audio iOS</label></td>
            <td><input type="file" name="fileaudio_ios" id="fileaudio_ios2"/><?php echo $fileaudio_ios; ?></td>
        </tr>
        <tr>
            <td><label for="fileaudio_android2">File Audio Android</label></td>
            <td><input type="file" name="fileaudio_android" id="fileaudio_android2"/><?php echo $fileaudio_android; ?></td>
        </tr>
        <tr>
            <td><label for="fileimg2">File Image</label></td>
            <td><input type="file" name="fileimg" id="fileimg2"/><?php echo $fileimg; ?></td>
        </tr>
        <tr>
            <td><label for="txttag2">Tags</label></td>
            <td><input type="text" name="txttag" style="width:100%" id="txttag2" value="<?php echo $txttag; ?>" maxlength="255" placeholder="Tags" /></td>
        </tr>
        <tr>
            <td><label for="txtserver2">Server Address</label></td>
            <td><input type="text" name="txtserver" id="txtserver2" maxlength="255" value="<?php echo $txtserver; ?>" placeholder="Server Address" class="form-control" /></td>
        </tr>
        <tr>
            <td><label for="txtport2">Server PORT</label></td>
            <td><input type="text" name="txtport" id="txtport2" maxlength="255" value="<?php echo $txtport; ?>" placeholder="Server PORT" class="form-control {number:true}" /></td>
        </tr>
        <tr>
            <td><label for="txtchanel2">Number of Channels</label></td>
            <td><input type="number" name="txtchanel" id="txtchanel2" value="<?php echo $txtchanel; ?>" min="0" step="1" max="1000" class="form-control {number:true}" /></td>
        </tr>
        <tr>
            <td><label for="txtccu2">Max CCU per Channel</label></td>
            <td><input type="number" name="txtccu" id="txtccu2" value="<?php echo $txtccu; ?>" step="1" min="0" max="1000" class="form-control {number:true}" /></td>
        </tr>
        <tr>
            <td><label>World Size</label></td>
            <td>
                <input type="text" name="txtwordsizex" id="txtwordsizex2" value="<?php echo $txtwordsizex; ?>" maxlength="255" placeholder="X" class="form-control {number:true}" />
                <input type="text" name="txtwordsizey" id="txtwordsizey2" value="<?php echo $txtwordsizey; ?>" maxlength="255" placeholder="Y" class="form-control {number:true}" />
            </td>
        </tr>
        <tr>
            <td><label>Interest Area</label></td>
            <td>
                <input type="text" name="txtintareax" id="txtintareax2" value="<?php echo $txtintareax; ?>" maxlength="255" placeholder="X" class="form-control {number:true}" />
                <input type="text" name="txtintareay" id="txtintareay2" value="<?php echo $txtintareay; ?>" maxlength="255" placeholder="Y" class="form-control {number:true}" />
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <?php 
                if($this->m_checking->actions("Level","module6","Edit",TRUE,FALSE,"home"))
                {
                    echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Update</span></button>';
                }
                ?>                            
            </td>
        </tr>
    </table>
</form>