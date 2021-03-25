<?php

header('Content-Type: text/html; charset=iso-8859-1');
include_once("../conexao/conexao.php");

switch ($_GET['acao']) {
    case 'ListarLocador':
        $sql = "SELECT DISTINCT codPessoaLocador, nome FROM contrato c 
                INNER JOIN pessoa p ON (c.codPessoaLocador = p.codPessoa)
                WHERE {$_POST['ano']} BETWEEN DATE_FORMAT(c.dataInicio,'%Y') AND DATE_FORMAT(c.dataFim,'%Y')
                    AND c.codContrato NOT IN (SELECT codContrato FROM contratoEncerramento)
                    AND c.informouFim = 0
                ORDER BY nome
                ";

        $rs = $mySQL->runQuery($sql);
        while ($rsLinha = mysqli_fetch_assoc($rs)) {
            echo "<option value=\"{$rsLinha['codPessoaLocador']}\">" . utf8_decode($rsLinha['nome']) . "</option>";
        }
        break;

    case 'listarIPTU':
        include_once '../blocoHTML/tabelaIPTU.php';
        break;

    case 'salvarParcela':
        try {
            $sqlSelect = "SELECT id FROM IPTU WHERE codContrato = {$_POST['codContrato']} AND   ano = {$_POST['ano']}";
            $mySQL->runQuery($sqlSelect);
            $result = $mySQL->getArrayResult();
            if (count($result) > 0) {
                $sqlSave = "UPDATE IPTU 
                SET {$_POST['parcela']} = {$_POST['parcelaVal']}, SMSEnviado = 0
                WHERE id = {$result[0]['id']}";
            } else {
                $sqlSave = "INSERT INTO IPTU (codContrato, ano, {$_POST['parcela']}, SMSEnviado) 
                VALUES({$_POST['codContrato']}, {$_POST['ano']}, {$_POST['parcelaVal']}, 0) ";
            }
            if (!$mySQL->runQuery($sqlSave))
                throw new Exception("Não foi possivel salvar o registro");
        } catch (Exception $ex) {
            header('HTTP/1.1 500');
            echo $ex->getMessage();
        }
        break;

    case 'enviarSMS':
        try {
            $id = date('YmdHms');
            include_once '../human_gateway_client_api/HumanClientMain.php';
            $account = "gabrielneiva.api";
            $password = "FMFpl6N8lm";
            $humanMultipleSend = new HumanMultipleSend($account, $password);

            $telefone = $_POST['celular'];
            $nome = $_POST['pNome'];
            $iptu = $_POST['parcela'] . '/' .  $_POST['ano'];

            if (is_null($nome)) {
                throw new Exception("Nome inválido");
            }
            if (is_null($telefone)) {
                throw new Exception("Telefone inválido");
            }

            $tipo = HumanMultipleSend::TYPE_C;



            $msg_list = "$telefone; Prezado $nome, nao identificamos junto ao GDF a quitacao do IPTU $iptu. Se foi efetuado o pagamento, favor remeter copia do recibo.Tabakal Imobiliaria; IPTU-{$id}" . "\n";
            $callBack = HumanMultipleSend::CALLBACK_INACTIVE;
            $responses = $humanMultipleSend->sendMultipleList($tipo, $msg_list, $callBack);

            if ($responses[0]->getCode() == '200') {
            // if (true) {
                if ($mySQL->runQuery("UPDATE IPTU SET SMSEnviado = 1 
                                      WHERE codContrato = {$_POST['codContrato']} AND ano = {$_POST['ano']}")) {
                    echo json_encode('SMS enviado.\n Dados atualizados com sucesso');
                } else {
                    throw new Exception("SMS enviado com sucesso, mas não foi possivel salvar dados de envio");
                }
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
