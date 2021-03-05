<?php
header('Content-Type: text/html; charset=iso-8859-1');
try {
    $id = date('YmdHms');
    include_once '../human_gateway_client_api/HumanClientMain.php';
    $account = "gabrielneiva.api";
    $password = "FMFpl6N8lm";
    $humanMultipleSend = new HumanMultipleSend($account, $password);

    $tipo = HumanMultipleSend::TYPE_C;
    $msg_list = "556181056006; Teste de envio de sms Aurélio; ID-Teste-A-{$id}\n";
    $msg_list .= "556191341099; Teste de envio de sms Leandro; ID-Teste-L-{$id}";
    $callBack = HumanMultipleSend::CALLBACK_INACTIVE;
    $responses = $humanMultipleSend->sendMultipleList($tipo, $msg_list, $callBack);

    if ($responses[0]->getCode() == '200') {
        echo '<h4>Envio de sms com sucesso</h4>';
    } else {
        echo '<h4>Erro ao eviar o sms</h4>';
    }

    echo '<br/><br/><br/> Detalhes do envio:<br/>';
    var_dump($responses);
} catch (Exception $exc) {
        echo '<h4>Erro no servidor ao executar a operação</h4>';
    echo "Erro: " .  $exc->getMessage(). "<br/><br/>";
    echo "Trace: <pre>" . print_r($exc->getTraceAsString()) . '</pre>';
}