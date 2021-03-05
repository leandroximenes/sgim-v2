<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

$titulo = 'Kit de boas vindas ';

if (isset($_SESSION["SISTEMA_codPessoa"])) {

    include("../../conexao/conexao.php");
    include("../../php/php.php");
    include("../diversos/util.php");
    global $mySQL;

    $mySQL->runQuery("call procContratoUnicoListar({$_GET['codContrato']})");
    $ArrayDados = $mySQL->getArrayResult();
    if (!empty($ArrayDados))
        $contrato = arrayUtf8Decode($ArrayDados[0]);

    $mySQL->runQuery("call procImovelUnicoListar({$contrato['codImovel']})");
    $ArrayDados = $mySQL->getArrayResult();
    if (!empty($ArrayDados))
        $imovel = arrayUtf8Decode($ArrayDados[0]);

    $mySQL->runQuery("call procPessoaListarUnico({$contrato['codProprietario']})");
    $ArrayDados = $mySQL->getArrayResult();
    if (!empty($ArrayDados))
        $proprietario = arrayUtf8Decode($ArrayDados[0]);

    $mySQL->runQuery("call procPessoaListarUnico({$contrato['codContratante']})");
    $ArrayDados = $mySQL->getArrayResult();
    if (!empty($ArrayDados))
        $inquilino = arrayUtf8Decode($ArrayDados[0]);

    $mySQL->runQuery("call procPessoaTelefoneListar({$contrato['codContratante']})");
    $ArrayDados = $mySQL->getArrayResult();
    if (!empty($ArrayDados))
        $inquilino_telefone = arrayUtf8Decode($ArrayDados[0]);

    $mySQL->runQuery("call procPessoaConjugeListar({$contrato['codContratante']})");
    $ArrayDados = $mySQL->getArrayResult();
    if (!empty($ArrayDados))
        $inquilino_conjuge = arrayUtf8Decode($ArrayDados[0]);
    ?>
    <style>
        body{
            margin-top: 15px;
            font-size: 11px;
            font-family: arial;
        }

        #tb-ir{
            font-size: 13px;
            width: 100%
        }

        #tb-ir tr:hover
        {
            background: #f7dcdf!important;
        }

        #tb-ir tr:nth-child(odd)
        {
            background: #F8F8FF; 
        }       

        .clearfix:after {
            content: ".";
            display: block;
            clear: both;
            visibility: hidden;
            height: 0;
        }

        table{
            font-size: 11px;
            font-family: arial;
        }

        th{
            background: #19688F;
            font-weight: bold;
            color: #FFF;
        }

        .vermelho{
            color: #990000;
        }

        .verde{
            color: #31CC2E;
        }

        #cabecalhoRelatorio{
            width: 100%;
            margin-bottom: 10px;
            height: 62px;
            background: url('img/bg_topo.jpg') bottom repeat-x;
            float: left;
        }

        #logoRelatorio{
            float: left;
        }

        #tituloRelatorio{
            float: right;
            text-align: right;
        }

        #nomeRelatorio{
            padding-top: 15px;
            font-size: 18px;
            color: #19688F;
        }


    </style>
    <script type="text/javascript" src="../../biblioteca_js/jquery-1.11.0.min.js"></script>


    <table border='0' width='500' cellpadding='3' cellspacing='1'>
        <thead>
            <tr>
                <td colspan='2'> 
                    <div id='cabecalhoRelatorio' class='clearfix'>
                        <div id='logoRelatorio'>
                            <img src='img/logo.jpg' />
                        </div>
                        <div id='tituloRelatorio'>
                            <div id='nomeRelatorio'>
                                <?php echo $titulo; ?>
                            </div>
                            <?php echo data(); ?>
                        </div>

                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <table id="tb-ir">
                        <thead>

                            <tr>
                                <th colspan="2">Dados que serão inseridos nos KITS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background: #f7dcdf; text-align: center; font-weight: bold">
                                <td colspan="2">Dados Imovel</td>
                            </tr>
                            <tr>
                                <td>Nr. Contrato</td>
                                <td><?= $contrato['codContrato'] ?></td>
                            </tr>
                            <tr>
                                <td>Meses</td>
                                <td><?= $contrato['qtdMeses'] ?></td>
                            </tr>
                            <tr>
                                <td>Inicio da locação</td>
                                <td><?= $contrato['dataInicio'] ?></td>
                            </tr>
                            <tr>
                                <td>Fim da locação</td>
                                <td><?= $contrato['dataFim'] ?></td>
                            </tr>
                            <tr>
                                <td>Nº IPTU</td>
                                <td><?= $imovel['nIptu'] ?></td>
                            </tr>

                            <tr>
                                <td>Endereço Imóvel</td>
                                <td><?= $contrato['endereco'] ?></td>
                            </tr>
                            <tr style="background: #f7dcdf; text-align: center; font-weight: bold">
                                <td colspan="2">Dados Proprietário</td>
                            </tr>
                            <tr>
                                <td>Nome Proprietário</td>
                                <td><?= $proprietario['nome'] ?></td>
                            </tr>
                            <tr>
                                <td>Nacionalidade</td>
                                <td><?= $proprietario['nacionalidade'] ?></td>
                            </tr>
                            <tr>
                                <td>Profissão</td>
                                <td><?= $proprietario['profissao'] ?></td>
                            </tr>
                            <tr style="background: #f7dcdf; text-align: center; font-weight: bold">
                                <td colspan="2">Dados Inquilnino</td>
                            </tr>
                            <tr>
                                <td>Nome Locatário</td>
                                <td><?= $inquilino['nome'] ?></td>
                            </tr>
                            <tr>
                                <td>Nacionalidade</td>
                                <td><?= $inquilino['nacionalidade'] ?></td>
                            </tr>
                            <tr>
                                <td>Profissão</td>
                                <td><?= $inquilino['profissao'] ?></td>
                            </tr>
                            <tr>
                                <td>Estado civil</td>
                                <td><?= $inquilino['estadoCivil'] ?></td>
                            </tr>
                            <tr>
                                <td>CPF</td>
                                <td><?= mascaraCpf($inquilino['cpf']) ?></td>
                            </tr>
                            <tr>
                                <td>Nr. RG e expediçao</td>
                                <td><?= $inquilino['rg'] . ' ' . $inquilino['orgaoExpedidor'] ?></td>
                            </tr>
                            <tr>
                                <td>Telefone fixo</td>
                                <td><?= $inquilino_telefone['telefone'] ?></td>
                            </tr>
                            <tr>
                                <td>Telefone celular</td>
                                <td><?= $inquilino_telefone['celular'] ?></td>
                            </tr>
                            <tr>
                                <td>Cônjuge</td>
                                <td><?= $inquilino_conjuge['nome'] ?></td>
                            </tr>
                            <tr>
                                <td>Cônjuge Nacionalidade</td>
                                <td><?= $inquilino_conjuge['nacionalidade'] ?></td>
                            </tr>
                            <tr>
                                <td>Cônjuge Profissão</td>
                                <td><?= $inquilino_conjuge['profissao'] ?></td>
                            </tr>
                            <tr>
                                <td>Cônjuge CPF</td>
                                <td><?= mascaraCpf($inquilino_conjuge['cpf']) ?></td>
                            </tr>
                            <tr>
                                <td>Cônjuge Nr. RG e expediçao</td>
                                <td><?= $inquilino_conjuge['rg'] . ' ' . $inquilino_conjuge['orgaoExpedidor'] ?></td>
                            </tr>
                            <tr style="background: #f7dcdf; height: 40px">
                                <td colspan="2"><b>Confira todos os dados acima antes da impressão</b></td>
                            </tr>
                            <tr>
                                <td colspan="2"><a href="kit_boas_vindas/termo_entrega_documentos.php?codContrato=<?= $_GET['codContrato'] ?>" target="_blank"><img src="../../img/word-icon.png"> 01. TERMO DE ENTREGA DE DOCUMENTOS</a></td>
                            </tr>
                            <tr>
                                <td colspan="2"><a href="kit_boas_vindas/recibo_entrega_chaves.php?codContrato=<?= $_GET['codContrato'] ?>" target="_blank"><img src="../../img/word-icon.png"> 01. RECIBO DE ENTREGA DE CHAVES</a></td>
                            </tr>
                            <tr>
                                <td colspan="2"><a href="kit_boas_vindas/carta_sindico.php?codContrato=<?= $_GET['codContrato'] ?>" target="_blank"><img src="../../img/word-icon.png"> 01. CARTA AO SÍNDICO</a></td>
                            </tr>
                            <tr>
                                <td colspan="2"><a href="kit_boas_vindas/boas_vidas.php?codContrato=<?= $_GET['codContrato'] ?>" target="_blank"><img src="../../img/word-icon.png"> 01. CARTA DE BOAS VINDAS</a></td>
                            </tr>
                        </tbody>
                    </table>

                </td>
            </tr>
        </thead>

    </table>
    <?php
} else {
    header('location:login.php');
}