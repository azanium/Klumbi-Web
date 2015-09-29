<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">                        
            <div class="panel panel-primary">
            <?php 
            $this->mongo_db->select_db("Articles");
            $this->mongo_db->select_collection("ContentPage");
            $temp = $this->mongo_db->findOne(array('alias' => "welcome",'state_document'=>'publish'));
            if($temp)
            {
                echo "<div class='panel-heading'><h4>".$temp['title']."</h4></div>";
                echo "<div class='panel-body'><div class='well well-lg'>".$temp['text']."</div></div>";
            }
            else
            {
                $generatetime=date("Y-m-d H:i:s");
                $time_start=  strtotime($generatetime);
                $datainsertredim = array(
                    'title'  =>"Welcome Page",
                    'text'  =>'Welcome on Klumbi',
                    'alias'  =>"welcome",                
                    'document_update'=>$this->mongo_db->time($time_start),
                    'state_document'  =>'publish',
                    'document_write'=>$this->mongo_db->time($time_start),
                );
                $this->mongo_db->update(array('alias'=> (string)"welcome"),array('$set'=>$datainsertredim),array('upsert' => TRUE));
                echo "<div class='panel-heading'><h4>Welcome on Klumbi</h4></div>";
                echo "<div class='panel-body'><div class='well well-lg'>Content Welcome</div></div>";
            }
            ?>                   
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4>User Bounce</h4>
                </div>
                <div class="panel-body text-center">
                    <canvas id="line-chart" height="300" width="700"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="panel panel-success">
                <div class='panel-heading'><h4>User Statistic</h4></div>
                <div class='panel-body text-center'>
                    <canvas id="pie-chart" style="width:100%; height: 300px"></canvas>
                    <p><span style="color: #F38630;">Male</span>, <span style="color: #0063DC;">Female</span>, <span style="color: #18a76c;">Unknown</span></p>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-8">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h4>Store Sell</h4>
              </div>
              <div class="panel-body">
                <div id="interactive" style="width:100%; height: 300px" class="centered"></div>
              </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-xs-12 col-sm-6">
            <a class="info-tiles tiles-brown" href="#">
                <div class="tiles-heading">Profit</div>
                <div class="tiles-body-alt">
                    <div class="text-center"><span class="text-top">$</span>854</div>
                    <small>+8.7% from last period</small>
                </div>
                <div class="tiles-footer">more info</div>
            </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
            <a class="info-tiles tiles-grape" href="#">
                <div class="tiles-heading">Revenue</div>
                <div class="tiles-body-alt">
                    <div class="text-center"><span class="text-top">$</span>22.7<span class="text-smallcaps">k</span></div>
                    <small>-13.5% from last week</small>
                </div>
                <div class="tiles-footer">go to accounts</div>
            </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
            <a class="info-tiles tiles-success" href="#">
                <div class="tiles-heading">Members</div>
                <div class="tiles-body-alt">
                    <i class="icon-group"></i>
                    <div class="text-center">109</div>
                    <small>new users registered</small>
                </div>
                <div class="tiles-footer">manage members</div>
            </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
            <a class="info-tiles tiles-midnightblue" href="#">
                <div class="tiles-heading">Orders</div>
                <div class="tiles-body-alt">
                    <i class="icon-shopping-cart"></i>
                    <div class="text-center">57</div>
                    <small>new orders received</small>
                </div>
                <div class="tiles-footer">manage orders</div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <h4 class="pull-left" style="margin: 0 0 20px;">User Report <small>(weekly)</small></h4>
                            <div class="btn-group pull-right">
                                <a href="javascript:;" class="btn btn-default btn-xs active">this week</a>
                                <a href="javascript:;" class="btn btn-default btn-xs ">previous week</a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="site-statistics" style="height:250px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <h4 class="pull-left" style="margin: 0 0 20px;">Annual Sales <small>(by quarter)</small></h4>
                            <div class="btn-group pull-right">
                                <a href="javascript:;" class="btn btn-default btn-xs active">2012</a>
                                <a href="javascript:;" class="btn btn-default btn-xs ">2011</a>
                                <a href="javascript:;" class="btn btn-default btn-xs ">2010</a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="budget-variance" style="height:250px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-orange">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <h4 class="pull-left" style="margin: 0 0 20px;">Server Load <small>(%)</small></h4>
                            <div class="btn-group pull-right">
                                <a href="javascript:;" class="btn btn-default btn-xs active">Server #1</a>
                                <a href="javascript:;" class="btn btn-default btn-xs ">Server #2</a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="server-load" style="height:102px"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-success">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <h4 class="pull-left" style="margin:0 0 10px">Site Statistics</h4>
                            <div class="btn-group pull-right">
                                <a href="javascript:;" id="updatePieCharts" class="btn btn-default-alt btn-xs"><i style="color:#808080;" class="icon-refresh"></i></a>
                                <a href="javascript:;" class="btn btn-default-alt btn-xs"><i style="color:#808080;" class="icon-wrench"></i></a>
                                <a href="javascript:;" class="btn btn-default-alt btn-xs"><i style="color:#808080;" class="icon-cog"></i></a>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <div class="easypiechart" id="newvisits" data-percent="65">
                                <span class="percent"></span>
                            </div>
                            <label for="newvisits">New Visits</label>
                            <hr class="visible-sm visible-xs" />
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <span class="easypiechart" id="bouncerate" data-percent="81">
                                <span class="percent"></span>
                            </span>
                            <label for="bouncerate">Bounce Rate</label>
                            <hr class="visible-sm visible-xs" />
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <span class="easypiechart" id="clickrate" data-percent="42">
                                <span class="percent"></span>
                            </span>
                            <label for="clickrate">Click Rate</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-3 col-sm-6 col-lg-3">
            <a class="info-tiles tiles-midnightblue" href="#">
                <div class="tiles-heading">
                    <div class="pull-left">Comments</div>
                    <div class="pull-right">+15.6%</div>
                </div>
                <div class="tiles-body">
                    <div class="pull-left"><i class="icon-comments-alt"></i></div>
                    <div class="pull-right">35</div>
                </div>
            </a>
        </div>
        <div class="col-xs-12 col-md-3 col-sm-6 col-lg-3">
            <a class="info-tiles tiles-success" href="#">
                <div class="tiles-heading">
                    <div class="pull-left">Likes</div>
                    <div class="pull-right">-5.5%</div>
                </div>
                <div class="tiles-body">
                    <div class="pull-left"><i class="icon-thumbs-up-alt"></i></div>
                    <div class="pull-right">318</div>
                </div>
            </a>
        </div>
        <div class="col-xs-12 col-md-3 col-sm-6 col-lg-3">
            <a class="info-tiles tiles-grape" href="#">
                <div class="tiles-heading">
                    <div class="pull-left">Bugs Fixed</div>
                    <div class="pull-right">+14.9%</div>
                </div>
                <div class="tiles-body">
                    <div class="pull-left"><i class="icon-check-sign"></i></div>
                    <div class="pull-right">21</div>
                </div>
            </a>
        </div>
        <div class="col-xs-12 col-md-3 col-sm-6 col-lg-3">
            <a class="info-tiles tiles-brown" href="#">
                <div class="tiles-heading">
                    <div class="pull-left">Downloads</div>
                    <div class="pull-right">-9.8%</div>
                </div>
                <div class="tiles-body">
                    <div class="pull-left"><i class="icon-download-alt"></i></div>
                    <div class="pull-right">128</div>
                </div>
            </a>
        </div>
    </div>
</div>
<script type="text/javascript">
<?php
$this->mongo_db->select_db("Users");
$this->mongo_db->select_collection("Properties");
$male = $this->mongo_db->count2(array('sex'=>'male'));
$female = $this->mongo_db->count2(array('sex'=>'female'));
$alldata = $this->mongo_db->count2(array());
?>
$(document).ready(function(){
var pieData = [
        {
            value: <?php echo (int)$male; ?>,
            color:"#F38630"
        },{
            value : <?php echo (int)$female; ?>,
            color : "#0063DC"
        },{
            value : <?php echo (int)($alldata-$female-$male); ?>,
            color : "#18a76c"
        }
    ];
    var myPie = new Chart(document.getElementById("pie-chart").getContext("2d")).Pie(pieData);
    var data = [
        { label: "Series1",  data: 10},
        { label: "Series2",  data: 30},
        { label: "Series3",  data: 90},
        { label: "Series4",  data: 70},
        { label: "Series5",  data: 80},
        { label: "Series6",  data: 110}
    ];
    $.plot($("#interactive"), data,
            {
                series: {
                        pie: {
                                show: true
                        }
                },
                grid: {
                        hoverable: true,
                        clickable: true
                },
                legend: {
                    show: false
                }
            });
    $("#interactive").bind("plothover", pieHover);
    function pieHover(event, pos, obj)
    {
            if (!obj)
                    return;
            percent = parseFloat(obj.series.percent).toFixed(2);
            $("#hover").html('<span style="font-weight: bold; color: '+obj.series.color+'">'+obj.series.label+' ('+percent+'%)</span>');
    }
    var lineChartData = {
        labels : ["January","February","March","April","May","June","July"],
        datasets : [
            {
                fillColor : "rgba(220,220,220,0.5)",
                strokeColor : "rgba(220,220,220,1)",
                pointColor : "rgba(220,220,220,1)",
                pointStrokeColor : "#fff",
                data : [65,59,90,81,56,55,40]
            },
            {
                fillColor : "rgba(151,187,205,0.5)",
                strokeColor : "rgba(151,187,205,1)",
                pointColor : "rgba(151,187,205,1)",
                pointStrokeColor : "#fff",
                data : [28,48,40,19,96,27,100]
            }
        ]
    }
    var myLine = new Chart(document.getElementById("line-chart").getContext("2d")).Line(lineChartData);
    try {
    $('.easypiechart#newvisits').easyPieChart({
        barColor: "#85c744",
        trackColor: '#edeef0',
        scaleColor: 'transparent',
        scaleLength: 5,
        lineCap: 'square',
        lineWidth: 2,
        size: 90,
        onStep: function(from, to, percent) {
            $(this.el).find('.percent').text(Math.round(percent));
        }
    });
    $('.easypiechart#bouncerate').easyPieChart({
        barColor: "#f39c12",
        trackColor: '#edeef0',
        scaleColor: 'transparent',
        scaleLength: 5,
        lineCap: 'square',
        lineWidth: 2,
        size: 90,
        onStep: function(from, to, percent) {
            $(this.el).find('.percent').text(Math.round(percent));
        }
    });
    $('.easypiechart#clickrate').easyPieChart({
        barColor: "#e73c3c",
        trackColor: '#edeef0',
        scaleColor: 'transparent',
        scaleLength: 5,
        lineCap: 'square',
        lineWidth: 2,
        size: 90,
        onStep: function(from, to, percent) {
            $(this.el).find('.percent').text(Math.round(percent));
        }
    });
    $('#updatePieCharts').on('click', function() {
        $('.easypiechart#newvisits').data('easyPieChart').update(Math.random()*100);
        $('.easypiechart#bouncerate').data('easyPieChart').update(Math.random()*100);
        $('.easypiechart#clickrate').data('easyPieChart').update(Math.random()*100);
        return false;
    });
    }
    catch(error) {}
    function randValue() {
        return (Math.floor(Math.random() * (2)));
    }
    var viewcount = [
        [1, 787 + randValue()],
        [2, 740 + randValue()],
        [3, 560 + randValue()],
        [4, 860 + randValue()],
        [5, 750 + randValue()],
        [6, 910 + randValue()],
        [7, 730 + randValue()]
    ];
    var uniqueviews = [
        [1, 179 + randValue()],
        [2, 320 + randValue()],
        [3, 120 + randValue()],
        [4, 400 + randValue()],
        [5, 573 + randValue()],
        [6, 255 + randValue()],
        [7, 366 + randValue()]
    ];
    var usercount = [
        [1, 70 + randValue()],
        [2, 260 + randValue()],
        [3, 30 + randValue()],
        [4, 147 + randValue()],
        [5, 333 + randValue()],
        [6, 155 + randValue()],
        [7, 166 + randValue()]
    ];
    var plot_statistics = $.plot($("#site-statistics"), [{
        data: viewcount,
        label: "View Count"
    }, {
        data: uniqueviews,
        label: "Unique Views"
    }, {
        data: usercount,
        label: "User Count"
    }], {
        series: {
            lines: {
                show: true,
                lineWidth: 1.5,
                fill: false
            },
            points: {
                show: true
            },
            shadowSize: 0
        },
        grid: {
            hoverable: true,
            clickable: true,
            borderWidth: 0
        },
        colors: ["#2bbce0", "#458adf", "#8e44ad"],
        xaxis: {
            tickColor: "transparent",
            ticks: [[1, "S"], [2, "M"], [3, "T"], [4, "W"], [5, "T"], [6, "F"], [7, "S"]],
            tickDecimals: 0,
            autoscaleMargin: 0,
            font: {
                family: "'Source Sans Pro',Arial",
                color: '#8c8c8c',
                size: 12
            }
        },
        yaxis: {
            ticks: 4,
            tickDecimals: 0,
            tickColor: "#e3e4e6",
            font: {
                color: '#8c8c8c',
                size: 12
            },
            tickFormatter: function (val, axis) {
                if (val>999) {return (val/1000) + "K";} else {return val;}
            }
        },
        legend : {
            labelBoxBorderColor: 'transparent'
        }
    });
    var d1 = [];
    var d2 = [];
    for (var i = 1; i < 5; i++) {
        d1.push([i, parseInt(Math.random() * 99)]);
        d2.push([i, parseInt(Math.random() * 99)]);
    } 
    var ds = new Array();
    ds.push({
    data:d1,
    label: "Budget",
    bars: {
        show: true,
        barWidth: 0.2,
        order: 1
    }
    });
    ds.push({
        data:d2,
        label: "Actual",
        bars: {
            show: true,
            barWidth: 0.2,
            order: 2
        }
    });
    var variance = $.plot($("#budget-variance"), ds, {
        series: {
            bars: {
                show: true,
                fill: 1
            }
        },
        grid: {
            hoverable: true,
            clickable: true,
            tickColor: "#e6e7e8",
            borderWidth: 0
        },
        colors: ["#9f9f9f", "#4f5259"],
        xaxis: {
            autoscaleMargin: 0.05,
            tickColor: "transparent",
            ticks: [[1, "Q1"], [2, "Q2"], [3, "Q3"], [4, "Q4"]],
            tickDecimals: 0,
            font: {
                color: '#8c8c8c',
                size: 12
            }
        },
        yaxis: {
            ticks: [0, 25, 50, 75, 100],
            font: {
                color: '#8c8c8c',
                size: 12
            },
            tickFormatter: function (val, axis) {
                return "$" + val + "K";
            }
        },
        legend : {
            labelBoxBorderColor: 'transparent'
        }
    });
    var previousPoint = null;
        $("#site-statistics,#budget-variance").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));
        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;

                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);
                showTooltip(item.pageX, item.pageY, item.series.label + ": " + Math.round(y));
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
    var previousPointBar = null;
        $("#budget-variance").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));
        if (item) {
            if (previousPointBar != item.dataIndex) {
                previousPointBar = item.dataIndex;
                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);

                showTooltip(item.pageX+30, item.pageY, item.series.label + ": $" + Math.round(y)+"K");

            }
        } else {
            $("#tooltip").remove();
            previousPointBar = null;
        }
    });
    function showTooltip(x, y, contents) {
        $('<div id="tooltip" class="tooltip top in"><div class="tooltip-inner">' + contents + '<\/div><\/div>').css({
            display: 'none',
            top: y - 40,
            left: x - 55
        }).appendTo("body").fadeIn(200);
    }
    var container = $("#server-load");
    var maximum = container.outerWidth() / 2 || 300;
    var data = [];

    function getRandomData() {

        if (data.length) {
            data = data.slice(1);
        }

        while (data.length < maximum) {
            var previous = data.length ? data[data.length - 1] : 50;
            var y = previous + Math.random() * 10 - 5;
            data.push(y < 0 ? 0 : y > 100 ? 100 : y);
        }
        var res = [];
        for (var i = 0; i < data.length; ++i) {
            res.push([i, data[i]])
        }
        return res;
    }
    series = [{
        data: getRandomData()
    }];
    var plot = $.plot(container, series, {
        series: {
            lines: {
                show: true,
                lineWidth: 1.5,
                fill: 0.15
            },
            shadowSize: 0
        },
        grid: {
            
            labelMargin: 10,
            tickColor: "#e3e4e6",
            borderWidth: 0
        },
        colors: ["#f39e17"],
        xaxis: {
            tickFormatter: function() {
                return "";
            },
            tickColor: "transparent"
        },
        yaxis: {
            min: 0,
            max: 100,
            font: {
                color: '#8c8c8c',
                size: 12
            }
        },
        legend: {
            show: true
        }
    });
    setInterval(function updateRandom() {
        series[0].data = getRandomData();
        plot.setData(series);
        plot.draw();
    }, 40);
});
</script>