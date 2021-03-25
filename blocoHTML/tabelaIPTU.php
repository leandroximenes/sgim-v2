<?php
header('Content-Type: text/html; charset=iso-8859-1');

include_once("../conexao/conexao.php");
include_once("../php/php.php");
include_once("../modulos/diversos/util.php");


$idsProprietários = implode(",", $_POST['id_proprietarios']);
$ano = $_POST['ano'];
$sql = "SELECT *, c.codContrato AS codigoContrato, ti.nome AS tipoImovel,
            (SELECT nome FROM pessoa WHERE c.codPessoaLocador = codPessoa) AS nomeLocador,
            (SELECT nome FROM pessoa WHERE c.codPessoaInquilino = codPessoa) AS nomeInquilino,
            (SELECT CONCAT(ddd, telefone) FROM telefone WHERE codTipoTelefone = 2 and codPessoa = c.codPessoaInquilino) as celular
        FROM contrato c
        INNER JOIN imovel i ON (c.codImovel = i.codImovel)
        INNER JOIN tipoImovel ti ON (ti.codTipoImovel = i.codTipoImovel)
        LEFT JOIN IPTU ip ON (c.codContrato = ip.codContrato AND ano = {$_POST['ano']})
        WHERE c.codContrato NOT IN (SELECT codContrato FROM contratoEncerramento) 
            AND c.codPessoaLocador IN ($idsProprietários) ;
        ";

try {
    $rs = $mySQL->runQuery($sql);
    $rsQuant = $rs->num_rows;
} catch (Exception $exc) {
    echo $exc->getMessage();
}
if ($rsQuant > 0 && count($_POST['id_proprietarios']) > 0) {
    while ($rsLinha = mysqli_fetch_assoc($rs)) {
        $imgSMS = $rsLinha['SMSEnviado'] == 1 ? "ok-sendemail" : "nt-sendemail";
?>
        <tr>
            <td class="parent codContrato" align="center"><?= ($rsLinha['codigoContrato']) ?></td>
            <td class="parent"><?= utf8_decode($rsLinha['endereco']) ?></td>
            <td class="parent"><?= utf8_decode($rsLinha['cidade']) ?></td>
            <td class="parent"><?= utf8_decode($rsLinha['nomeLocador']) ?></td>
            <td class="parent nome"><?= utf8_decode($rsLinha['nomeInquilino']) ?></td>
            <td class="parent"><?= $rsLinha['tipoImovel'] ?></td>
            <td class="parent"><?= $rsLinha['nIptu'] ?></td>

            <?php for ($i = 1; $i <= 6; $i++) : ?>
                <td>
                    <select class="cmbParcela" name='parcela<?= $i ?>'>
                        <option value="0" <?= $rsLinha['parcela' . $i] == '0' ? ' selected="selected"' : ''; ?>>
                            Não Pago
                        </option>
                        <option value="1" <?= $rsLinha['parcela' . $i] == '1' ? ' selected="selected"' : ''; ?>>
                            Pago
                        </option>
                    </select>
                </td>

            <?php endfor; ?>
            <td>
                <input type="hidden" class="telefone" value="<?= $rsLinha['celular'] ?>" />
                <a class="sendSMS" href="#">
                    <img src="../../img/<?= $imgSMS ?>.png">
                </a>
            </td>
        </tr>
<?php
    }
} else {
    echo '<tr><td colspan="6">Nenhum inquilino para o locatário selecinado</td></tr>';
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.parent').mouseover(function() {
            $(this).children().show();
        });
        $('.parent').mouseout(function() {
            $(this).children().hide();
        });
    });

    $('.sendMailIR').on('click', function(e) {
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
            data: {
                value: value
            },
            success: function() {
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
            error: function(e) {
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