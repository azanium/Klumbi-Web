<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/plugin/Highcharts-2.3.3/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/plugin/Highcharts-2.3.3/js/modules/exporting.js"></script>
<script type="text/javascript"> 
$(document).ready(function() { 
    $('#modal').modal('show');
    var chart;
    var options = {    
            chart: {
                renderTo: 'container',
                type: 'line',
                marginRight: 15,
                marginBottom: 15
            },    
            title: {
                text: 'Daily visits Player',
                x: -20
            },    
            subtitle: {
                text: 'Statistic data',
                x: -20
            },    
            xAxis: {
                tickWidth: 0,
                gridLineWidth: 1,
                labels: {
                    align: 'left',
                    x: 3,
                    y: -3
                },
                categories: [
                    <?php
                    if($isidata)
                    {
                        $hasil="";
                        foreach($isidata  as $dt=>$val)
                        {
                            $hasil .="'".$val['tgl']."',";
                        }
                        echo substr($hasil, 0, strlen($hasil)-1);
                    }
                    ?>
                ]
            },    
            yAxis: [{ 
                title: {
                    text: 'Sum of Visitors'
                },
                labels: {
                    align: 'left',
                    x: 3,
                    y: 16,
                    formatter: function() {
                        return Highcharts.numberFormat(this.value, 0);
                    }
                },
                showFirstLabel: false
            }, { 
                linkedTo: 0,
                gridLineWidth: 0,
                opposite: true,
                title: {
                    text: null
                },
                labels: {
                    align: 'right',
                    x: -3,
                    y: 16,
                    formatter: function() {
                        return Highcharts.numberFormat(this.value, 0);
                    }
                },
                showFirstLabel: false
            }],    
            legend: {
                align: 'left',
                verticalAlign: 'top',
                y: 20,
                floating: true,
                borderWidth: 0
            },    
            tooltip: {
                shared: true,
                crosshairs: true
            },    
            plotOptions: {
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function() {
                                hs.htmlExpand(null, {
                                    pageOrigin: {
                                        x: this.pageX,
                                        y: this.pageY
                                    },
                                    headingText: this.series.name,
                                    maincontentText: Highcharts.dateFormat('%A, %b %e, %Y', this.x) +':<br/> '+
                                        this.y +' visits',
                                    width: 700
                                });
                            }
                        }
                    },
                    marker: {
                        lineWidth: 1
                    }
                }
            },    
            series: [{
                name: 'Unique visitors',
                data: [
                    <?php
                    if($isidata)
                    {
                        $hasil="";
                        $index=0;
                        foreach($isidata  as $dt=>$val)
                        {
                            if($index==(count($isidata)-1))
                            {
                                $hasil .="{ y: ".$val['jml']."/*,marker: {symbol: 'url(".base_url()."resources/image/girl-beauty-consultant-showing-icon.png)'}*/ }".",";
                            }
                            else
                            {
                                $hasil .=$val['jml'].",";
                            } 
                            $index++;
                        }
                        echo substr($hasil, 0, strlen($hasil)-1);
                    }
                    ?>
                ],
                lineWidth: 4,
                marker: {
                    radius: 4
                }
            }, {
                name: 'Total visitors',
                data: [
                    <?php
                    if($isidata)
                    {
                        $hasil="";
                        $index=0;
                        foreach($isidata  as $dt=>$val)
                        {
                            if($index==0)
                            {
                                $hasil .="{ y: ".$val['totalpengunjung']."/*,marker: {symbol: 'url(".base_url()."resources/image/girl-idea-icon.png)'}*/ }".",";
                            }
                            else
                            {
                                $hasil .=$val['totalpengunjung'].",";
                            } 
                            $index++;
                        }
                        echo substr($hasil, 0, strlen($hasil)-1);
                    }
                    ?>
                ]
            }]
        };
        chart = new Highcharts.Chart(options);
});
</script>
<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Room</h4>
            </div>
            <div class="modal-body">
                <div align="center" id="container" style="min-width: 400px; height: 600px; margin: 0 auto"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>