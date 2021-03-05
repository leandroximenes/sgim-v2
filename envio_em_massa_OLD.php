<?php
session_start();
date_default_timezone_set('America/Sao_Paulo'); 
header('Content-Type: text/html; charset=iso-8859-1');
$servidor = "tabakalimoveis.com.br"; /* maquina a qual o banco de dados está */
$usuario = "tabaka_admin"; /* usuario do banco de dados MySql */
$senha = "a1b2c3d4"; /* senha do banco de dados MySql */
$banco = "tabaka_sistema"; /* seleciona o banco a ser usado */

$conexao = mysql_connect($servidor, $usuario, $senha);  /* Conecta no bando de dados MySql */

mysql_select_db($banco); /* seleciona o banco a ser usado */


//Altere a query abaixo colocando a tabela onde estão os telefones celulares
$pagamento = mysql_query("select pagamento.*, pessoa.nome, pessoa.email from pagamento 

INNER JOIN contrato on contrato.codContrato = pagamento.codContrato
INNER JOIN pessoa on contrato.codPessoaLocador = pessoa.codPessoa

where valorPagamento is not null AND enviouSms = 0");

$sqlAniversario = "SELECT *, DATE_FORMAT(dataNascimento,'%d/%m'), DATE_FORMAT(NOW(),'%d/%m'), informouAniversario, DATE_FORMAT(NOW(),'%y') FROM pessoa
 INNER JOIN pessoaFisica ON pessoaFisica.codPessoa = pessoa.codPessoa

 WHERE pessoa.codPessoa NOT IN (
  SELECT codPessoa FROM fiador WHERE codPessoa NOT IN ( SELECT codPessoaLocador FROM contrato)
                       AND codPessoa NOT IN ( SELECT codPessoaInquilino FROM contrato ) 
 ) 
AND DATE_FORMAT(dataNascimento,'%d/%m') = DATE_FORMAT(NOW(),'%d/%m')
AND (informouAniversario <> DATE_FORMAT(NOW(),'%y') OR informouAniversario is null)
"; 

$aniversario = mysql_query($sqlAniversario);

$fimContrato = mysql_query("
SELECT * FROM contrato where NOW() >= SUBDATE(dataFim, INTERVAL 30 DAY)
AND codContrato not in (SELECT codContrato FROM contratoEncerramento)
AND informouFim = 0");



if($_REQUEST['acao'] == 'contar'){
    $qtdAniversarios = mysql_num_rows($aniversario);
    $qtdPagamentos = mysql_num_rows($pagamento);
    $qtdFimContratos = mysql_num_rows($fimContrato);
    
    $retorno = json_encode(array('qtdAniversarios' => $qtdAniversarios, 'qtdPagamentos' => $qtdPagamentos, 'qtdFimContratos' => $qtdFimContratos));
    echo $retorno;
    die;
}

$mensagem = null;
if (mysql_num_rows($aniversario) > 0 && empty($mensagem)) {
    $row = mysql_fetch_array($aniversario);
    $codPessoa = $row['codPessoa'];
    $ddd = 61; //$row['ddd'];
    $celular = 81056006; //$row['celular'];
    $result2 = mysql_query("update pessoaFisica set informouAniversario = DATE_FORMAT(NOW(),'%y') where codPessoa = {$codPessoa}");
    $mensagem = "Seu aniversario e uma data especial. A TABAKAL deseja-lhe muita saúde, paz e alegria. Parabéns!";
}

if (mysql_num_rows($fimContrato) > 0 && empty($mensagem)) {
    $row = mysql_fetch_array($fimContrato);
    $codContrato = $row['codContrato'];
    $ddd = 61; //$row['ddd'];
    $celular = 81056006; //$row['celular'];
    //atualize o campo envio para true do registro atual, onde acabamos de pegar o celular
    $result2 = mysql_query("update contrato set informouFim = 1 where codContrato = {$codContrato}");

    $mensagem = "Seu contrato de locacao se encerra nos proximos 30 dias. Favor entrar em contato com a TABAKAL.";
}

if (mysql_num_rows($pagamento) > 0 && empty($mensagem)) {
    $row = mysql_fetch_array($pagamento);
    $codPagamento = $row['codPagamento'];
    $ddd = 61; //$row['ddd'];
    $celular = 81056006; //$row['celular'];

	$result2 = mysql_query("update pagamento set enviouSms = 1 where codPagamento=" . $codPagamento);
	
    ob_start();
    
    include("sgim/conexao/conexao.php");
    include("sgim/php/php.php");
    include("sgim/modulos/diversos/util.php");
    include('sgim/modulos/extrato/conteudo_pagamento.php');

    $extrato = ob_get_contents();
    ob_end_clean();
    ob_end_flush();
   
    $cabecalho = "MIME-Version: 1.0\n";
    $cabecalho .= "Content-Type: text/html; charset=UTF-8\n";
    $cabecalho .= "From: \TABAKAL\" <{$email_remetente}>\n";

	@mail('lelomagno@gmail.com', 'Extrato Locação _ ('.  utf8_decode($row['nome']).')', $extrato, $cabecalho);
    //@mail('lelomagno@gmail.com', 'teste', $extrato, $cabecalho);
    //atualize o campo envio para true do registro atual, onde acabamos de pegar o celular
    

    $mensagem = "A TABAKAL realizou, nesta data, creditos relativos a locacao de seu imovel. Favor consultar seu e-mail para maiores detalhes.";
}


if (!empty($mensagem)) {


    //url_retorno é o endereço onde está o arquivo envio_em_massa.php. Pode ser local (localhost) ou na web
    $url_retorno = "http://sgim.tabakalimoveis.com.br/envio_em_massa.php";

    $Usuario = "tabakal";
    $Senha = "123";
    ?>
    <html>
        <body onLoad="formulario.submit()">
            <form id="formulario" name="formulario" action="http://www.bb1.com.br/sms/envio_sms.asp" method="post">
                <input name="strUsuario" type="hidden" value="<?= $Usuario ?>" />
                <input name="strSenha" type="hidden" value="<?= $Senha ?>" />
                <input name="intDDD" type="hidden" value="<?= $ddd ?>" />
                <input name="intCelular" type="hidden" value="<?= $celular ?>" />
                <input name="memMensagem" type="hidden" value="<?= $mensagem ?>" />
                <input name="url_retorno" type="hidden" value="<?= $url_retorno ?>" />
                <input name="sms_marketing" type="hidden" value="nao" />
            </form>
        </body>
    </html>
    <?php
} else {
    mysql_close($conexao);
    ?>
    <script>
    parent.finalizaEnvio();
    </script>
    finalizou
    <?php
}
?>