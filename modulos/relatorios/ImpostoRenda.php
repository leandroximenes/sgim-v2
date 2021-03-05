<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

//header("Content-type: application/vnd.ms-word");
//header("Content-type: application/force-download");
//header("Content-Disposition: attachment; filename=relatorio_aniversariantes.doc");
//header("Pragma: no-cache");

$titulo = 'Relatório de Imposto de Renda para Proprietário e Inquilino';

if (isset($_SESSION["SISTEMA_codPessoa"])) {

    include("../../conexao/conexao.php");
    include("../../php/php.php");

    global $mySQL;
    ?>
    <style>
        body{
            margin: 0px;
            font-size: 11px;
            font-family: arial;
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

        #tbodyPricipal tr:nth-child(odd) {
            background-color: #fff;
        }
        #tbodyPricipal tr:nth-child(even) {
            background-color: #CFE4EF;
        }

        #cmbProprietario{
            width: 100%; 
            font-size: 10px; 
            height: 100%
        }

        .popup{
            margin: 0 7px 7px 7px;
            background-color: #FAF0E6; 
            display: none;
            border: 1px solid;
            float: left;
            padding: 3px;
            position: absolute;
        }



    </style>
    <script type="text/javascript" src="../../biblioteca_js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var mydate = new Date();
            var year = mydate.getYear();
            if (year < 1000)
                year += 1900;
            $('#cbmAno').val(year);
            ListarLocador();
        });
        function ListarInquilinos() {
            $.ajax({
                type: "POST",
                async: false,
                url: '../../ajax/ImpostoRenda.php?acao=trazerInquilino',
                data: {
                    id_proprietarios: $('#cmbProprietario').val(),
                    ano: $('#cbmAno').val()
                },
                success: function (data) {
                    $('#tbodyInquilinosIR').html(data);
                },
                error: function () {
                    $('#tbodyInquilinosIR').html('Não foi possivel trazer os inquilinos');
                }
            });
        }

        function ListarLocador() {
            $.ajax({
                type: "POST",
                async: false,
                url: '../../ajax/ImpostoRenda.php?acao=ListarLocador',
                data: {
                    ano: $('#cbmAno').val()
                },
                success: function (data) {
                    $('#cmbProprietario').html(data);
                    $('#tbodyInquilinosIR').html('');
                },
                error: function () {
                    alert('Não foi possivel listar os Locadores');
                }
            });
        }
    </script>

    <table border='0' width='990' height="500" cellpadding='3' cellspacing='1'>
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
        </thead>
        <tbody>

            <tr>
                <th width="21%"> Locador</th>
                <th> Relatório</th>
            </tr>

            <tr>
                <td width='150' height="400" valign="top">
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
                    <select id="cmbProprietario" multiple="multiple" onchange="ListarInquilinos()">
                    </select>
                </td>
                <td align='center' valign="top">
                    <div style="position: absolute; height: 445px; overflow: auto; width: 780px"> 
                        <table id="tb-ir" style="width: 100%; vertical-align: text-top;">
                            <thead>
                                <tr>
                                    <th>Locador</th>
                                    <th width="7%">Relatorio</th>
                                    <th width="7%">Declarante</th>
                                    <th width="7%">Comunicado</th>
                                    <th width="7%">Enviado</th>
                                    <th style="background-color: #191970">Inquilino</th>
                                    <th style="background-color: #191970" width="7%">Relatorio</th>
                                    <th style="background-color: #191970" width="7%">Declarante</th>
                                    <th style="background-color: #191970" width="7%">Comunicado</th>
                                    <th style="background-color: #191970" width="7%">Enviado</th>
                                </tr>
                            </thead>
                            <tbody  id="tbodyInquilinosIR">

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