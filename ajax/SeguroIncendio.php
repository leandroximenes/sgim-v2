<?php

header('Content-Type: text/html; charset=iso-8859-1');
include("../conexao/conexao.php");
include("../php/php.php");
include_once("../modulos/diversos/util.php");

switch ($_GET['acao']) {

    case 'enviarSMS':
        try {
            $id = date('YmdHms');
            include_once '../human_gateway_client_api/HumanClientMain.php';
            $account = "gabrielneiva.api";
            $password = "FMFpl6N8lm";
            $humanMultipleSend = new HumanMultipleSend($account, $password);

            $telefone = '5561986092074';
            $arrayNome[0] = 'Leandro';

            $tipo = HumanMultipleSend::TYPE_C;
            // $msg_list = "556181056006; Teste de envio de sms Aurélio; ID-Teste-A-{$id}\n";
            // $msg_list .= "556191341099; Teste de envio de sms Leandro; ID-Teste-L-{$id}";
            $msg_list = "$telefone; {$arrayNome[0]}, 
            Prezado fulano, não identificamos em nosso sistema a renovação do seguro contra incêndio do imóvel 
            locado por vossa senhoria. Caso já tenha efetuado, favor desconsiderar essa mensagem e remeter cópia 
            da apólice para efetuarmos a devida baixa.
            Atenciosamente
            Tabakal Imobiliária
            ; ID-IR-E-{$id}\n";
            $callBack = HumanMultipleSend::CALLBACK_INACTIVE;
            $responses = $humanMultipleSend->sendMultipleList($tipo, $msg_list, $callBack);

            if ($responses[0]->getCode() == '200') {
                echo 'Envio de sms com sucesso';
            } else {
                throw new Exception("Erro ao eviar o sms");
            }
        } catch (Exception $exc) {
            header('HTTP/1.1 500');
            echo $exc->getMessage();
        }
        break;
    default:
        break;
}
