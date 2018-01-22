<?php
	//Classe que representa a tabela Bola
	class BolaBean{
		private $id;
		private $valor;
		private $concursoId;

		public function getId(){
			return $this->id;
		}

		public function setId($valor){
			$this->id = $valor;
		}

		public function getValor(){
			return $this->valor;
		}

		public function setValor($valor){
			$this->valor = $valor;
		}

		public function getConcursoId(){
			return $this->concursoId;
		}

		public function setConcursoId($valor){
			$this->concursoId = $valor;
		}
	}
?>
