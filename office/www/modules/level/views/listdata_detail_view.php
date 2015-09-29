<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var datatable;
$(document).ready(function(){
    $("#editbrandfrm").validate();
    datatable=$('#chiledatatable').dataTable({
            'bJQueryUI': true,
            'bDestroy': true,
            'bAutoWidth': false,
            'bRetrieve':true,
            'sEmptyTable': 'No Cases available for selection',
            "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "sSearch": ""
            },
            'aoColumns': [ { 'sClass' : 'alignRight' }, null, null, { 'sClass' : 'text-center','bSortable': false }]
        });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
    $('#txttag2').select2({width: "resolve", tags:<?php echo json_encode($this->tambahan_fungsi->list_tag()); ?>});
});
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#detaildata" data-toggle="tab"><i class="icon-th"></i> Detail Data</a></li>
                          <li><a href="#editdatadetail" data-toggle="tab"><i class="icon-pencil"></i> Edit Data</a></li>
                          <li><a href="#rendersetting" data-toggle="tab"><i class="icon-th-large"></i> Render Setting</a></li>
                        </ul>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="tab-content">
                        <div class="tab-pane active" id="detaildata">
                            <table id="chiledatatable" class="table table-striped table-bordered datatables" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="5%">Index</th>
                                        <th width="40%">Object Name</th>
                                        <th width="50%">Tag</th>
                                        <th width="5%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if($assets)
                                    {
                                        foreach($assets as $key => $val)
                                        {
                                            echo "<tr>";
                                            echo "<td>".$key."</td>";
                                            echo "<td>".$val['objectName']."</td>";
                                            echo "<td>".$val['tag']."</td>";
                                            $url=base_url()."level/removelist/".$_id."/".$key;
                                            $imgdelete=$this->template_icon->detail(TRUE,$url,'Delete',"delete.png","Are you sure want to delete level ".$val['objectName']." ?","linkdeletechild");
                                            echo "<td>".$imgdelete."</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="editdatadetail">
                            <?php
                                $this->load->view("form_data_level",$isiform); 
                            ?>
                        </div>
                        <div class="tab-pane" id="rendersetting">
                            <table width="100%">
                                <tr>
                                    <td width="28%"><label>Directori</label></td>
                                    <td width="2%">:</td>
                                    <td width="70%">&nbsp;<?php echo $directory; ?></td>
                                </tr>
                                <tr>
                                    <td><label for="fogActive">fogActive</label></td>
                                    <td>:</td>
                                    <td><input type="text" name="fogActive" value="<?php echo $fogActive; ?>" maxlength="255" placeholder="fogActive" class="inputbox lengkung_4 pading_5" /></td>
                                </tr>
                                <tr class="baground_0">
                                    <td><label for="fogColor">fogColor</label></td>
                                    <td>:</td>
                                    <td><input type="text" name="fogColor" value="<?php echo $fogColor; ?>" maxlength="255" placeholder="fogColor" class="inputbox lengkung_4 pading_5" /></td>
                                </tr>
                                <tr class="baground_1">
                                    <td><label for="fogDensity">fogDensity</label></td>
                                    <td>:</td>
                                    <td><input type="text" name="fogDensity" value="<?php echo $fogDensity; ?>" maxlength="255" placeholder="fogDensity" class="inputbox lengkung_4 pading_5" /></td>
                                </tr>
                                <tr>
                                    <td><label for="fogStartDistance">fogStartDistance</label></td>
                                    <td>:</td>
                                    <td><input type="text" name="fogStartDistance" value="<?php echo $fogStartDistance; ?>" maxlength="255" placeholder="fogStartDistance" class="inputbox lengkung_4 pading_5" /></td>
                                </tr>
                                <tr>
                                    <td><label for="fogEndDistance">fogEndDistance</label></td>
                                    <td>:</td>
                                    <td><input type="text" name="fogEndDistance" value="<?php echo $fogEndDistance; ?>" maxlength="255" placeholder="fogEndDistance" class="inputbox lengkung_4 pading_5" /></td>
                                </tr>
                                <tr>
                                    <td><label for="fogMode">fogMode</label></td>
                                    <td>:</td>
                                    <td><input type="text" name="fogMode" value="<?php echo $fogMode; ?>" maxlength="255" placeholder="fogMode" class="inputbox lengkung_4 pading_5" /></td>
                                </tr>
                                <tr>
                                    <td>Light Maps</td>
                                    <td>:</td>
                                    <td>
                                    <?php
                                    if(isset($lightmaps['lightmapsCount']))
                                    {
                                        echo "<table border='1'>";
                                        echo "<tr>";
                                        echo "<td>Index</td>";
                                        echo "<td>Near</td>";
                                        echo "<td>Far</td>";
                                        echo "<td>Index</td>";
                                        echo "<td>Near</td>";
                                        echo "<td>Far</td>";
                                        echo "</tr>";
                                        for($idx = 0; $idx < $lightmaps['lightmapsCount']; $idx = $idx + 2)
                                        {
                                            echo "<tr>";
                                            echo "<td>".$idx."</td>";
                                            echo "<td>".str_replace($directory . '/', '', $lightmaps['near_' . $idx])."</td>";
                                            echo "<td>".str_replace($directory . '/', '', $lightmaps['far_' . $idx])."</td>";
                                            $idx_ = $idx + 1;
                                            echo "<td>".$idx_."</td>";
                                            echo "<td>".str_replace($directory . '/', '', isset($lightmaps['near_' . $idx_])?$lightmaps['near_' . $idx_]:"")."</td>";
                                            echo "<td>".str_replace($directory . '/', '', isset($lightmaps['far_' . $idx_])?$lightmaps['far_' . $idx_]:"")."</td>";
                                            echo "</tr>";
                                        } 
                                        echo "</table>";
                                    }                            
                                    ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>