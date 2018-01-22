<?php
	//Classe que representa a tabela Concurso
	class ConcursoBean{
		private $id;
		private $dataSorteio;
		private $arrecadacaoTotal;
		private $estimativaPremio;
		private $valorAcumuladoEspecial;

		public function getId(){
			return $this->id;
		}

		public function setId($valor){
			$this->id = $valor;
		}

		public function getDataSorteio(){
			return $this->dataSorteio;
		}

		public function setDataSorteio($valor){
			$this->dataSorteio = $valor;
		}

		public function getArrecadacaoTotal(){
			return $this->arrecadacaoTotal;
		}

		public function setArrecadacaoTotal($valor){
			$this->arrecadacaoTotal = $valor;
		}

		public function getEstimativaPremio(){
			return $this->estimativaPremio;
		}

		public function setEstimativaPremio($valor){
			$this->estimativaPremio = $valor;
		}

		public function getValorAcumuladoEspecial(){
			return $this->valorAcumuladoEspecial;
		}

		public function setValorAcumuladoEspecial($valor){
			$this->valorAcumuladoEspecial = $valor;
		}
	}
?>
