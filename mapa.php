<?php
die;
session_start();
date_default_timezone_set('America/Sao_Paulo');
header('Content-Type: text/html; charset=iso-8859-1');
$servidor = "50.62.209.122"; /* maquina a qual o banco de dados est?$)C"/(v */
$usuario = "tabaka_admin"; /* usuario do banco de dados MySql */
$senha = "a1b2c3d4"; /* senha do banco de dados MySql */
$banco = "Tabakal"; /* seleciona o banco a ser usado */


$conexao = mysql_connect($servidor, $usuario, $senha);  /* Conecta no bando de dados MySql */

mysql_select_db($banco); /* seleciona o banco a ser usado */

if ($_REQUEST['acao'] == 'salvaPosicao') {
    $lat = !empty($_REQUEST['lat']) ? $_REQUEST['lat'] : null;
    $lng = !empty($_REQUEST['lng']) ? $_REQUEST['lng'] : null;
    $imovel = !empty($_REQUEST['imovel']) ? $_REQUEST['imovel'] : null;
    $sqlUpdate = "UPDATE imovel SET latitude = {$lat}, longitude = {$lng} WHERE codImovel = {$imovel}";
    mysql_query($sqlUpdate);
    die;
}
?>

<html>
    <head>
        <script type="text/javascript" src="biblioteca_js/jquery-1.11.0.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script>
            var map;
            marker = new Array();
            contentString = new Array();
            infowindow = new Array();
            function initialize() {
                var mapOptions = {
                    zoom: 12,
                    center: new google.maps.LatLng(-15.8, -47.8547)
                };
                map = new google.maps.Map(document.getElementById('map-canvas'),
                        mapOptions);

                marcaImoveis();
            }


            $(document).ready(function() {
             //   altura = $(document).height(); //altura da página
             //   $('#map-canvas').css('height', altura + 'px');
             //   google.maps.event.addDomListener(window, 'load', initialize);
            });



            function marcaImoveis() {
<?php
$imoveis = mysql_query("SELECT i.cep, i.endereco, i.codImovel, i.latitude, i.longitude, i.endereco, c.dataFim, pl.nome as locador, pi.nome as inquilino
from  imovel as i 
LEFT JOIN contrato as c on i.codImovel = c.codImovel AND c.status = 1
LEFT JOIN pessoa as pl on pl.codPessoa = c.codPessoaLocador
LEFT JOIN pessoa as pi on pi.codPessoa = c.codPessoaInquilino");
while ($rs = mysql_fetch_array($imoveis)):
    if (strlen($rs['cep']) > 7):
        
        $dataFim = mktime(0, 0, 0, date('m', strtotime($rs['dataFim'])), date('d', strtotime($rs['dataFim'])), date('Y', strtotime($rs['dataFim'])));
        $dataHoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        
        if($dataFim > $dataHoje){
            $status = "Alugado";
            $dataFim = date('d/m/Y', $dataFim);
        }else{
            $status = "Desocupado";
            $dataFim = '';
        }
        ?>
        contentString['<?php echo $rs['codImovel'] ?>'] = <?php echo iconv('iso-8859-1', 'ASCII//TRANSLIT//IGNORE', "'<div id=\"content\">' +
                                '<div id=\"siteNotice\">' +
                                '</div>' +
                                '<b style=\" font-size: 10pt; font-family: arial; color: #666666;  \" id=\"firstHeading\" class=\"firstHeading\">".preg_replace('/\s/',' ',utf8_decode($rs['endereco']))."</b>' +
                                '<div id=\"bodyContent\" style=\" font-size: 9pt; font-family: arial; color: #666666;  \">' +
                                '<br><b>Status: </b> {$status} ' +
                                '<br><b>Propietario: </b>". addslashes(utf8_decode($rs['locador'])) ."' +
                                '<br><b>Inquilino: </b>".  addslashes(utf8_decode($rs['inquilino'])) ."' +
                                '<br><b>Fim do contrato: </b>". $dataFim ."' +
                                '</div>' +
                                '</div>';"); ?>
        <?php
        if (empty($rs['latitude']) && empty($rs['longitude'])) {
            ?>
                            $.ajax({
                                url: "http://maps.google.com/maps/api/geocode/json?address=<?php echo substr($rs['cep'], 0, 5) . '-' . substr($rs['cep'], 5) ?>&sensor=false",
                                context: document.body
                            }).done(function(data) {
                                var json = data;
                                $(json).each(function(i, obj) {

                                    carregaPonto(<?php echo $rs['codImovel'] ?>, obj.results[0].geometry.location.lat, obj.results[0].geometry.location.lng);
                                    $.ajax({
                                        url: "mapa.php",
                                        type: "POST",
                                        data: {'acao': 'salvaPosicao', 'lat': obj.results[0].geometry.location.lat, 'lng': obj.results[0].geometry.location.lng, 'imovel': <?php echo $rs['codImovel'] ?>},
                                        dataType: "html"});
                                });
                            });
            <?php
        } else {
            echo "carregaPonto('" . $rs['codImovel'] . "','" . $rs['latitude'] . "','" . $rs['longitude'] . "','". $status ."');";
        }
        ?>

                        



        <?php
    endif;
endwhile;
?>
            }



            function carregaPonto(cod, lat, lng, status) {
                var img = 'http://sgim.tabakalimoveis.com.br/iconemapaVermelho.png';
                if(status == "Alugado"){
                    img = 'http://sgim.tabakalimoveis.com.br/iconemapaAzul.png';
                }
               var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat, lng),
                    map: map,
                    icon: img 
                });

                if(typeof contentString[cod] != 'undefined'){
                    var texto = contentString[cod]; 
                }else{
                    var texto = 'TXT - '+cod;
                }
                
                var infowindow = new google.maps.InfoWindow({
                    content: texto
                });
                
                google.maps.event.addListener(marker, 'click', function() {
                        infowindow.open(map, marker);
                });
            }




            function print_r(arr, text, tab) {
                var text = (text ? text : "Array \n("), tab = (tab ? tab : "\t");

                for (key in arr) {
                    if (typeof arr[key] == "object")
                        text = print_r(arr[key], text + "\n" + tab + "[" + key + "] => Array \n" + tab + "(", "\t" + tab);
                    else
                        text += "\n" + tab + "[" + key + "] => " + arr[key];

                    text += "\n" + (tab.substr(0, tab.length - 1)) + ")\n";
                }
                return text;

            }


        </script>
    </head>

    <body style="margin: 0px">
        <div id="map-canvas" style="width: 100%; height: 470px;"></div> 
    </body>