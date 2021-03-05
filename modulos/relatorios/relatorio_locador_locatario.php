<?php
header('Content-Type: text/html; charset=iso-8859-1');
error_reporting(0);
try {
    if (isset($_GET['expWord'])) {
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=document_name.doc");
    }
    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');

    include_once ("../../conexao/conexao.php");
    include_once ("../../php/php.php");
    include_once ("../diversos/util.php");

    $sql = sprintf("CALL procContratoUnicoListar({$_GET['contrato']})");
    $rsContrato = $mySQL->runQuery($sql);
    $contrato = mysqli_fetch_assoc($rsContrato);

    $sql = sprintf("CALL procPessoaListarUnico({$contrato['codContratante']})");
    $rsinquilino = $mySQL->runQuery($sql);
    $inquilino = mysqli_fetch_assoc($rsinquilino);

    $sql = "SELECT * FROM pagamento
        WHERE codcontrato = {$_GET['contrato']} AND DATE_FORMAT(datavencimento,'%Y') = {$_GET['ano']} 
        ORDER BY datavencimento";
    $result = $mySQL->runQuery($sql);
    $rendimentos = $mySQL->getArrayResult();

    $titulo = isset($_GET['locador']) ? 'RENDIMENTOS' : 'PAGAMENTOS';
    $coluna = isset($_GET['locador']) ? 'RENDIMENTO BRUTO' : 'VALOR DO ALUGUEL';
    $tdAux = isset($_GET['locador']) ? '<td style="border:1px solid #000">0,00</td>' : '';
    ?>

    <style type="text/css">
        body{
            font-family:Sans-Serif;-webkit-print-color-adjust:exact
        }

        table{
            vertical-align:text-top;border-spacing:0;border-collapse:collapse;width:100%
        }

        table td{
            border:1px solid #000
        }

        .zebrada thead{
            font-weight:700
        }

        .zebrada tbody tr:nth-child(odd){
            background-color:#ccc
        }
    </style>

    </style>
    <script type="text/javascript" src="../../biblioteca_js/jquery-1.11.0.min.js"></script>
    <div style="width: 584px;">
        <table style="text-align: center;vertical-align:text-top;border-spacing:0;border-collapse:collapse;width:100%;border: 1px solid black">
            <tr>
                <td style="border:1px solid #000" colspan="2">
                    <b>MINISTÉRIO DA FAZENDA </b><br />
                    Secretaria da Receita Federal do Brasil <br />
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #000">
                    COMPROVANTE ANUAL DE <?= $titulo ?> DE ALUGUÉIS <br />
                </td>
                <td style="border:1px solid #000">
                    Ano Calendário: <?= $_GET['ano']; ?>
                </td>
            </tr>
        </table>
        <br/>
        <b>1 - Beneficiário do Rendimento (Locador)</b>
        <table style="vertical-align:text-top;border-spacing:0;border-collapse:collapse;width:100%;border: 1px solid black">
            <tr>
                <td style="border:1px solid #000">
                    <b>Nome / Nome Empresarial:</b><br />
                    <?= utf8_decode($contrato['proprietario']) ?>
                </td>
                <td width="30%" style='border:1px solid #000'>
                    <b>CPF / CNPJ:</b><br />
                    <?= mascaraCpf($contrato['cpf']) ?>
                </td>
            </tr>
        </table>
        <br />
        <b>2 - Fonte Pagadora (Locatário)</b>
        <table style="vertical-align:text-top;border-spacing:0;border-collapse:collapse;width:100%;border: 1px solid black">
            <tr>
                <td style="border:1px solid #000">
                    <b>Nome / Nome Empresarial:</b><br />
                    <?= utf8_decode($contrato['inquilino']) ?>
                </td>
                <td width="30%" style='border:1px solid #000'>
                    <b>CPF / CNPJ:</b><br />
                    <?= ($inquilino['cpf'] == null) ? mascaraCNPJ($inquilino['cnpj']) : mascaraCpf($inquilino['cpf']) ?>
                </td>
            </tr>
        </table>
        <br />
        <b>3 - Rendimentos (em Reais)</b>
        <table class="zebrada" style="vertical-align:text-top;border-spacing:0;border-collapse:collapse;width:100%;border: 1px solid black">
            <thead>
                <tr>
                    <td style="border:1px solid #000">Mês</td>
                    <td style="border:1px solid #000"><?= $coluna ?></td>
                    <?php if (isset($_GET['locador'])): ?>
                        <td style="border:1px solid #000">Valor Comissão</td>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalRendimento = 0;
                $totalComissao = 0;
                for ($mesAux = 1; $mesAux <= 12; $mesAux++) {
                    $auxExisteMes = 0;
                    $zebrada = $mesAux % 2 != 0 ? 'style=" background-color:#ccc"' : '';
                    foreach ($rendimentos as $value) {
                        $dataVencimento = new DateTime($value['dataVencimento']);
                        if ($mesAux == $dataVencimento->format('m')) {
                            if ($value['dataPagamento'] > 0) {
                                ?>
                                <tr <?= $zebrada ?>>
                                    <td style="border:1px solid #000"><?= (ucfirst(strftime('%B', strtotime(date_format($dataVencimento, 'Y-m-d'))))) ?></td>
                                    <td style="border:1px solid #000"><?= number_format($value['valor'] - $value['valorDesconto'], 2, ',', '.') ?></td>
                                    <?php
                                    if (isset($_GET['locador'])):
                                        $valorCondominio = round(($value['valor'] - $value['valorDesconto']) * $value['comissao'] / 100, 2);
                                        ?>

                                        <td style="border:1px solid #000"><?= number_format($valorCondominio, 2, ',', '.') ?></td>
                                <?php endif; ?>
                                </tr>
                                <?php
                                $totalRendimento += $value['valor'] - $value['valorDesconto'];
                                $totalComissao += $valorCondominio;
                                $auxExisteMes = 1;
                                break;
                            }
                        }
                    }
                    if ($auxExisteMes == 0) {
                        $dataVencimento = new DateTime($_GET['ano'] . '-' . $mesAux . '-01');
                        echo "<tr $zebrada>
                                    <td style='border:1px solid #000'>" . ucfirst(strftime('%B', strtotime(date_format($dataVencimento, 'Y-m-d')))) . "</td>
                                    <td style='border:1px solid #000'>0,00</td>
                                    $tdAux
                                  </tr>";
                    }
                }
                ?>
                <tr style="font-weight: bold">
                    <td style="border:1px solid #000">Total</td>
                    <td style="border:1px solid #000"><?= number_format($totalRendimento, 2, ',', '.') ?></td>
                    <?php if (isset($_GET['locador'])): ?>
                        <td style="border:1px solid #000"><?= number_format($totalComissao, 2, ',', '.') ?></td>
    <?php endif; ?>
                </tr>
            </tbody>
        </table>
        <br />
        <b>4 - Informações Complementares</b>
        <table style="vertical-align:text-top;border-spacing:0;border-collapse:collapse;width:100%;border: 1px solid black">
            <tr>
                <td style="border:1px solid #000">
    <?php if (isset($_GET['locador'])): ?>
                        <b>CNPJ da Administradora do Imóvel (Imobiliária):</b> 06.864.021/0001-31 <br />
                        <b>Nome:</b> TABAKAL EMPREENDIMENTOS IMOBLIARIOS LTDA<br />
                        <b>Endereço:</b> SCLN 309 BLOCO D SALAS 104/105 - ASA NORTE<br /><br />
    <?php endif; ?>
                    <b>DADOS DO IMÓVEL</b><br /><br />
                    <b>Número do Contrato:</b>  <?= $contrato['codContrato'] ?> &nbsp;&nbsp;&nbsp;<b>Data do Contrato:</b> <?= $contrato['dataInicio'] ?>&nbsp;&nbsp;&nbsp;<b>Tipo do Imóvel:</b> Urbano <br />
                    <b>Endereço do Imóvel:</b> <?= utf8_decode($contrato['endereco']) ?><br />
                    <b>UF:</b><?= $contrato['uf'] ?>&nbsp;&nbsp;&nbsp;<b>Município:</b> <?= utf8_decode($contrato['cidade']) ?>&nbsp;&nbsp;&nbsp;<b>CEP:</b> <?= mascaraCep($contrato['cep']) ?><br />
                    <br />


                </td>
            </tr>
        </table>
        <br />
        <b>5 - Informações Complementares</b>
        <table style="vertical-align:text-top;border-spacing:0;border-collapse:collapse;width:100%;border: 1px solid black">
            <tr>
                <td style="border:1px solid #000">
                    Nome<br /><br />
                    Marleide Teles
                </td>
                <td valign="top" width="30%" style="border:1px solid #000">
                    Data<br />
                </td>
                <td valign="top"  width="30%" style="border:1px solid #000">
                    Assinatura <br />
                </td>
            </tr>
        </table>
    </div>
    <?php
} catch (Exception $exc) {
    echo $exc->getTraceAsString();
}