$(document).ready(function() {
    $('.popovers').popover({container: 'body', trigger: 'hover', placement: 'top'});
    $('.tooltips').tooltip();    
    prettyPrint();
    $('.toggle').toggles({on:true});
    /*
    $(".chathistory").niceScroll({horizrailenabled:false}); 
    try {
    $('.easypiechart#serverload').easyPieChart({
        barColor: "#e73c3c",
        trackColor: '#edeef0',
        scaleColor: 'transparent',
        scaleLength: 5,
        lineCap: 'round',
        lineWidth: 2,
        size: 90,
        onStep: function(from, to, percent) {
            $(this.el).find('.percent').text(Math.round(percent));
        }
    });
    $('.easypiechart#ramusage').easyPieChart({
        barColor: "#f39c12",
        trackColor: '#edeef0',
        scaleColor: 'transparent',
        scaleLength: 5,
        lineCap: 'round',
        lineWidth: 2,
        size: 90,
        onStep: function(from, to, percent) {
            $(this.el).find('.percent').text(Math.round(percent));
        }
    });
    }
    catch(error) {}
    $("#currentbalance").sparkline([12700,8573,10145,21077,15380,14399,19158,23911,15401,16793,13115,23315], {
    type: 'bar',
    barColor: '#62bc1f',
    height: '45',
    barWidth: 7});*/
});
/*$('.chatinput textarea').keypress(function (e) {
  if (e.which == 13) {
    var chatmsg = $(".chatinput textarea").val();
    var oo=$(".chathistory").html();
    var d=new Date();
    var n=d.toLocaleTimeString();
    if (!!$(".chatinput textarea").val())
        $(".chathistory").html(oo+ "<div class='chatmsg'><p>"+chatmsg+"</p><span class='timestamp'>"+n+"</span></div>");
    $(".chathistory").getNiceScroll().resize();
    $(".chathistory").animate({ scrollTop: $(document).height() }, 0);
    $(this).val('');
    return false;
  }
});
$("a#hidechatbtn").click(function () {
    $("#widgetarea").toggle();
    $("#chatarea").toggle();
});
$("#chatbar li a").click(function () {
    $("#widgetarea").toggle();
    $("#chatarea").toggle();
});
$('#slideitout').click(function() {
    $('#demo-theme-settings').toggleClass('shown');
    return false;
});*/
if($.cookie('fixed-header') === 'navbar-static-top') {
    $('#fixedheader').toggles();
} 
else 
{
    $('#fixedheader').toggles({on:true});
}
$('.dropdown-menu').on('click', function(e){
    if($(this).hasClass('dropdown-menu-form')){
        e.stopPropagation();
    }
});
/*$('#fixedheader').on('toggle', function (e, active) {
    $('header').toggleClass('navbar-fixed-top navbar-static-top');
    $('body').toggleClass('static-header');
    rightbarTopPos();
    if (active) {
        $.removeCookie('fixed-header');
    } else {
        $.cookie('fixed-header', 'navbar-static-top');
    }
});
$("#demo-color-variations a").click(function(){
    $("head link#styleswitcher").attr("href", $(this).data("theme"));
    $.cookie('theme',$(this).data("theme"));
    return false;
});
$("#demo-header-variations a").click(function(){
    $("head link#headerswitcher").attr("href", $(this).data("headertheme"));
    $.cookie('headertheme',$(this).data("headertheme"));
    return false;
});*/