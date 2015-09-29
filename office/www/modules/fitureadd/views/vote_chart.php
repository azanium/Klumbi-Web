<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/plugin/Highcharts-2.3.3/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/plugin/Highcharts-2.3.3/js/modules/exporting.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#modal').modal('show');
    var chart;
    chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'column',
                margin: [ 50, 50, 100, 80]
            },
            title: {
                text: 'Result Statistic'
            },
            subtitle: {
                text: 'Option selected'
            },
            xAxis: {
                categories: [
                    <?php
                    foreach($listdata as $dt)
                    {
                        echo "'".$dt['question']."',";
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
                    text: 'Data Count'
                }
            },
            legend: {
                enabled: true,
//                layout: 'vertical',
//                backgroundColor: '#FFFFFF',
//                align: 'left',
//                verticalAlign: 'top',
//                x: 100,
//                y: 400,
//                floating: true,
//                shadow: true
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        'Count : '+ Highcharts.numberFormat(this.y, 0) +
                        ' hits';
                }
            },
            series: [{
                name: 'Option Questions',
                data: [
                    <?php
                    foreach($listdata as $dt)
                    {
                        echo $dt['count'].",";
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
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }],
            exporting: {
                enabled: true
            }
        });
});
</script>
<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Data Chart</h4>
            </div>
            <div class="modal-body">
                <div align="center" id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>        
    </div>
</div>