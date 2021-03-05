<h3>Teste de Email</h3>
<form method="post">
    Nome destinatario: <input style="margin-left: 10px; width: 300px" type="text" name="nome"/><br/>
    Endereco de email do destinatario: <input style="margin-left: 10px; width: 300px" type="text" name="email"/>
    <br/>
    <input type="submit" value="Enviar teste"/>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

   try {
        $cabecalho = "MIME-Version: 1.0\n";
        $cabecalho .= "Content-Type: text/html; charset=UTF-8\n";
        $cabecalho .= "From: TABAKAL <atendimento@tabakalimoveis.com.br>\n";
        $cabecalho .= "Bcc: extratos@tabakalimoveis.com.br, atendimento@tabakalimoveis.com.br, tabakal.imoveis@hotmail.com, tabakalimoveis@hotmail.com";

        $respostaEnvioEmail = mail($_POST['email'], 'Testando o funcionamento do email. Nome teste: ' . ($_POST['nome']) . '', $extrato, $cabecalho);


        if ($respostaEnvioEmail) {
var_dump($respostaEnvioEmail);
            echo 'Email eviado com sucesso';

        } else {
            throw new Exception("não foi possivel enviar o email");

        }
    } catch (Exception $exc) {
        echo $exc->getMessage();

    }
}