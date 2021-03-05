<h3>Teste de email novo</h3>
<form method="post">
    Nome destinatario: <input style="margin-left: 10px; width: 300px" type="text" name="nome"/><br/>
    Endereco de email do destinatario: <input style="margin-left: 10px; width: 300px" type="text" name="email"/>
    <br/>
    <input type="submit" value="Enviar teste"/>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        include './PHPMailer/src/PHPMailer.php';
        include './PHPMailer/src/SMTP.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->Debugoutput = 'html';
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host = "tabakalimoveis.com.br";
        $mail->Port = 465; // or 587
        $mail->IsHTML(true);
        $mail->Username = "atendimento@tabakalimoveis.com.br";
        $mail->Password = "atendimento";
        $mail->SetFrom("atendimento@tabakalimoveis.com.br", "TABAKAL");
        $mail->Subject = "Teste";
        $mail->Body = "Testando o funcionamento do email. Nome teste: {$_POST['nome']}. Email enviado às " . date('d/m/Y h:s');
        $mail->AddAddress($_POST['email']);
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Mensagem enviada com sucesso";
        }
    } catch (Exception $exc) {
        echo $exc->getMessage();
    }
}


