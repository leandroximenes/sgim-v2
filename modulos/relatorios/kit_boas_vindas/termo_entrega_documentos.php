<?php
header('Content-Type: text/html; charset=iso-8859-1');
error_reporting(1);
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=document_termo_entrega_documentos_{$_GET[codContrato]}.doc");
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
include("../../../conexao/conexao.php");
include("../../../php/php.php");
include("../../diversos/util.php");

$mySQL->runQuery("call procContratoUnicoListar({$_GET['codContrato']})");
$ArrayDados = $mySQL->getArrayResult();
if (!empty($ArrayDados))
    $contrato = arrayUtf8Decode($ArrayDados[0]);

$mySQL->runQuery("call procPessoaListarUnico({$contrato['codContratante']})");
$ArrayDados = $mySQL->getArrayResult();
if (!empty($ArrayDados))
    $inquilino = arrayUtf8Decode($ArrayDados[0]);

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
    
    .recuo { text-indent:4em }
    
    .zebrada thead{
        font-weight:700
    }

    .zebrada tbody tr:nth-child(odd){
        background-color:#ccc
    }
</style>
<script type="text/javascript" src="../../biblioteca_js/jquery-1.11.0.min.js"></script>
<div style="width: 584px;">
    <p align="center"><b>TERMO DE ENTREGA DE DOCUMENTOS E ORIENTAÇÕES PARA O <?= strtoupper($inquilino['nome']) ?><br/>
            CONTRATO N.° <?= $contrato['codContrato'] ?></b><br/><br/></p>

    <p class="justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Eu,</span> <?= $inquilino['nome'] ?>, 
        na qualidade de <b>LOCATÁRIO(A)</b>, declaro ter recebido da <b>TABAKAL EMPREENDIMENTOS IMOBILIÁRIOS LTDA.</b>, 
        pessoa jurídica de direito privado, inscrita no CNPJ/MF n.º 06.864.021/0001-31, Inscrição Estadual n.º 07.457.662/001-02 e 
        Conselho Regional de Corretores de Imóveis - CRECI/DF n.º 9508, estabelecida na <b>SCLN QUADRA 309 BLOCO D Nº 50 SALAS 104/105, 
            70755-540, ASA NORTE, BRASÍLIA/DF</b>, os documentos e orientações relativas ao imóvel sito na <?= $contrato['endereco'] ?>, objeto 
        desta locação:</p><br/><br/>

    <b>DOCUMENTOS:</b><br/>

    <ul>
        <li><b>Recibo de Chaves </b>(declaro ter recebido uma via);</li>
        <li><b>Carta para  Síndico </b>(declaro ter recebido uma via);</li>
        <li><b>Carta de Boas Vindas </b>(declaro ter recebido uma via);</li>
        <li><b>Contrato de Locação </b>(declaro ter recebido uma via assinada);</li>
        <li><b>Termo de Vistoria </b>(declaro ter recebido uma via. Estou ciente do prazo de 7 dias corridos, a contar desta data, para entregar 
            contestação/observações, em 3 vias de igual teor);</li>
    </ul>


    <b>ORIENTAÇÕES E INFORMAÇÕES:</b><br/><br/>
    <p class="justify"> <b>Seguro Incêndio</b> - Comprometo-me a entregar proposta de contratação em até 7 dias da assinatura do contrato de locação, 
    e apólice em até 20 dias. O seguro é renovado a cada 12 meses e, caso não ocorra contratação/renovação, autorizo a cobrança, em única parcela, 
    em meu boleto de aluguel;</p>
    <p class="justify"><b>CEB inscrição, CAESB inscrição e GAS </b>- Estou ciente que, de posse do contrato de locação assinado com reconhecimento de firma do locador 
    e dos meus documentos pessoais, devo encaminhar às respectivas concessionárias pedido de religação de fornecimento de serviços e fazer alteração 
    cadastral ao consumidor, ainda que tenha fornecimento;</p>
    <p class="justify"><b>IPTU/TLP inscrição "Nr. IPTU"</b> - Estou ciente que devo entregar os comprovantes de pagamento na imobiliária e estar atento aos vencimentos 
    dos boletos enviados pelos correios ou retirar a 2ª via no site da SEFAZ (www.fazenda.df.gov.br). Em caso de dúvida deverei entrar em contato com 
    a imobiliária;</p>
    <p class="justify"><b>Taxas Extras, Atas e Nada Consta </b>- Estou ciente que devo entregar imediatamente na imobiliária boletos e atas das taxas extras, antes de 
    seus vencimentos. Caso eu venha efetuar os pagamentos,</p>
    devo apresentar os comprovantes e solicitar ressarcimento. Caso ocorra mora  por minha culpa terei que arcar com juros e multas. Estou ciente que 
    devo apresentar à administradora, a cada 06 meses, ou sempre que me for solicitado, o Nada Consta do Condomínio;<br/>
    <p class="justify"><b>Boleto de Aluguel </b>- Estou ciente que, caso não o receba em tempo hábil para o pagamento, é minha obrigação comparecer à imobiliária para 
    retirar um novo, antes do vencimento;</p>
    <p class="justify"><b>Imóveis com Garagem </b>- O controle deve ser devolvido juntamente com as chaves e em condições de uso. Caso contrário ficarei obrigado a 
    realizar a troca ou reparos necessários para o bom funcionamento;</p>
    <p class="justify"><b>Comprovantes de Pagamento </b>- Estou ciente que devo encaminhar trimestralmente todos os comprovantes de pagamento (Condomínio, Energia 
    Elétrica, Água, IPTU/TLP etc.). No caso do IPTU/TLP, deverei entregar todo ano o carnê quitado com os comprovantes originais, sabendo que se 
    trata de imposto e que o documento pertence ao LOCADOR(A).</p><br/><br/>

    <p align="right">Brasília-DF, <?= data() ?></p><br/><br/>

    <p align="center">________________________________________<br/>
        LOCATÁRIO: <?= $inquilino['nome'] ?><br/>
        CPF n.º <?= mascaraCpf($contrato['cpf']) ?> <br/>
    </p>


</div>


