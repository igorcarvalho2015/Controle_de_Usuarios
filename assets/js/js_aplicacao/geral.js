var url_completa = document.URL;
var $table = function () {

    achou = $("form").find(".tabela_requisicao");
    if (achou.length > 0) {
        $('.tabela_requisicao').dataTable({
            "destroy": true,
            "ajax": url_completa + '/datatable',
            "language": {
                "url": base_url + 'assets/js/Portuguese-Brasil.json'
            }
            //	"oLanguage": {"sUrl": base_url+'assets/datatables/Portuguese-Brasil.json'},
        });
    }
}
$table();

$("form").submit(function (event) {

    form = $(this).attr('id');
    modelo = $(this).attr('modelo');
    acao = $("#" + form + " input[type=submit]").hasClass('cadastrar_dados');

    if (acao == true) {
        cadastrar(form, modelo);
    }
    else {
        acao_at = $("#" + form + " input[type=submit]").hasClass('alterar_dados');

        if (acao_at == true) {
            alterar(form, modelo);
        }
        else {
            alert("Nenhuma acao!");
        }
    }
    event.preventDefault();
    return false;
});
$("form input[type=reset]").click(function (e) {
    form = $(this).closest('form').attr('id');
    reset_formulario(form, 'nv');
    $(".error_field").remove();
    apos_cancelar();
});

$("form input[type=text], form input[type=password]").click(function (e) {
    $(this).next().remove();
});

function reset_formulario(form, ac) {

    $("#" + form)[0].reset();
    $("#" + form + " input[type=submit], #" + form + " input[type=reset]").removeAttr('disabled');
    $("#" + form + " input[type=hidden]").val('');
    if (ac == 'nv') {
        $("#" + form + " input[type=submit]").removeClass('btn-success alterar_dados').addClass('btn-primary cadastrar_dados');
        $("#" + form + " input[type=submit]").attr('value', 'Salvar');
    }
    else if (ac = 'ed') {
        $("#" + form + " input[type=submit]").removeClass('btn-success cadastrar_dados').addClass('btn-primary alterar_dados');
        $("#" + form + " input[type=submit]").attr('value', 'Alterar');
    }
}

//salva um registro
var cadastrar = function (form, modelo) {
    $("#" + form + " input[type=submit], #" + form + " input[type=reset]").attr('disabled', 'disabled');
    var v = $("#" + form + " input[type=submit]").attr('value');
    $("#" + form + " input[type=submit]").attr('value', 'Processando...');
    // var formData = new FormData($("#" + form)[0]);
    var formData = $("#" + form).serialize();
    formData = formData + "&modelo=" + modelo;
    var request = $.ajax({
        url: url_completa + "/cadastrar",
        type: "POST",
        data: formData,
        // async: false,
        // cache: false,
        // contentType: false,
        // processData: false,
        dataType: "json"
    });

    request.done(function (data) {

        $(".error_field").remove();

        if (data.RETORNO == 0) {
            $.each(data.MSG, function (index, value) {
                //$("#" + index).css({"border":"1px solid red"});
                $("#" + index).after("<span class='error_field'>" + value + "</span>");
            });

            $("#" + form + " input[type=submit]").attr('value', 'Salvar');
            $("#" + form + " input[type=submit], #" + form + " input[type=reset]").removeAttr('disabled');

        }
        else {
            reset_formulario(form, 'nv');
            msg_valida_ajax(data.RETORNO, form);
            apos_salvar(form);
            $table();
        }
    });
    request.fail(function (jqXHR, textStatus) {
        $("#" + form + " input[type=submit]").removeClass('btn-success alterar_dados').addClass('btn-primary cadastrar_dados');
        $("#" + form + " input[type=submit]").attr('value', 'Salvar');
        $("#" + form + " input[type=submit], #" + form + " input[type=reset]").removeAttr('disabled');
        alert("Erro no Servidor, Requisicao falhou: " + textStatus);
    });
}

function msg_valida_ajax(v, form) {
    reset_formulario(form, 'nv');
    $(".form-valida-alert").fadeIn();
    if (v == 1) {

        $(".form-valida-alert").removeClass("form-valida-danger");
        $(".form-valida-alert").addClass("form-valida-success");
        $(".form-valida-alert").html('Operacao realizada com sucesso!');
    }
    else {
        $(".form-valida-alert").removeClass("form-valida-success");
        $(".form-valida-alert").addClass("form-valida-danger");
        $(".form-valida-alert").html('Ocorreu algum Erro na operação!');
    }
    $(".form-valida-alert").fadeOut(4000);
}

$("table").on('click', '.editar', function (e) {
    form = $(this).closest('form').attr('id');
    modelo = $("#" + form).attr('modelo');
    var chave = $(this).attr("data-id");
    var formData = new FormData();
    formData.append('modelo', modelo);
    formData.append('chave', chave);
    var request = $.ajax({
        url: url_completa + "/editar",
        type: "POST",
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json"
    });

    request.done(function (data) {

        //$(".error_field").remove();

        if (data.RETORNO == 1) {
            reset_formulario(form, 'ed');

            $.each(data.DADOS, function (idx, obj) {

                if ($("#" + form + " #" + idx).is("[multiple]")) {
                    var values = obj;
                    $.each(values.split(","), function (i, e) {
                        $("#" + form + " #" + idx +" option[value='" + e + "']").prop("selected", true);
                    });
                }
                else if ($("#" + form + " #" + idx).is(':radio')) {
                    $("#" + form).find(':radio[name=' + idx + '][value="' + obj + '"]').prop('checked', true);
                }
                else if ($("#" + form + " #" + idx).is(':checkbox')) {
                    $("#" + form + " #" + idx).prop("checked", true);
                }
                else {
                    $("#" + form + " #" + idx).val(obj);
                }

                $("#" + form + " .update").show();
                $("#" + form + " .save").hide();
            });

            apos_editar(form);

        }
        else {
            //msg_valida_ajax(data.RETORNO, form);
        }

    });
    request.fail(function (jqXHR, textStatus) {
        alert("Erro no Servidor, Requisicao falhou: " + textStatus);

    });

});

var alterar = function (form, modelo) {
    $("#" + form + " input[type=submit], #" + form + " input[type=reset]").attr('disabled', 'disabled');

    $("#" + form + " input[type=submit]").attr('value', 'Processando...');
    var formData = new FormData($("#" + form)[0]);
    formData.append('modelo', modelo);
    var request = $.ajax({
        url: url_completa + "/alterar",
        type: "POST",
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json"
    });

    request.done(function (data) {

        $(".error_field").remove();

        if (data.RETORNO == 0) {
            $.each(data.MSG, function (index, value) {
                $("#" + index).after("<span class='error_field'>" + value + "</span>");
            });
        }
        else {
            msg_valida_ajax(data.RETORNO, form);
            apos_alterar(form);
            $table();
        }

        $("#" + form + " input[type=submit]").attr('value', 'Salvar');
        $("#" + form + " input[type=submit], #" + form + " input[type=reset]").removeAttr('disabled');
    });
    request.fail(function (jqXHR, textStatus) {
        alert("Erro no Servidor, Requisicao falhou: " + textStatus);
        $("#" + form + " input[type=submit]").attr('value', 'Alterar');
        $("#" + form + " input[type=submit], #" + form + " input[type=reset]").removeAttr('disabled');
    });
}

$("form").on('click', '.excluir', function () {
    form = $(this).closest('form').attr('id');

    v = $(this).attr('data-id');
    $('.bs-example-modal-sm_' + form).remove();
    $("#" + form).append('<div data-keyboard="false" data-backdrop="static" class="modal fade bs-example-modal-sm_' + form + '" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">' +
            '<div class="modal-dialog modal-sm">' +
            '<div class="modal-content">' +
            '<div class="modal-body">' +
            '<p>Confirmar a exclusão deste registro?</p>' +
            '<p id="msg_erro_exclusao" style="color: #d43f3a"></p>' +
            '</div>' +
            '<div class="modal-footer">' +
            '<button type="button" class="btn btn-primary confirm_delete" val="' + v + '">Confirmar</button>' +
            '<button type="button" class="btn btn-danger cancel_delete" data-dismiss="modal">Cancelar</button>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>'
            );
    $('.bs-example-modal-sm_' + form).modal('show');
});

//confirma a exclusao do registro
$("form").on('click', '.confirm_delete', function () {

    $("#" + form + " .confirm_delete, #" + form + " .cancel_delete").attr('disabled', 'disabled');
    form = $(this).closest('form').attr('id');
    modelo = $(this).closest('form').attr('modelo');
    v = $(this).attr('val');

    var request = $.ajax({
        url: url_completa + "/excluir",
        type: 'POST',
        dataType: 'json',
        data: {id: v, modelo: modelo}
    });
    request.done(function (data) {


        if (data.RETORNO == 1) {
            $('.bs-example-modal-sm_' + form).modal('hide');
            reset_formulario(form, 'nv');
            apos_cancelar(form);
            $table();
        }
        else { //erro no banco
            $('.bs-example-modal-sm_' + form + " #msg_erro_exclusao").html("<h4>Erro no banco ao excluir o registro!</h4>");
        }

    });
    request.fail(function (jqXHR, textStatus) {
        //    alert("Requisicao falhou: " + textStatus);
        $('.bs-example-modal-sm_' + form + " #msg_erro_exclusao").html("Erro ao excluir o registro!");
    });
    $("#" + form + " .confirm_delete, #" + form + " .cancel_delete").removeAttr('disabled');
});
var apos_salvar = function (form) {
};
var apos_alterar = function (form) {
};
var apos_editar = function (form) {
};
var apos_cancelar = function (form) {
};

$('.money2').mask("#.##0,00", {reverse: true});
$('.date').mask('00/00/0000');
$('.cpf').mask('000.000.000-00', {reverse: true});

