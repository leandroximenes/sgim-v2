<?php
header('Content-Type: text/html; charset=iso-8859-1');
error_reporting(-1);
ini_set('display_errors', 'Off');
//header("Content-type: application/vnd.ms-word");
//header("Content-Disposition: attachment;Filename=document_recibo_entrega_chaves_{$_GET[codContrato]}.doc");
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
include("../conexao/conexao.php");
include("../php/php.php");
include("../modulos/diversos/util.php");

$idCodContrato = $_GET['CodContrato'];

$mySQL->runQuery("call procContratoUnicoListar($idCodContrato)");
$ArrayDados = $mySQL->getArrayResult();
if (count($ArrayDados) > 0 ) {
    $contrato = arrayUtf8Decode($ArrayDados[0]);
    $mySQL->runQuery("SELECT codPagamento, DATE_FORMAT(dataVencimento, '%m/%Y') AS dataVencimento 
                      FROM pagamento  
                      WHERE codContrato = $idCodContrato
                      AND dataPagamento is null
                      AND dataVencimento < (DATE_ADD(NOW(),INTERVAL -1 MONTH))
                      ORDER BY dataVencimento;");
    $ArrayMesesDevedores = $mySQL->getArrayResult();
    $meses = '';
    foreach ($ArrayMesesDevedores as $key => $value) {
        if ($key == 0){
            $meses = $value['dataVencimento'];
            $ids = $value['codPagamento'];
        }
        else{
            $meses .= ', ' . $value['dataVencimento'];
            $ids .= ', ' . $value['codPagamento'];
        }
    }
}
?>
<style type="text/css">
    body{
        font-family:Sans-Serif;-webkit-print-color-adjust:exact
    }

    table{
        vertical-align:text-top;border-spacing:0;border-collapse:collapse;width:100%
    }

    table td{
        border:1px solid #000
    }

    .justify{
        text-align: justify;
    }

    .zebrada thead{
        font-weight:700
    }

    .zebrada tbody tr:nth-child(odd){
        background-color:#ccc
    }
</style>
<script type="text/javascript" src="../biblioteca_js/jquery-1.11.0.min.js"></script>
<div style="width: 584px;">
    <p align="right"><b>Brasília (DF), <?= data(); ?></b><br/><br/></p>

    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prezado(a),
        </span> <?= $contrato['inquilino'] ?>,</p>


    <p class="justify">Até a presente data não consta(m) em nossos registros o(s) comprovante(s) de quitação 
        da(s) parcela(s) do aluguel do imóvel sito <?= $contrato['endereco'] ?> com vencimento(s) em
        <?= $meses ?>.
    </p>

    <p class="justify">Dessa forma, pedimos a gentileza de comparecer em nosso escritório com os referidos 
        comprovantes ou enviá-los por e-mail (tabakal.imoveis@hotmail.com) ou Whatsapp (98190-1122) a fim 
        de que possamos solucionar tal pendência.</p>

    <p class="justify">Colocamo-nos à sua disposição para maiores esclarecimentos através dos telefones 
        3340-0921 ou 98190-1122.</p><br/><br/>

    <p align="center">Atenciosamente,.</p><br/><br/>

    <p align="center"><b>TABAKAL</b> Emp. Imobiliários Ltda</p><br/><br/>
</div>


