$(function() {
    $('.arrow').click(function(e) {
        if ($(this).closest('li').hasClass('showMenu')) {
            $(this).closest('li').removeClass('showMenu');
        } else {
            $(this).closest('li').addClass('showMenu');
        }
    });

    $('.sidebar')
      .on('mouseover', function () {
        $(this).removeClass('close');
      })
      .on('mouseout', function () {
        $(this).addClass('close');
      });
});