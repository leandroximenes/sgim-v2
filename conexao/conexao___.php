<?php
	$database_type = "mysql";
	$database_default = "tabaka_sistema";
	$database_hostname = "tabakalimoveis.com.br";
        $database_username = "tabaka_admin";
        $database_password = "a1b2c3d4";
        $database_port = "3306";

	define("CONFIGDATABASE_DEFAULT", $database_default);
	define("CONFIGDATABASE_HOSTNAME", $database_hostname);
	define("CONFIGDATABASE_USERNAME", $database_username);
	define("CONFIGDATABASE_PASSWORD", $database_password);
	define("CONFIGINSTALL_TYPE", "REMOTO");

class MySQL {
	var $host = CONFIGDATABASE_HOSTNAME;
	var $usr = CONFIGDATABASE_USERNAME;
	var $pw  = CONFIGDATABASE_PASSWORD;
	var $db  = CONFIGDATABASE_DEFAULT;
	
	var $sql; // Query - instrução SQL
	var $conn; // Conexão ao banco
	var $resultado; // Resultado de uma consulta

	function MySQL() {
	}

	// Esta função conecta-se ao banco de dados e o seleciona.
	function connMySQL() {
		$this->conn = mysqli_connect($this->host, $this->usr, $this->pw, $this->db);
		if (!$this->conn) {
			echo "<p>Não foi possível conectar-se ao servidor MySQL.</p>\n" .
			"<p><strong>Erro MySQL: " . mysqli_connect_error() . "</strong></p>\n";
			exit();
		} elseif (!mysqli_select_db($this->conn, $this->db)) {
			echo "<p>Não foi possível selecionar o Banco de Dados desejado.</p>\n" .
			"<p><strong>Erro MySQL: " . mysqli_error($this->conn) . "</strong></p>\n";
			exit();
		}
	}

	//Função para executar SPs (Stored Prodeures). Utiliza-se a função mysqli_multi_query() porque //as SPs retornam mais de um conjunto de resultados e a função mysqli_query() não consegue //trabalhar com respostas múltiplas, ocasionando eventuais erros.
	function execSP($sql) {
		$this->connMySQL();
		$this->sql = $sql;
		if (mysqli_multi_query($this->conn, $this->sql)) {
			$this->resultado = mysqli_store_result($this->conn);
			$row = mysqli_fetch_row($this->resultado);
			$this->closeConnMySQL();
			mysqli_free_result($this->resultado);
			return $row[0];
		} else {
		echo "<p>Não foi possível executar a seguinte instrução " . "SQL:</p><p><strong>$sql</strong></p>\n"."<p>Erro MySQL: " . mysqli_error($this->conn) . "</p>";
		exit();
		$this->closeConnMySQL();
		}
	}

	function runQuery($sql) {
		$this->connMySQL();
		$this->sql = $sql;
		try
		{ 
			if($this->resultado = mysqli_query($this->conn, $this->sql)) {
				$this->closeConnMySQL();
				return $this->resultado;
			} else {
				throw new Exception("Erro ao executar comando."); 
				exit();
				$this->closeConnMySQL();	
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	//Função para encerramento da conexão com o banco de dados.
	function closeConnMySQL() {
		return mysqli_close($this->conn);
	}
	
        
        function prepareStmt($sql){
            $stmt = mysqli_prepare($this->conn, $sql);
            return $stmt;
        }
        
        function executeStmt($stmt){
           return mysqli_stmt_execute($stmt);
        }
        
        function fechRow($stmt){
           return sqlite_fetch_single($stmt);
        }
        
} // Finaliza a classe MySQL

//Instancia novo objeto da classe MySQL.
$mySQL = new MySQL;



class MySQL2 {
	var $host = CONFIGDATABASE_HOSTNAME;
	var $usr = CONFIGDATABASE_USERNAME;
	var $pw  = CONFIGDATABASE_PASSWORD;
	var $db  = CONFIGDATABASE_DEFAULT;
	
	var $sql; // Query - instrução SQL
	var $conn; // Conexão ao banco
	var $resultado; // Resultado de uma consulta

	function MySQL() {
	}

	// Esta função conecta-se ao banco de dados e o seleciona.
	function connMySQL() {
		$this->conn = $link = mysql_connect($this->host, $this->usr, $this->pw);
		if (!$this->conn) {
			echo "<p>Não foi possível conectar-se ao servidor MySQL.</p>\n" .
			"<p><strong>Erro MySQL: " . mysql_error() . "</strong></p>\n";
			exit();
		} elseif (!mysql_select_db($this->db, $this->conn)) {
			echo "<p>Não foi possível selecionar o Banco de Dados desejado.</p>\n" .
			"<p><strong>Erro MySQL: " . mysql_error($this->conn) . "</strong></p>\n";
			exit();
		}
	}

	//Função para executar SPs (Stored Prodeures). Utiliza-se a função mysqli_multi_query() porque //as SPs retornam mais de um conjunto de resultados e a função mysqli_query() não consegue //trabalhar com respostas múltiplas, ocasionando eventuais erros.


	function runQuery($sql) {
		$this->connMySQL();
		$this->sql = $sql;
		try
		{ 
			if($this->resultado = mysql_query($this->sql, $this->conn)) {
				$this->closeConnMySQL();
				return $this->resultado;
			} else {
				throw new Exception("Erro ao executar comando."); 
				exit();
				$this->closeConnMySQL();	
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	//Função para encerramento da conexão com o banco de dados.
	function closeConnMySQL() {
		return mysql_close($this->conn);
	}
	
        
        function prepareStmt($sql){
            $stmt = mysqli_prepare($this->conn, $sql);
            return $stmt;
        }
        
        function executeStmt($stmt){
           return mysqli_stmt_execute($stmt);
        }
        
        function fechAssoc($stmt){
           return mysql_fetch_assoc($stmt);
        }
        
} // Finaliza a classe MySQL

?>
