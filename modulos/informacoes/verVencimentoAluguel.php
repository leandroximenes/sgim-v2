<?php

include("../../conexao/conexao_simples.php");

global $mySQLAlert;

$sql = "select distinct c.qtdMeses, DATE_ADD(c.dataInicio, INTERVAL 11 month) as dataAviso, pf.nome, i.endereco from pagamento as p 
inner join contrato as c on p.codContrato = c.codContrato
inner join pessoa as pf on pf.codPessoa = c.codPessoaInquilino
inner join imovel as i on i.codImovel = c.codImovel
where c.codContrato not in (SELECT codContrato FROM contratoEncerramento)
AND 
c.qtdMeses > 12 
AND (
     (
      DATE_ADD(NOW(), INTERVAL -7 day) < DATE_ADD(c.dataInicio, INTERVAL 12 month) 
      AND 
      DATE_ADD(NOW(), INTERVAL 7 day) > DATE_ADD(c.dataInicio, INTERVAL 12 month)
     )
      OR
     (
      DATE_ADD(NOW(), INTERVAL -7 day) < DATE_ADD(c.dataInicio, INTERVAL 24 month) 
      AND 
      DATE_ADD(NOW(), INTERVAL 7 day) > DATE_ADD(c.dataInicio, INTERVAL 24 month)
     ) 
    )";
$q = mysql_query($sql);
$rsQuant = mysql_num_rows($q);

if ($rsQuant > 0) {
    echo true;
} else {
    echo false;
}