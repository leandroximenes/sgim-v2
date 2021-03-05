<?php

header('Content-Type: text/html; charset=iso-8859-1');
include("../conexao/conexao.php");
include("../php/php.php");
include("../OLD-10-03-14-modulos/diversos/util.php");

switch ($_GET['acao']) {
    case 'trazerInquilino':
        $_POST['mySQL'] = $mySQL;
        include_once '../blocoHTML/tabelaInquilinosIR.php';
        break;
    
    case 'enviarEmail':
        try {
            $cabecalho = "MIME-Version: 1.0\n";
            $cabecalho .= "Content-Type: text/html; charset=UTF-8\n";
            $cabecalho .= "From: \TABAKAL\" <atendimento@tabakalimoveis.com.br>\n";
//        $cabecalho .= "Bcc: extratos@tabakalimoveis.com.br, tabakal.imoveis@hotmail.com, tabakalimoveis@hotmail.com, gneivamachado@gmail.com";
            $cabecalho .= "Bcc: tabakal.imoveis@hotmail.com, lelomagno@gmail.com";

            ob_start();
            include '../modulos/relatorios/relatorio_locador_locatario.php';
            $content = ob_get_clean();

            $titulo = isset($_GET['locador']) ? $contrato['proprietario'] : $inquilino['nome'];
            $idEnvolvido = isset($_GET['locador']) ? $contrato['codProprietario'] : $inquilino['codPessoa'];
            $finalmsg = isset($_GET['locador']) ? " locação de seu imovel." : " sua locação";
            $arrayNome = explode(' ', strtoupper(utf8_decode($titulo)));
            
            $queryEmail = $mySQL->runQuery("SELECT email FROM pessoa WHERE codPessoa = $idEnvolvido");
            $resultEmail = $mySQL->getArrayResult();
            $email = $resultEmail[0]['email'];
            $resu = mail($email, "COMPROVANTE ANUAL IRPF {$_GET['ano']} - {$titulo}", $content, $cabecalho);

            $queryTelefone = $mySQL->runQuery("SELECT * FROM telefone WHERE codTipoTelefone = 2 AND codPessoa = $idEnvolvido");
            $resultTelefone = $mySQL->getArrayResult();
            $telefone = "55{$resultTelefone[0]['ddd']}{$resultTelefone[0]['telefone']}";
            if ($resu) {
                if (atualizarTabela('enviar', $mySQL)) {
                    echo json_encode(array(
                        'result' => 'Processo realizado com sucesso'
                    ));
                    include_once '../human_gateway_client_api/HumanClientMain.php';
                    $account = "gabrielneiva.api";
                    $password = "FMFpl6N8lm";
                    $humanMultipleSend = new HumanMultipleSend($account, $password);

                    $id = date('YmdHms');
                    $tipo = HumanMultipleSend::TYPE_C;
                    $msg_list = "$telefone; {$arrayNome[0]}, a TABAKAL enviou para seu e-mail a declaração anual IRPF de {$_GET['ano']} relativo a {$finalmsg}; ID-IR-E-{$id}\n";
//                    $msg_list = "556181056006; {$arrayNome[0]}, a TABAKAL enviou para seu e-mail a declaração anual IRPF de {$_GET['ano']} relativo a {$finalmsg}; ID-IR-A-{$id}\n";
//                    $msg_list .= "556191341099; {$arrayNome[0]}, a TABAKAL enviou para seu e-mail a declaração anual IRPF de {$_GET['ano']} relativo a {$finalmsg}; ID-IR-L-{$id}";
                    $callBack = HumanMultipleSend::CALLBACK_INACTIVE;
                    $responses = $humanMultipleSend->sendMultipleList($tipo, $msg_list, $callBack);
                    if ($responses[0]->getCode() != '200') {
                        throw new Exception('Não foi possivel enviar o SMS');
                    }
                } else {
                    throw new Exception('Não foi possivel completar o processo');
                }
            } else {
                throw new Exception('Erro ao enviar email');
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
        break;
        
    case 'ListarLocador':
        $sql = "SELECT DISTINCT codPessoaLocador, nome FROM (
                        SELECT DISTINCT c.codPessoaLocador, p.nome, 
                                (SELECT COUNT(dataPagamento) 
                                FROM pagamento p 
                                WHERE c.codContrato = p.codContrato 
                                        AND dataPagamento is not null 
                                        AND {$_POST['ano']} BETWEEN DATE_FORMAT(dataPagamento,'%Y') 
                                        AND DATE_FORMAT(dataPagamento,'%Y')
                                ) as meses_pagos
                        FROM contrato c 
                        INNER JOIN pessoa p ON (c.codPessoaLocador = p.codPessoa)
                        WHERE {$_POST['ano']} BETWEEN DATE_FORMAT(c.dataInicio,'%Y') AND DATE_FORMAT(c.dataFim,'%Y')
                ) AS T1
                WHERE T1.meses_pagos > 0
                ORDER BY nome";

        $rs = $mySQL->runQuery($sql);
        while ($rsLinha = mysqli_fetch_assoc($rs)) {
            echo "<option value=\"{$rsLinha['codPessoaLocador']}\">" . utf8_decode($rsLinha['nome']) . "</option>";
        }
        break;

    case 'ligar':
        if (atualizarTabela('ligar', $mySQL)) {
            echo json_encode(array(
                'result' => 'Processo realizado com sucesso'
            ));
        } else {
            throw new Exception('Não foi possivel atualizar o comunicado');
        }
        break;

    case 'confirmarDeclarante':
        if (atualizarTabela('declarar', $mySQL)) {
            echo json_encode(array(
                'result' => 'Processo realizado com sucesso'
            ));
        } else {
            throw new Exception('Não foi possivel atualizar o declarante');
        }
        break;

    default:
        break;
}

function atualizarTabela($coluna, $mySQL) {
    $value = $_POST['value'] == 'ok' ? 0 : 1;

    $sql = "SELECT * FROM envio_relatorio_ir
                    WHERE codContrato = {$_GET['contrato']} AND ano = {$_GET['ano']}";
    $result = $mySQL->runQuery($sql);
    $resutEnvioIR = $mySQL->getArrayResult();
    $idEnvioIR = $resutEnvioIR[0]['id'];

    switch ($coluna) {
        case 'declarar':
            $colunasInsert = 'declarante_locador, declarante_inquilino';
            $colunaUpdate = isset($_GET['locador']) ? " SET declarante_locador = $value " : " SET declarante_inquilino = $value";
            break;
        case 'ligar':
            $colunasInsert = 'comunicado_locador, comunicado_inquilino';
            $colunaUpdate = isset($_GET['locador']) ? " SET comunicado_locador = $value" : " SET comunicado_inquilino = $value";
            break;
        case 'enviar':
            $colunasInsert = 'envio_locador, envio_inquilino';
            $colunaUpdate = isset($_GET['locador']) ? " SET envio_locador = 1" : " SET envio_inquilino = 1";
            break;
        default:
            break;
    }


    if (is_null($idEnvioIR)) {
        $set = isset($_GET['locador']) ? ' 1, null' : ' null, 1';
        $sqlSave = "INSERT INTO envio_relatorio_ir (codContrato, ano, $colunasInsert)
                        VALUES ({$_GET['contrato']}, {$_GET['ano']}, $set);";
    } else {
        $sqlSave = "UPDATE envio_relatorio_ir " . $colunaUpdate . " WHERE id = $idEnvioIR";
    }

    return $mySQL->runQuery($sqlSave);
}
