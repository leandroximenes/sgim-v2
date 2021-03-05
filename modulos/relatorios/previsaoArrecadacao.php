<?php
error_reporting(E_ALL);
ini_set('display_errors', 'Off');
session_start();
header('Content-Type: text/html; charset=iso-8859-1');
?>
<style>
    body{
        margin: 0px;
        font-size: 11px;
        font-family: arial;
        padding: 5px
    }

    input{
        margin: 0 5px;
    }

    input[type=text]{
        width: 100px;
    }


    .clearfix:after {
        content: ".";
        display: block;
        clear: both;
        visibility: hidden;
        height: 0;
    }


    table{
        font-size: 11px;
        font-family: arial;
    }

    th{
        background: #19688F;
        font-weight: bold;
        color: #FFF;
    }

    .vermelho{
        color: #990000;
    }

    .verde{
        color: #31CC2E;
    }

    #cabecalhoRelatorio{
        width: 650px;
        margin-bottom: 10px;
        height: 62px;
        background: url('img/bg_topo.jpg') bottom repeat-x;
        float: left;
    }

    #logoRelatorio{
        float: left;
    }

    #tituloRelatorio{
        float: right;
        text-align: right;
    }

    #nomeRelatorio{
        padding-top: 15px;
        font-size: 18px;
        color: #19688F;
    }

    tbody tr:nth-child(2n+2) {
        background: #CFE4EF;
    }

    .agrupador td{
        background: #6699FF;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
    }

    .hide{
        display: none;
    }
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="../../biblioteca_js/jquery-1.11.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function () {
        $('#dtInicio').val('<?= $_POST['dtInicio'] ?>');
        $('#dtFim').val('<?= $_POST['dtFim'] ?>');
        $('.result').addClass('hide');
        $('.agrupador').on('click', function () {
            var id = $(this).attr('id');
            $('.' + id).toggleClass('hide');
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

    });
</script>

<form method='POST'>
    <br/>
    <b>Data Inicio:</b><input id="dtInicio" class="date" required="required" type='text' name='dtInicio' size='6'> 
    <b>Data Fim:</b><input id="dtFim" class="date" type='text' name='dtFim' size='6' required="required">
    <input type='submit' value='Enviar'>
</form>
<?php
//header("Content-type: application/vnd.ms-word");
//header("Content-type: application/force-download");
//header("Content-Disposition: attachment; filename=relatorio_aniversariantes.doc");
//header("Pragma: no-cache");

$titulo = 'Relat&oacute;rio de Previs&atildeo de Arrecada&ccedil;&atildeo';

if (isset($_SESSION["SISTEMA_codPessoa"])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include("../../conexao/conexao.php");
        include("../../php/php.php");
        include("../diversos/util.php");

        $dtInicio = inverteData($_POST['dtInicio']);
        $dtFim = inverteData($_POST['dtFim']);
        global $mySQL;
        $sql = "SELECT 
                P.codContrato, PL.nome, codPessoaInquilino, 
                P.valor, 
                DATE_FORMAT(dataInicio,'%d/%m/%Y') as dataInicio,
                DATE_FORMAT(dataFim,'%d/%m/%Y') as dataFim,
                ROUND(((P.valor - P.valorDesconto) * P.comissao /100),2) AS comissao, 
                DATE_FORMAT(dataVencimento,'%m/%Y') AS dataVencimento, 
                DATE_FORMAT(dataVencimento,'%Y%m') AS agrupador
            FROM pagamento P
            INNER JOIN contrato C on C.codContrato = P.codContrato 
            INNER JOIN pessoa PL on C.codPessoaLocador = PL.codPessoa
            WHERE C.status = 1 AND P.codContrato NOT IN (SELECT codContrato FROM contratoEncerramento WHERE data <= dataVencimento)
            AND P.dataVencimento BETWEEN '" . $dtInicio . "' AND '" . $dtFim . "'
            ORDER BY DATE_FORMAT(dataVencimento,'%Y%m'), 2";

        $query = $mySQL->runQuery($sql);
        $result = $mySQL->getArrayResult();
        $agrupadores = array_icount_values($result, true, 'agrupador');

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
                        <th> M&ecirc;s de Arrecada&ccedil;&atildeo </th>
                        <th> C&oacute;digo Contrato </td>
                        <th> Locador </td>
                        <th> Inquilino </td>
                        <th> In&iacute;cio</td>
                        <th> Fim </td>
                        <th> Valor do Aluguel </th>
                        <th> Valor da comiss&atildeo TABAKAL</th>
                    </tr>
                    <?php
                    $grp = '';
                    foreach ($result as $value) :
                        if ($value['agrupador'] != $grp) {
                            echo '<tr id="' . $value['agrupador'] . '" class="agrupador">
                                <td>' . $value['dataVencimento'] . '</td>
                                <td> qtde(' . $agrupadores[$value['agrupador']]['total'] . ')</td>
                                <td>-</td><td>-</td><td>-</td><td>-</td>
                                <td>' . number_format($agrupadores[$value['agrupador']]['valor'], 2, ",", ".") . '</td>
                                <td>' . number_format($agrupadores[$value['agrupador']]['comissao'], 2, ",", ".") . '</td>
                              </tr>';
                        }
                        $grp = $value['agrupador'];
                        ?>
                        <tr class="result <?= $value['agrupador'] ?>" >
                            <td width='50' align='center'> <?php echo $value['dataVencimento']; ?></td>
                            <td width='50' align='center'> <?php echo $value['codContrato']; ?></td>
                            <td width='100px' align='center'> <?php echo ($value['nome']); ?></td>
                            <td width='100px' align='center'> <?php echo ($arrayPessoa[$value['codPessoaInquilino']]); ?></td>
                            <td width='50' align='center'> <?php echo $value['dataInicio']; ?></td>
                            <td width='50' align='center'> <?php echo $value['dataFim']; ?></td>
                            <td width='50' align='right'> <?php echo number_format($value['valor'], 2, ",", "."); ?></td>
                            <td width='80' align='right'> <?php echo number_format($value['comissao'], 2, ",", "."); ?></td>
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

function array_icount_values($arr, $lower = true, $paramentro) {
    $arr2 = array();
    if (!is_array($arr['0'])) {
        $arr = array($arr);
    }
    foreach ($arr as $k => $v) {
        foreach ($v as $key => $v2) {
            if ($key == $paramentro) {
                if ($lower == true) {
                    $v2 = strtolower($v2);
                }
                if (!isset($arr2[$v2])) {
                    $arr2[$v2]['total'] = 1;
                    $arr2[$v2]['valor'] = $arr[$k]['valor'];
                    $arr2[$v2]['comissao'] = $arr[$k]['comissao'];
                } else {
                    $arr2[$v2]['total'] ++;
                    $arr2[$v2]['valor'] += $arr[$k]['valor'];
                    $arr2[$v2]['comissao'] += $arr[$k]['comissao'];
                }
            }
        }
    }
    return $arr2;
}

function array_enum($array, $campoIndex, $campoLabel) {
    foreach ($array as $key => $value) {
        $newArray[$value[$campoIndex]] = ($value[$campoLabel]);
    }
    return $newArray;
}
?>
