<?php
error_reporting(E_ALL);
ini_set('display_errors', 'Off');
session_start();
header('Content-Type: text/html; charset=iso-8859-1');
include("../../conexao/conexao.php");
include("../../php/php.php");

global $mySQL;

//header("Content-type: application/vnd.ms-word");
//header("Content-type: application/force-download");
//header("Content-Disposition: attachment; filename=relatorio_aniversariantes.doc");
//header("Pragma: no-cache");

$titulo = 'Relatório de Pagamentos com Vencimento';
$atraso = isset($_POST['atraso']) ? $_POST['atraso'] : 0;
?>
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css">
<script src="js/jquery-3.2.0.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            paging: false,
            scrollY: false,
            searching: false,
            bSortClasses: false
        });

        $('.sendMailSms').on('click', function (e) {
            e.preventDefault();
            var _this = $(this);
            _this.children().attr('src', '../../img/loading.gif');
            var CodContrato = _this.attr('href');
            $.ajax({
                type: "POST",
                async: false,
                dataType: "json",
                url: '../../ajax/Pagamento.php?acao=' + CodContrato,
                success: function (data) {
                    console.log(data);
                    $('.' + data.icon).attr('src', '../../img/ok-sendemail.png');
                },
                error: function (e) {
                    alert(e.responseText);
                }
            });
        });
    });
</script>
<style>
    body{
        margin: 5px;
        font-size: 11px;
        font-family: arial;

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
        width: 950px;
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

    #myTable{
        width: 950px!important;
        float: left
    }

    #myTable th{
        cursor: pointer!important;
    }

    #myTable tbody tr:nth-child(even) {
        background-color: #fff;
    }
    #myTable tbody tr:nth-child(odd) {
        background-color: #CFE4EF;
    }

    #myTable tbody tr:hover
    {
        background: #f7dcdf!important;
    }

    #myTable td, th{
        padding: 2px;
        border: 1px solid #ccc;
    }

    table.dataTable thead th, table.dataTable thead td {
        padding: 4px;
        border-bottom: 1px solid #111;
    }

    table.dataTable thead th{
        padding-right: 15px;
    }
</style>

<form method="POST">
    <table border='0' width='950' cellpadding='3' cellspacing='1'>
        <tr>
            <td colspan='6'> 
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
        <tr>
            <td colspan='6' style=""> Meses de atraso: 
                <select name="atraso" onchange="this.form.submit();">
                    <?php
                    for ($i = 0; $i <= 5; $i++) {
                        $selected = ($atraso == $i) ? "selected='selected'" : "";
                        echo "<option value='$i' $selected>$i</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
    </table>

    <?php
    $sqlAluguel = "
        SELECT contrato.*, DATE_FORMAT(pagamento.dataVencimento,'%d/%m/%Y') as dataVencimento,  
            DATE_FORMAT(contrato.dataFim,'%d/%m/%Y') as dataFimFormatada, 
            DATEDIFF(NOW(),pagamento.dataVencimento) AS diasAtraso,
            pagamento.parcela,
            pagamento.enviouEmailSmsAtraso,
            inquilino.nome as Inquilino, 
            locatario.nome as Locatario 
        FROM contrato 
        INNER JOIN pagamento ON contrato.codContrato = pagamento.codContrato
        INNER JOIN pessoa as inquilino ON contrato.codPessoaInquilino = inquilino.codPessoa
        INNER JOIN pessoa as locatario ON contrato.codPessoaLocador = locatario.codPessoa
        where contrato.status = 1 
        and contrato.dataFim > now() 
        and pagamento.dataPagamento is null
        and pagamento.dataVencimento < (DATE_ADD(NOW(),INTERVAL -$atraso MONTH))
        AND pagamento.codContrato NOT IN (SELECT codContrato FROM contratoEncerramento)
        ORDER BY diasAtraso";

    $sqlCondomínio = "
        SELECT contrato.*, DATE_FORMAT(pagamentoCondominio.dataVencimento,'%d/%m/%Y') as dataVencimentoCondominio,  
            DATE_FORMAT(contrato.dataFim,'%d/%m/%Y') as dataFimFormatada, 
            DATEDIFF(NOW(),pagamentoCondominio.dataVencimento) AS diasAtrasoCondominio,
            pagamentoCondominio.parcela as parcelaConcodominio,
            pagamentoCondominio.enviouEmailSmsAtraso as enviouEmailSmsAtrasoCondominio,
            inquilino.nome as Inquilino, 
            locatario.nome as Locatario 
        FROM contrato 
        INNER JOIN pagamentoCondominio ON contrato.codContrato = pagamentoCondominio.codContrato
        INNER JOIN pessoa as inquilino ON contrato.codPessoaInquilino = inquilino.codPessoa
        INNER JOIN pessoa as locatario ON contrato.codPessoaLocador = locatario.codPessoa
        where contrato.status = 1 
        and contrato.dataFim > now() 
        and pagamentoCondominio.dataPagamento is null
        and pagamentoCondominio.dataVencimento < (DATE_ADD(NOW(),INTERVAL -$atraso MONTH))
        AND pagamentoCondominio.codContrato NOT IN (SELECT codContrato FROM contratoEncerramento)
        ORDER BY diasAtrasoCondominio";


    $mySQL->runQuery($sqlAluguel);
    $arrayAtrasos[] = $mySQL->getArrayResult();

    $mySQL->runQuery($sqlCondomínio);
    $arrayAtrasos[] = $mySQL->getArrayResult();
    ?>
    <?php
    if (count($arrayAtrasos) > 0) {
        ?>
        <table id="myTable">
            <thead>
                <tr>
                    <th rowspan="2">Inquilino</th>
                    <th rowspan="2">Locador</th>
                    <th rowspan="2">Data Fim Contrato</th>
                    <th rowspan="2">Contrato</th>
                    <th colspan="4">Aluguel</th>
                    <th colspan="4">Condominio</th>
                </tr>
                <tr>
                    <th>Parcela</th>
                    <th>Vencmento</th>
                    <th>Dias atraso</th>
                    <th>Email<br/>SMS</th>
                    <th>Parcela</th>
                    <th>Vencmento</th>
                    <th>Dias atraso</th>
                    <th>Email<br/>SMS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($arrayAtrasos as $atrasos) {
                    foreach ($atrasos as $value) {
                        $imgIcon = "";
                        if ($value['enviouEmailSmsAtraso'] == 1)
                            $imgIcon = "<img class=\"pagamento_{$value['codContrato']}\" src=\"../../img/ok-sendemail.png\"/>";
                        else if ($value['diasAtraso'] != null && $value['enviouEmailSmsAtraso'] == null)
                            $imgIcon = "<img class=\"pagamento_{$value['codContrato']}\" src=\"../../img/nt-sendemail.png\"/>";

                        $imgIconCond = "";
                        if ($value['enviouEmailSmsAtrasoCondominio'] == 1)
                            $imgIconCond = "<img class=\"condominio_{$value['codContrato']}\" src=\"../../img/ok-sendemail.png\"/>";
                        else if ($value['diasAtrasoCondominio'] != null && $value['enviouEmailSmsAtrasoCondominio'] == null)
                            $imgIconCond = "<img class=\"condominio_{$value['codContrato']}\" src=\"../../img/nt-sendemail.png\"/>";
                        ?>			
                        <tr>
                            <td width='1000'> <?php echo utf8_decode($value['Inquilino']); ?></td>
                            <td width='1000'> <?php echo utf8_decode($value['Locatario']); ?></td>
                            <td width='70' align='center'> <?php echo $value['dataFimFormatada']; ?></td>
                            <td width='50' align='center'> <?php echo $value['codContrato']; ?></td>
                            <td width='50' align='center'> <?php echo $value['parcela']; ?></td>
                            <td width='80' align='center'> <?php echo $value['dataVencimento']; ?></td>
                            <td width='80' align='center'> <?php echo $value['diasAtraso']; ?></td>
                            <td width='80' align='center'><a class="sendMailSms" href="enviarSMSEmailInquilino&CodContrato=<?= $value['codContrato']; ?>"><?= $imgIcon ?></a></td>
                            <td width='50' align='center'> <?php echo $value['parcelaConcodominio']; ?></td>
                            <td width='80' align='center'> <?php echo $value['dataVencimentoCondominio']; ?></td>
                            <td width='80' align='center'> <?php echo $value['diasAtrasoCondominio']; ?></td>
                            <td width='80' align='center'><a class="sendMailSms" href="enviarSMSEmailInquilinoCondominio&CodContrato=<?php echo $value['codContrato']; ?>"><?= $imgIconCond ?></a></td>
                        </tr>
            <?php
        }
    }
    ?>
            </tbody>
        </table>
    <?php
} else {
    echo '<tr><td>Não existem Pagamentos em atraso!</td></tr>';
}
?>
</table>
</form>