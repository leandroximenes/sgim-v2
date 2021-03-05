<?php
header('Content-Type: text/html; charset=iso-8859-1');

//include("../../conexao/conexao.php");
//include("../../php/php.php");


$idsProprietários = implode(",", $_POST['id_proprietarios']);
$ano = $_POST['ano'];
$sql = " SELECT 
            c.codContrato, 
            pl.nome as nomeLocatario, 
            pl.email as emailLocatario,
            pl.telefones as telefonesLocatario,
            pl.cpf as cpfLocatario,
            pi.nome as nomeInquilino, 
            pi.email as emailInquilino,
            pi.telefones as telefonesInquilino,
            pi.cpf as cpfInquilino,
            er.envio_locador, 
            er.envio_inquilino,
            er.declarante_locador,
            er.declarante_inquilino,
            er.comunicado_locador,
            er.comunicado_inquilino
        FROM contrato c 
        INNER JOIN 
		(select p.codPessoa, p.nome, p.email, pf.cpf, GROUP_CONCAT(concat_ws('-',t.ddd, t.telefone)) as telefones from pessoa p
		left join pessoaFisica pf on (p.codPessoa = pf.codPessoa and p.codTipoPessoa = 1)
		left join telefone t on (p.codPessoa = t.codPessoa)
		group by p.codPessoa) pl 
	ON (c.codPessoaLocador = pl.codPessoa)
        INNER JOIN 
		(select p.codPessoa, p.nome, p.email, pf.cpf, GROUP_CONCAT(concat_ws('-',t.ddd, t.telefone)) as telefones from pessoa p
		left join pessoaFisica pf on (p.codPessoa = pf.codPessoa and p.codTipoPessoa = 1)
		left join telefone t on (p.codPessoa = t.codPessoa)
		group by p.codPessoa) pi
	ON (c.codPessoaInquilino = pi.codPessoa)
LEFT JOIN envio_relatorio_ir er ON (er.codContrato = c.codContrato AND er.ano = $ano)
        WHERE codPessoaLocador IN ($idsProprietários) 
        AND {$ano} BETWEEN DATE_FORMAT(dataInicio,'%Y') AND DATE_FORMAT(dataFim,'%Y')
        ORDER BY nomeLocatario, nomeInquilino ";

$rs = $mySQL->runQuery($sql);
$rsQuant = $rs->num_rows;

if ($rsQuant > 0 && count($_POST['id_proprietarios']) > 0) {
    while ($rsLinha = mysqli_fetch_assoc($rs)) {
        ?>
        <tr>
            <td class="parent"><?= utf8_decode($rsLinha['nomeLocatario']) ?>
                <div class="popup">
                    cpf: <?= $rsLinha['cpfLocatario'] ?><br/>
                    telefone: <?= str_replace(',__-________', '', $rsLinha['telefonesLocatario'])   ?><br/>
                    email: <?= utf8_decode($rsLinha['emailLocatario']) ?><br/>
                </div>
            </td>
            <td align="center">
                <a href="relatorio_locador_locatario.php?contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>&locador=true" target="_blank"><img src="../../img/ic_relatorio.gif"></a>
                <a href="relatorio_locador_locatario.php?contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>&locador=true&expWord=true" target="_blank"><img src="../../img/word-icon.png"></a>
            </td>
            <td align="center">
                <?php if (is_null($rsLinha['declarante_locador']) || $rsLinha['declarante_locador'] == 0): ?>
                    <a class="sendMailIR" href="relatorio_locador_locatario.php?acao=confirmarDeclarante&locador=true&contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>" target="_blank">
                        <img src="../../img/nt-declarante.png">
                    </a>
                <?php else: ?>
                    <a class="sendMailIR" href="relatorio_locador_locatario.php?acao=confirmarDeclarante&locador=true&contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>" target="_blank">
                        <img src="../../img/ok-declarante.png">
                    </a>
                <?php endif; ?>
            </td>
            <td align="center">
                <?php if (is_null($rsLinha['comunicado_locador']) || $rsLinha['comunicado_locador'] == 0): ?>
                    <a class="sendMailIR" href="relatorio_locador_locatario.php?acao=ligar&locador=true&contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>" target="_blank">
                        <img src="../../img/nt-phone.png">
                    </a>
                <?php else: ?>
                    <a class="sendMailIR" href="relatorio_locador_locatario.php?acao=ligar&locador=true&contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>" target="_blank">
                        <img src="../../img/ok-phone.png">
                    </a>
                <?php endif; ?>
            </td>
            <td align="center">
                <?php if (is_null($rsLinha['envio_locador'])): ?>
                    <a class="sendMailIR" href="relatorio_locador_locatario.php?acao=enviarEmail&locador=true&contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>" target="_blank">
                        <img src="../../img/nt-sendemail.png">
                    </a>
                <?php else: ?>
                    <a class="sendMailIR" href="relatorio_locador_locatario.php?acao=enviarEmail&locador=true&contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>" target="_blank">
                        <img src="../../img/ok-sendemail.png">
                    </a>
                <?php endif; ?>
            </td>
            <td class="parent"><?= utf8_decode($rsLinha['nomeInquilino']) ?>
                <div class="popup">
                    cpf: <?= $rsLinha['cpfInquilino'] ?><br/>
                    telefone: <?= str_replace(',__-________', '', $rsLinha['telefonesInquilino'])   ?><br/>
                    email: <?= utf8_decode($rsLinha['emailInquilino']) ?><br/>
                </div>
            </td>
            <td align="center">
                <a href="relatorio_locador_locatario.php?contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>" target="_blank"><img src="../../img/ic_relatorio.gif"></a>&nbsp
                <a href="relatorio_locador_locatario.php?contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>&expWord=true" target="_blank"><img src="../../img/word-icon.png"></a>
            </td>
            <td align="center">
                <?php if (is_null($rsLinha['declarante_inquilino']) || $rsLinha['declarante_inquilino'] == 0): ?>
                    <a class="sendMailIR" href="relatorio_locador_locatario.php?acao=confirmarDeclarante&contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>" target="_blank">
                        <img src="../../img/nt-declarante.png">
                    </a>
                <?php else: ?>
                    <a class="sendMailIR" href="relatorio_locador_locatario.php?acao=confirmarDeclarante&contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>" target="_blank">
                        <img src="../../img/ok-declarante.png">
                    </a>
                <?php endif; ?>
            </td>
            <td align="center">
                <?php if (is_null($rsLinha['comunicado_inquilino']) || $rsLinha['comunicado_inquilino'] == 0): ?>
                    <a class="sendMailIR" href="relatorio_locador_locatario.php?acao=ligar&contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>" target="_blank">
                        <img src="../../img/nt-phone.png">
                    </a>
                <?php else: ?>
                    <a class="sendMailIR" href="relatorio_locador_locatario.php?acao=ligar&contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>" target="_blank">
                        <img src="../../img/ok-phone.png">
                    </a>
                <?php endif; ?>
            </td>
            <td align="center">
                <?php if (is_null($rsLinha['envio_inquilino'])): ?>
                    <a class="sendMailIR" href="relatorio_locador_locatario.php?acao=enviarEmail&contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>" target="_blank">
                        <img src="../../img/nt-sendemail.png">
                    </a>
                <?php else: ?>
                    <a class="sendMailIR" href="relatorio_locador_locatario.php?acao=enviarEmail&contrato=<?= $rsLinha['codContrato'] ?>&ano=<?= $ano ?>" target="_blank">
                        <img src="../../img/ok-sendemail.png">
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }
} else {
    echo '<tr><td colspan="6">Nenhum inquilino para o locatário selecinado</td></tr>';
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('.parent').mouseover(function () {
            $(this).children().show();
        });
        $('.parent').mouseout(function () {
            $(this).children().hide();
        });
    });

    $('.sendMailIR').on('click', function (e) {
        e.preventDefault();
        var _this = $(this);
        var src = _this.children().attr('src');
        var separe_src = src.split("-");
        var value = separe_src[0].substring(10, 12);

        _this.children().attr('src', '../../img/loading.gif');
        var link = _this.attr('href').split("?");

        $.ajax({
            type: "POST",
            async: false,
            dataType: "json",
            url: '../../ajax/ImpostoRenda.php?' + link[1],
            data: {value: value},
            success: function () {
                if (separe_src[1] == 'sendemail.png') {
                    _this.children().attr('src', '../../img/ok-' + separe_src[1]);
                } else {
                    //inverte
                    console.log(separe_src[0].substring(10, 12));
                    if (separe_src[0].substring(10, 12) == 'ok') {
                        _this.children().attr('src', '../../img/nt-' + separe_src[1]);
                    } else {
                        _this.children().attr('src', '../../img/ok-' + separe_src[1]);
                    }
                }
            },
            error: function (e) {
                if (separe_src[1] == 'sendemail.png') {
                    _this.children().attr('src', src);
                } else {
                    //inverte
                    if (separe_src[0].substring(10, 12) == 'ok') {
                        _this.children().attr('src', '../../img/nt-' + separe_src[1]);
                    } else {
                        _this.children().attr('src', '../../img/ok-' + separe_src[1]);
                    }
                }
                alert(e.responseText);
            }
        });
    });
</script>

