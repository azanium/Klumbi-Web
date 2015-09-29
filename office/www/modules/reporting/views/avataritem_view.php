<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
var chart;
$(document).ready(function() {
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
   dttable=$('#datatable').dataTable( {
       "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("reporting/avataritem/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
       'sEmptyTable': 'No Cases available for selection',
        "aoColumns": [ { "bSortable": false,"sClass" : "alignRight" }, {"bSortable": false}, {"bSortable": false}, {"bSortable": false}, {"bSortable": false}, {"bSortable": false}, {"bSortable": false}, { "sClass" : "alignRight","bSortable": false }, { "sClass" : "alignRight","bSortable": false }]
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
    chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'column',
                margin: [ 50, 50, 100, 80]
            },
            title: {
                text: 'Avatar Items Love'
            },
            subtitle: {
                text: 'Chart View.'
            },
            xAxis: {
                categories: [
                    <?php
                        if($listconfig)
                        {
                            foreach($listconfig as $dt)
                            {
                                $childname=(!isset($dt['name'])?"":$dt['name']);
                                echo "'".$childname."',";
                            }
                        }
                        else
                        {
                            echo "'No Label'";
                        }
                    ?>
                ],
                labels: {
                    rotation: -45,
                    align: 'right',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Count Love'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        'Count: '+ Highcharts.numberFormat(this.y, 0) +
                        ' Love';
                }
            },
            series: [{
                name: 'Avataritem',
                data: [
                    <?php
                        if($listconfig)
                        {
                            $datanilai="";
                            foreach($listconfig as $dt)
                            {
                                $this->mongo_db->select_db("Social");
                                $this->mongo_db->select_collection("Social");
                                $datanilai .= $this->mongo_db->count2(array('avatar_id'=>(string)$dt['_id'],'type'=>'AvatarItemLove')).", ";
                                //$datanilai .= (int)rand(1, 5000).", ";
                            }
                            echo $datanilai;
                        }
                        else
                        {
                            echo "0";
                        }
                    ?>
                ],                
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: 4,
                    y: 10,
                    style: {
                        fontSize: '8px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
        });
});
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Avatar Item Report</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div id="accordioninpanel" class="accordion-group">
                        <div class="accordion-item">
                            <a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapsekomment"><h4>Chart Loves</h4></a>
                            <div id="collapsekomment" class="collapse">
                                <div class="accordion-body">
                                    <div id="container" style="min-width: 400px; height: 400px;width: 75%;">&nbsp;</div>
                                    <div class="text-center panel-footer">
                                        <?php echo $paging; ?>
                                        <div class="text-info">Count Data <strong><?php echo $count; ?></strong></div>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapsetable"><h4>Tables</h4></a>
                            <div id="collapsetable" class="in">
                                <div class="accordion-body">
                                    <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="15%">Name</th>
                                                <th width="15%">Type</th>
                                                <th width="10%">Gender</th>
                                                <th width="15%">Category</th>
                                                <th width="10%">Payment</th>
                                                <th width="10%">Brand</th>
                                                <th width="10%">Love</th>
                                                <th width="10%">Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="9">No Data</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>