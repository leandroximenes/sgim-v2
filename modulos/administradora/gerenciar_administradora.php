<?php

session_start();
header('Content-Type: text/html; charset=iso-8859-1');

include "../diversos/util.php";

if (isset($_SESSION["SISTEMA_codPessoa"])) {

    include("../../conexao/conexao.php");
    include("../../php/php.php");

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

    function administradoraListar() {
        global $mySQL;
        $pagamento = $mySQL->runQuery("SELECT codAdministradora, nome FROM administradora WHERE status = 1 ORDER BY nome");
        $rsQuant = $qtdPagamentos = mysqli_num_rows($pagamento);
        $ArrayResult = $mySQL->getArrayResult();

        if ($rsQuant > 0) {
            $json = JEncode($ArrayResult);
            echo '({"total":"' . $rsQuant . '","resultado":' . $json . '})';
        } else {
            echo '({"total":"0", "results":""})';
        }
    }

    function administradoraVwListar() {
        global $mySQL;
        $pagamento = $mySQL->runQuery("SELECT * FROM administradora ORDER BY nome");
        $rsQuant = $qtdPagamentos = mysqli_num_rows($pagamento);
        $ArrayResult = $mySQL->getArrayResult();

        if ($rsQuant > 0) {
            $json = JEncode($ArrayResult);
            echo '({"total":"' . $rsQuant . '","resultado":' . $json . '})';
        } else {
            echo '({"total":"0", "results":""})';
        }
    }

    function administradoraGerenciar() {

        global $mySQL;

        if (isset($_POST['codAdministradora'])) {
            $codAdministradora = $_POST['codAdministradora'];
        } else {
            $codAdministradora = 0;
        }

        if ($_POST['telefone'] <> "__________") {
            $telefone = str_replace("(", "", $_POST['telefone']);
            $telefone = str_replace(")", "", $telefone);
            $telefone = str_replace("-", "", $telefone);
        } else {
            $telefone = 0;
        }

        if ($_POST['telefone2'] <> "__________") {
            $telefone2 = str_replace("(", "", $_POST['telefone2']);
            $telefone2 = str_replace(")", "", $telefone2);
            $telefone2 = str_replace("-", "", $telefone2);
        } else {
            $telefone2 = 0;
        }

        $nome = $_POST['nome'];
        $email = strToUpper($_POST['email']);
        $endereco = $_POST['endereco'];
        $cep = str_replace("-", "", $_POST['cep']);
        $bairro = $_POST['bairro'];
        $uf = $_POST['uf'];
        $cidade = $_POST['cidade'];
        $observacao = $_POST['observacoes'];

        $sql = sprintf("call procAdministradoraGerenciar($codAdministradora,'$nome','$email','$telefone', '$telefone2', '$site', '$endereco', '$cep', '$bairro', '$uf', '$cidade', '$observacao')");

        try {
            if (!($rs = $mySQL->runQuery($sql))) {
                throw new Exception("Erro ao executar comando.");
            } else {
                echo "{success:true}";
            }
        } catch (Exception $e) {
            echo "{success:false}";
            exit;
        }
    }

    function procAdministradoraAtivarDesativar($acao) {

        global $mySQL;
        $codAdministradora = (integer) (isset($_POST['codAdministradora']) ? $_POST['codAdministradora'] : $_GET['codAdministradora']);
        $sql = sprintf("call procAdministradoraAtivarDesativar($acao, $codAdministradora)");

        try {
            if (!($rs = $mySQL->runQuery($sql))) {
                throw new Exception("Erro ao executar comando.");
            } else {
                echo "{success:true}";
            }
        } catch (Exception $e) {
            echo "{failure:true}";
            exit;
        }
    }

    $acao = "";
    if (isset($_POST['acao'])) {
        $acao = $_POST['acao'];
    }

    switch ($acao) {
        case "administradoraListar":
            administradoraListar();
            break;

        case "administradoraVwListar":
            administradoraVwListar();
            break;

        case "administradoraGerenciar":
            administradoraGerenciar();
            break;
        
        case "administradoraDesativar":
            procAdministradoraAtivarDesativar(0);
            break;
        
        case "administradoraAtivar":
            procAdministradoraAtivarDesativar(1);
            break;
    }
} else {
    header('location:login.php');
}
?>