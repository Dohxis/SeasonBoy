$( window ).resize(function() {
    var height = $('.tile').innerWidth();
    $('.tile').css({'height':height + 'px'});
});

$( window ).ready(function() {
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        $('.center-things').css('margin-left', '0px');
        $('.tile').css('width', '1%');
        $('.panel-body').css('padding', '0px');
    }
    var height = $('.tile').innerWidth();
    $('.tile').css({'height':height + 'px'});
});