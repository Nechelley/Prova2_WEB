<?php
	//Classe que representa a tabela SuperGanhadores
	class SuperGanhadoresBean{
		private $id;
		private $cidade;
		private $uf;
		private $ganhadoresId;

		public function getId(){
			return $this->id;
		}

		public function setId($valor){
			$this->id = $valor;
		}

		public function getCidade(){
			return $this->cidade;
		}

		public function setCidade($valor){
			$this->cidade = $valor;
		}

		public function getUf(){
			return $this->uf;
		}

		public function setUf($valor){
			$this->uf = $valor;
		}

		public function getGanhadoresId(){
			return $this->ganhadoresId;
		}

		public function setGanhadoresId($valor){
			$this->ganhadoresId = $valor;
		}
	}
?>
