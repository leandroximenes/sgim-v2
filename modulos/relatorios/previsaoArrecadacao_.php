<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

//header("Content-type: application/vnd.ms-word");
//header("Content-type: application/force-download");
//header("Content-Disposition: attachment; filename=relatorio_aniversariantes.doc");
//header("Pragma: no-cache");

$titulo = 'Relatório de Previsão de Arrecadação';

if (isset($_SESSION["SISTEMA_codPessoa"])) {

    include("../../conexao/conexao.php");
    include("../../php/php.php");

    global $mySQL;
    $sql = "select 
count(C.codContrato) AS vlTotalImoveis,
sum(P.valor) AS Pagamento,
sum((P.valor * C.comissao) / 100) AS Comissao, 
DATE_FORMAT(dataVencimento,'%m/%y') AS mesReferencia
FROM pagamento P
INNER JOIN contrato C on C.codContrato = P.codContrato
WHERE C.status = 1  
AND DATE_FORMAT(P.dataVencimento,'%y') >= DATE_FORMAT(now(),'%y')

GROUP BY DATE_FORMAT(P.dataVencimento,'%m/%y')
ORDER BY DATE_FORMAT(P.dataVencimento,'%y'), DATE_FORMAT(P.dataVencimento,'%m') ";
    $rs = $mySQL->runQuery($sql);
    $rsQuant = $rs->num_rows;

    if ($rsQuant > 0) {
        ?>
        <style>
            body{
                margin: 0px;
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
        </style>



        <table border='0' width='650' cellpadding='3' cellspacing='1'>


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
                <th> Mês de Arrecadação </th>
                <th> Quantidade de imóveis alugados </td>
                <th> Valor total de Alugueis </th>
                <th> Valor total comissão TABAKAL</th>
            </tr>
            <?php
            $cont = 0;
            while ($rsLinha = mysqli_fetch_assoc($rs)) {
                if ($cont % 2 == 0) {
                    $bg = '#CFE4EF';
                } else {
                    $bg = '#FFF';
                }
                ?>			
                <tr bgcolor='<?php echo $bg; ?>'>
                    <td width='50' align='center'> <?php echo $rsLinha['mesReferencia']; ?></td>
                    <td width='50' align='center'> <?php echo $rsLinha['vlTotalImoveis']; ?></td>
                    <td width='50' align='center'> <?php echo number_format($rsLinha['Pagamento'], 2, ",", "."); ?></td>
                    <td width='80' align='center'> <?php echo number_format($rsLinha['Comissao'], 2, ",", "."); ?></td>
                </tr>
                <?php
                $cont ++;
            }
            ?>
        </table>

        <?php
    } else {
        echo 'Não existem Pagamentos em atraso!';
    }
} else {
    header('location:login.php');
}
?>
<script>
    window.print();
</script>