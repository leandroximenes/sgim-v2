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
    <p align="center"><b>RECIBO DE ENTREGA DE CHAVES DO IMÓVEL CONTRATO N.° <?= $contrato['codContrato'] ?></b><br/><br/></p>

    <p class="justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Eu,</span> <?= $inquilino['nome'] ?>, <?= $inquilino['nacionalidade'] ?>, <?= $inquilino['profissao'] ?>, 
        <?= $inquilino['estadoCivil'] ?>, 
        inscrito no CPF <?= mascaraCpf($inquilino['cpf']) ?> e RG <?= $inquilino['rg'] ?> <?= $inquilino['orgaoExpedidor'] ?>, na qualidade de LOCATÁRIO(A), declaro ter 
        recebido da TABAKAL EMPREENDIMENTOS IMOBILIÁRIOS LTDA, pessoa jurídica de direito privado, inscrita no 
        CNPJ/MF n.º 06.864.021/0001-31, Inscrição Estadual n.º 07.457.662/001-02  e Conselho Regional de Corretores de 
        Imóveis - CRECI n.º 9508, estabelecida na SCLN QUADRA 309 BLOCO D Nº 50 SALAS 104/105, 70755-540, ASA NORTE, BRASÍLIA/DF, 
        as chaves do imóvel sito na <?= $imovel['endereco'] ?>
        </p><br/><br/><br/><br/>

        <p align="right">Brasília-DF, <?= data() ?></p><br/><br/>

        <p align="center">________________________________________<br/>
            LOCATÁRIO: <?= $inquilino['nome'] ?><br/>
            CPF n.º <?= mascaraCpf($contrato['cpf']) ?> <br/>
        </p>


</div>


