<?php
	// die(getcwd());
	require_once('../Dao/Conexao/ProcessaQuery.class.php');
	//Classe que executa as acoes no banco
	class ConcursoDao{
		//Conecta com o banco e insere o obj
		public static function inserir($obj){
			//cria a query
			$query = array();

			//add concurso
			$query[0] = "INSERT INTO Concurso
			(id,data_sorteio,arrecadacao_total,estimativa_premio,valor_acumulado_especial)
			VALUES
			({$obj->concurso},'{$obj->dataSorteio}',{$obj->arrecadacaoTotal},{$obj->estimativaPremio},{$obj->valorAcumuladoEspecial});";

			$query[1] = "INSERT INTO Bola
			(valor,Concurso_id)
			VALUES";
			foreach ($obj->bolas as $bola) {
				$query[1] .= "({$bola},{$obj->concurso}),";
			}
			$query[1] = rtrim($query[1],",").";";

			//11 bolas acertadas

			$query[2] = "INSERT INTO Ganhadores
			(qnt_bolas_acertadas,qnt_ganhadores,rateio,Concurso_id)
			VALUES
			(11,{$obj->qntGanhadores11},{$obj->rateio11},{$obj->concurso});";

			//12 bolas acertadas

			$query[3] = "INSERT INTO Ganhadores
			(qnt_bolas_acertadas,qnt_ganhadores,rateio,Concurso_id)
			VALUES
			(12,{$obj->qntGanhadores12},{$obj->rateio12},{$obj->concurso});";

			//13 bolas acertadas

			$query[4] = "INSERT INTO Ganhadores
			(qnt_bolas_acertadas,qnt_ganhadores,rateio,Concurso_id)
			VALUES
			(13,{$obj->qntGanhadores13},{$obj->rateio13},{$obj->concurso});";

			//14 bolas acertadas

			$query[5] = "INSERT INTO Ganhadores
			(qnt_bolas_acertadas,qnt_ganhadores,rateio,Concurso_id)
			VALUES
			(14,{$obj->qntGanhadores14},{$obj->rateio14},{$obj->concurso});";

			//15 bolas acertadas

			$query[6] = "INSERT INTO Ganhadores
			(qnt_bolas_acertadas,qnt_ganhadores,rateio,Concurso_id,acumulado)
			VALUES
			(15,{$obj->qntGanhadores15},{$obj->rateio15},{$obj->concurso},{$obj->acumulado15});";

			$query[7] = "INSERT INTO SuperGanhador
			(cidade,uf,Ganhadores_id)
			VALUES";
			for ($i=0; $i < $obj->qntGanhadores15; $i++) {
				$query[7] .= "('{$obj->cidades[$i]}','{$obj->ufs[$i]}',LAST_INSERT_ID()),";
			}
			$query[7] = rtrim($query[7],",").";";

			//executa
			return ProcessaQuery::executarQuery($query);
		}

		//atualiza o objeto no banco
		public static function atualizar($obj){
			// //cria a query
			// $query = array();
            //
			// //add concurso
			// $query[0] = "UPDATE Concurso SET
			// 			data_sorteio = '{$obj->dataSorteio}',
			// 			arrecadacao_total = {$obj->arrecadacaoTotal},
			// 			estimativa_premio = {$obj->estimativaPremio},
			// 			valor_acumulado_especial = {$obj->valorAcumuladoEspecial}
			// 			WHERE id = {$obj->concurso};";
            //
			// $query[1] = "UPDATE Bola SET
			// (valor,Concurso_id)
			// VALUES";
			// foreach ($obj->bolas as $bola) {
			// 	$query[1] += "({$bola},{$obj->concurso}),";
			// }
			// $query[1] = rtrim($query[1],",").";";
            //
			// //11 bolas acertadas
            //
			// $query[2] = "INSERT INTO Ganhadores
			// (qnt_bolas_acertadas,qnt_ganhadores,rateio,Concurso_id)
			// VALUES
			// (11,{$obj->qntGanhadores11},{$obj->rateio11},{$obj->concurso});";
            //
			// //12 bolas acertadas
            //
			// $query[3] = "INSERT INTO Ganhadores
			// (qnt_bolas_acertadas,qnt_ganhadores,rateio,Concurso_id)
			// VALUES
			// (12,{$obj->qntGanhadores12},{$obj->rateio12},{$obj->concurso});";
            //
			// //13 bolas acertadas
            //
			// $query[4] = "INSERT INTO Ganhadores
			// (qnt_bolas_acertadas,qnt_ganhadores,rateio,Concurso_id)
			// VALUES
			// (13,{$obj->qntGanhadores13},{$obj->rateio13},{$obj->concurso});";
            //
			// //14 bolas acertadas
            //
			// $query[5] = "INSERT INTO Ganhadores
			// (qnt_bolas_acertadas,qnt_ganhadores,rateio,Concurso_id)
			// VALUES
			// (14,{$obj->qntGanhadores14},{$obj->rateio14},{$obj->concurso});";
            //
			// //15 bolas acertadas
            //
			// $query[6] = "INSERT INTO Ganhadores
			// (qnt_bolas_acertadas,qnt_ganhadores,rateio,Concurso_id,acumulado)
			// VALUES
			// (15,{$obj->qntGanhadores15},{$obj->rateio15},{$obj->concurso},{$obj->acumulado15});";
            //
			// $query[7] = "INSERT INTO Ganhadores
			// (cidade,uf,Ganhadores_id)
			// VALUES";
			// for ($i=0; $i < count($obj->qntGanhadores15); $i++) {
			// 	$query[7] += "('{$obj->cidades[$i]}','{$obj->uf[$i]}',LAST_INSERT_ID()),";
			// }
			// $query[7] = rtrim($query[7],",").";";
            //
			// //executa
			// return ProcessaQuery::executarQuery($query);
		}

		//retorna os ids da tabela concurso
		public static function getIds(){
			$query = "SELECT DISTINCT id FROM Concurso;";
			// die($query);
			//executa
			return ProcessaQuery::consultarQuery($query);
		}
	}
?>
