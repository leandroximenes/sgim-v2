<!DOCTYPE html>
    <html lang="pt-br">
      <head>
        <title>Título da página</title>
        <meta http-equiv="Content-Type" content="text/html;charset=iso8859-1">
      </head>
      <body>
        <?php

        header('Content-Type: text/html; charset=iso8859-1');
        define('REPLACE_FLAGS', ENT_COMPAT | ENT_XHTML);
        ini_set('display_errors', 1);
        error_reporting(E_ALL ^ E_NOTICE);

        $arrayColum = array();

        try {
            include("../conexao/conexao.php");

            $database = "u672794128_sgim";

            $mySQL->runQuery("show tables");
            $tables = $mySQL->getArrayResult();

            echo "START TRANSACTION;";
            foreach ($tables as $table) {
                $param = "Tables_in_$database";
                $mySQL->runQuery("SHOW KEYS FROM {$table[$param]} WHERE Key_name = 'PRIMARY'");
                $resultPrimary = $mySQL->getArrayResult();
                // echo "tablea: {$resultPrimary[0]['Table']} => PK: {$resultPrimary[0]['Column_name']}<br />";
                if(!in_array($table[$param], array('vwPessoa', 'telefone', 'uf'))){
                    alteraValoresTabela($resultPrimary[0]['Table'], $resultPrimary[0]['Column_name']);
                }
            }
            echo "COMMIT;";
            echo "<pre>";
            // print_r($arrayColum);
            echo "</pre><br />";
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }

        function alteraValoresTabela($tableName, $pkName) {
            global $mySQL;
            global $arrayColum;

            $mySQL->runQuery("SELECT * FROM $tableName");
            $result = $mySQL->getArrayResult();
            $sql_ok = '';
            foreach ($result as $resultKey => $colunas) {
                $sql_ok = "UPDATE $tableName SET ";
                $aux = 0;
                foreach ($colunas as $colunasName => $colunaVal) {
                    if (!is_null($colunaVal) && $pkName != $colunasName) {
                        $decoded = utf8_decode($colunaVal);
                        $decoded = utf8_decode($decoded);
                        if (
                            (!strpos($decoded, '?') !== false) && 
                            (!is_numeric($decoded)) && 
                            (!in_array($colunasName, 
                                array(
                                    'dataNascimento', 
                                    'rg', 
                                    'dataFim',
                                    'dataInicio',
                                    'data',
                                    'conta',
                                    'agencia',
                                    'dataCadastro',
                                    'telefoneCondominio',
                                    'telefoneSindico',
                                    'nCeb',
                                    'latitude',
                                    'longitude',
                                    'nCaesb',
                                    'nCeb',
                                    'nIptu',
                                    'dataVencimento',
                                    'dataPagamento',
                                    'dataRepasse',
                                    'senha',
                                    'telefone2',
                                    'uf',
                                    'cep',
                                    'telefone',
                                    'cpf',
                                    'vencimentoCondominio',
                                    'ufTrabalho',
                                    'sexo',
                                    'cepTrabalho',
                                    )
                                )
                            )
                        ){ // checa se deu erro
                            $slash = addslashes($decoded);
                            $sql_ok .= "$colunasName = '$slash', ";
                            $aux ++;
                            $arrayColum[$colunasName] = $colunasName;
                        }
                    }
                }
                $sql_ok .= "WHERE $pkName = {$colunas[$pkName]};";
                $sql_ok = str_replace(", WHERE", "  WHERE", $sql_ok);

                $mysqli = new mysqli("localhost", "u672794128_admin", "Tabakal&sgim01", "sgim_ok");

                if ($mysqli->connect_errno) {
                    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
                    exit();
                }

                // Change character set to utf8
                $mysqli->set_charset("utf8");

                if($aux > 0){
                    echo "<pre>";
                    echo $sql_ok;
                    echo "</pre><br />";
                }
                //if(!$mysqli->query($sql_ok)){
                    //throw new Exception($mysqli->error);
                //}
                $mysqli->close();
            }
            // echo "tudo certo com a tabela $tableName<br /><br />";
        }
        ?>
    </body>
</html>