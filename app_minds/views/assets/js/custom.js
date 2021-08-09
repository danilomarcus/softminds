/* CUSTOM JS */

$(function () {

    /* jQuery MASK */
    $(".mask-money").mask('000.000.000.000.000,00', {reverse: true, placeholder: "0,00"});
    $(".mask-date").mask('00/00/0000', {reverse: true});
    $(".mask-month").mask('00/0000', {reverse: true});
    $(".mask-cpf").mask('000.000.000-00', {reverse: true});
    $(".mask-cnpj").mask('00.000.000/0000-00', {reverse: true});

    /* ajax form */
    $("form:not('.ajax_off')").submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var load = $(".ajax_load");
        var flashClass = "ajax_response";
        var flash = $("." + flashClass);

        form.ajaxSubmit({
            url: form.attr("action"),
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            success: function (response) {

                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    load.fadeOut(200);
                }

                //message
                if (response.message) {
                    if (flash.length) {
                        flash.html(response.message).fadeIn(100).effect("bounce", 300);
                    } else {
                        form.prepend("<div class='" + flashClass + "'>" + response.message + "</div>")
                            .find("." + flashClass).effect("bounce", 300);
                    }
                } else {
                    flash.fadeOut(100);
                }
            },
            complete: function () {

                if (form.data("reset") === true) {
                    form.trigger("reset");
                }
            },
            error: function () {
                var message = "<div class='alert alert-danger'>Desculpe mas não foi possível processar a requisição. Favor tente novamente!</div>";
                if (flash.length) {
                    flash.html(message).fadeIn(100).effect("bounce", 300);
                } else {
                    form.prepend("<div class='" + flashClass + "'>" + message + "</div>")
                        .find("." + flashClass).effect("bounce", 300);
                }
                load.fadeOut(200);
            }
        });
    });

    /**
     * funcao para redirecionar para a remocao de um registro
     */
    $(".deleteRegister").on('click', function () {
        if (window.confirm('Tem certeza? Esta ação não poderá ser desfeita. você quer continuar?')) {
            var url = $(this).attr('data-url');
            var load = $(".ajax_load");
            load.fadeIn(200).css("display", "flex");
            window.location.href = url;
            load.fadeOut(200); // caso falhe a linha de cima
        }
    });

    /**
     * checa se eh fornecedor para cadastrar 2 endereco
     */
    $("#is_supplier").on('change', function (e) {
        e.preventDefault();
        e.stopPropagation();
        // endereco 02
        $("#div-address-02").css("display", "none");

        // acesso ao portal
        $("#portal_access").attr('disabled', false);
        $("#portal_access").val('');

        if ($(this).val() == "1") {
            $("#div-address-02").css("display", "flex");

            // desabilita por padrao o acesso ao portal
            $("#portal_access").val('0');
            $("#portal_access").attr('disabled', true);

        }
    });

    /**
     * mask cpf / CNPJ
     */
    $("#document").on('keyup', function (e) {
        doc = $(this).val();
        doc = doc.replace(/\D/g,"");
        length = doc.length;
        if(length < 12){
            doc = doc.replace(/(\d{3})(\d)/,"$1.$2");
            doc = doc.replace(/(\d{3})(\d)/,"$1.$2");
            doc = doc.replace(/(\d{3})(\d{1,2})$/,"$1-$2");
            $(this).val(doc);
        }else{
            doc = doc.replace(/^(\d{2})(\d)/,"$1.$2");
            doc = doc.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3");
            doc = doc.replace(/\.(\d{3})(\d)/,".$1/$2");
            doc = doc.replace(/(\d{4})(\d)/,"$1-$2");
            $(this).val(doc);
        }
    });

    /**
     *  Select form input para cadastro de item no pedido
     *  */
    $('.js-order-product').select2({
        placeholder: 'Digite o nome do produto',
        language: "pt",
        ajax: {
            // url: '../../index.php/orders/select_product',
            url: '../../orders/select_product',
            dataType: 'json',
            processResults: function (response) {
                $("#id_item_alter").val(); // limpa o id do item a ser alterado
                $("#id_product_alter").val(); // limpa o id do produto a ser alterado
                return {
                    results: response
                };
            },
            cache: true
        }
    });
    /**
     * selected item from order add item
     */
    $('#produto').on('select2:select', function (e) {
        var data = e.params.data;
        $("#price").val(data.price);
    });

    /**
     * alteracao de item do pedido
     */
    $(".edit-item-order").on('click', function () {
        id_item_order = $(this).attr('data-id');
        qtde = $(this).attr('data-qtde');
        price = $(this).attr('data-price');
        produto = $(this).attr('data-produto');
        id_produto = $(this).attr('data-produto-id');
        // habilita a edicao no campo de insercao
        $("#id_item_alter").val(id_item_order); // id do item a ser alterado
        $("#id_product_alter").val(id_produto); // id do produto a ser alterado
        $("#select2-produto-container").html(produto);
        $("#produto").val(id_produto);
        $("#amount").val(qtde);
        $("#price").val(price);
    });

});
