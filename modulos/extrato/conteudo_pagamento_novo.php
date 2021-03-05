<?php
    

    global $mySQL;
    $mySQL = new MySQL();
    

    $sql = "SELECT PE.nome as nomeLocatario, ";
    $sql .= "I.endereco, ";
    $sql .= "I.cidade, ";
    $sql .= "I.bairro, ";
    $sql .= "I.uf, ";
    $sql .= "C.codContrato, ";
    $sql .= "CONCAT(C.codContrato,'/',DATE_FORMAT(C.dataInicio, '%Y')) as numero, ";
    $sql .= "DATE_FORMAT(C.dataInicio, '%d/%m/%Y') as dataInicio, ";
    $sql .= "B.nome as nomeBanco, ";
    $sql .= "DB.agencia, ";
    $sql .= "DB.conta, ";
    $sql .= "P.parcela, ";
    $sql .= "DATE_FORMAT(DATE_SUB(P.dataVencimento, INTERVAL 1 MONTH), '%d/%m/%Y') as periodoInicial, ";
    $sql .= "DATE_FORMAT(DATE_SUB(P.dataVencimento, INTERVAL 1 DAY), '%d/%m/%Y') as periodoFinal, ";
    $sql .= "DATE_FORMAT(P.dataPagamento, '%d/%m/%Y') as dataPagamento, ";
    $sql .= "DATE_FORMAT(P.dataRepasse, '%d/%m/%Y') as dataRepasse, ";
    $sql .= "P.valor, ";
    $sql .= "P.valorMulta, ";
    $sql .= "CASE WHEN P.dataPagamento > P.dataVencimento THEN P.valorMulta ELSE '0' END as valorMulta, ";
    $sql .= "C.comissao, ";
    $sql .= "C.intermediacao, ";
    $sql .= "P.valorDesconto, ";
    $sql .= "P.valorGastoInquilino, ";
    $sql .= "P.valorGastoServico, ";
    $sql .= "P.valorPagamento, ";
    $sql .= "P.observacaoPagamento, ";
    $sql .= "DB.observacao as favorecido ";
    $sql .= "FROM pagamento P ";
    $sql .= "INNER JOIN contrato C ON (P.codContrato = C.codContrato) ";
    $sql .= "INNER JOIN imovel I ON (C.codImovel = I.codImovel) ";
    $sql .= "INNER JOIN pessoa PE ON (I.codPessoa = PE.codPessoa) ";
    $sql .= "LEFT JOIN dadoBancario DB ON (PE.codPessoa = DB.codPessoa) ";
    $sql .= "LEFT JOIN banco B ON (DB.codBanco = B.codBanco) ";
    $sql .= "WHERE  ";
    $sql .= "P.codPagamento = " . (int) $codPagamento;
    $sql .= " ORDER BY  ";
    $sql .= "C.`status` DESC, C.codContrato ASC; ";

    //$sql          = sprintf("CALL procContratoPagamentoUnicoListar($codPagamento)");

    $mySQL->connMySQL();
    $stmt = $mySQL->runQuery($sql);
//    $linha = $mySQL->fechAssoc($stmt);
    $linha = $mySQL->getArrayResult();

    $valorRecebido = $linha['valorMulta'] > 0 ? $linha['valor'] + $linha['valorMulta'] : $linha['valor'] - $linha['valorDesconto'];
    //$valorComissao = ($valorRecebido * $linha['comissao']) / 100;
    $valorComissao = ($valorRecebido * ($linha['comissao'] +$linha['intermediacao'] )) / 100;
    $repace = $valorRecebido - $valorComissao - $linha['valorGastoServico'] - $linha['valorGastoInquilino'];
    ?>
    <style> 
        body{
            margin: 0px;
            font-size: 12px;
            font-family: arial;
        }

        .clearfix:after {
            content: ".";
            display: block;
            clear: both;
            visibility: hidden;
            height: 0;
        }


        table{
            font-size: 11pt;
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

        .clJustificar{
            text-align: justify;
        }

        #cabecalhoRelatorio{
            width: 650px;
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
    <table border='0' width='600' cellpadding='0' cellspacing='0'>
        <tr>
            <td colspan='2'>
                <div class="clJustificar">
                    <table width='600'>
                        <tr>
                            <td colspan='2'> 
                                <div id='cabecalhoRelatorio' class='clearfix'>
                                    <div id='logoRelatorio'>
                                        <img src='http://www.tabakalimoveis.com.br/sgim/modulos/extrato/img/logo.jpg' />
                                    </div>
                                    <div id='tituloRelatorio'>
                                        <div id='nomeRelatorio'>
                                            <?php echo $titulo; ?>
                                        </div>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    </table>
                    <table width='600'>
                        <tr>
                            <td colspan='2'><b>Locador(a):</b> <?php echo utf8_decode($linha['nomeLocatario']); ?></td>
                        </tr>
                        <tr>
                            <td colspan='2'><b>Endereço do Imóvel Locado:</b> <?php echo utf8_decode($linha['endereco']); ?></td>
                        </tr>	
                        <tr>
                            <td>
                                <b>Bairro:</b> <?php echo utf8_decode($linha['bairro']); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                            </td>
                            <td>
                                <b>Cidade:</b> <?php echo utf8_decode($linha['cidade']) . '/' . utf8_decode($linha['uf']); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Nº do Contrato:</b> <?php echo $linha['numero']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                            </td>
                            <td>
                                <b>Início da Locação:</b> <?php echo $linha['dataInicio']; ?> 
                            </td>
                        </tr>
                    </table>
                    <hr/>

                    <table width='600'>
                        <tr>
                            <td><b>Banco:</b> <?php echo $linha['nomeBanco']; ?></td>

                            <td><b>Agência:</b> <?php echo $linha['agencia']; ?></td>
                            <td><b>Conta:</b> <?php echo $linha['conta']; ?></td>
                        </tr>
                        <?php
                        if ($linha['favorecido'] != '') {
                            ?>
                            <tr>
                                <td colspan='3'><b>Favorecido(a):</b> <?php echo utf8_decode($linha['favorecido']); ?></td>
                            </tr>	
                            <?php
                        }
                        ?>
                    </table>

                    <hr/>

                    <table width='600'>
                        <tr>
                            <td>
                                <b>Período de Locação:</b> <?php echo $linha['periodoInicial']; ?> a 
                                <?php echo $linha['periodoFinal']; ?>
                            </td>
                            <td><b>Parcela:</b> <?php echo $linha['parcela']; ?></td>
                        </tr>	
                    </table>

                    <hr/>

                    <table width='600'>
                        <tr>
                            <td>
                                <b>Data de Crédito:</b>
                            </td>
                            <td colspan='3'>
                                <?php echo $linha['dataRepasse']; ?>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <b>Valor de Locação:</b>
                            </td>
                            <td style="width: 40%">
                                <?php echo 'R$ ' . number_format($linha['valor'], 2, ',', '.'); ?>
                            </td>
                            <td>
                                <b>Comissão:</b> 
                            </td>
                            <td>
                                <?php echo 'R$ ' . number_format($valorComissao, 2, ',', '.'); ?>
                            </td>
                        </tr>	
                        <tr>
                            <td>
                                <b>Multa:</b>
                            </td>
                            <td>
                                <?php echo 'R$ ' . number_format($linha['valorMulta'], 2, ',', '.'); ?>
                            </td>
                            <td> 
                                <b>Locatário:</b> 
                            </td>
                            <td><?php echo 'R$ ' . number_format($linha['valorGastoInquilino'], 2, ',', '.'); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <b>Desconto:</b> 
                            </td>
                            <td>
                                <?php echo 'R$ ' . number_format($linha['valorDesconto'], 2, ',', '.'); ?>
                            </td>
                            <td> <b>Tabakal:</b> 
                            </td>
                            <td>
                                <?php echo 'R$ ' . number_format($linha['valorGastoServico'], 2, ',', '.'); ?>
                            </td>

                        </tr>

                        <tr>
                            <td>
                                <b>Aluguel Recebido:</b></td><td> <?php echo 'R$ ' . number_format($valorRecebido, 2, ',', '.'); ?>
                            </td>
                            <td><b>Repasse:</b> </td><td><?php echo 'R$ ' . number_format($repace, 2, ',', '.'); ?></td>
                        </tr>
                    </table>

                    <hr/>

                    <table width='600'>
                        <tr>
                            <td>
                                <b>Observações:</b> <?php echo utf8_decode($linha['observacaoPagamento']); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td align='right'>
                                Brasília-DF, <?php echo data(); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                _____________________________
                                <br/>
                                Marleide Teles
                                <br/>
                                TABAKAL IMÓVEIS
                            </td>
                        </tr>
                    </table>

                </div>	
                <br/>
                <br/>
                <br/>	


            </td>
        </tr>
    </table>
