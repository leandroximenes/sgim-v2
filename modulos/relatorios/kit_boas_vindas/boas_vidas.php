<?php
header('Content-Type: text/html; charset=iso-8859-1');
error_reporting(1);
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=document_recibo_entrega_chaves_{$_GET[codContrato]}.doc");
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
include("../../../conexao/conexao.php");
include("../../../php/php.php");
include("../../diversos/util.php");

$mySQL->runQuery("call procContratoUnicoListar({$_GET['codContrato']})");
$ArrayDados = $mySQL->getArrayResult();
if (!empty($ArrayDados))
    $contrato = arrayUtf8Decode($ArrayDados[0]);

$mySQL->runQuery("call procImovelUnicoListar({$contrato['codImovel']})");
$ArrayDados = $mySQL->getArrayResult();
if (!empty($ArrayDados))
    $imovel = arrayUtf8Decode($ArrayDados[0]);

$mySQL->runQuery("call procPessoaListarUnico({$contrato['codContratante']})");
$ArrayDados = $mySQL->getArrayResult();
if (!empty($ArrayDados))
    $inquilino = arrayUtf8Decode($ArrayDados[0]);

if (!empty($ArrayDados))
    $inquilino_conjuge = arrayUtf8Decode($ArrayDados[0]);
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
<script type="text/javascript" src="../../biblioteca_js/jquery-1.11.0.min.js"></script>
<div style="width: 584px;">
    <p align="center"><b>CARTA DE BOAS VINDAS</b><br/><br/></p>

    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prezado(a),</span> <?= $inquilino['nome'] ?>,</p>
    
    
    <p class="justify">É com grande satisfação que a 
        <b>TABAKAL EMPREENDIMENTOS IMOBILIÁRIOS</b> o(a) recebe como <b>LOCATÁRIO(A)</b> do imóvel sito na <?= $imovel['endereco'] ?>.
    </p>
    
    <p class="justify">Temos como princípio básico a satisfação contínua dos nossos clientes, sejam eles <b>LOCADORES</b> ou <b>LOCATÁRIOS</b>.</p>
    
    <p class="justify">Pedimos atenção máxima principalmente com o Laudo Inicial de Vistoria e o Termo de Entrega de Documentos. São documentos 
        importantes na entrada no imóvel, que vão dar a segurança para uma relação saudável e sem transtornos. Com base nas informações do Laudo 
        Inicial de Vistoria, pedimos atenção especial para a manutenção e conservação do imóvel.</p>
    
    <p class="justify">Tomando os devidos cuidados temos plena certeza que, ao final do contrato, o imóvel estará bem conservado e o(a) senhor(a) 
        evitará maiores transtornos e gastos</p>

    <p>Seja bem vindo(a) e boa moradia.</p><br/><br/>

        <p align="right">Brasília-DF, <?= data() ?></p><br/><br/>

        <p align="center">________________________________________<br/>
            TABAKAL Empreendimentos Imobiliários<br/>
            Marleide de Araújo Teles<br/>
        </p>


</div>


