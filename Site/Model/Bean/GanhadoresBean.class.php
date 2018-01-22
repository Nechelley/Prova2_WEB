<?php
	//Classe que representa a tabela Ganhadores
	class GanhadoresBean{
		private $id;
		private $qntBolasAcertadas;
		private $qntGanhadores;
		private $rateio;
		private $acumulado;
		private $concursoId;

		public function getId(){
			return $this->id;
		}

		public function setId($valor){
			$this->id = $valor;
		}

		public function getQntBolasAcertadas(){
			return $this->qntBolasAcertadas;
		}

		public function setQntBolasAcertadas($valor){
			$this->qntBolasAcertadas = $valor;
		}

		public function getQntGanhadores(){
			return $this->qntGanhadores;
		}

		public function setQntGanhadores($valor){
			$this->qntGanhadores = $valor;
		}

		public function getRateio(){
			return $this->rateio;
		}

		public function setRateio($valor){
			$this->rateio = $valor;
		}

		public function getAcumulado(){
			return $this->acumulado;
		}

		public function setAcumulado($valor){
			$this->acumulado = $valor;
		}

		public function getConcursoId(){
			return $this->concursoId;
		}

		public function setConcursoId($valor){
			$this->concursoId = $valor;
		}
	}
?>
