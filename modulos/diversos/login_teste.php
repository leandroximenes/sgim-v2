<?php
header("Content-Type: text/html; charset=ISO-8859-1", true);
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$_POST['login'] = 'teste@teste.com';
$_POST['senha'] = '123';

session_start();
include("../../conexao/conexao.php");

function JEncode($arr) {
    if (version_compare(PHP_VERSION, "5.2", "<")) {
        require_once("./JSON.php");
        $json = new Services_JSON();
        $data = $json->encode($arr);
    } else {
        //utf_prepare($arr);
        $data = json_encode($arr);
    }
    return $data;
}

global $mySQL;


$email = $_POST['login'];
$senha = $_POST['senha'];

$sql = sprintf("call procUsuarioLogin('$email','$senha')");

try {
    if (!($rs = $mySQL->runQuery($sql))) {
        throw new Exception("Erro ao executar comando.");
    } else {

        if ($rs->num_rows > 0) {
            $linha = mysqli_fetch_assoc($rs);
            $_SESSION["SISTEMA_codPessoa"] = $linha['codPessoa'];
            $_SESSION["SISTEMA_nome"] = $linha['nome'];
            //$_SESSION['SISTEMA_grupo']     = $linha['codSistemaGrupo'];

            echo "{success:true}";
        } else {
            echo '{failure:true, file:"' . htmlentities("Login e/ou senha inválidos!") . '",tipo:"2"}';
        }
    }
} catch (Exception $e) {
    echo '{failure:true, file:"' . htmlentities("Login e/ou senha inválidos!") . '",tipo:"2"}';
}
?>