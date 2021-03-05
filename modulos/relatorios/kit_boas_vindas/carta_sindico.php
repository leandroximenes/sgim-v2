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

$mySQL->runQuery("call procPessoaListarUnico({$contrato['codProprietario']})");
$ArrayDados = $mySQL->getArrayResult();
if (!empty($ArrayDados))
    $proprietario = arrayUtf8Decode($ArrayDados[0]);

$mySQL->runQuery("call procPessoaListarUnico({$contrato['codContratante']})");
$ArrayDados = $mySQL->getArrayResult();
if (!empty($ArrayDados))
    $inquilino = arrayUtf8Decode($ArrayDados[0]);

$mySQL->runQuery("call procPessoaTelefoneListar({$contrato['codContratante']})");
$ArrayDados = $mySQL->getArrayResult();
if (!empty($ArrayDados))
    $inquilino_telefone = arrayUtf8Decode($ArrayDados[0]);

$mySQL->runQuery("call procPessoaConjugeListar({$contrato['codContratante']})");
$ArrayDados = $mySQL->getArrayResult();
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
    <p align="center"><b>COMUNICADO DE NOVO LOCATÁRIO(A)CONTRATO N.° <?= $contrato['codContrato'] ?></b><br/><br/></p>

    <p align="center"><b>Prezado Síndico do <Endereço Imóvel>, N.° <?= $imovel['endereco'] ?></b><br/><br/></p>

    <p class="justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Comunicamos a Vossa Senhoria que o imóvel 
        situado à <?= $imovel['endereco'] ?>, de propriedade do(a) Sr.(a) <?= $proprietario['nome'] ?>, <?= $proprietario['nacionalidade'] ?>, 
        <?= $proprietario['profissao'] ?>, por nós administrado, foi alugado para o Sr.(a)  <?= $inquilino['nome'] ?>, <?= $inquilino['nacionalidade'] ?>, 
        <?= $inquilino['profissao'] ?>, <?= $inquilino['estadoCivil'] ?>, inscrito no CPF <?= mascaraCpf($inquilino['cpf']) ?> e 
        RG <?= $inquilino['rg'] ?> <?= $inquilino['orgaoExpedidor'] ?>, 
        <?php if (!empty($inquilino_conjuge)): ?>
            <?= $inquilino_conjuge['nome'] ?>, <?= $inquilino_conjuge['nacionalidade'] ?>, <?= $inquilino_conjuge['profissao'] ?>, inscrito no CPF 
            <?= mascaraCpf($inquilino_conjuge['cpf']) ?> e RG <?= $inquilino_conjuge['rg'] ?> <?= $inquilino_conjuge['orgaoExpedidor'] ?>,  

        <?php endif; ?>
        pelo período de <?= $contrato['qtdMeses'] ?> meses, tendo seu início em <?= $contrato['dataInicio'] ?> e término 
        em <?= $contrato['dataFim'] ?>, ficando o(a) LOCATÁRIO(A), a partir dessa data, responsável pelo pagamento das taxas de condomínio.
    </p>   

    <p class="justify">
        Solicitamos ainda nos comunicar todo e qualquer assunto relativo ao imóvel em referência, principalmente cobrança de <b>TAXAS EXTRAS</b>, 
        correspondências, comunicações eventuais e especialmente atrasos no pagamento de condomínios. Dessa forma poderemos colaborar com a 
        sua Administração no recebimento de eventuais atrasos e solução de outros problemas.
    </p>

    <p class="justify">
        As <b>TAXAS EXTRAS</b>, poderão ser encaminhadas ao <b>LOCATÁRIO(A)</b> para pagamento ou serem cobradas em nosso escritório, sempre 
        acompanhada da <b>ATA DA ASSEMBLEIA</b> que deliberou o assunto. Queremos lembrar que, caso opte pelo recebimento em nosso escritório não, 
        pagamos multa por atraso, uma vez que efetuamos o pagamento no ato da apresentação.
    </p>

    <p class="justify">
        Para maiores esclarecimentos, estamos a sua disposição, em nosso escritório sito à SCLN QUADRA 309 BLOCO D Nº 50 SALAS 104/105, 70755-540, 
        ASA NORTE, BRASÍLIA/DF ou pelos telefones (61) 3340-0921 / 98190-1122, em horário comercial.
    </p>

    <p class="justify">
        Trabalhamos em parceria com condomínios e muito agradeceríamos qualquer indicação para locação de imóveis eventualmente vagos ou até mesmo venda
        nesse condomínio.
    </p><br/><br/>


    <p align="right">Brasília-DF, <?= data() ?></p><br/><br/>
    
    <p>Atenciosamente,</p><br/><br/>

    <p align="center">________________________________________<br/>
        TABAKAL Empreendimentos Imobiliários<br/>
        Marleide de Araújo Teles<br/>
    </p>


</div>


