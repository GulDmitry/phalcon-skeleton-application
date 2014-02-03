$(function() {
    $('[data-toggle=offcanvas]').click(function() {
        $('.row-offcanvas').toggleClass('active');
    });

    $('#languages').on('click', 'a', function(event) {
        $.cookie('lang', $(event.target).data('key'));
        location.reload();
    });
});
