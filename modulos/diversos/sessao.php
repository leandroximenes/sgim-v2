<?php
	session_start(); 

	echo '(
        {"total":"1",
        "resultado":[
            {"sessao":"' . htmlentities($_SESSION["SISTEMA_nome"]) . '",
              "id_perfil":"' . htmlentities($_SESSION["SISTEMA_perfil"]) . '"}
        ]})';

?>