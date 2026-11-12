$(function () {
    /*url ajax*/
    var action = $('link[rel="base"]').attr('href') + "/_cdn/widgets/filter/filter.ajax.php";

    /*filter access*/
    $('html, body').on('click', '.j_filter_access', function () {
        if ($('.workcontrol_filter').css('left') !== '-230px') {
            $('.workcontrol_filter').animate({left: '-230px'}, 300);
        } else {
            $('.workcontrol_filter').animate({left: '0'}, 300);
        }

        $.post(action, {history: $(this).attr('data-history'), action: 'filter_access'});

        return false;
    });

    /*label check*/
    $('html').on('click', '.j_filter_check', function () {
        if (!$(this).find('input').is(':checked')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    });

    /*slider jQuery UI*/
    (function () {
        var pdtPrice = $('form[name="workcontrol_filter"]').find('input[name="pdt_price"]');
        var pdtMinPrice = parseInt(pdtPrice.attr('data-min'));
        var pdtMaxPrice = parseInt(pdtPrice.attr('data-max'));

        var pdtStepPrice = parseInt(pdtPrice.attr('data-step'));
        var pdtRangePrice = parseInt(pdtPrice.attr('data-range'));

        $("#slider_price").slider({
            range: true,
            min: pdtMinPrice,
            max: pdtMaxPrice,
            values: [pdtStepPrice, pdtRangePrice],
            slide: function (event, ui) {
                $("#amount").val("R$ " + ui.values[0] + " - R$ " + ui.values[1]);
            },
            change: function (event, ui) {
                pdtPrice.val(ui.values[0] + ',' + ui.values[1]);
                $('form[name="workcontrol_filter"]').trigger('change');
            }
        });

        if (pdtMinPrice === pdtMaxPrice) {
            $("#slider_price").slider("option", "disabled", true);
        }

        $("#amount").val("R$ " + $("#slider_price").slider("values", 0) + " - R$ " + $("#slider_price").slider("values", 1));
    })();
    /*clear | desmarca todas as categorias e reaplica o filtro (vazio)*/
    $('html').on('click', '.j_filter_clear', function () {
        var form = $(this).closest('form[name="workcontrol_filter"]');
        form.find('input[name="pdt_department[]"]').prop('checked', false);
        form.find('.j_filter_check').removeClass('active');
        form.trigger('change');
    });

    /*change form | grava o filtro e atualiza só a lista de produtos via ajax, sem recarregar a página*/
    var filterXhr = null;
    $('html').on('change', 'form[name="workcontrol_filter"]', function () {
        var products = $('section.products').first();

        // um novo clique cancela a requisição anterior; o form já vai com todas as marcações
        if (filterXhr) {
            filterXhr.abort();
        }

        products.addClass('workcontrol_filter_loading');

        filterXhr = $.post(action, $(this).serialize(), function (data) {
            var url = BASE + '/' + data.redirect;

            filterXhr = $.get(url, function (html) {
                var page = $('<div>').append($.parseHTML(html));
                var newProducts = page.find('section.products').first();
                var newDepartment = page.find('.workcontrol_filter_form .department').first();

                if (!newProducts.length) {
                    window.location.href = url;
                    return;
                }

                products.html(newProducts.html()).removeClass('workcontrol_filter_loading');
                if (newDepartment.length) {
                    $('.workcontrol_filter_form .department').first().replaceWith(newDepartment);
                }

                // volta para a página 1 da categoria na barra de endereço (o filtro sempre começa nela)
                if (window.history && window.history.replaceState && window.location.href !== url) {
                    window.history.replaceState(null, '', url);
                }

                // só rola se o topo da lista ficou fora da tela
                if (products.offset().top < $(window).scrollTop()) {
                    $('html, body').animate({scrollTop: products.offset().top - 20}, 300);
                }
            }).fail(function (xhr, status) {
                if (status !== 'abort') {
                    window.location.href = url;
                }
            });
        }, 'json').fail(function (xhr, status) {
            if (status !== 'abort') {
                products.removeClass('workcontrol_filter_loading');
            }
        });
    });
});
