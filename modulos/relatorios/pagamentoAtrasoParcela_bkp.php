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
?>
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

    table tbody tr:nth-child(even) {
        background-color: #fff;
    }
    table tbody tr:nth-child(odd) {
        background-color: #CFE4EF;
    }

    table tbody tr:hover
    {
        background: #f7dcdf!important;
    }
</style>
<form method="POST">
    <table border='0' width='650' cellpadding='3' cellspacing='1'>
        <thead>
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
                            $selected = ($_POST['atraso'] == $i) ? "selected='selected'" : "";
                            echo "<option value='$i' $selected>$i</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </thead>
        <tbody>
            <?php
            $atraso = ($_POST['atraso']) ? $_POST['atraso'] : 0;
            
            $sqlAluguel = "
                SELECT contrato.*, DATE_FORMAT(pagamento.dataVencimento,'%d/%m/%Y') as dataVencimento,  
                    DATE_FORMAT(contrato.dataFim,'%d/%m/%Y') as dataFimFormatada, 
                    DATEDIFF(NOW(),pagamento.dataVencimento) AS diasAtraso,
                    pagamento.parcela,
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

            if (count($arrayAtrasos) > 0) {
                ?>
                <tr>
                    <th rowspan="2"> Inquilino</th>
                    <th rowspan="2"> Locador</th>
                    <th rowspan="2"> Data Fim Contrato</th>
                    <th rowspan="2"> Contrato</th>
                    <th colspan="3"> Aluguel</th>
                    <th colspan="3"> Condominio</th>
                </tr>
                <tr>
                    <th> Parcela</th>
                    <th> Vencmento</th>
                    <th> Dias atraso</th>
                    <th> Parcela</th>
                    <th> Vencmento</th>
                    <th> Dias atraso</th>
                </tr>
                <?php
                foreach ($arrayAtrasos as $atrasos) {
                        foreach ($atrasos as $value) {
                        ?>			
                        <tr>
                            <td width='200'> <?php echo utf8_decode($value['Inquilino']); ?></td>
                            <td width='200'> <?php echo utf8_decode($value['Locatario']); ?></td>
                            <td width='70' align='center'> <?php echo $value['dataFimFormatada']; ?></td>
                            <td width='50' align='center'> <?php echo $value['codContrato']; ?></td>
                            <td width='50' align='center'> <?php echo $value['parcela']; ?></td>
                            <td width='80' align='center'> <?php echo $value['dataVencimento']; ?></td>
                            <td width='80' align='center'> <?php echo $value['diasAtraso']; ?></td>
                            <td width='50' align='center'> <?php echo $value['parcelaConcodominio']; ?></td>
                            <td width='80' align='center'> <?php echo $value['dataVencimentoCondominio']; ?></td>
                            <td width='80' align='center'> <?php echo $value['diasAtrasoCondominio']; ?></td>
                        </tr>
                        <?php
                    }
                }
            } else {
                echo '<tr><td>Não existem Pagamentos em atraso!</td></tr>';
            }
            ?>
        </tbody>
    </table>
</form>