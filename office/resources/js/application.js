$(function () {
if($.cookie('admin_leftbar_collapse') === 'collapse-leftbar') 
{
    $('body').addClass('collapse-leftbar');
} 
else 
{
    $('body').removeClass('collapse-leftbar');
}
$('body').on('click', 'ul.acc-menu a', function() {
    var LIs = $(this).closest('ul.acc-menu').children('li');
    $(this).closest('li').addClass('clicked');
    $.each( LIs, function(i){
        if( $(LIs[i]).hasClass('clicked') ) 
        {
            $(LIs[i]).removeClass('clicked');
            return true;
        }
        if($.cookie('admin_leftbar_collapse') !== 'collapse-leftbar' || $(this).parents('.acc-menu').length > 1) $(LIs[i]).find('ul.acc-menu:visible').slideToggle();
        $(LIs[i]).removeClass('open');
    });
    if($(this).siblings('ul.acc-menu:visible').length>0)
        $(this).closest('li').removeClass('open');
    else
        $(this).closest('li').addClass('open');
        if($.cookie('admin_leftbar_collapse') !== 'collapse-leftbar' || $(this).parents('.acc-menu').length > 1) $(this).siblings('ul.acc-menu').slideToggle({
            duration: 200,
            progress: function(){
                checkpageheight();
            }
        });
});
var targetAnchor;
$.each ($('ul.acc-menu a'), function() {
    if( this.href == window.location ) {
        targetAnchor = this;
        return false;
    }
});
var parent = $(targetAnchor).closest('li');
while(true) {
    parent.addClass('active');
    parent.closest('ul.acc-menu').show().closest('li').addClass('open');
    parent = $(parent).parents('li').eq(0);
    if( $(parent).parents('ul.acc-menu').length <= 0 ) break;
}
var liHasUlChild = $('li').filter(function(){
    return $(this).find('ul.acc-menu').length;
});
$(liHasUlChild).addClass('hasChild');
if($.cookie('admin_leftbar_collapse') === 'collapse-leftbar') {
    $('ul.acc-menu:first ul.acc-menu').css('visibility', 'hidden');
}
$('ul.acc-menu:first > li').hover(function() {
    if($.cookie('admin_leftbar_collapse') === 'collapse-leftbar')
        $(this).find('ul.acc-menu').css('visibility', '');
}, function() {
    if($.cookie('admin_leftbar_collapse') === 'collapse-leftbar')
        $(this).find('ul.acc-menu').css('visibility', 'hidden');
});
$("#widgetarea").css({"max-height":$("body").height()});
$("#widgetarea").niceScroll({horizrailenabled:false});
ww = $(window).width();
$(window).resize(function() {
    widgetheight();
    ww = $(window).width();

    if (ww<786) {
        $("body").removeClass("collapse-leftbar");
        $.removeCookie("admin_leftbar_collapse");
    } else {
        $("body").removeClass("show-leftbar");
    }
});
$("a#leftmenu-trigger").click(function () {
    if (($(window).width())<786) {
        $("body").toggleClass("show-leftbar");
    } 
    else 
    {
        $("body").toggleClass("collapse-leftbar");
        if($.cookie('admin_leftbar_collapse') === 'collapse-leftbar') {
            $.cookie('admin_leftbar_collapse', '');
            $('ul.acc-menu').css('visibility', '');
        }
        else {
            $.each($('.acc-menu'), function() {
                if($(this).css('display') == 'none')
                    $(this).css('display', '');
            });                
            $('ul.acc-menu:first ul.acc-menu').css('visibility', 'hidden');
            $.cookie('admin_leftbar_collapse', 'collapse-leftbar');
        }
    }
});
$("a#rightmenu-trigger").click(function () {
    $("body").toggleClass("show-rightbar");
    widgetheight();        
    if($.cookie('admin_rightbar_show') === 'show-rightbar')
    {
        $.cookie('admin_rightbar_show', '');
    }
    else
    {
        $.cookie('admin_rightbar_show', 'show-rightbar');
    }                
});
checkpageheight();
});
$(".widget-body").on('shown.bs.collapse', function () {
    widgetheight();
});
function checkpageheight() {
    sh=$("#page-leftbar").height();
    ch=$("#page-content").height();
    if (sh>ch)
    {
        $("#page-content").css("min-height",sh+"px");
    }        
}
function widgetheight() {
    $("#widgetarea").css({"max-height":$("body").height()});
    $("#widgetarea").getNiceScroll().resize();
}
$(window).scroll(function(){
    $("#widgetarea").getNiceScroll().resize();
    $(".chathistory").getNiceScroll().resize();
    rightbarTopPos();
});
$(window).resize(function(){
    rightbarRightPos();
});
rightbarRightPos();
function rightbarTopPos() 
{
    var scr=$('body.static-header').scrollTop();    
    if (scr<41) 
    {
        $('#page-rightbar').css('top',40-scr + 'px');
    } 
    else 
    {
        $('#page-rightbar').css('top',0);
    }
}
function rightbarRightPos () {
    if ($('body').hasClass('fixed-layout')) {
        var $pc = $('#page-content');
        var ending_right = ($(window).width() - ($pc.offset().left + $pc.outerWidth()));
        if (ending_right<0) ending_right=0;
        $('#page-rightbar').css('right',ending_right);
    }
}
try {
    enquire.register("screen and (max-width: 768px)", {
        match : function() {
            $(function() {
                $("body").swipe( {
                    swipe:function(event, direction, distance, duration, fingerCount) {
                        if (direction=="right")
                            $("body").addClass("show-leftbar");
                        if (direction=="left")
                            $("body").removeClass("show-leftbar");
                    }
                });
                $('ul ul.acc-menu').css('visibility', '');
            });
        }
    });
}
catch (e) {}
$('#headerbardropdown').click(function() {    
    $('#headerbar').css('top',0);
    return false;
});
$('#headerbardropdown').click(function(event) {
  $('html').one('click',function() {
    $('#headerbar').css('top','-1000px');
  });
  event.stopPropagation();
});
$('#search>a').click(function () {
    $('#search').toggleClass('keep-open');
    $('#search>a i').toggleClass("opacity-control");
});
$('#search').click(function(event) {
  $('html').one('click',function() {
    $('#search').removeClass('keep-open');
    $('#search>a i').addClass("opacity-control");
  });
  event.stopPropagation();
});
$(".panel-footer").prev().css("border-radius","0");