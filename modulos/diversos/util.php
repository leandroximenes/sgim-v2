<?php
error_reporting(E_ALL);
ini_set("display_errors", 0);
// Função que valida o CPF
function validaCPF($cpf) { // Verifiva se o número digitado contém todos os digitos
    //$cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
    // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
        return false;
    } else {   // Calcula os números para verificar se o CPF é verdadeiro
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf{$c} != $d) {
                return false;
            }
        }

        return true;
    }
}

function extenso($valor, $maiusculas, $moeda, $np) {
//$maiusculas true para definir o primeiro caracter
//$moeda true para definir se escreve Reais / Centavos para usar com numerais simples ou monetarios
    // verifica se tem virgula decimal
    if (strpos($valor, ",") > 0) {
        // retira o ponto de milhar, se tiver
        $valor = str_replace(".", "", $valor);

        // troca a virgula decimal por ponto decimal
        $valor = str_replace(",", ".", $valor);
    }

    if (!$moeda) {
        $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
    } else {
        $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
    }

    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");

    if (!$moeda) { // se for usado apenas para numerais
        if ($np)
            $u = array("", "uma", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
        else
            $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
    }
    else {
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
    }
    $z = 0;
    $rt = "";
    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);
    for ($i = 0; $i < count($inteiro); $i++)
        for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
            $inteiro[$i] = "0" . $inteiro[$i];

    $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
    for ($i = 0; $i < count($inteiro); $i++) {
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

        $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
        $t = count($inteiro) - 1 - $i;
        $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
        if ($valor == "000")
            $z++;
        elseif ($z > 0)
            $z--;
        if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
            $r .= (($z > 1) ? " de " : "") . $plural[$t];

        if ($r)
            $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
    }

    if (!$maiusculas) {
        $return = $rt ? $rt : "zero";
    } else {
        $return = ucwords($rt) ? ucwords($rt) : "Zero";
    }

    return ucfirst(trim($return));
}

function utf8_decode_array(&$input) {
    if (is_string($input)) {
        $input = utf8_decode($input);
    } else if (is_array($input)) {
        foreach ($input as &$value) {
            utf8_decode_array($value);
        }

        unset($value);
    } else if (is_object($input)) {
        $vars = array_keys(get_object_vars($input));

        foreach ($vars as $var) {
            utf8_decode_array($input->$var);
        }
    }
}

function mascaraCpf($cpf) {
    if ($cpf != null) {
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);
    }
}

function mascaraCNPJ($cnpj) {
    if ($cnpj != null) {
        return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
    }
}

function mascaraCep($cep) {
    if ($cep != null) {
        return substr($cep, 0, 5) . '-' . substr($cep, 5);
    }
}

function mascaraTel($tel) {
    $pos = strpos($tel, '_');
    if (!$pos) {
        $pos = strpos($tel, '-');
        return ucfirst(substr($tel, 0, $pos)) . substr($tel, $pos, 11) . '-' . substr($tel, $pos + 11) . ', ';
    }
}

function arrayUtf8Enconde(array $array) {

    //instancia um novo array
    $novo = array();

    //entar em um loop para verificar e converter cada indice do array
    foreach ($array as $i => $value) {

        //verifica se o indice é um array
        if (is_array($value)) {

            //aqui chama novamente o próprio método para verificar novamente(recursividade)
            $value = $this->arrayUtf8Enconde($value);
        } elseif (!mb_check_encoding($value, 'UTF-8')) {//se não for array, verifica se o valor está codificado como UTF-8
            //aqui ele codifica
            $value = utf8_encode($value);
        }

        //recoloca o valor no array
        $novo[$i] = $value;
    }

    //retorna o array
    return $novo;
}

function arrayUtf8Decode(array $array) {

    //instancia um novo array
    $novo = array();

    //entar em um loop para verificar e converter cada indice do array
    foreach ($array as $i => $value) {

        //verifica se o indice é um array
        if (is_array($value)) {

            //aqui chama novamente o próprio método para verificar novamente(recursividade)
            $value = $this->arrayUtf8Decode($value);
        } else {//se não for array, verifica se o valor está codificado como UTF-8
            //aqui ele codifica
            $value = utf8_decode($value);
        }

        //recoloca o valor no array
        $novo[$i] = $value;
    }

    //retorna o array
    return $novo;
}

function inverteData($data) {
    if (count(explode("/", $data)) > 1) {
        return implode("-", array_reverse(explode("/", $data)));
    } elseif (count(explode("-", $data)) > 1) {
        return implode("/", array_reverse(explode("-", $data)));
    }
}

?>