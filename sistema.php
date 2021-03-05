<?php
session_start();

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html; charset=iso8859-1');

if (isset($_SESSION['SISTEMA_codPessoa'])) {
    ?>
    <html>
        <head><meta charset="iso8859-1">

            
            <title>:: Tabakal Imóveis - SisGIM - Sistema de Gerenciamento de Imóveis.::</title>

            <link rel="stylesheet" type="text/css" href="ext-3.3.1/resources/css/ext-all-notheme.css" />
            <script type="text/javascript" src="ext-3.3.1/adapter/ext/ext-base.js"></script>
            <script type="text/javascript" src="ext-3.3.1/ext-all-debug.js"></script>


            <script src="biblioteca_js/GMapPanel.js"></script>
            <!-- script src="biblioteca_js/maps.js"></script -->

            <script type="text/javascript" src="biblioteca_js/jquery-1.11.0.min.js"></script>
            <script type="text/javascript" src="biblioteca_js/RowExpander.js"></script>
            <script type="text/javascript" src="biblioteca_js/Ext.ux.Sound.js"></script>
            <script type="text/javascript" src="biblioteca_js/Ext.ux.window.MessageWindow.js"></script>

            <link rel="stylesheet" href="ext-3.3.1/resources/css/ext-all.css" />
            <link rel="stylesheet" href="biblioteca_css/estilos.css" />
            <script type="text/javascript" src="biblioteca_js/principal.js"></script>
            <script type="text/javascript" src="biblioteca_js/scripts.js"></script>

            <script type="text/javascript" src="biblioteca_js/janelaGerenciarImovel.js"></script>	
            <script type="text/javascript" src="biblioteca_js/janelaFiadorRelacionar.js"></script>
            <script type="text/javascript" src="biblioteca_js/janelaConjugeRelacionar.js"></script>
            <script type="text/javascript" src="biblioteca_js/janelaPerfisRelacionar.js"></script>

            <script type="text/javascript" src="biblioteca_js/janelaGerenciarPagamento.js"></script>
            <script type="text/javascript" src="biblioteca_js/janelaGerenciarPagamentoCondominio.js"></script>
            <script type="text/javascript" src="biblioteca_js/janelaAjusteContrato.js"></script>
            <script type="text/javascript" src="biblioteca_js/janelaGerenciarObservacao.js"></script>
            <script type="text/javascript" src="biblioteca_js/janelaGerenciarObservacaoRepasse.js"></script>
            <script type="text/javascript" src="biblioteca_js/janelaGerenciarSumario.js"></script>

            <script type="text/javascript" src="biblioteca_js/janelaCadastrarContrato.js"></script>
            <script type="text/javascript" src="biblioteca_js/janelaGerenciarContrato.js"></script>
            <script type="text/javascript" src="biblioteca_js/janelaGerenciarRepasse.js"></script>

            <script type="text/javascript" src="biblioteca_js/janelaDadoBancarioRelacionar.js"></script>
            <script type="text/javascript" src="biblioteca_js/janelaGerenciarProfissao.js"></script>
            <script type="text/javascript" src="biblioteca_js/janelaGerenciarGrupo.js"></script>
            <!-- <script type="text/javascript" src="biblioteca_js/janelaGerenciarFeriado.js"></script>-->
            <script type="text/javascript" src="biblioteca_js/janelaGerenciarCidade.js"></script>
            <script type="text/javascript" src="biblioteca_js/janelaGerenciarBanco.js"></script>
            <script type="text/javascript" src="biblioteca_js/janelaGerenciarUsuario.js"></script>	
            <script type="text/javascript" src="biblioteca_js/janelaContratoEncerrar.js"></script>

            <script type="text/javascript" src="biblioteca_js/janelaGerenciarAdministradora.js"></script>	


            <script type="text/javascript" src="biblioteca_js/SearchField.js"></script>	
            <script type="text/javascript" src="biblioteca_js/styleswitcher.js"></script>

        </head>

        <body>
            <div id="sms" style="position: absolute; height: 70px; width: 230px; padding: 5px; font-size: 9pt; font-family: arial; color: #666666; right: 5px; top:5px; z-index: 1000; border: 1px solid #99bbe8; border-radius: 3px;">
                <div style="float: left; width: 100%; margin-bottom: 5px;"><center><b>Envio de sms</b></center></div>
                <div style="float: left; " id="qtdSms"></div>
                <div id="btSms" style="float: right; cursor: pointer; display: none; padding: 5px; color:#fff; margin-top: 20px; background-color: #0392de; border-radius: 3px;"><center><b>Enviar</b></center></div>
            </div>


            <iframe id="iframeSms" src="" width="200" height="100" style="display: none; position: absolute; right: 0px; top:0px; z-index: 1000" ></iframe>
            <iframe src="mapa.php"  width="100%" id="mapaFrame" height="500" style="position: absolute; border: 0px; right: 0px; top:123px; z-index: 1000" ></iframe>
        </body>
        <?php
    } else {
        header('location:login.php');
    }
    ?>
    <script>
        $(document).ready(function () {
            altura = $(document).height() - 130; //altura da pï¿½gina
            $('#mapaFrame').attr('height', altura + 'px');
            atualizaQtdSms();

            $('#btSms').click(function () {
                $('#qtdSms').html('Enviando...');
                $('#btSms').fadeOut();
                $.ajax({
                    type: "POST",
                    async: false,
                    dataType: 'json',
                    url: 'envio_em_massa.php',
                    success: function (dados) {
                        console.log(dados);
                        console.log(dados.msng);
                        if (dados.msng != '') {
                            alert('Não foi possivel enviar os sms');
                            alert(dados.msng);
                        }
                        atualizaQtdSms();
                    },
                    error: function () {
                        alert('Não foi possivel enviar os sms');
                    }
                });
            });
        });

        function atualizaQtdSms() {
            $('#qtdSms').html('Atualizando...');
            $.ajax({
                type: "POST",
                async: false,
                dataType: 'json',
                url: 'envio_em_massa.php',
                data: {acao: 'contar'},
                success: function (data) {
                    $('#qtdSms').html('');
                    $('#qtdSms').append("Qtd. de Pagamentos: " + eval(data.qtdPagamentos));
                    $('#qtdSms').append("<Br>Qtd. de Fim Contratos: " + eval(data.qtdFimContratos));
                    $('#qtdSms').append("<Br>Qtd. de Aniversários: " + eval(data.qtdAniversarios));

                    if ((data.qtdPagamentos + data.qtdFimContratos + data.qtdAniversarios) > 0) {
                        $('#btSms').fadeIn();
                    }
                },
                error: function () {
                    $('#qtdSms').html('Não foi possivel atualizar os dados');
                }
            });
            setTimeout('atualizaQtdSms()', 60000);
        }
    </script>
</html>