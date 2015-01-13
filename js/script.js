$(function(){
    layout();
    $(window).resize(layout);

    loadUsers();
    loadHistory();
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
}

function loadHistory() {
    var history = $('.history');
    get('history', function(){
        history.addClass('loading');
    }, function(json, status){
        history.removeClass('loading');
        if (json) {
            for(var i=0; i<json.length; i++) {
                historyItem(json[i]).appendTo(history);
            }
        }

        if (history[0].scrollHeight>0) {
            history.scrollTop(history[0].scrollHeight);
        }
    })
}

function loadUsers() {
    var users = $('.users');
    get('users', function(){
        users.addClass('loading');
    }, function(json, status){
        users.removeClass('loading');
        if (json) {
            for(var i=0; i<json.length; i++) {
                userItem(json[i]).appendTo(users);
            }
        }
    });
}

function userItem(user) {
    return $('<li>')
        .val(user.id)
        .attr('class', user.presence)
        .html(user.name)
}

function historyItem(item) {
    var div = $('<div class="item">');

    $('<div class="date">')
        .append(
            $('<span class"date">')
                .html(item.date)
        )
        .append(
            $('<span class="time">')
                .html(item.time)
        )
        .appendTo(div);

    $('<strong class="from">')
        .html(item.from)
        .appendTo(div)

    $('<div class="message">')
        .html(item.message)
        .appendTo(div);

    return div;
}

function get(url, before, complete) {
    $.ajax({
        url: url,
        beforeSend: before,
        complete: function(req) {
            if (req.status == 200) {
                data = JSON.parse(req.responseText);
                complete(data);
            } else {
                complete(false, {code: req.status, text: req.statusText});
            }
        }
    })
}