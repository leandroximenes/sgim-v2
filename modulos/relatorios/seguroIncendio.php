<?php
error_reporting(E_ALL);
ini_set('display_errors', 'Off');
session_start();
header('Content-Type: text/html; charset=iso-8859-1');
?>
<style>
    body {
        margin: 0px;
        font-size: 11px;
        font-family: arial;
        padding: 5px
    }

    input {
        margin: 0 5px;
    }

    input[type=text] {
        width: 100px;
    }


    .clearfix:after {
        content: ".";
        display: block;
        clear: both;
        /* visibility: hidden; */
        height: 0;
    }


    table {
        font-size: 11px;
        font-family: arial;
    }

    th {
        background: #19688F;
        font-weight: bold;
        color: #FFF;
    }

    .vermelho {
        color: #990000;
    }

    .verde {
        color: #31CC2E;
    }

    #cabecalhoRelatorio {
        width: 650px;
        margin-bottom: 10px;
        height: 62px;
        background: url('img/bg_topo.jpg') bottom repeat-x;
        float: left;
    }

    #logoRelatorio {
        float: left;
    }

    #tituloRelatorio {
        float: right;
        text-align: right;
    }

    #nomeRelatorio {
        padding-top: 15px;
        font-size: 18px;
        color: #19688F;
    }

    tbody tr:nth-child(2n+2) {
        background: #CFE4EF;
    }

    .agrupador td {
        background: #6699FF;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
    }

    /* .hide{
        display: none;
    } */
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="../../biblioteca_js/jquery-1.11.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        $('#dtInicio').val('<?= $_POST['dtInicio'] ?>');
        $('#dtFim').val('<?= $_POST['dtFim'] ?>');
        // $('.result').addClass('hide');
        $('.agrupador').on('click', function() {
            var id = $(this).attr('id');
            // $('.' + id).toggleClass('hide');
        });
        $(".date").datepicker({
            dateFormat: 'dd/mm/yy',
            dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
            dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
            monthNames: ['Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior'
        });

        $('.sendSMS').on('click', function(e) {
            e.preventDefault();
            var _this = $(this);
            
            var src = _this.children().attr('src');
            _this.children().attr('src', '../../img/loading.gif');


            $.ajax({
                type: "POST",
                dataType: "json",
                url: '../../ajax/SeguroIncendio.php?acao=enviarSMS',
                data: {
                    nome : _this.siblings('.firstName').val(),
                    celular : _this.siblings('.cel').val()
                },
                success: function() {
                    _this.children().attr('src', '../../img/ok-sendemail.png');
                },
                error: function(e) {
                    _this.children().attr('src', '../../img/nt-sendemail.png');
                    alert(e.responseText);
                }
            });
        });
    });
</script>

<form method='POST'>
    <br />
    <b>Data Inicio:</b><input id="dtInicio" class="date" required="required" type='text' name='dtInicio' size='6'>
    <b>Data Fim:</b><input id="dtFim" class="date" type='text' name='dtFim' size='6' required="required">
    <input type='submit' value='Enviar'>
</form>
<?php

$titulo = 'Relat&oacute;rio de Seguro Incêndio';

if (isset($_SESSION["SISTEMA_codPessoa"])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include("../../conexao/conexao.php");
        include("../../php/php.php");
        include("../diversos/util.php");

        $dtInicio = inverteData($_POST['dtInicio']);
        $dtFim = inverteData($_POST['dtFim']);
        global $mySQL;
        $sql = "
            SELECT DATE_FORMAT(dataFim,'%m/%Y') AS dataRerefencia, codContrato,
                PL.nome, 
                codPessoaInquilino, 
                (SELECT SUBSTRING_INDEX(nome, ' ', 1) FROM pessoa WHERE codPessoa = codPessoaInquilino) AS primeiroNome,
                DATE_FORMAT(dataInicio,'%d/%m/%Y') AS dataInicio,
                DATE_FORMAT(dataFim,'%d/%m/%Y') AS dataFim,
                DATEDIFF(dataFim, CURDATE()) AS diasVencer,
                DATEDIFF(dtFimSI, CURDATE()) AS diasVencerSI,
                DATE_FORMAT(dtInicioSI,'%d/%m/%Y') AS dtInicioSI,
                DATE_FORMAT(dtFimSI,'%d/%m/%Y') AS dtFimSI,
                tipoSI,
                (SELECT CONCAT('55', ddd, telefone) FROM telefone WHERE codTipoTelefone = 2 AND codPessoa = codPessoaInquilino) AS celular
            FROM contrato C 
            INNER JOIN pessoa PL ON C.codPessoaLocador = PL.codPessoa
            WHERE C.status = 1 AND C.dataFim BETWEEN '" . $dtInicio . "' AND '" . $dtFim . "'
            AND codContrato NOT IN (SELECT codContrato FROM contratoEncerramento)
            ORDER BY diasVencer,1,2";

        $query = $mySQL->runQuery($sql);
        $result = $mySQL->getArrayResult();

        $mySQL->runQuery("SELECT codPessoa, nome FROM pessoa;");
        $resultPessoa = $mySQL->getArrayResult();
        $arrayPessoa = array_enum($resultPessoa, 'codPessoa', 'nome');
        if (count($result) > 0) {
?>
            <table border='0' width='650' cellpadding='3' cellspacing='1'>
                <thead>
                    <tr>
                        <td colspan='8'>
                            <div id='cabecalhoRelatorio' class='clearfix'>
                                <div id='logoRelatorio'>
                                    <img src='img/logo.jpg' />
                                </div>
                                <div id='tituloRelatorio'>
                                    <div id='nomeRelatorio'>
                                        <?php echo $titulo; ?>
                                    </div>
                                    <?php echo data(); ?>
                                </div>

                            </div>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th> M&ecirc;s de Refência </th>
                        <th> C&oacute;digo Contrato </td>
                        <th> Locador </td>
                        <th> Inquilino </td>
                        <th> In&iacute;cio do contrato</td>
                        <th> Fim do contrato</td>
                        <th> Dias a vencer do contrato</td>
                        <th> In&iacute;cio do seguro</td>
                        <th> Fim do seguro</td>
                        <th> Tipo de seguro</td>
                        <th> Dias a vencer do S. Incêndio</td>
                        <th> SMS</td>
                    </tr>
                    <?php
                    foreach ($result as $value) :
                        $diasVencerText = '';
                        if ($value['diasVencer'] == 0 || $value['diasVencer'] == 1) {
                            $diasVencerText = $value['diasVencer'] . ' dia';
                        } else if ($value['diasVencer'] == -1) {
                            $diasVencerText = 'vencido à ' . abs($value['diasVencer']) . ' dia';
                        } else if ($value['diasVencer'] < 0) {
                            $diasVencerText = 'vencido à ' . abs($value['diasVencer']) . ' dias';
                        } else {
                            $diasVencerText = $value['diasVencer'] . ' dias';
                        }

                        $diasVencerSIText = '';
                        if (is_null($value['diasVencerSI'])) {
                            $diasVencerSIText = '-';
                        } else if ($value['diasVencerSI'] == 0 || $value['diasVencerSI'] == 1) {
                            $diasVencerSIText = $value['diasVencerSI'] . ' dia';
                        } else if ($value['diasVencerSI'] == -1) {
                            $diasVencerSIText = 'vencido à ' . abs($value['diasVencerSI']) . ' dia';
                        } else if ($value['diasVencerSI'] < 0) {
                            $diasVencerSIText = 'vencido à ' . abs($value['diasVencerSI']) . ' dias';
                        } else {
                            $diasVencerSIText = $value['diasVencerSI'] . ' dias';
                        }
                    ?>
                        <tr class="result" ?>
                            <td width='50' align='center'> <?php echo $value['dataRerefencia']; ?></td>
                            <td width='50' align='center'> <?php echo $value['codContrato']; ?></td>
                            <td width='100px' align='center'> <?php echo utf8_decode($value['nome']); ?></td>
                            <td width='100px' align='center'> <?php echo utf8_decode($arrayPessoa[$value['codPessoaInquilino']]); ?></td>
                            <td width='50' align='center'> <?php echo $value['dataInicio']; ?></td>
                            <td width='50' align='center'> <?php echo $value['dataFim']; ?></td>
                            <td width='50' align='center'> <?php echo $diasVencerText ?> </td>
                            <td width='50' align='center'> <?php echo $value['dtInicioSI']; ?></td>
                            <td width='50' align='center'> <?php echo $value['dtFimSI']; ?></td>
                            <td width='50' align='center'> <?php echo $value['tipoSI']; ?></td>
                            <td width='50' align='center'> <?php echo $diasVencerSIText ?> </td>
                            <td width='50' align='center'> 
                                <a href="#" class="sendSMS"> <img src="../../img/nt-sendemail.png"></a>
                                <input type="hidden" value="<?php echo utf8_decode($value['primeiroNome']); ?>" class="firstName" />
                                <input type="hidden" value="<?php echo ($value['celular']); ?>" class="cel" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

<?php
        } else {
            echo 'Não existem Pagamentos em atraso!';
        }
    }
} else {
    header('location:login.php');
}

function array_enum($array, $campoIndex, $campoLabel)
{
    foreach ($array as $key => $value) {
        $newArray[$value[$campoIndex]] = ($value[$campoLabel]);
    }
    return $newArray;
}
?>