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
			// die(print_r($query,true));
			//executa
			return ProcessaQuery::executarQuery($query);
		}

		//atualiza o objeto no banco
		public static function atualizar($obj){
			// //cria a query
			$query = array();

			//add concurso
			$query[0] = "UPDATE Concurso SET
						data_sorteio = '{$obj->dataSorteio}',
						arrecadacao_total = {$obj->arrecadacaoTotal},
						estimativa_premio = {$obj->estimativaPremio},
						valor_acumulado_especial = {$obj->valorAcumuladoEspecial}
						WHERE id = {$obj->concurso};";

			//deleto as bolas antigas e insiroas novas
			$query[1] = "DELETE FROM Bola WHERE Concurso_id = {$obj->concurso};";

			$query[2] = "INSERT INTO Bola
			(valor,Concurso_id)
			VALUES";
			foreach ($obj->bolas as $bola) {
				$query[2] .= "({$bola},{$obj->concurso}),";
			}
			$query[2] = rtrim($query[2],",").";";

			//11 bolas acertadas
			$query[3] = "UPDATE Ganhadores SET
						qnt_ganhadores = {$obj->qntGanhadores11},
						rateio = {$obj->rateio11}
						WHERE qnt_bolas_acertadas = 11 AND Concurso_id = {$obj->concurso};";

			//12 bolas acertadas
			$query[4] = "UPDATE Ganhadores SET
						qnt_ganhadores = {$obj->qntGanhadores12},
						rateio = {$obj->rateio12}
						WHERE qnt_bolas_acertadas = 12 AND Concurso_id = {$obj->concurso};";

			//13 bolas acertadas
			$query[5] = "UPDATE Ganhadores SET
						qnt_ganhadores = {$obj->qntGanhadores13},
						rateio = {$obj->rateio13}
						WHERE qnt_bolas_acertadas = 13 AND Concurso_id = {$obj->concurso};";

			//14 bolas acertadas
			$query[6] = "UPDATE Ganhadores SET
						qnt_ganhadores = {$obj->qntGanhadores14},
						rateio = {$obj->rateio14}
						WHERE qnt_bolas_acertadas = 14 AND Concurso_id = {$obj->concurso};";

			//15 bolas acertadas
			$query[7] = "UPDATE Ganhadores SET
						qnt_ganhadores = {$obj->qntGanhadores15},
						rateio = {$obj->rateio15},
						acumulado = {$obj->acumulado15}
						WHERE qnt_bolas_acertadas = 15 AND Concurso_id = {$obj->concurso};";


			//deleto os superganhadores antigos e insiro os novos
			$query[8] = "DELETE FROM SuperGanhador WHERE
			Ganhadores_id = (SELECT id FROM Ganhadores WHERE qnt_bolas_acertadas = 15 AND Concurso_id = {$obj->concurso});";//esse select so retona um linha semrpe

			$query[9] = "INSERT INTO SuperGanhador
			(cidade,uf,Ganhadores_id)
			VALUES";
			for ($i=0; $i < count($obj->qntGanhadores15); $i++) {
				$query[9] .= "('{$obj->cidades[$i]}','{$obj->ufs[$i]}',(SELECT id FROM Ganhadores WHERE qnt_bolas_acertadas = 15 AND Concurso_id = {$obj->concurso})),";
			}
			$query[9] = rtrim($query[9],",").";";
			// print_r($query);
			// die("sdsd");
			//executa
			return ProcessaQuery::executarQuery($query);
		}

		//remove o obj com o id passado
		public static function remover($id){
			$query = "DELETE FROM Concurso WHERE id = {$id};";
			// die($query);
			//executa
			return ProcessaQuery::executarQuery($query);
		}

		//retorna os ids da tabela concurso
		public static function getIds(){
			$query = "SELECT DISTINCT id FROM Concurso;";
			// die($query);
			//executa
			return ProcessaQuery::consultarQuery($query);
		}

		//retorna tudo que esta no banco, ate o limite
		public static function getTudo($limit){
			$query = array();
			$query[0] = "SELECT a.id,data_sorteio,arrecadacao_total,estimativa_premio,valor_acumulado_especial,
							qnt_bolas_acertadas,qnt_ganhadores,rateio,acumulado,cidade,uf
					FROM
					Concurso AS a
					INNER JOIN
					(
						SELECT Concurso_id,qnt_bolas_acertadas,qnt_ganhadores,rateio,acumulado,cidade,uf
						FROM Ganhadores LEFT JOIN SuperGanhador ON Ganhadores.id = Ganhadores_id
					) AS b
					ON
					a.id = b.Concurso_id order by id,qnt_bolas_acertadas

					;";


			$query[1] = "SELECT valor FROM Bola ORDER BY Concurso_id;";

			//executa
			return ProcessaQuery::consultarQuery($query);
		}

		//retornaas bolas mais sorteadas
		public static function getBolasMaisSorteadas($top){
			$query = "SELECT valor, COUNT(*) AS qnt FROM Bola GROUP BY valor ORDER BY qnt DESC LIMIT {$top};";
			// die($query);
			//executa
			return ProcessaQuery::consultarQuery($query);
		}

		//retorna todas as bolas
		public static function getBolas(){
			$query = "SELECT Concurso_id,valor FROM Bola;";
			// die($query);
			//executa
			return ProcessaQuery::consultarQuery($query);
		}
	}
?>
