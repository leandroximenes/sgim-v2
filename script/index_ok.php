<!DOCTYPE html>
    <html lang="pt-br">
      <head>
        <title>Título da página</title>
        <meta http-equiv="Content-Type" content="text/html;charset=iso8859-1">
      </head>
      <style type="text/css">
        body{
            
        }
        div{
            float: left;
            width:800px;
            
        }
        
      </style>

      <body>
        <div>
            <?php

            header('Content-Type: text/html; charset=iso8859-1');
            define('REPLACE_FLAGS', ENT_COMPAT | ENT_XHTML);
            ini_set('display_errors', 1);
            error_reporting(E_ALL ^ E_NOTICE);

            try {
                include("../conexao/conexao.php");

                $database = "u672794128_sgim";

                $mySQL->runQuery("show tables");
                $tables = $mySQL->getArrayResult();


                foreach ($tables as $table) {
                    $param = "Tables_in_$database";
                    $mySQL->runQuery("SHOW KEYS FROM {$table[$param]} WHERE Key_name = 'PRIMARY'");
                    $resultPrimary = $mySQL->getArrayResult();
                    echo "tablea: {$resultPrimary[0]['Table']} => PK: {$resultPrimary[0]['Column_name']}<br />";
                    alteraValoresTabela($resultPrimary[0]['Table'], $resultPrimary[0]['Column_name']);
                }
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }

            function alteraValoresTabela($tableName, $pkName) {
                global $mySQL;

                $mySQL->runQuery("SELECT * FROM $tableName");
                $result = $mySQL->getArrayResult();
                $sql_ok = '';
                foreach ($result as $resultKey => $colunas) {
                    $sql_ok = "UPDATE $tableName SET ";
                    foreach ($colunas as $colunasName => $colunaVal) {
                        if (!is_null($colunaVal) && $pkName != $colunasName) {
                            $decoded = utf8_decode($colunaVal);
                            // $decoded = utf8_decode($decoded);
                            // if(!mb_check_encoding($decoded, 'UTF-8')){
                                $sql_ok .= "$colunasName = '$decoded', ";
                            // }
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

                    echo "<pre>";
                    echo $sql_ok;
                    echo "</pre><br />";
                    //if(!$mysqli->query($sql_ok)){
                        //throw new Exception($mysqli->error);
                    //}
                    $mysqli->close();
                }
                echo "tudo certo com a tabela $tableName<br /><br />";
            }
            ?>
        </div>
    </body> 
</html>