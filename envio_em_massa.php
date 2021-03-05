<?php

session_start();

include("conexao/conexao.php");
include("php/php.php");
include("modulos/diversos/util.php");

date_default_timezone_set('America/Sao_Paulo');
header('Content-Type: text/html; charset=iso-8859-1');

//Altere a query abaixo colocando a tabela onde estão os telefones celulares
$pagamento = $mySQL->runQuery("select pagamento.*, pessoa.nome, pessoa.email, telefone.ddd, telefone.telefone from pagamento 
INNER JOIN contrato on contrato.codContrato = pagamento.codContrato
INNER JOIN pessoa ON (contrato.codPessoaLocador = pessoa.codPessoa AND pessoa.status = 1)
LEFT JOIN telefone on pessoa.codPessoa = telefone.codPessoa AND (telefone.telefone like '9%' OR telefone.telefone like '7%' OR telefone.telefone like '8%')
where valorPagamento is not null AND dataRepasse is not null  AND enviouSms = 0");
$qtdPagamentos = mysqli_num_rows($pagamento);
$ArrayPagamentos = $mySQL->getArrayResult();


$sqlAniversario = "SELECT *, DATE_FORMAT(dataNascimento,'%d/%m'), DATE_FORMAT(NOW(),'%d/%m'), informouAniversario, DATE_FORMAT(NOW(),'%y')
FROM pessoaFisica
INNER JOIN pessoa on pessoaFisica.codPessoa = pessoa.codPessoa
LEFT JOIN telefone on pessoa.codPessoa = telefone.codPessoa AND (telefone.telefone like '9%' OR telefone.telefone like '7%' OR telefone.telefone like '8%')
WHERE pessoaFisica.codPessoa NOT IN (
    SELECT codPessoa FROM pessoaGrupo 
    WHERE codGrupo = 6 )
AND DATE_FORMAT(dataNascimento,'%d/%m') = DATE_FORMAT(NOW(),'%d/%m')
AND (informouAniversario < DATE_FORMAT(NOW(),'%y') OR informouAniversario is null)
AND pessoa.status = 1
";

$aniversario = $mySQL->runQuery($sqlAniversario);
$qtdAniversarios = mysqli_num_rows($aniversario);
$Aniversariantes = $mySQL->getArrayResult();

$fimContrato = $mySQL->runQuery("
SELECT * FROM contrato 
INNER JOIN pessoa ON (contrato.codPessoaLocador = pessoa.codPessoa AND pessoa.status = 1)
LEFT JOIN telefone on pessoa.codPessoa = telefone.codPessoa AND (telefone.telefone like '9%' OR telefone.telefone like '7%' OR telefone.telefone like '8%')
where NOW() >= SUBDATE(dataFim, INTERVAL 30 DAY)
AND codContrato not in (SELECT codContrato FROM contratoEncerramento)
AND informouFim = 0");
$qtdFimContratos = mysqli_num_rows($fimContrato);
$ArrayResultadoFimContratos = $mySQL->getArrayResult();


if ($_REQUEST['acao'] == 'contar') {

    $retorno = json_encode(array('qtdAniversarios' => $qtdAniversarios, 'qtdPagamentos' => $qtdPagamentos, 'qtdFimContratos' => $qtdFimContratos));
    echo $retorno;
    die;
}

include_once 'human_gateway_client_api/HumanClientMain.php';
$account = "gabrielneiva.api";
$password = "FMFpl6N8lm";
$sender = new HumanSimpleSend($account, $password);


$mensagemAlerta = '';


if ($qtdAniversarios > 0) {
    foreach ($Aniversariantes as $value) {
        $codPessoa = $value['codPessoa'];
        $ddd = $value['ddd'];
        $celular = $value['telefone'];
        //$conexao = mysql_connect($servidor, $usuario, $senha); 

        if (!empty($ddd) && !empty($celular)) {
            try {
                $data = !empty($value['dataRepasse']) ? $value['dataRepasse'] : 'now';
                $message = new HumanSimpleMessage("Feliz aniversario! Que sua vida seja constantemente presenteada com bons e felizes momentos. Parabens! TABAKAL.", "55" . $ddd . $celular, "_hide", "ID-Aniversario-" . $codPessoa);
                $response = $sender->sendMessage($message);
                $response = $sender->queryStatus("ID" . $value['codPessoa'] . '-' . date('Y'));
                $statusEnvio = $response->getCode() . " -> " . $response->getMessage();
                $result2 = $mySQL->runQuery("update pessoaFisica set informouAniversario = DATE_FORMAT(NOW(),'%y') where codPessoa = {$codPessoa}");
            } catch (Exception $exc) {
                $mensagemAlerta .= "Mensagem para " . $value['nome'] . " - " . $exc->getMessage() . "\\n";
            }
        } else {
            $mensagemAlerta .= "Mensagem para " . $value['nome'] . " - Nao enviado (numero celular nao localizado) \\n";
        }
    }
}

if ($qtdFimContratos > 0 && empty($mensagemAlerta)) {
    foreach ($ArrayResultadoFimContratos as $value) {
        $codContrato = $value['codContrato'];
        $ddd = $value['ddd'];
        $celular = $value['telefone'];

        if (!empty($ddd) && !empty($celular)) {
            $mensagem = "Seu contrato de locacao se encerra nos proximos 30 dias. Favor entrar em contato com a TABAKAL.";
            try {
                $data = !empty($value['dataRepasse']) ? $value['dataRepasse'] : 'now';
                $message = new HumanSimpleMessage($mensagem, "55" . $ddd . $celular, "_hide", "ID" . $value['codContrato']);
                $response = $sender->sendMessage($message);
                $response = $sender->queryStatus("ID" . $value['codContrato'] . '-' . $value['codPessoa'] . '-' . date('Y'));
                $statusEnvio = $response->getCode() . " -> " . $response->getMessage();
                $result2 = $mySQL->runQuery("update contrato set informouFim = 1 where codContrato = {$codContrato}");
            } catch (Exception $exc) {
                $mensagemAlerta .= "Mensagem para " . $value['nome'] . " - " . $exc->getMessage() . "\\n";
            }
        } else {
            $mensagemAlerta .= "Mensagem para " . $value['nome'] . " - Nao enviado (numero celular nao localizado) \\n";
        }
        //atualize o campo envio para true do registro atual, onde acabamos de pegar o celular
    }
}

if ($qtdPagamentos > 0 && empty($mensagemAlerta)) {

    $cabecalho = "MIME-Version: 1.0\n";
    $cabecalho .= "Content-Type: text/html; charset=UTF-8\n";
    $cabecalho .= "From: TABAKAL <atendimento@tabakalimoveis.com.br>\n";
    $cabecalho .= "Bcc: tabakal.imoveis@hotmail.com, extratos@tabakalimoveis.com.br";

    $codPagamento = "";

    foreach ($ArrayPagamentos as $value) {
        $codPagamento = $value['codPagamento'];
        $codPagamentoTodos .= $codPagamento . ',';
        $ddd = $value['ddd'];
        $celular = $value['telefone'];

        $valorRecebido = $value['valorMulta'] > 0 ? $value['valor'] + $value['valorMulta'] : $value['valor'] - $value['valorDesconto'];
        $valorComissao = ($valorRecebido * $value['comissao']) / 100;
        $repace = $valorRecebido - $valorComissao - $value['valorGastoServico'] - $value['valorGastoInquilino'];

        ob_start();
        include('modulos/extrato/conteudo_pagamento.php');

        $extrato = ob_get_clean();
        $respostaEnvioEmail = mail($value['email'], 'Extrato Locação _ (' . utf8_decode($value['nome']) . ')', $extrato, $cabecalho);

        if ($respostaEnvioEmail) {


            if (!empty($ddd) && !empty($celular)) {

                try {
                    $data = !empty($value['dataRepasse']) ? $value['dataRepasse'] : 'now';
                    $message = new HumanSimpleMessage("A TABAKAL realizou em " . date('d/m/Y', strtotime($data)) . " credito de R$ " . number_format($repace, 2, ',', '.') . " relativo a locacao de seu imovel. Favor consultar seu e-mail. ", "55" . $ddd . $celular, "_hide", "ID" . $value['codPagamento']);
                    $response = $sender->sendMessage($message);
                    $response = $sender->queryStatus("ID" . $value['codPagamento']);
                    $statusEnvio = $response->getCode() . " -> " . $response->getMessage();
                    $result2 = $mySQL->runQuery("update pagamento set enviouSms = 1 where codPagamento = $codPagamento ");
                    // $mensagemAlerta .= "Mensagem para " . $value['nome'] . " - Status envio " . $statusEnvio . "\\n";
                } catch (Exception $exc) {
                    $mensagemAlerta .= "Mensagem para " . $value['nome'] . " - " . $exc->getTraceAsString() . "\\n";
                }
            } else {
                $mensagemAlerta .= "Mensagem para " . $value['nome'] . " - Não enviado (numero celular Não localizado) \\n";
            }
        } else {
            $mensagemAlerta .= "Não foi possivel enviar o email para {$value['nome']}";
        }
    }
}
echo json_encode(array('msng' => $mensagemAlerta));
