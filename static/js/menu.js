$(document).ready(function () {
    $('header.mobile > .top .menu').click(function (evt) {
        evt.preventDefault();

        $(this).toggleClass('selected').parent().toggleClass('selected');
        var height = $(document).height()

        var menu = $('header.mobile > .bottom');
        menu.toggleClass('open');
        if (menu.hasClass('open')) {
            menu.css('height', height);
        } else {
            menu.css('height', '');
        }
    });

    $('.mobile > .bottom .dropdown').click(function (evt) {
        evt.preventDefault();

        var list = $(this).siblings('.dropdown-list').eq(0);
        var lists = $('.mobile > .bottom .dropdown-list');
        lists.css('height', 0);

        list.toggleClass('open');
        var height = 0;

        if (list.hasClass('open')) {
            list.children('li').each(function (i) {
                var element = $(this);
                height += element.height();
            });
        }
        list.css('height', height);
    });
});