<?php
	require_once('Util/Msgs.php');

	// require_once('../Model/Bean/AulaBean.class.php');
	// require_once('../Model/Dao/AulaDao.class.php');

	$_DADOS = $_POST;

	$acao = trim($_DADOS['acao']);
	if(!isset($acao) || $acao == ''){//se houve algum problema na hora de receber a acao
		?><script>alert('<?php echo $msgSemAcao; ?>');</script><?php
		header('Location: ../View');
	}

	switch($acao){
		case 'carregarHTML':
			//abrir arquivo
			$arq = fopen("../Dao/Arquivos/lotfac.HTM","r");

			//pegar dados
			$texto = array();//cada indice sera uma linha
			fgets($arq);//pula linha vazia
			while(!feof($arq)){
				array_push($texto, trim(strip_tags(fgets($arq))) );
			}

			//tratar dados e colocar no retorno pronto em json

			$retorno = new stdClass();
			$retorno->status = true;
			$retorno->resposta = array();

			$apontador = 0;
			$tamanho = count($texto);
			while($apontador < $tamanho){
				$linha = new stdClass();
				//pegar primeiras colunas
				$linha->concurso = intval($texto[$apontador++]);
				$aux = $texto[$apontador++];
				$linha->dataSorteio = substr($aux,6,4)."-".substr($aux,3,2)."-".substr($aux,0,2);
				$linha->bolas = array();
				for($i = 0; $i < 15; $i++){
					array_push($linha->bolas, intval($texto[$apontador++]));
				}
				$linha->arrecadacaoTotal = floatval($texto[$apontador++]);
				$linha->qntGanhadores15 = intval($texto[$apontador++]);

				$linha->cidades = array();
				$linha->ufs = array();
				$aux = trim($texto[$apontador++]);
				array_push($linha->cidades, substr($aux,0,strlen($aux)-2));//tira os dois ultimos caracteres
				array_push($linha->ufs, substr($aux,strlen($aux)-2));//pega os dois ultimos caracteres

				$linha->qntGanhadores14 = intval($texto[$apontador++]);
				$linha->qntGanhadores13 = intval($texto[$apontador++]);
				$linha->qntGanhadores12 = intval($texto[$apontador++]);
				$linha->qntGanhadores11 = intval($texto[$apontador++]);

				$linha->rateio15 = floatval($texto[$apontador++]);
				$linha->rateio14 = floatval($texto[$apontador++]);
				$linha->rateio13 = floatval($texto[$apontador++]);
				$linha->rateio12 = floatval($texto[$apontador++]);
				$linha->rateio11 = floatval($texto[$apontador++]);

				$linha->acumulado15 = floatval($texto[$apontador++]);
				$linha->estimativaPremio = floatval($texto[$apontador++]);
				$linha->valorAcumuladoEspecial = floatval($texto[$apontador++]);

				$apontador++;//pula linha vazia

				//hora de pegar a localizcao dos ganhadores 15 que faltam
				for($i = 0; $i < $linha->qntGanhadores15 - 1; $i++){
					$aux = trim($texto[$apontador++]);
					array_push($linha->cidades, substr($aux,0,strlen($aux)-2));//tira os dois ultimos caracteres
					array_push($linha->ufs, substr($aux,strlen($aux)-2));//pega os dois ultimos caracteres
				}

				$apontador++;//pula linha vazia
				array_push($retorno->resposta, $linha);
			}

			//fecha arquivo
			fclose($arq);

			
			//ler dados

			// $nome = Util::limpaString($_DADOS['nome']);
			// $data = Util::limpaString($_DADOS['data']);
			// $periodoRegime = base64_decode(Util::limpaString($_DADOS['periodoRegime']));

			//cria bean
			// $aulaBean = new AulaBean();
			// $aulaBean->setNome($nome);
			// $aulaBean->setData($data);
			// $aulaBean->setPeriodoRegimeId($periodoRegime);

			//executa no banco
			// $retorno = AulaDao::addAula($aulaBean);
			break;
	}
	echo json_encode($retorno);
?>
