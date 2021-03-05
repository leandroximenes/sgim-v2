<?php
error_reporting(E_ALL);
ini_set("display_errors", 0);
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

header("Content-type: application/vnd.ms-word");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=contrato_locacao_fiador_modelo.doc");
header("Pragma: no-cache");

$titulo = 'Relatório de Aniversariantes';

if (isset($_SESSION["SISTEMA_codPessoa"])) {

    include("../../conexao/conexao.php");
    include("../../php/php.php");
    include("../diversos/util.php");

    global $mySQL;
    $codContrato = $_GET['codContrato'];
    $sql = sprintf("CALL procContratoUnicoListar($codContrato)");
    $rs = $mySQL->runQuery($sql);

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

        p.quebra    { 
            page-break-before: always 
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

    <?php
    $rsLinha = mysqli_fetch_assoc($rs);
    utf8_decode_array($rsLinha);
    $enderecoImovel = $rsLinha['endereco'];
    $cidadeImovel = $rsLinha['cidade'];
    $ufImovel = $rsLinha['uf'];
    $cep = $rsLinha['cep'];
    $bairroImovel = $rsLinha['bairro'];
    $codLocatario = $rsLinha['codContratante'];
    $dataInicio = $rsLinha['dataInicio'];
    $dataFim = $rsLinha['dataFim'];
    $valor = $rsLinha['valor'];
    $descontoPontualidade = floatval($rsLinha['descontoPontualidade']);
    $qtdMeses = $rsLinha['qtdMeses'];
    ?>



    <table border='0' width='600' cellpadding='0' cellspacing='0'>
        <tr>
            <td colspan='2'>
        <center><b style='font-size:18.5px'><u>CONTRATO DE LOCAÇÃO DE IMÓVEL RESIDENCIAL</u></b></center>
        

        <div style="page-break-before: always">

            <b><p>A - IMÓVEL:</b> <?php echo $enderecoImovel . ' - ' . $bairroImovel . ' - ' . $cidadeImovel . ' - ' . $ufImovel . ' - CEP: ' . mascaraCep($cep); ?></p>
            <br/>
            <br/>
            <div class="clJustificar">
                <p><b>B – ADMINISTRADORA: TABAKAL</b> EMPREENDIMENTOS IMOBILIÁRIOS LTDA., inscrita no CNPJ/MF 06.864.021/0001-31, CRECI/DF 9508, estabelecida no CLN 309, Bloco D, n° 50, Salas 104/105, nesta Capital, representada legalmente pela corretora de imóveis MARLEIDE DE ARAÚJO TELES, brasileira, residente e domiciliada nesta capital, inscrita no CRECI/DF n° 8091. 
            </p></div>
            <br/>
            <?php
            $sql = sprintf("CALL procPessoaListarUnico($codLocatario)");
            $rsLocatorio = $mySQL->runQuery($sql);
            $rsLocatarioLinha = mysqli_fetch_assoc($rsLocatorio);
            utf8_decode_array($rsLocatarioLinha);

            $nomeLocatario = $rsLocatarioLinha['nome'];
            $profissao = $rsLocatarioLinha['profissao'];
            $cpf = mascaraCpf($rsLocatarioLinha['cpf']);
            $rg = number_format($rsLocatarioLinha['rg'], 0, ',', '.');
            $orgaoExpedidor = $rsLocatarioLinha['orgaoExpedidor'];
            $enderecoLocatario = $rsLocatarioLinha['endereco'];
            $bairroLocatario = $rsLocatarioLinha['bairro'];
            $cidadeLocatario = $rsLocatarioLinha['cidade'];
            $ufLocatario = $rsLocatarioLinha['uf'];
            $cepLocatario = mascaraCep($rsLocatarioLinha['cep']);
            $nacionalidade = $rsLocatarioLinha['nacionalidade'];
            

            //se existir conjuge ele lista!
            $sqlConjuge = sprintf("CALL procPessoaConjugeListar($codLocatario)");
            $rsConjugeLocatario = $mySQL->runQuery($sqlConjuge);
            $rsLocatarioConjugeLinha = mysqli_fetch_assoc($rsConjugeLocatario);
            utf8_decode_array($rsLocatarioConjugeLinha);

            $conjugeLocatarioNome = $rsLocatarioConjugeLinha['nome'];
            $conjugeLocatarioRg = number_format((int) str_replace('.', '', $rsLocatarioConjugeLinha['rg']), 0, ',', '.');
            $conjugeLocatarioCpf = mascaraCpf($rsLocatarioConjugeLinha['cpf']);
            $conjugeLocatarioOrgaoExpedidor = $rsLocatarioConjugeLinha['orgaoExpedidor'];
            $conjugeLocatarioProfissao = $rsLocatarioConjugeLinha['profissao'];
            $conjugeLocatarioNacionalidade = $rsLocatarioConjugeLinha['nacionalidade'];

            $strConjugeListar = '';
            if (!empty($conjugeLocatarioNome)) {
                $strConjugeListar = ' casado(a) com <b>' . $conjugeLocatarioNome . '</b>, ' . ($conjugeLocatarioNacionalidade) . '(a), portador(a) da carteira de identidade ' . $conjugeLocatarioRg . ' ' . $conjugeLocatarioOrgaoExpedidor . ', inscrito(a) no CPF sob nº  ' . $conjugeLocatarioCpf . ',';
            }else{
               
               //$strConjugeListar = $rsLocatarioLinha['estadoCivil'] . '(a),' ;

            }

            //fim
            ?>
            <div class="clJustificar">
                <p><b>C - LOCATÁRIO(S)(A): <?php echo ($nomeLocatario); ?></b>, <?php echo ($nacionalidade); ?>(a), <?php echo $profissao; ?>, portador(a) da carteira de identidade <?php echo $rg . ' ' . $orgaoExpedidor; ?>, inscrito(a) no CPF sob nº <?php echo $cpf; ?>, <?php echo $strConjugeListar; ?> residente(s) e domiciliado(a)(s) no(a) <?php echo ($enderecoLocatario); ?> - <?php echo ($bairroLocatario) . " - " . $cidadeLocatario . "-" . $ufLocatario; ?> – CEP: <?php echo $cepLocatario; ?>.
            </p></div>
            <br/>
            <div class="clJustificar">
                <p><b>D – PRAZO:</b> <?php echo $qtdMeses; ?> (<?php echo str_replace(' reais', '',  extenso($qtdMeses, false, true, true)); ?>) meses, 
                com início em <?php echo $dataInicio; ?> e término em <?php echo $dataFim; ?>. 
                Ocorrendo prorrogação contratual, tornando-se a avença por prazo indeterminado, 
                responde(m) o(s)(a) LOCATÁRIO(S)(A) e fiador(es) nos termos ora pactuados até a efetiva devolução do imóvel.</p>
            </div>
            <br/>
            <div class="clJustificar">
                <p><b>E – ALUGUEL MENSAL INICIAL:</b> R$ <?php echo number_format($valor, 2, ',', '.'); ?> 
                (<?php echo extenso($valor, false, true, true); ?>), com desconto de pontualidade de 
                R$ <?php echo number_format($descontoPontualidade, 2, ',', '.'); ?> 
                (<?php echo extenso($descontoPontualidade, false, true, true); ?>).
            </p></div>
            <br/>
            <div>
                <p><b>F – PRAZO DE REAJUSTE:</b> Anual, com base no IGPM/FGV.</p>
            </div>
            <br/>
            <div>
                <p><b>G – USO DO IMÓVEL:</b> Residencial.<br/></p>
            </div>

        </div>

        <div class="clJustificar">
        <p>
            Os signatários deste instrumento, de um lado a ADMINISTRADORA, indicado na alínea “B” acima, e de outro lado o(s)(a) LOCATÁRIO(S)(A), indicado(s)(a) na alínea “C”, contrata(m) a locação do imóvel (Residencial) mencionado na alínea “A”, sob as seguintes cláusulas e condições:</p>
        </div>
        
        
        <b>CLÁUSULA I – DO PRAZO</b>
        <br/>
        <br/>
        <div class="clJustificar">
            O prazo do presente contrato de locação é o determinado na alínea ?D?, com seu término também fixado na mesma alínea, independentemente de qualquer aviso, interpelação judicial ou extrajudicial, não se havendo como presumida a falta de oposição da ADMINISTRADORA o fato de findo o prazo estipulado, continuar(em) o(s)(a) LOCATÁRIO(S)(A) na posse do imóvel, por qualquer motivo. 
        </div>

        <br/>
        <div class="clJustificar">
        <b>Parágrafo Primeiro:</b> Estabelece-se que após o transcurso de 12 (doze) meses da vigência do contrato, caso haja interesse por parte do(s)(a) LOCATÁRIO(S)(A) na devolução do imóvel, poderá(ão) fazê-lo sem o pagamento da multa de que trata a Cláusula V, desde que notifique(m) sua intenção a ADMINISTRADORA com antecedência mínima de 30 (trinta) dias para a devolução do imóvel. <b>Caso ocorra a devolução anterior aos 30(trinta) dias, arcará o locatário com custos da locação e seus acessórios até o final do prazo do aviso. Se ultrapassado os 30(trinta) dias, o aluguel e acessórios serão calculados proporcionalmente aos dias excedidos. Caso ocorra a devolução anterior ao período de 12 (doze) meses o(s)(a) LOCATÁRIO(S)(A) pagará(ão) a multa pactuada na Cláusula V proporcional aos meses faltantes.</b>
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Segundo:</b> Caso ocorra prorrogação automática da avença, serão mantidas todas as cláusulas e condições ora estabelecidas.
        </div>
        <br/>
        
        <b>CLÁUSULA II – DO ALUGUEL</b>
        <br/>
        <br/>
        <div class="clJustificar">
            O aluguel mensal do imóvel, vencível no dia <?php echo substr($dataInicio, 0, 2); ?> de cada mês civil correspondente, será pago pelo(s)(a) LOCATÁRIO(S)(A) independente de qualquer aviso, através de boleto bancário emitido pela administradora.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Primeiro:</b> O reajuste do aluguel pactuado na alínea “E” ocorrerá de acordo com a variação acumulada do IGPM/FGV, apurada a cada 12 (doze) meses considerando-se o acumulado no mês anterior ao do reajuste - e excluindo-se eventuais contagens pro rata die do índice mencionado que se vinculem à data da assinatura do contrato -, ou na menor periodicidade que permitir a legislação, sendo o novo valor do aluguel processado e cobrado automaticamente, independente de qualquer notificação ou aviso.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Segundo:</b> Na hipótese de não publicação, extinção ou suspensão do índice de atualização monetária eleito neste contrato (IGPM/FGV), o reajuste do aluguel permanecerá em pleno vigor, sendo regulado, na seguinte ordem de seqüência de índices: a) IGP - DI/FGV b) IPC/FIPE c) IPC/BRASIL/FGV d) IPC/DIEESE.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Terceiro:</b> Ocorrendo a extinção do índice aplicável para o reajuste do aluguel mencionado no Parágrafo Primeiro e dos demais índices substitutivos mencionados no Parágrafo Segundo, serão adotados os índices máximos que a lei indicar em substituição, e na falta deste, será utilizada a variação de mercado havida no período.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Quarto:</b> Em caso de atraso no pagamento do aluguel, haverá cobrança de multa moratória de 10% (dez por cento) e juros de mora de 1% (um por cento) ao mês. Se a cobrança for requerida por intermédio de processo judicial, incidirá sobre o débito, multa moratória de 2% (dois por cento), juros de mora de 1% (um por cento); e honorários de 20% (vinte por cento) (parte final da alínea “d”, inc II, do art. 62, da Lei 8.245/91).
        </div>

        <br/>
        <b>CLÁUSULA III – DOS ENCARGOS</b>
        <br/>
        <br/>
        <div class="clJustificar">
            Além do aluguel mensal, o(s)(a) LOCATÁRIO(S)(A) pagará(ão) o IPTU/TLP, despesas ordinárias de condomínio (portaria, água, limpeza, jardinagem, etc.), se houver, taxa de religação de energia elétrica e água – quando tiver dado causa à interrupção desses serviços. Os comprovantes de adimplemento destas obrigações deverão ser apresentados no ato do pagamento do aluguel.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo primeiro:</b> Caso o pagamento dos encargos de que trata o caput desta Cláusula não seja efetuado pelo(s)(a) LOCATÁRIO(S)(A) e a cobrança seja requerida por processo judicial, incidirá sobre o valor do débito multa de 2% (dois por cento), juros de mora 1% (um por cento) ao mês, correção monetária, e honorários advocatícios de 20% (vinte por cento) sobre o valor da causa. 
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo segundo:</b> Sendo o IPTU/TLP pago em atraso, este será cobrado, acrescida de multa e juros determinados pelos Órgãos Governamentais.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Terceiro:</b> O descumprimento de qualquer obrigação contratual por qualquer das partes, que ensejar a intervenção de advogado, os honorários advocatícios, serão suportados pela parte contratante que der margem a interferência do referido profissional à razão de 20% (vinte por cento) (parte final da alínea “d”, inc.II, do art. 62, da Lei 8.245/91).
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Quarto:</b> O(s)(A) LOCATÁRIO(S)(A) obriga(m)-se a pagar o seguro contra incêndio do imóvel locado. O valor segurado deverá ser 120 (cento e vinte) vezes o valor do aluguel, em companhia seguradora de sua livre escolha. O prêmio do referido seguro se reverterá à ADMINISTRADORA. Obriga(m)-se o(s)(a) LOCATÁRIO(S)(A) em renovar o contrato de seguro anualmente.
        </div>

        <br/>
        <b>CLÁUSULA IV – TOLERÂNCIA</b>
        <br/>
        <br/>

        <div class="clJustificar">
            Caso venha a ser admitida qualquer tolerância em favor do(s)(a) LOCATÁRIO(S)(A), no cumprimento das obrigações pactuadas, tal tolerância jamais poderá ser admitida como modificação do presente contrato, não dando ensejo à novação constante do Código Civil, permanecendo, a todo tempo, em vigor as cláusulas do presente, como se nenhum favor houvesse intercorrido.
        </div>

        <br/>
        <b>CLÁUSULA V – DA RESCISÃO</b>
        <br/>
        <br/>

        <div class="clJustificar">
            Na falta do pagamento pontual do aluguel e demais encargos locatícios, ou na violação de qualquer cláusula contratual, o presente contrato será rescindido de pleno direito, independente de notificação, aviso ou interpelação, obrigando-se o(s)(a) LOCATÁRIO(S)(A) à imediata restituição do imóvel inteiramente desocupado, e nas condições ajustadas neste instrumento, sujeitando-se a multa compensatória de valor correspondente a 03 (três) meses do aluguel em vigor no momento da infração. Caso necessário à intervenção de advogado, responderá(ão) o(s)(a) LOCATÁRIO(S)(A) pelo pagamento de honorários de 20% (vinte por cento) e despesas processuais.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Único:</b> Passando o contrato a viger por prazo indeterminado, e havendo interesse do(s)(a) LOCATÁRIO(S)(A) em rescindi-lo, deverá(ão) notificar seu interesse por escrito à ADMINISTRADORA com antecedência mínima de 30 (trinta) dias. 
        </div>

        <br/>
        <b>CLÁUSULA VI – TRANSFERÊNCIA E SUBLOCAÇÃO</b>
        <br/>
        <br/>

        <div class="clJustificar">
            O(s)(A) LOCATÁRIO(S)(A) não poderá(ão) ceder, mesmo gratuitamente, ou transferir o presente contrato, nem sublocar no todo ou em parte o imóvel locado, podendo apenas ser utilizado pelo(s)(a) LOCATÁRIO(S)(A), e será usado unicamente para o fim consignado na alínea “G” deste instrumento. A infração a este dispositivo ensejará rescisão contratual nos termos da Cláusula V.
        </div>

        <br/>
        <b>CLÁUSULA VII – DA CONSERVAÇÃO</b>
        <br/>
        <br/>

        <div class="clJustificar">
            O(s)(A) LOCATÁRIO(S)(A) declara(m) neste ato haver vistoriado o imóvel objeto desta locação, e verificado se encontrar na mais perfeita ordem e condições de uso (conforme Laudo de Vistoria anexo que faz parte integrante deste instrumento), em especial quanto à pintura, acabamento, aparelhos sanitários e instalações em geral, tudo em perfeito estado físico e de funcionamento. Compromete(m)-se o(s)(a) LOCATÁRIO(S)(A) a restituí-los nas mesmas condições em que os recebeu, exceto desgaste natural pelo seu uso normal, com as ressalvas da cláusula VIII.
        </div>

        <br/>
        <b>CLÁUSULA VIII – DA DEVOLUÇÃO</b>
        <br/>
        <br/>

        <div class="clJustificar">
            Finda a locação, compromete(m)-se o(s)(a) LOCATÁRIO(S)(A) a devolver(em) o imóvel locado nas mesmas condições em que o recebeu: limpo, com pintura nova, vidros e louças sanitárias de modo que possa ser imediatamente realugado, sem despesas para a ADMINISTRADORA. Para tanto, será feita uma vistoria antes do encerramento do contrato – que o(s)(a) LOCATÁRIO(S)(A) se obriga(m) em marcar no prazo mínimo de 05 (cinco) dias e máximo de 10 (dez) dias anteriores à devolução definitiva do imóvel -, a fim de que o(s)(a) LOCATÁRIO(S)(A) proceda(m) a eventuais reparos necessários.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Primeiro:</b> Garante-se à ADMINISTRADORA o direito de não receber as chaves do imóvel para fins de encerramento da relação contratual até que seja recomposto o imóvel, arcando o(s)(a) LOCATÁRIO(S)(A) com todas as despesas decorrentes, inclusive pelos aluguéis e encargos até a efetiva liberação.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Segundo:</b> Se a reparação do imóvel ficar a cargo da ADMINISTRADORA, esta solicitará 03 (três) orçamentos de pessoas físicas ou jurídicas diferentes, e ordenará a que se realizem com aquele que apresentar menor preço, servindo o recibo, fatura ou nota fiscal para posterior cobrança da respectiva quantia do(s)(a) LOCATÁRIO(S)(A).
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Terceiro:</b> No ato de devolução definitiva do imóvel, obriga(m)-se o(s)(a) LOCATÁRIO(S)(A) em apresentar o comprovante de desligamento dos serviços de energia (CEB) e água (CAESB), e respectivas certidões ou declarações negativas de débito emitidas por essas prestadoras de serviços, além de apresentar o nada consta do condomínio (se for o caso) relativos a todo período de locação.
        </div>

        <br/>
        <b>CLÁUSULA IX – BENFEITORIAS</b>
        <br/>
        <br/>

        <div class="clJustificar">
            É vedado ao(s)(à) LOCATÁRIO(S)(A) erigir qualquer benfeitoria no imóvel objeto deste contrato.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo único:</b> Em caso de inobservância da proibição prevista no caput desta cláusula, o(s)(a) LOCATÁRIO(S)(A) não poderá(ão) exigir indenizações pelas benfeitorias que fizer no imóvel, sejam voluptuárias, úteis ou necessárias, e caso sejam realizadas, não lhe autorizarão o exercício do direito de retenção (art. 578 do Código Civil), ficando desde logo incorporadas ao imóvel.
        </div>

        <br/>    
        <b>CLÁUSULA X – DA GARANTIA LOCATÍCIA</b>
        <br/>
        <br/>
        Assina(m) como fiador(es) e principal(is) pagador(es). 
        <br/>
        <br/>

    <?php
    $cont = 1;
    $sql = sprintf("CALL procContratoFiadorSelecionar($codContrato)");
    
    $rsFiador = $mySQL->runQuery($sql);

    $rsQuant = $rsFiador->num_rows;

    if ($rsQuant > 0) {
        while ($rsFiadorLinha = mysqli_fetch_assoc($rsFiador)) {
            utf8_decode_array($rsFiadorLinha);
            $codPessoaEstadoCivil = $rsFiadorLinha['codPessoa'];
	    
            if ($rsFiadorLinha['estadoCivil'] == 'Casado') { //casado
                $sqlFiador = sprintf("CALL procPessoaConjugeListar($codPessoaEstadoCivil)");
                $rsConjugeFiador = $mySQL->runQuery($sqlFiador);
                $rsConjugeFiadorLinha = mysqli_fetch_assoc($rsConjugeFiador);
                $strConjugeFiador = "";
                if (!empty($rsConjugeFiadorLinha['nome'])) {
                    
                    $idendidadeTratada = str_replace("-", "", str_replace(".", "", $rsConjugeFiadorLinha['rg']));
                    if(is_int($idendidadeTratada)){
                       $idendidadeTratada = number_format($idendidadeTratada, 0, ',', '.');
                    }else{
                        $idendidadeTratada = $rsConjugeFiadorLinha['rg'];
                    }
                    
                    $strConjugeFiador = " casado(a) com <b>" . $rsConjugeFiadorLinha['nome'] . "</b>, portador(a) da carteira de identidade " . $idendidadeTratada . ' ' . $rsConjugeFiadorLinha['orgaoExpedidor'] . ", inscrito(a) no CPF sob nº  " . mascaraCpf($rsConjugeFiadorLinha['cpf']);
                }
               
            }
            else{
            	$strConjugeFiador = '' ;
            	
            }

            ?>				
                <div class="clJustificar"><b><?php  echo $cont; ?>° FIADOR(A): <?php echo ($rsFiadorLinha['nome']); ?></b>, <?php echo ($rsFiadorLinha['nacionalidade']); ?>(a), <?php echo ($rsFiadorLinha['profissao']); ?>, 
                portador(a) da carteira de identidade <?php echo number_format(str_replace('.', '', $rsFiadorLinha['rg']), 0, ',', '.') . ' ' . $rsFiadorLinha['orgaoExpedidor']; ?>, 
                inscrito(a) no CPF sob nº  <?php echo mascaraCpf($rsFiadorLinha['cpf']);  ?>, <?php echo isset($rsFiadorLinha['estadocivil']) ? $rsFiadorLinha['estadocivil'] : '';
             echo ($strConjugeFiador);?>,  
                residente(s) e domiciliado(a)(s) no(a) <?php echo ($rsFiadorLinha['endereco']); ?> - <?php echo ($rsFiadorLinha['bairro']); ?> -  
                <?php echo ($rsFiadorLinha['cidade']); ?>, CEP: <?php echo mascaraCep($rsFiadorLinha['cep']); ?>.
                </div>
                
                <br/>
                

                <?php
                $cont ++;
                
            }
            
        }

        ?>

        <div class="clJustificar">
            <b>Parágrafo Primeiro:</b> Assume(m) solidariamente o(s)(a) fiador(es)(a) acima qualificado(s)(a) com o(s)(a) LOCATÁRIO(S)(A), o compromisso de fielmente cumprir(em) todas as cláusulas e condições do presente contrato até a efetiva devolução das chaves, responsabilizando-se por todas as informações da qualificação acima, especialmente as relativas ao estado civil.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Segundo:</b> Renuncia(m) expressamente o(s) fiador(es), por este ato, à faculdade que lhe(s) confere(m) o art. 835 do Código Civil (Lei 10.406/02), não podendo alegar em juízo ou fora dele, que tenha havido notificação enviada à ADMINISTRADORA capaz de, por si só, exonerá-lo(s)(a) da garantia prestada.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Terceiro:</b> A garantia fidejussória compreenderá quaisquer acréscimos, reajustes ou acessórios da dívida principal, inclusive todas as despesas judiciais, honorários advocatícios (20 %) e demais cominações, até a final liquidação de quaisquer ações movidas em desfavor do(s)(a) LOCATÁRIO(S)(A), em decorrência do presente contrato.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Quarto:</b> O(s)(A) fiador(es)(a) renuncia(m) expressamente ao benefício da prévia execução dos bens dos afiançados (arts. 827 e 828, inc.I, do CC) e não poderá(ão) sob qualquer pretexto exonerar(em)-se desta fiança, que é prestada sem limitação de tempo, até a definitiva resolução do contrato e suas implicações, mesmo que este se prorrogue automaticamente por prazo indeterminado, estipulações estas em relação às quais o(s)(a) fiador(es)(a) concorda(m) expressamente, não podendo futuramente alegar que suas obrigações se encerrariam ao final do primeiro período contratual.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Quinto:</b> O não cumprimento das obrigações expressas neste contrato pelo(s)(a) LOCATÁRIO(S)(A) ou pelo(s)(a) seu(s)(a) fiador(es)(a) faculta à ADMINISTRADORA a solicitação de inclusão de seu(s) nome(s) no cadastro de devedores do Serviço de Proteção ao Crédito (SPC), ou qualquer outra entidade com finalidade semelhante. O cancelamento da inscrição se dará após a quitação dos débitos existentes, correndo por conta do(s)(a) LOCATÁRIO(S)(A) e seu(s)/sua(s) fiador(es)(a) todas as despesas, bem como a responsabilidade pela baixa do registro.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Sexto:</b> Em caso de morte, incapacidade civil, falência, insolvência ou inidoneidade moral ou financeira do(s)(a) fiador(es)(a), poderá a ADMINISTRADORA exigir a sua substituição, a qual deverá ser cumprida no prazo máximo de 15 (quinze) dias, a contar da comunicação ao(s)(à) LOCATÁRIO(S)(A). A falta de cumprimento desta exigência, cuja satisfação ficará subordinada à aprovação da ADMINISTRADORA, constituirá justa causa para rescisão do contrato, aplicando-se a penalidade prevista na Cláusula V, até a efetiva devolução do imóvel.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Sétimo:</b> Na hipótese de extinção ou perda de garantia no curso da locação, enquanto não ocorrer à substituição, o aluguel deverá ser pago antecipadamente, na data prevista.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Oitavo:</b> Obriga(m)-se o(s)(a) fiador(es)(a) a informar qualquer alteração de endereço sob pena de se considerar válidas, para todos os efeitos legais, as correspondências que lhe forem encaminhadas para o endereço acima indicado.
        </div>

        <br/>    
        <b>CLÁUSULA XI – DO ABANDONO DO IMÓVEL</b>
        <br/>
        <br/>

        <div class="clJustificar">
            A fim de se resguardar o imóvel de qualquer eventualidade decorrente da ausência do(s)(a) LOCATÁRIO(S)(A), fica a ADMINISTRADORA expressamente autorizada a ocupar o imóvel, independentemente de procedimento judicial, caracterizando-se como abandono a ausência comprovada do(s)(a) LOCATÁRIO(S)(A), combinada com a inadimplência de 02 (dois) meses de aluguel;
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Primeiro:</b> Fica a ADMINISTRADORA autorizada a remover os bens que porventura existirem no imóvel, devendo para tanto lavrar um termo relacionando-os, termo este que virá assinado por duas testemunhas e pela ADMINISTRADORA, ou seu representante.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Segundo:</b> Se no prazo de 90 (noventa) dias a contar da data do termo de abandono e imissão na posse, não forem procurados os bens nele relacionados, fica a ADMINISTRADORA expressamente autorizada a alienar o suficiente para saldar o débito.
        </div>

        <br/>
        <b>CLÁUSULA XII – DAS OBRIGAÇÕES</b>
        <br/>
        <br/>

        <div class="clJustificar">
            Sob pena de responsabilidade civil do(s)(a) LOCATÁRIO(S)(A), este(s)(a) deverá(ão) informar à ADMINISTRADORA quando do recebimento de quaisquer papéis ou documentos entregues no endereço do bem locado relativos a esta ou ao imóvel. Caso assim não proceda, o(s)(a) LOCATÁRIO(S)(A) arcará(ão) com os encargos que forem aplicados em razão do descumprimento desta obrigação.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Primeiro:</b> O(s)(A) LOCATÁRIO(S)(A) obriga(m)-se a transferir para o seu nome as tarifas de luz e água, no prazo de 30 (trinta) dias a contar da assinatura deste instrumento, devendo apresentá-las de imediato à ADMINISTRADORA, sob pena de ser aplicada à multa prevista na cláusula V.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Segundo:</b> Obriga(m)-se o(s)(a) LOCATÁRIO(S)(A) a informar à ADMINISTRADORA qualquer alteração de endereço, sob pena de se considerarem válidas, para todos os efeitos legais, as correspondências que lhe forem encaminhadas para o endereço acima indicado ou do imóvel locado.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Terceiro:</b> Obriga-se a ADMINISTRADORA a informar ao(s)(a) LOCATÁRIO(S)(A) qualquer alteração de endereço, sob pena de se considerarem válidas, para todos os efeitos legais, as correspondências que lhe forem encaminhadas para o endereço acima indicado.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Quarto:</b> Após a vigência do contrato e tornando-se ele por prazo indeterminado, obriga(m)-se o(s)(a) LOCATÁRIO(S)(A) a atender(em) a solicitação da ADMINISTRADORA quanto ao preenchimento de nova ficha cadastral com dados atualizados, sob pena de a recusa nesse sentido, configurar infração punível com a multa estabelecida no caput da cláusula V.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Quinto:</b> Autoriza(m) o(s)(a) LOCATÁRIO(S)(A) que a citação, intimação ou notificação poderá ser formalizada mediante correspondência com aviso de recebimento, ou, tratando-se de pessoa jurídica ou firma individual, também mediante telex ou fac-símile, ou, ainda, sendo necessário, pelas demais formas previstas no Código de Processo Civil.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Sexto:</b> O(s)(A) LOCATÁRIO(S)(A) recebe(m), neste ato, a Convenção do Condomínio e o Regimento Interno, comprometendo-se a cumpri-los integralmente.
        </div>

        <br/>
        <b>CLÁUSULA XIII – FATOS SUPERVENIENTES</b>
        <br/>
        <br/>

        <div class="clJustificar">
            Em caso de desapropriação, incêndio, ou qualquer outro fato que torne impeditiva a continuidade da locação e que não tenha resultado da ação ou omissão das partes contratantes, considerar-se-á extinta a locação de pleno direito, sem que seja imputada indenização a qualquer título, reciprocamente, sem prejuízo da cobrança de eventuais débitos anteriores à ocorrência.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Único:</b> A ADMINISTRADORA não responderá, em nenhuma hipótese, por quaisquer danos que venha(m) a sofrer o(s)(a) LOCATÁRIO(S)(A)  em razão de derramamento de líquidos (água, rompimento de canos, chuvas, torneiras, defeitos de esgoto ou fossas, entre outros), incêndios, arrombamentos, roubos, furtos e quaisquer outros casos, inclusive fortuitos ou de força maior, do que neste ato o(s)(a) LOCATÁRIO(S)(A)  tem pleno conhecimento e concorda(m) expressamente.
        </div>

        <br/>
        <b>CLÁUSULA XIV – DA EXONERAÇÃO DE RESPONSABILIDADE </b>
        <br/>
        <br/>

        <div class="clJustificar">
            O(s)(A) LOCATÁRIO(S)(A)  assume(m) toda e qualquer responsabilidade pelo atendimento de exigências que venham a ser feitas pelas autoridades locais para sua instalação, inclusive, em havendo negativa total para o fim a que pretende destinar o imóvel.
        </div>

        <br/>

        <div class="clJustificar">
            <b>Parágrafo Único:</b> Multas oriundas do uso do imóvel em discordância com quaisquer normas legais serão de responsabilidade do(s)(a) LOCATÁRIO(S)(A) .
        </div>

        <br/>
        <b>CLÁUSULA XV – DAS AÇÕES JUDICIAIS</b>
        <br/>
        <br/>

        <div class="clJustificar">
            Todas as obrigações decorrentes do presente contrato, mesmo em caso de prorrogação, são extensivas aos herdeiros e sucessores dos contratantes e exigíveis de pleno direito, nos prazos e pelas formas mencionadas, independentes de qualquer aviso, notificação judicial ou extrajudicial. No débito, serão consideradas inclusive as multas, juros, correções e indenizações, como dívida líquida e certa, cobrável judicialmente do(s)(a) LOCATÁRIO(S)(A) . Incluem-se, neste caso, também as custas judiciais e honorários advocatícios despendidos para preservação e consecução dos direitos da ADMINISTRADORA.
        </div>

        <br/>
        <b>CLÁUSULA XVI – DO FORO</b>
        <br/>
        <br/>

        <div class="clJustificar">
            Elege-se o foro da Circunscrição Especial Judiciária de Brasília–DF para dirimir quaisquer dúvidas originadas deste contrato, com expressa renúncia a qualquer outro, por mais privilegiado que seja.
        </div>
        <br/>

        <div class="clJustificar">
            E, por estarem justos e contratados, assinam o presente contrato em 02 (duas) vias de igual teor e forma, para um só efeito, na presença das testemunhas abaixo.
        </div>

        <br/>
        <br/>
        <br/>

    <?php
    $dia = date("d");
    $mess = date("m");
    $ano = date("y");

    switch ($mess) {
        case "01": $mes = "Janeiro";
            break;
        case "02": $mes = "Fevereiro";
            break;
        case "03": $mes = "Março";
            break;
        case "04": $mes = "Abril";
            break;
        case "05": $mes = "Maio";
            break;
        case "06": $mes = "Junho";
            break;
        case "07": $mes = "Julho";
            break;
        case "08": $mes = "Agosto";
            break;
        case "09": $mes = "Setembro";
            break;
        case "10": $mes = "Outubro";
            break;
        case "11": $mes = "Novembro";
            break;
        case "12": $mes = "Dezembro";
            break;
    }

    echo '<center>Brasília-DF, ' . $dia . ' de ' . $mes . ' de 20' . $ano . '.</center>';
    ?>


        <br/>
        <br/>
        <br/>
        <br/>
        
        <br/>
        <table width='600'>
            <tr>
                <td align='center' width='50%'>
                    ________________________________
                    <br/>
                    <b>ADMINISTRADORA
                    <br/>
                    TABAKAL</b> Emp. Imobiliários Ltda. 
                    <br/>
                    CNPJ/MF nº 06.864.021/0001-31

                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <br/>
        <br/>

        <table width='600'>
            <tr>
                <td colspan='2' align='center'>
                    ________________________________
                    <br/>
                    <b>LOCATÁRIO(A)</b>
                    <br/>
                <?php echo ($nomeLocatario); ?>
                    <br/>
                    CPF nº <?php echo $cpf; ?>
                </td>

                    <?php
                    if ($strConjugeListar != '') {
                        ?>
                    <td align='center' width='50%'>
                        ________________________________
                        <br/>
                        <b>CÔNJUGE</b>
                        <br/>
        <?php echo ($conjugeLocatarioNome); ?>
                        <br/>
                        CPF nº <?php echo $conjugeLocatarioCpf; ?>
                    </td>
        <?php
    }
    ?>
            </tr>
        </table>
        <br/>
        <br/>
        <br/>


        <table width='600'>

            <?php
            $sql = sprintf("CALL procContratoFiadorSelecionar($codContrato)");
            $rsFiador = $mySQL->runQuery($sql);

            $rsQuant = $rsFiador->num_rows;

            if ($rsQuant > 0) {

                while ($rsFiadorLinha = mysqli_fetch_assoc($rsFiador)) {
                    utf8_decode_array($rsFiadorLinha);
                    $codPessoaEstadoCivil = $rsFiadorLinha['codPessoa'];

                    if ($rsFiadorLinha['codEstadoCivil'] = 2) { //casado
                        $sqlFiador = sprintf("CALL procPessoaConjugeListar($codPessoaEstadoCivil)");
                        $rsConjugeFiador = $mySQL->runQuery($sqlFiador);
                        $rsConjugeFiadorLinha = mysqli_fetch_assoc($rsConjugeFiador);
                        utf8_decode_array($rsConjugeFiadorLinha);
                    }
                    ?>
                    <tr>	
                        <td align='center' width='50%' height='170'>
                            ________________________________
                            <br/>
                            <b>FIADOR(A)</b>
                            <br/>
                        <?php echo ($rsFiadorLinha['nome']); ?>
                            <br/>
                            CPF nº <?php echo mascaraCpf($rsFiadorLinha['cpf']); ?> 	
                        </td>
            <?php
            if ($rsConjugeFiadorLinha['nome'] <> "") {
                ?>
                            <td align='center' width='50%'>
                                ________________________________
                                <br/>
                                <b>CÔNJUGE</b>
                                <br/>
                            <?php echo ($rsConjugeFiadorLinha['nome']); ?>
                                <br/>
                                CPF nº <?php echo mascaraCpf($rsConjugeFiadorLinha['cpf']); ?>  
                            </td>
                        <?php
                    }
                    ?>
                    </tr>	
            <?php
        }
    }
    ?>

        </table>
        <br/>
        <br/>
        <b>Testemunhas:</b>
        <br/>
        <br/>
        <br/>

        <table width='600'>
            <tr>
                <td align='center' width='50%'>
                    ________________________________
                    <br/>
                    Aurélio Magno da Fonseca Pinto
                    <br/>
                    CPF nº 444.079.121-20	
                </td>

                <td align='center' width='50%'>
                    ________________________________
                    <br/>
                    Lígia de Lima Paz
                    <br/>
                    CPF nº 919.484.601-49
                </td>
            </tr>
        </table>
    </td>
    </tr>
    </table>


    <?php
} else {
    header('location:login.php');
}
?>