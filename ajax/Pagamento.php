<?php
header('Content-Type: text/html; charset=iso-8859-1');

switch ($_GET['acao']) {
    case 'enviarSMSEmailInquilino':
        try {
            $cabecalho = "MIME-Version: 1.0\n";
            $cabecalho .= "Content-Type: text/html; charset=UTF-8\n";
            $cabecalho .= "From: TABAKAL <atendimento@tabakalimoveis.com.br>\n";
//        $cabecalho .= "Bcc: extratos@tabakalimoveis.com.br, tabakal.imoveis@hotmail.com, tabakalimoveis@hotmail.com, gneivamachado@gmail.com";
            $cabecalho .= "Bcc: tabakal.imoveis@hotmail.com, lelomagno@gmail.com, leandroj.r.ximenes@gmail.com";

            ob_start();
            include '../blocoHTML/EmailInquilinoCobranca.php';
            $content = ob_get_clean();

            $idEnvolvido = $contrato['codContratante'];

            $queryEmail = $mySQL->runQuery("SELECT email FROM pessoa WHERE codPessoa = $idEnvolvido");
            $resultEmail = $mySQL->getArrayResult();
            $email = $resultEmail[0]['email'];
            $resu = mail($email, "Comprovante Pagamento Aluguel", $content, $cabecalho);

            if ($resu) {
                if (atualizarTabelaPagamento($ids, $mySQL)) {
                    include_once '../human_gateway_client_api/HumanClientMain.php';
                    $account = "gabrielneiva.api";
                    $password = "FMFpl6N8lm";
                    $humanMultipleSend = new HumanMultipleSend($account, $password);

                    $queryTelefone = $mySQL->runQuery("SELECT * FROM telefone WHERE codTipoTelefone = 2 AND codPessoa = $idEnvolvido");
                    $resultTelefone = $mySQL->getArrayResult();
                    $telefone = "55{$resultTelefone[0]['ddd']}{$resultTelefone[0]['telefone']}";
                    $id = date('YmdHms');
                    $tipo = HumanMultipleSend::TYPE_C;
                    $msg_list = "$telefone; {$contrato['inquilino']}, a TABAKAL enviou para seu e-mail solicitação de comprovante de pagamento do aluguel do imóvel locado por Vossa Senhoria.; ID-CA-E-{$id}\n";
                    $msg_list .= "556181056006; {$contrato['inquilino']},a TABAKAL enviou para seu e-mail solicitação de comprovante de pagamento do aluguel do imóvel locado por Vossa Senhoria.; ID-CA-A-{$id}\n";
                    $callBack = HumanMultipleSend::CALLBACK_INACTIVE;
                    $responses = $humanMultipleSend->sendMultipleList($tipo, $msg_list, $callBack);
                    if ($responses[0]->getCode() != '200') {
                        throw new Exception('Não foi possivel enviar o SMS');
                    }
                    echo json_encode(array(
                        'result' => 'Processo realizado com sucesso',
                        'icon' => "pagamento_{$contrato['codContrato']}"
                    ));
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

    case 'enviarSMSEmailInquilinoCondominio':
        try {
            $cabecalho = "MIME-Version: 1.0\n";
            $cabecalho .= "Content-Type: text/html; charset=UTF-8\n";
            $cabecalho .= "From: TABAKAL <atendimento@tabakalimoveis.com.br>\n";
//        $cabecalho .= "Bcc: extratos@tabakalimoveis.com.br, tabakal.imoveis@hotmail.com, tabakalimoveis@hotmail.com, gneivamachado@gmail.com";
            $cabecalho .= "Bcc: tabakal.imoveis@hotmail.com, lelomagno@gmail.com";

            ob_start();
            include '../blocoHTML/EmailInquilinoCobrancaCondominio.php';
            $content = ob_get_clean();

            $idEnvolvido = $contrato['codContratante'];

            $queryEmail = $mySQL->runQuery("SELECT email FROM pessoa WHERE codPessoa = $idEnvolvido");
            $resultEmail = $mySQL->getArrayResult();
            $email = $resultEmail[0]['email'];
            $resu = mail($email, "Comprovante Pagamento Condomínio", $content, $cabecalho);

            if ($resu) {
                if (atualizarTabelaPagamentocondominio($ids, $mySQL)) {
                    include_once '../human_gateway_client_api/HumanClientMain.php';
                    $account = "gabrielneiva.api";
                    $password = "FMFpl6N8lm";
                    $humanMultipleSend = new HumanMultipleSend($account, $password);

                    $queryTelefone = $mySQL->runQuery("SELECT * FROM telefone WHERE codTipoTelefone = 2 AND codPessoa = $idEnvolvido");
                    $resultTelefone = $mySQL->getArrayResult();
                    $telefone = "55{$resultTelefone[0]['ddd']}{$resultTelefone[0]['telefone']}";
                    $id = date('YmdHms');
                    $tipo = HumanMultipleSend::TYPE_C;
                    $msg_list = "$telefone; {$contrato['inquilino']}, a TABAKAL enviou para seu e-mail solicitação de comprovante de pagamento do condomínio do imóvel locado por Vossa Senhoria.; ID-CA-E-{$id}\n";
                    $msg_list .= "556181056006; {$contrato['inquilino']}, a TABAKAL enviou para seu e-mail solicitação de comprovante de pagamento do condomínio do imóvel locado por Vossa Senhoria.; ID-CA-A-{$id}\n";
                    $callBack = HumanMultipleSend::CALLBACK_INACTIVE;
                    $responses = $humanMultipleSend->sendMultipleList($tipo, $msg_list, $callBack);
                    if ($responses[0]->getCode() != '200') {
                        throw new Exception('Não foi possivel enviar o SMS');
                    }
                    echo json_encode(array(
                        'result' => 'Processo realizado com sucesso',
                        'icon' => "condominio_{$contrato['codContrato']}"
                    ));
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

    default :
        break;
}

function atualizarTabelaPagamento($ids, $mySQL) {
    $a = $mySQL->runQuery("
            UPDATE pagamento SET enviouEmailSmsAtraso = 1 
            WHERE codPagamento IN($ids) ");
    return $a;
}

function atualizarTabelaPagamentocondominio($ids, $mySQL) {
    $a = $mySQL->runQuery("
            UPDATE pagamentoCondominio SET enviouEmailSmsAtraso = 1 
            WHERE codPagamentoCondominio IN($ids) ");
    return $a;
}
