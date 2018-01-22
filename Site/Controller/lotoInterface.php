<?php
	require_once('Utils/Msgs.php');
	require_once('Utils/Util.class.php');

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
