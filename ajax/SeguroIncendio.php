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

            $telefone = $_POST['celular'];
            $nome = $_POST['nome'];

            if(is_null($nome)){
                throw new Exception("Nome inválido");
            }
            if(is_null($telefone)){
                throw new Exception("Telefone inválido");
            }

            $tipo = HumanMultipleSend::TYPE_C;
            // $msg_list = "556181056006; Teste de envio de sms Aurélio; ID-Teste-A-{$id}\n";
            // $msg_list .= "556191341099; Teste de envio de sms Leandro; ID-Teste-L-{$id}";
            $msg_list = "$telefone; Prezado $nome, nao identificamos a renovacao do seguro contra incendio. Caso ja tenha efetuado, favor remeter copia da apolice. Tabakal Imobiliaria; SE-{$id}"."\n";
            $callBack = HumanMultipleSend::CALLBACK_INACTIVE;
            $responses = $humanMultipleSend->sendMultipleList($tipo, $msg_list, $callBack);

            if ($responses[0]->getCode() == '200') {
                echo json_encode('Envio de sms com sucesso');
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
