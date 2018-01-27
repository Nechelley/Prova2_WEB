<?php
	error_reporting(0);
	set_time_limit(500);
	require_once('Util/Msgs.php');
	require_once("../Dao/ConcursoDao.class.php");

	$_DADOS = $_POST;

	$acao = trim($_DADOS['acao']);
	if(!isset($acao) || $acao == ''){//se houve algum problema na hora de receber a acao
		?><script>alert('<?php echo $msgSemAcao; ?>');</script><?php
		header('Location: ../View');
	}

	$retorno = new stdClass();
	switch($acao){
		case 'carregarHTML':
			//abrir arquivo
			$arq = fopen("../Dao/Arquivos/lotfac.HTM","r");

			//pegar dados
			$texto = array();//cada indice sera uma linha
			fgets($arq);//pula linha vazia
			while(!feof($arq)){
				array_push($texto, utf8_encode(trim(strip_tags(fgets($arq)))) );
			}

			//tratar dados e colocar no retorno pronto em json
			$retorno->status = true;
			$retorno->resposta = array();

			$apontador = 0;
			$tamanho = count($texto);
			while($apontador < $tamanho-34){//-34 para remover o ultimo objeto
				$linha = new stdClass();
				//pegar primeiras colunas
				$linha->concurso = intval($texto[$apontador++]);
				$aux = $texto[$apontador++];
				$linha->dataSorteio = substr($aux,6,4)."-".substr($aux,3,2)."-".substr($aux,0,2);
				$linha->bolas = array();
				for($i = 0; $i < 15; $i++){
					array_push($linha->bolas, intval($texto[$apontador++]));
				}
				$linha->arrecadacaoTotal = floatval(str_replace(".","",$texto[$apontador++]));
				$linha->qntGanhadores15 = intval($texto[$apontador++]);

				$linha->cidades = array();
				$linha->ufs = array();
				$aux = trim($texto[$apontador++]);
				array_push($linha->cidades, utf8_encode(substr($aux,0,strlen($aux)-2)));//tira os dois ultimos caracteres
				array_push($linha->ufs, utf8_encode(substr($aux,strlen($aux)-2)));//pega os dois ultimos caracteres

				$linha->qntGanhadores14 = intval($texto[$apontador++]);
				$linha->qntGanhadores13 = intval($texto[$apontador++]);
				$linha->qntGanhadores12 = intval($texto[$apontador++]);
				$linha->qntGanhadores11 = intval($texto[$apontador++]);

				$linha->rateio15 = floatval(str_replace(".","",$texto[$apontador++]));
				$linha->rateio14 = floatval(str_replace(".","",$texto[$apontador++]));
				$linha->rateio13 = floatval(str_replace(".","",$texto[$apontador++]));
				$linha->rateio12 = floatval(str_replace(".","",$texto[$apontador++]));
				$linha->rateio11 = floatval(str_replace(".","",$texto[$apontador++]));

				$linha->acumulado15 = floatval(str_replace(".","",$texto[$apontador++]));
				$linha->estimativaPremio = floatval(str_replace(".","",$texto[$apontador++]));
				$linha->valorAcumuladoEspecial = floatval(str_replace(".","",$texto[$apontador++]));

				$apontador++;//pula linha vazia

				//hora de pegar a localizcao dos ganhadores 15 que faltam
				for($i = 0; $i < $linha->qntGanhadores15 - 1; $i++){
					$aux = trim($texto[$apontador++]);
					array_push($linha->cidades, utf8_encode(substr($aux,0,strlen($aux)-2)));//tira os dois ultimos caracteres
					array_push($linha->ufs, utf8_encode(substr($aux,strlen($aux)-2)));//pega os dois ultimos caracteres
				}

				$apontador++;//pula linha vazia

				$linha->ocorreu = 1;

				array_push($retorno->resposta, $linha);
			}

			//fecha arquivo
			fclose($arq);

			//parte do sincronismo com o banco passar para a opcao de baixo depois//////////////////////////////////////////////////////////////////////////
			$dados = $retorno->resposta;
			$retorno = null;
			//pegar informacoes dos concursos
			$ids = ConcursoDao::getIds();
			if(!$ids->status){//deu errado
				$retorno->status = false;
				$retorno->resposta = $ids->resposta;
			}
			else{
				//coloco os ids em um array
				$idsArray = array();
				foreach ($ids->resposta as $id) {
					array_push($idsArray,$id->id);
				}

				//analiso o obj a ser sincronizado usando o ids como norte
				foreach ($dados as $obj) {
					// print_r($obj);
					if($retorno == null){
						if(in_array($obj->concurso,$idsArray)){
							//se o obj estiver no entao as infs dele devem ser atualizadas no banco
							$ret = ConcursoDao::atualizar($obj);
							if(!$ret->status){//deu erro
								$retorno = $ret;
								break;
							}
							$key = array_search($obj->concurso, $idsArray);
							unset($idsArray[$key]);
						}
						else{
							//se o objeto nao esta entao e novo e deve ser inserido
							$ret = ConcursoDao::inserir($obj);
							if(!$ret->status){//deu erro
								$retorno = $ret;
								break;
							}
						}
					}
				}

				if($retorno == null){
					//agora os ids que sobraram serao removidos
					foreach ($idsArray as $id) {
						$ret = ConcursoDao::remover($id);
						if(!$ret->status){//deu erro
							$retorno = $ret;
							break;
						}
					}
				}

				//sincronizacao concluida
				if($retorno == null){
					$retorno = new stdClass();
					$retorno->status = true;
					$retorno->resposta = $dados;
				}
			}
			break;
		case 'sincronizarHTML':
			//le dados
			// print_r(json_decode(html_entity_decode(urldecode($_DADOS["dadosParaSincronizar"]))));
			echo "<br>";
			// $dadosParaSincronizar = json_decode(utf8_encode($_DADOS["dadosParaSincronizar"]));

			// print_r(json_last_error());


			break;
		case 'carregarTudoDoBanco':
			$ret = ConcursoDao::getTudo();
			if(!$ret->status){//deu errado
				$retorno->status = false;
				$retorno->resposta = $ret->resposta;
			}
			else{//deu certo
				$retorno->status = true;
				$retorno->resposta = array();

				$ret = $ret->resposta;

				$td = array();

				$tamanho = count($ret[0]);
				$apontador = 0;
				$apontadorBolas = 0;
				while($apontador < $tamanho){
					$obj = new stdClass();

					$obj->concurso = $ret[0][$apontador]->id;
					$obj->dataSorteio = $ret[0][$apontador]->data_sorteio;
					$obj->arrecadacaoTotal = $ret[0][$apontador]->arrecadacao_total;
					$obj->estimativaPremio = $ret[0][$apontador]->estimativa_premio;
					$obj->valorAcumuladoEspecial = $ret[0][$apontador]->valor_acumulado_especial;

					$obj->bolas = array();
					$aux = $apontadorBolas*15;
					for($i = $aux; $i < $aux+15; $i++){
						array_push($obj->bolas, $ret[1][$i]->valor);
					}
					$apontadorBolas++;

					$obj->qntGanhadores11 = $ret[0][$apontador]->qnt_ganhadores;
					$obj->rateio11 = $ret[0][$apontador++]->rateio;

					$obj->qntGanhadores12 = $ret[0][$apontador]->qnt_ganhadores;
					$obj->rateio12 = $ret[0][$apontador++]->rateio;

					$obj->qntGanhadores13 = $ret[0][$apontador]->qnt_ganhadores;
					$obj->rateio13 = $ret[0][$apontador++]->rateio;

					$obj->qntGanhadores14 = $ret[0][$apontador]->qnt_ganhadores;
					$obj->rateio14 = $ret[0][$apontador++]->rateio;

					$obj->qntGanhadores15 = $ret[0][$apontador]->qnt_ganhadores;
					$obj->rateio15 = $ret[0][$apontador]->rateio;
					$obj->acumulado15 = $ret[0][$apontador]->acumulado;

					$obj->cidades = array();
					$obj->ufs = array();
					for($i = 0; $i < $obj->qntGanhadores15; $i++){
						array_push($obj->cidades, utf8_encode($ret[0][$apontador]->cidade));//tira os dois ultimos caracteres
						array_push($obj->ufs, utf8_encode($ret[0][$apontador]->uf));//pega os dois ultimos caracteres
						$apontador++;
					}
					if($obj->qntGanhadores15 == 0)
						$apontador++;

					array_push($retorno->resposta, $obj);
				}
			}
			break;
		case 'getBolasMaisSorteadas':
			$top = $_DADOS['top'];
			$retorno = ConcursoDao::getBolasMaisSorteadas($top);
			break;
		case 'testarJogada':
			$retorno = ConcursoDao::getBolas();
			if($retorno->status){//deu certo
				$bolas = $retorno->resposta;

				$retorno->status = true;
				$retorno->resposta = array();//0,...,15 posicoes do vetor
				for ($i=0; $i <= 15; $i++) {
					$retorno->resposta[$i] = 0;
				}

				$escolhas = json_decode($_DADOS['escolhas']);//15 numero escolhidos pelo usuario

				// $vezesQueGanhou = array();
				$apontador = 0;
				while($apontador < count($bolas)){
					$concurso = new stdClass();
					$concurso->id = $bolas[$apontador]->Concurso_id;
					$concurso->bolasAcertadas = 0;

					$bolasParaComparar = array();
					for ($i = 0; $i < 15; $i++) {
						array_push($bolasParaComparar, $bolas[$apontador++]->valor);
					}

					foreach ($escolhas as $escolha) {
						//verifico se a escolha aparece nas bolas
						$flgAchou = -1;//suponho que nao achou o par correspondente
						for ($i = 0; $i < count($bolasParaComparar); $i++) {
							if($escolha == $bolasParaComparar[$i]){
								$flgAchou = $i;//achou par correspondente
								break;
							}
						}

						if($flgAchou != -1){//achou correspondente
							//removo a bola ja comparada
							unset($bolasParaComparar[$flgAchou]);
							$bolasParaComparar = array_values($bolasParaComparar);
							$concurso->bolasAcertadas++;
						}
					}
					//agora com as comparacoes ja feitas insiro o resultado dele neste concuso
					//neste ponto da para decidir oq retornar
					// array_push($vezesQueGanhou, $concurso);
					$retorno->resposta[$concurso->bolasAcertadas] += 1;//falo que no concurso ele acerto tantas bolas
				}
			}
			break;
		case 'getEstadosComMaisGanhadores':
			$top = $_DADOS['top'];

			$retorno = ConcursoDao::getEstadosComMaisGanhadores();
			if($retorno->status){//deu certo
				$estados = $retorno->resposta;

				$retorno->status = true;
				$retorno->resposta = array();

				$totalEstados = count($estados);//usar para calcular as porcentagens
				$top = $top == "" ? $totalEstados : $top;
				for ($i = 0; $i < $top; $i++) {
					$estados[$i]->qnt /= $totalEstados;//para calcular as porcentagens
					array_push($retorno->resposta, $estados[$i]);
				}
			}
			break;

		case 'criarNovoConcurso':
			$obj = json_decode($_DADOS['concurso']);

			$retorno = ConcursoDao::getIdUltimoConcurso();
			if($retorno->status){//deu certo
				$obj->id = $retorno->resposta[0]->id + 1;

				$retorno = ConcursoDao::criarNovoConcurso($obj);
				$retorno->resposta = $obj->id;
			}
			break;
		case 'addJogadaNoConcurso':
			$jogada = json_decode($_DADOS['jogada']);

			//testa se o concurso ainda nao foi concluido
			$retorno = ConcursoDao::getConcurso($jogada->id);
			if($retorno->status){//deu certo
				if(empty($retorno->resposta)){
					$retorno = ConcursoDao::addJogadaNoConcurso($jogada);
				}
				else{//ja foi concluido
					$retorno->status = false;
					$retorno->resposta = "Concurso já finalizado!";
				}
			}
			break;
		case 'encerrarConcurso':
			$obj = json_decode($_DADOS['obj']);

			//testa se o concurso ainda nao foi concluido
			$retorno = ConcursoDao::getConcurso($obj->concurso);
			if($retorno->status){//deu certo
				if(empty($retorno->resposta)){
					$retorno = ConcursoDao::encerrarConcurso($obj);
				}
				else{//ja foi concluido
					$retorno->status = false;
					$retorno->resposta = "Concurso já finalizado!";
				}
			}
			break;
	}

	echo json_encode($retorno);
?>
