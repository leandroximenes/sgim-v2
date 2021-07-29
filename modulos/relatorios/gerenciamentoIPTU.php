<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

//header("Content-type: application/vnd.ms-word");
//header("Content-type: application/force-download");
//header("Content-Disposition: attachment; filename=relatorio_aniversariantes.doc");
//header("Pragma: no-cache");

$titulo = 'Relatório de IPTU';

if (isset($_SESSION["SISTEMA_codPessoa"])) {

    include("../../conexao/conexao.php");
    include("../../php/php.php");

    global $mySQL;
?>
    <style>
        body {
            margin: 0px;
            font-size: 11px;
            font-family: arial;
        }

        #tb-ir tr:hover {
            background: #f7dcdf !important;
        }

        #tb-ir tr:nth-child(odd) {
            background: #F8F8FF;
        }

        #tb-ir td,
        th {
            height: 30px;
        }

        #tb-ir a {
            float: left;
            margin-right: 5px;
        }

        .clearfix:after {
            content: ".";
            display: block;
            clear: both;
            visibility: hidden;
            height: 0;
        }


        table {
            width: 97%;
            padding: 5px;
            font-size: 11px;
            font-family: arial;
        }

        th {
            background: #19688F;
            font-weight: bold;
            color: #FFF;
        }

        .vermelho {
            color: #990000;
        }

        .verde {
            color: #31CC2E;
        }

        #cabecalhoRelatorio {
            width: 100%;
            margin-bottom: 10px;
            height: 62px;
            background: url('img/bg_topo.jpg') bottom repeat-x;
            float: left;
        }

        #logoRelatorio {
            float: left;
        }

        #tituloRelatorio {
            float: right;
            text-align: right;
        }

        #nomeRelatorio {
            padding-top: 15px;
            font-size: 18px;
            color: #19688F;
        }

        #tbodyPricipal tr:nth-child(odd) {
            background-color: #fff;
        }

        #tbodyPricipal tr:nth-child(even) {
            background-color: #CFE4EF;
        }

        #cmbProprietario {
            width: 100%;
            font-size: 10px;
            height: 100%
        }

        .popup {
            margin: 0 7px 7px 7px;
            background-color: #FAF0E6;
            display: none;
            border: 1px solid;
            float: left;
            padding: 3px;
            position: absolute;
        }
    </style>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var mydate = new Date();
            var year = mydate.getYear();
            if (year < 1000)
                year += 1900;
            $('#cbmAno').val(year);
            ListarLocador();

            $("input:checkbox[name=parcela]").change(function() {
                ListarLocador();
            });

            $('body').on('click','.sendSMS', function(){
                enviaSMS($(this));
            });
        });


        function ListarLocador() {
            let checked = [];
            $("input:checkbox[name=parcela]:checked").each(function() {
                checked.push($(this).val());
            });
            $.ajax({
                type: "POST",
                async: false,
                url: '../../ajax/IPTU.php?acao=listarIPTUPorParcela',
                data: {
                    ano: $('#cbmAno').val(),
                    parcelas: checked
                },
                success: function(data) {
                    $('#tbodyInquilinosIR').html(data);
                },
                error: function() {
                    alert('Não foi possivel listar os Locadores');
                }
            });
        }

        function enviaSMS(_this) {
            _this.children().attr('src', '../../img/loading.gif');
            $.ajax({
                type: "POST",
                async: false,
                url: '../../ajax/IPTU.php?acao=enviarSMS',
                data: {
                    ano: $('#cbmAno').val(),
                    codContrato: _this.closest("tr").find(".codContrato").html(),
                    celular: _this.siblings('.telefone').val(),
                    pNome: _this.siblings('.PNome').val(),
                    nome: _this.closest("tr").find(".nome").html(),
                    parcela: _this.closest("tr").find(".ProximaParcela").val(),
                    id_iptu: _this.closest("tr").find(".id_iptu").val(),
                },
                success: function() {
                    _this.children().attr('src', '../../img/ok-sendemail.png');
                },
                error: function(e) {
                    _this.children().attr('src', '../../img/nt-sendemail.png');
                    alert(e.responseText);
                }
            });
        }
    </script>

    <table border='0' width='90%' cellpadding='3' cellspacing='1'>
        <thead>
            <tr>
                <td colspan='2'>
                    <div id='cabecalhoRelatorio' class='clearfix'>
                        <div id='logoRelatorio' onclick="location.reload(true)">
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
        </thead>
        <tbody>

            <tr>
                <th width="10%"> Filtro</th>
                <th width="79%"> Informações do contrato</th>
            </tr>

            <tr>
                <td valign="top">
                    <b>Ano: </b>
                    <select id="cbmAno" onchange="ListarLocador()" style="margin-top: 10px;">
                        <option value="2013">2013</option>
                        <option value="2014">2014</option>
                        <option value="2015">2015</option>
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                        <option value="2031">2031</option>
                        <option value="2032">2032</option>
                        <option value="2033">2033</option>
                        <option value="2034">2034</option>
                        <option value="2035">2035</option>
                        <option value="2036">2036</option>
                        <option value="2037">2037</option>
                        <option value="2038">2038</option>
                        <option value="2039">2039</option>
                        <option value="2040">2040</option>
                    </select>
                    <br />
                    <br />
                    Parcelas não pagas:
                    <br />
                    <label>
                        <b>1º parcela: </b>
                        <input name="parcela" type="checkbox" value="parcela1">
                    </label>
                    <br />
                    <label>
                        <b>2º parcela: </b>
                        <input name="parcela" type="checkbox" value="parcela2">
                    </label>
                    <br />
                    <label>
                        <b>3º parcela: </b>
                        <input name="parcela" type="checkbox" value="parcela3">
                    </label>
                    <br />
                    <label>
                        <b>4º parcela: </b>
                        <input name="parcela" type="checkbox" value="parcela4">
                    </label>
                    <br />
                    <label>
                        <b>5º parcela: </b>
                        <input name="parcela" type="checkbox" value="parcela5">
                    </label>
                    <br />
                    <label>
                        <b>6º parcela: </b>
                        <input name="parcela" type="checkbox" value="parcela6">
                    </label>
                </td>
                <td align='center' valign="top">
                    <div style="height: 85vh; overflow: auto; width:100%">
                        <table id="tb-ir" style="width: 100%; vertical-align: text-top;">
                            <thead>
                                <tr>
                                    <th width="6%">Cod Contrato</th>
                                    <th>Endereço</th>
                                    <th width="5%">Cidade</th>
                                    <th width="12%">Proprietário</th>
                                    <th width="12%">Locatário</th>
                                    <th width="5%">Tipo</th>
                                    <th width="6%">IPTU</th>
                                    <th width="4%" style="background-color: #191970">1º Parcela</th>
                                    <th width="4%" style="background-color: #191970">2º Parcela</th>
                                    <th width="4%" style="background-color: #191970">3º Parcela</th>
                                    <th width="4%" style="background-color: #191970">4º Parcela</th>
                                    <th width="4%" style="background-color: #191970">5º Parcela</th>
                                    <th width="4%" style="background-color: #191970">6º Parcela</th>
                                    <th width="3%" style="background-color: #191970">SMS</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyInquilinosIR">

                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
<?php
} else {
    header('location:login.php');
}
