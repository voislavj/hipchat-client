$(function(){
    layout();
    $(window).resize(layout);
});

function layout() {
    var H = $(window).height();
    var header  = $('header');
    var footer  = $('footer');
    var history = $('.history');
    var users   = $('.users');

    var height = H - header.outerHeight() - footer.outerHeight();
    history.height(height);
    users.height(height);

    if (history[0].scrollHeight>0) {
        history.scrollTop(history[0].scrollHeight);
    }
}