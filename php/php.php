<?php
	function verificarData($data){
		$dataNascimentoInversa = (int) $data;
		$dataInversa = (int) date('Ymd');
		
		if($dataInversa > $dataNascimentoInversa){
			return 'vermelho';
		}else{
			return 'verde';
		}
	}

	function data(){
		$diaSemana[0] = 'Domingo';
		$diaSemana[1] = 'Segunda-feira';
		$diaSemana[2] = 'Terça-feira';
		$diaSemana[3] = 'Quarta-feira';
		$diaSemana[4] = 'Quinta-feira';
		$diaSemana[5] = 'Sexta-feira';
		$diaSemana[6] = 'Sábado';

		$mes[1] = 'Janeiro';
		$mes[2] = 'Fevereiro';
		$mes[3] = 'Março';
		$mes[4] = 'Abril';
		$mes[5] = 'Maio';
		$mes[6] = 'Junho';
		$mes[7] = 'Julho';
		$mes[8] = 'Agosto';
		$mes[9] = 'Setembro';
		$mes[10] = 'Outubro';
		$mes[11] = 'Novembro';
		$mes[12] = 'Dezembro';

		return $diaSemana[date('w')] . ', ' . date('d') . ' de ' . $mes[(int)date('m')] . ' de ' . date('Y');
	}
?>