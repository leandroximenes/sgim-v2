<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

header("Content-type: application/vnd.ms-word");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=contrato_administracao_modelo.doc");
header("Pragma: no-cache");

$titulo = 'COntrato de administração';

if (isset($_SESSION["SISTEMA_codPessoa"])) {

    include("../../conexao/conexao.php");
    include("../../php/php.php");
    include("../diversos/util.php");

    global $mySQL;
    $codImovel = $_GET['codImovel'];
    $sql = sprintf("CALL procContratoImovelUnicoListar($codImovel)");
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
    $bairroImovel = $rsLinha['bairro'];
    $cidadeImovel = $rsLinha['cidade'];
    $ufImovel = $rsLinha['uf'];
    $cepImovel = mascaraCep($rsLinha['cep']);
    $codProprietario = $rsLinha['codProprietario'];
    $valor = $rsLinha['valor'];
    $intermediacao = $rsLinha['intermediacao'];

    $sql = sprintf("CALL procPessoaListarUnico($codProprietario)");
    $rsLocatorio = $mySQL->runQuery($sql);
    $rsLocatarioLinha = mysqli_fetch_assoc($rsLocatorio);
    
    utf8_decode_array($rsLocatarioLinha);
    
    $nomeLocatario = ($rsLocatarioLinha['nome']);
    $emailProprietario = ($rsLocatarioLinha['email']);
    $profissao = ($rsLocatarioLinha['profissao']);
    $cpf = mascaraCpf($rsLocatarioLinha['cpf']);
    $rg = number_format($rsLocatarioLinha['rg'], 0, ',', '.');
    $orgaoExpedidor = $rsLocatarioLinha['orgaoExpedidor'];
    $enderecoLocatario = $rsLocatarioLinha['endereco'];
    $bairroLocatario = $rsLocatarioLinha['bairro'];
    $cidadeLocatario = $rsLocatarioLinha['cidade'];
    $cepLocatario = mascaraCep($rsLocatarioLinha['cep']);
    $nacionalidade = $rsLocatarioLinha['nacionalidade'];

    $sqlTelefone = sprintf("CALL procPessoaTelefoneListar($codProprietario)");

    $rsTelefone = $mySQL->runQuery($sqlTelefone);

    $sqlBanco = sprintf("CALL procPessoaDadoBancarioListar($codProprietario)");
    $rsBanco = $mySQL->runQuery($sqlBanco);
    $rsBancoLinha = mysqli_fetch_assoc($rsBanco);
    utf8_decode_array($rsBancoLinha);

    $banco = ($rsBancoLinha['banco']);
    $agencia = $rsBancoLinha['agencia'];
    $conta = $rsBancoLinha['conta'];
    $observacao = ($rsBancoLinha['observacao']);

    //se existir conjuge ele lista!
    $sqlConjuge = sprintf("CALL procPessoaConjugeListar($codProprietario)");
    $rsConjugeLocatario = $mySQL->runQuery($sqlConjuge);


    $rsLocatarioConjugeLinha = mysqli_fetch_assoc($rsConjugeLocatario);
    $conjugeLocatarioNome = ($rsLocatarioConjugeLinha['nome']);
    $conjugeLocatarioRg = number_format((int)str_replace('.', '', str_replace('-', '', $rsLocatarioConjugeLinha['rg'])), 0, ',', '.');
    $conjugeLocatarioOrgaoExpedidor = $rsLocatarioConjugeLinha['orgaoExpedidor'];
    $conjugeLocatarioCpf = mascaraCpf($rsLocatarioConjugeLinha['cpf']);
    $conjugeLocatarioProfissao = $rsLocatarioConjugeLinha['profissao'];
    $conjugeLocatarioNacionalidade = $rsLocatarioConjugeLinha['nacionalidade'];

    if ($conjugeLocatarioNome != "") {
        $strConjugeListar = ' casado(a) com ' . $conjugeLocatarioNome . ', ' . ($conjugeLocatarioNacionalidade) . ', portador(a) da carteira de identidade ' . $conjugeLocatarioRg . ' ' . $conjugeLocatarioOrgaoExpedidor . ', inscrito(a) no CPF sob nº ' . $conjugeLocatarioCpf . ',';
    } else {
        $strConjugeListar = "";
    }
    //fim
    ?>

    <table border='0' width='600' cellpadding='0' cellspacing='0'>
        <tr>
            <td colspan='2'>
        <center><b style='font-size:18.5px'><u>CONTRATO DE ADMINISTRAÇÃO DE IMÓVEL</u></b></center>
        
        <br/>

        <div class="clJustificar">
            <b>Contrato de Administração de Imóvel </b>que fazem entre si, <?php echo $nomeLocatario . ", " . $nacionalidade . "(a), " . $profissao . ", " ?> 
            portador(a) da carteira de identidade <?php echo $rg . ' ' . $orgaoExpedidor . ", "; ?>	inscrito(a) no CPF sob nº <?php echo $cpf; ?>, 
            <?php if ($strConjugeListar != "") {
                echo $strConjugeListar;
            } ?>  
            residente(s) e domiciliado(a)(s)  no(a) 
            <?php
            echo $enderecoLocatario . " - " . $bairroLocatario . " - " . $cidadeLocatario . " - CEP: " . $cepLocatario;
            echo " Fones: "
            ?>
            <?php
            while ($linhaTelefone = mysqli_fetch_assoc($rsTelefone)) {

                $mystring = $linhaTelefone['telefone'];
                //$findme   = '________';
                //$pos = strpos($mystring, $findme);
                //if(!$pos){
                echo mascaraTel($mystring);
                //}
            }
            ?>e <b>TABAKAL</b> Empreendimentos Imobiliários Ltda., inscrita no CNPJ/MF sob o nº 06.864.021/0001-31 e Inscrição CF/DF 
            nº 07.457.662/001-02 - Brasília-DF, e no Conselho Regional de Corretores de Imóveis - CRECI, sob o nº 9508, representada 
            pela corretora de imóveis MARLEIDE DE ARAUJO TELES, CRECI/DF 8091, aqui denominados, respectivamente, 
            CONTRATANTE(S) LOCADOR(ES)(A) e CONTRATADA ADMINISTRADORA, mediante as seguintes condições:
        </div>
        <br />		
        <div class="clJustificar">
            <b>CLÁUSULA PRIMEIRA - </b>
            O(s)(A) Contratante(s) Locador(es)(a) ajusta(m) com a Contratada Administradora a administração de um imóvel situado no(a) <b> 
                <?php echo $enderecoImovel . ' - ' . $bairroImovel . ' - ' . ($cidadeImovel) . "-" . $ufImovel . " - CEP: " . $cepImovel; ?></b>, 
                tudo de conformidade com os termos da procuração anexa, que passa a fazer parte integrante deste instrumento. 
        </div>
        <br/>
        <div class="clJustificar">
            <b>CLÁUSULA SEGUNDA - </b>
            À Contratada Administradora é facultada, sob sua inteira responsabilidade, a escolha do(a) locatário(a) e das garantias 
            fidejussórias que ele prestar, estabelecendo as condições do contrato de locação que em nome do(s)(a) Contratante(s) Locador(es)(a) firmará, 
            observando a legislação pertinente, e obviamente, seus interesses.
        </div>
        <br/>
        <div class="clJustificar">
            <b>CLÁUSULA TERCEIRA - </b>
            O valor do contrato de locação inicial a ser celebrado será de 
            R$ <?php echo number_format($valor, 2, ',', '.'); ?> (<?php echo extenso($valor, false, true, true); ?>), reajustáveis a cada 12 (doze) meses, de acordo com o IGPM/FGV. 
            Fica consignado que correrá por conta do(a) locatário(a) os encargos de água, luz, seguro de incêndio, telefone, IPTU/TLP e condomínio. 
        </div>
        <br/>
        <div class="clJustificar">
            <b>CLÁUSULA QUARTA - </b>
            A Contratada Administradora prestará assistência advocatícia ao(s)(à) Contratante(s) Locador(es)(a), defendendo todos seus direitos, 
            especificamente no que diz respeito à locação e acessórios do imóvel ora administrado.
        </div>
        <br/>
        <div class="clJustificar">
            <b>Parágrafo Único – </b>As despesas judiciais e os honorários advocatícios estranhos ao contrato de locação e seus acessórios correrão por conta do(s)(a) Contratante(s) Locador(es)(a).
        </div>
        <br/>
        <div class="clJustificar">
            <b>CLÁUSULA QUINTA - </b>
            A Contratada Administradora, na hipótese de não pagamento do(a) locatário(a), efetuará às custas do(s)(a) Contratante(s) 
            Locador(es)(a) os pagamentos dos impostos, taxas, condomínios e outros encargos pertinentes ao imóvel e à sua locação; e as demais despesas decorrentes, 
            bem como as de reparos e pintura que se fizerem necessárias, cobrando-os do(a) locatário(a) e seu(s) fiador(a)(es) o que for de obrigação destes.
        </div>			
        <br/>
        <div class="clJustificar">
            <b>Parágrafo Único - </b>
            As despesas com anúncios em jornais, internet, que serão feitos a critério da Contratada Administradora, se houver rescisão de 
            administração antes do imóvel ser locado, correrão por conta do(s)(a) Contratante(s) Locador(es)(a).
        </div>			
        <br/>
        <div class="clJustificar">		
            <b>CLÁUSULA SEXTA - </b>
            A Contratada Administradora fará jus, a título de remuneração pelos serviços que prestar ao(s)(à) Contratante(s) Locador(es)(a), a comissão de 10% 
            (dez por cento) do valor dos aluguéis líquidos recebidos do(a) locatário(a), e será esta descontada na prestação mensal de contas, contra recibo.<br/><br/>

            <?php if($intermediacao > 0): 
                $importancia = $intermediacao + 10;
                ?>
            
           <b>Parágrafo Único</b> – Será descontada do primeiro aluguel e em todo novo contrato de locação a importância correspondente a <?= $importancia ?>% (<?php echo extenso($importancia, false, false, true); ?> por cento) do valor 
           do aluguel, sendo <?= $intermediacao ?>% (<?php echo extenso($intermediacao, false, false, true); ?> por cento) de taxa de intermediação e 10% (dez por cento) de taxa de administração. A taxa de intermediação refere-se a despesas 
           de aferição da idoneidade do pretendente e fiadores, vistoria, visitas ao imóvel, anúncios e outras necessárias a promoção da locação (art.22 - item VII- lei 8.245/91), 
           conforme resolução COFECI no 334/92 e Tabela Referencial de Valores aprovada pelo CRECI/DF, na XIX a Sessão Plenária, em 23.11.96. 
            <?php endif;?>

        </div>			
        <br/>
        <div class="clJustificar">					
            <b>CLÁUSULA SÉTIMA - </b>
            O(s)(A) Contratante(s) Locador(es)(a) estipula(m) que tem interesse em receber da Contratada Administradora o aluguel líquido 
            conforme a seguir: Depósito Bancário no <?php echo $banco; ?>  - Agência: <?php echo $agencia . " - "; ?> Conta Corrente 
                <?php echo $conta . " -  Favorecido(a): <b>" . $observacao; ?></b>.
        </div>			
        <br/>
        <div class="clJustificar">		
            <b>CLÁUSULA OITAVA - </b>
            A Contratada Administradora colocará à disposição do(s)(o) Contratante(s) Locador(es)(a) o valor líquido referente ao aluguel até o quinto dia útil, 
            a contar da data do efetivo recebimento do aluguel. Mensalmente a  Contratada Administradora enviará ao e-mail  <b>
                <?php echo strtolower($emailProprietario); ?></b>, extrato com créditos e débitos relativos à locação.
        </div>			
        <br/>
        <div class="clJustificar">		
            <b>CLÁUSULA NONA - </b>
            A Contratada Administradora ficará desobrigada de efetuar o pagamento do aluguel ao(s)(à) Contratante(s) Locador(es)(a) se este não for pago pelo(a) 
            locatário(a) em caso de desapropriação, interdição, venda ou penhora, arresto ou seqüestro do imóvel, calamidade pública e guerra, quando ajuizada ação 
            de retomada, ou ainda, quando por qualquer motivo o(s)(a) Contratante(s) Locador(es)(a) der(em) causa a que o(a) locatário(a) retenha o pagamento.
        </div>			
        <br/>
        <div class="clJustificar">		
            <b>CLÁUSULA DÉCIMA - </b>
            Não efetuado o pagamento do aluguel pelo(a) locatário(a) e necessitando a Contratada Administradora promover a cobrança amigável e/ou judicial contra o 
            mesmo não poderá(ão) o(s)(a) Contratante(s) Locador(es)(a), em hipótese alguma, revogar(em) a procuração que àquela outorgou(aram), nem tampouco obstar(em), 
            por qualquer forma, os procedimentos judiciais que serão promovidos, sob pena de ficar(em) sujeito(s) o(s)(a) Contratante(s) Locador(es)(a) ao pagamento de 
            uma indenização equivalente ao montante do que esteja sendo exigido do(a) locatário(a) em Juízo.
        </div>			
        <br/>
        <div class="clJustificar">					
            <b>CLÁUSULA DÉCIMA PRIMEIRA – </b>
            Ao(s)(À) Contratante(s) Locador(es)(a) caberão os juros, a correção monetária e as multas cobradas do(a) locatário(a). 
            Sobre tais verbas serão devidos à Contratada Administradora, o percentual de comissão, na forma pactuada na <b>CLÁUSULA SEXTA</b>.
        </div>			
        <br/>
        <div class="clJustificar">			
            <b>Parágrafo Primeiro - </b> Caso o pagamento do aluguel não seja efetuado pelo(a) locatário(a) na data de vencimento e havendo repasse do valor ao(s)(à) 
            Contratante(s) Locador(es)(a) até o quinto dia útil, a contar da data de seu vencimento, pela Contratada Administradora, a esta caberão o valor principal, os juros, a correção monetária e as multas cobradas do(a) locatário(a). O pagamento é uma deliberação da Contratada Administradora, não constituindo novação da Contratada Administradora o depósito do valor do aluguel não pago ao(s)(à) Contratante(s) Locador(es)(a).
        </div>
        <br/>

        <div class="clJustificar">			
            <b>Parágrafo Segundo - </b>
            A Contratada Administradora, mediante consentimento do(s)(a) Contratante(s) Locador(es)(a), promoverá a cobrança ou não da multa de rescisão contratual 
            estipulada no contrato de locação firmado com o(a) locatário(a).
        </div>

        <br/>
        <div class="clJustificar">							
            <b>CLÁUSULA DÉCIMA SEGUNDA - </b>
            A Contratada Administradora mediante autorização do(s)(a) Contratante(s) Locador(es)(a) celebrará novo contrato de locação, por prazo idêntico ou diverso, 
            se a locação em curso vier a ser rescindida antes do prazo previsto, seja amigável ou judicialmente.
        </div>			
        <br/>
        <div class="clJustificar">							
            <b>Parágrafo Único - </b>
            Ocorrendo à hipótese prevista no caput desta Cláusula, se obriga a Contratada Administradora dar ciência ao(s)(à) Contratante(s) Locador(es)(a), 
            tudo com vistas a ser ajustado novo preço e anuência quanto ao novo prazo da locação.
        </div>			
        <br/>
        <div class="clJustificar">							
            <b>CLÁUSULA DÉCIMA TERCEIRA - </b>
            Ao(s)(À) Contratante(s) Locador(es)(a) será defeso celebrar acordos com locatário(a) sem expressa anuência escrita da Contratada Administradora, 
            assim como ingerir na administração do imóvel, sob pena de multa equivalente ao valor de 01 (um) mês de aluguel conforme disposto na <b>CLÁUSULA TERCEIRA.</b>
        </div>			
        <br/>
        <div class="clJustificar">							
            <b>CLÁUSULA DÉCIMA QUARTA - </b>Na vigência do presente contrato de administração, caso seja autorizada a venda pelo(s)(a) Contratante(s) Locador(es)(a), fica desde já a Contratada Administradora, nomeada a intermediadora da venda do imóvel em questão, fazendo jus, portanto, à comissão equivalente a 5% (cinco por cento) sobre o valor da transação.
        </div>			
        <br/>
        <div class="clJustificar">							
            <b>CLÁUSULA DÉCIMA QUINTA - </b>O presente contrato de Administração é celebrado por prazo idêntico ao contrato de locação a ser celebrado e somente poderá ser rescindido nas seguintes condições:
        </div>			
        <br/>
        <div class="clJustificar">							
            a) - Por justa causa, caso a Contratada Administradora, sem qualquer justificativa válida, deixe de prestar contas do aluguel, se recebido do(a) locatário(a),
            após o prazo de carência, salvo motivo de força maior, tais como greve bancária, calamidade pública, etc. Nestas circunstâncias nada será devido de comissão à
            Contratada Administradora, exigindo-se apenas se notifique extrajudicialmente a Contratada Administradora, a fim de que se proceda administrativamente à 
            rescisão do presente contrato, sob pena de não o fazendo ser feita judicialmente. 
        </div>			
        <br/>
        <div class="clJustificar">							
            b) - Sem justa causa, devendo ser precedida de notificação com antecedência mínima de <b>90 (noventa)</b> dias do vencimento do contrato locatício, caso pretenda(m) o(s)(a) Contratante(s) Locador(es)(a) retirar(em) o imóvel da Administração da Contratada Administradora, após o vencimento do contrato locatício. Neste caso, arcará(ão) o(s)(a) Contratante(s) Locador(es)(a) com o pagamento da comissão imobiliária pactuada na <b>CLÁUSULA SEXTA</b>, calculada sobre os meses restantes até o término do contrato locatício ou, na ausência da notificação no prazo previsto, o equivalente a um mês de aluguel.
        </div>			
        <br/>
        <div class="clJustificar">							
            <b>Parágrafo Único - </b>Se a Contratada Administradora, sem motivo justificado, rescindir o presente contrato de Administração, se obrigará igualmente ao pagamento da multa correspondente a comissão imobiliária pactuada na CLÁUSULA SEXTA, calculada sobre os meses restantes até o término do contrato locatício, ou na ausência de notificação no prazo previsto, o equivalente a um mês de aluguel.
        </div>			
        <br/>
        <div class="clJustificar">											
            <b>CLÁUSULA DÉCIMA SEXTA - </b>Rescindido este contrato, ficará sem efeito a procuração referida na <b>CLÁUSULA PRIMEIRA</b>, outorgada pelo(s)(a) Contratante(s) Locador(es)(a) à Contratada Administradora.
        </div>			
        <br/>
        <div class="clJustificar">															
            <b>CLÁUSULA DÉCIMA SETIMA - </b>Elegem os contratantes o foro da Circunscrição Judiciária de Brasília-DF, com exclusão de qualquer outro, para que sejam dirimidas as questões oriundas deste contrato.
        </div>			
        <br/>
        <br/>
        <br/>		
        <div style="text-align: center"> <!-- data-->
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

            echo 'Brasília-DF, ' . $dia . ' de ' . $mes . ' de 20' . $ano .'.';
            ?>
        </div>
        <br />			
        <br/>
        <br/>
        <br/>
        <table width='600'>
            <tr>
                <td align='center'>
                    ________________________________
                    <br/>
                    <b>CONTRATANTE LOCADOR(A)</b>
                    <br/>
                    <?php echo $nomeLocatario; ?>
                    <br/>
                    CPF nº <?php echo $cpf; ?>
                </td>
                <?php
                if ($conjugeLocatarioNome != "") {
                    ?>
                    <td align='center'>
                        ________________________________
                        <br/>
                        <b>CÔNJUGE</b>
                        <br/>
        <?php echo $conjugeLocatarioNome; ?>
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
            <tr>
                <td align='center' width='50%'>
                    ________________________________
                    <br/>
                    <b>CONTRATADA ADMINISTRADORA</b>
                    <br/>
                    <b>TABAKAL</b> Emp. Imobiliários Ltda. 
                    <br/>
                    CNPJ/MF nº 06.864.021/0001-31
                </td>
            </tr>
        </table>
        <br/>
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