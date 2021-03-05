<?php

session_start();
header('Content-Type: text/html; charset=iso-8859-1');
include '../diversos/util.php';
if (isset($_SESSION["SISTEMA_codPessoa"])) {

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

    function pagamentoCondominioListar() {
        global $mySQL;

        $codContrato = $_POST['codContrato'];
        $sql = sprintf("SELECT * FROM pagamentoCondominio WHERE codContrato = $codContrato");

        $rs = $mySQL->runQuery($sql);
        $rsQuant = $rs->num_rows;

        if ($rsQuant > 0) {
            while ($rsLinha = mysqli_fetch_assoc($rs)) {
                $arr[] = $rsLinha;
            }
            $json = JEncode($arr);
            echo '({"total":"' . $rsQuant . '","resultado":' . $json . '})';
        } else {
            echo '({"total":"0", "resultado":""})';
        }
    }

    function pagamentoCondominioGerenciar() {

        global $mySQL;

        if ($_POST['dataPagamento'] != "") {
            $dataPagamento = $_POST['dataPagamento'];
        } else {
            $dataPagamento = 0;
        }

        $codContrato = (int) $_POST['codContrato'];
        $codPagamento = (int) $_POST['codPagamentoCondominio'];
        $parcela = (int) $_POST['parcela'];
        $dataVencimento = $_POST['dataVencimento'];
        $dataPagamento = empty($_POST['dataPagamento']) ? "null" : "'{$_POST['dataPagamento']}'";
        $valor = str_replace(',', '.', $_POST['valor']);

        $sql = sprintf("call procPagamentoCondominioGerenciar($codContrato,$codPagamento,$parcela,'$dataVencimento', $dataPagamento, '$valor')");

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

    function gerarCondominio() {
        try {
            
            global $mySQL;
            
            $mySQL->connMySQL();
            $mySQL->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            
            $codContrato = $_POST['codContrato'];
            $vencimentoCondominio = inverteData($_POST['DataVencimentoCondominio']);
            $valorCondominio = str_replace(',', '.', str_replace("R$","",$_POST['valorCondominio']));
            $rs = $mySQL->runQuery("UPDATE contrato SET valorCondominio = $valorCondominio, vencimentoCondominio = '$vencimentoCondominio' WHERE codContrato = $codContrato");

            $rs = $mySQL->runQuery("SELECT * FROM contrato WHERE codContrato = $codContrato");
            $ArrayResult = $mySQL->getArrayResult();

            $qtdMeses = $ArrayResult[0]['qtdMeses'];
            $valorCondominio = $ArrayResult[0]['valorCondominio'];

            for ($i = 0; $i <= $ArrayResult[0]['qtdMeses']; $i++) {
                $vencimentoCondominio =  new DateTime($ArrayResult[0]['vencimentoCondominio']);
                $vencimentoCondominio->modify("+$i month");
                $dataFormatada = $vencimentoCondominio->format('Y-m-d');
                $sqlPagamentoCondominio = sprintf("call procPagamentoCondominioCadastrar($codContrato,$i,'$dataFormatada', '$valorCondominio')");
                $rsPagamento = $mySQL->runQuery($sqlPagamentoCondominio);
            }
            $mySQL->conn->commit();
            echo "{success:true}";
        } catch (Exception $exc) {
            $mySQL->conn->rollback();
            echo "{success:false}";
        }
    }

    /* ----------------------------------------------------------------------------------------------------------------------
      ------------------------------------------------------------------------------------------------------------------------
      Recebe o parametro que indica que função irá ser executada
      ------------------------------------------------------------------------------------------------------------------------
      --------------------------------------------------------------------------------------------------------------------- */


    $acao = "";
    if (isset($_POST['acao'])) {
        $acao = $_POST['acao'];
    }

    switch ($acao) {
        case "pagamentoCondominioListar":
            pagamentoCondominioListar();
            break;

        case "pagamentoCondominioGerenciar":
            pagamentoCondominioGerenciar();
            break;

       
        case "gerarCondominio":
            gerarCondominio();
            break;
    }
} else {
    header('location:login.php');
}
?>