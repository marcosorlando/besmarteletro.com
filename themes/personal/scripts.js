var typed = new Typed('.multiple-text', {
    strings: tags,
    typedSpeed: 50,
    backSpeed: 100,
    backDelay: 1000,
    loop: true
});

$(function () {
    $('[name="form_signature"]').submit(function () {
        var formdata = $('[name="form_signature"]').serialize();
        var Link = $(location).attr('href') + 'themes/new/_ajax/Feed.ajax.php';

        $.ajax({
            dataType: 'json',
            type: 'post',
            url: Link,
            data: formdata,
            success: function (results) {
                $('div.callback_return').html(results.trigger);
            }
        }); // end ajax
    });
});

$('#btn-about').click(function () {
    let linkId = $(this).attr('href');

    if ($(linkId).is(':visible')) {
        $(linkId).show().slideUp('low');
        $(this).text('+ Leia Mais').removeClass('wc_goto');
    } else {
        $(linkId).show().slideDown('low');
        $(this).text('- Leia Menos').addClass('wc_goto');

        $("a.wc_goto [href='#about']").click();



    }


});
