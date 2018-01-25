<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home</title>
		<script type="text/javascript">
			var retorno;
			function carregarJSON(){
				var url = "../Controller/lotoInterface.php";
				var acao = "carregarHTML";

				ajax = new XMLHttpRequest();
				ajax.open("POST",url);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send("acao="+acao);
				ajax.onload = function() {
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							retorno = JSON.parse(ajax.responseText).resposta;//objeto com as informacoes carregadas do arquivo
							console.log(retorno);
							alert("Carregado!");
						}
					}
				};
			}

			// //sincroniza com o banco de acordo com o id do concurso
			// function sincronizarJSON(){
			// 	//pegar dados da tabela
			// 	var dadosParaSincronizar = getDados();
            //
			// 	var url = "../Controller/lotoInterface.php";
			// 	var acao = "sincronizarHTML";
            //
			// 	ajax = new XMLHttpRequest();
			// 	ajax.open("POST",url);
			// 	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			// 	ajax.send("acao="+acao+"&dadosParaSincronizar="+dadosParaSincronizar);
			// 	ajax.onload = function() {
			// 		if (ajax.readyState == 4) {
			// 			if (ajax.status == 200) {
			// 				alert(JSON.parse(ajax.responseText).status);//objeto com as informacoes carregadas do arquivo
			// 			}
			// 		}
			// 	};
			// }
			// //cria o json com as informacoes da tabela
			// function getDados(){
			// 	return JSON.stringify(retorno);
			// }

			//retorna todas as informacoes salvas no banco
			function carregarTudoDoBanco(){
				var url = "../Controller/lotoInterface.php";
				var acao = "carregarTudoDoBanco";

				ajax = new XMLHttpRequest();
				ajax.open("POST",url);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send("acao="+acao);
				ajax.onload = function() {
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							console.log(JSON.parse(ajax.responseText).resposta);//objeto com as informacoes carregadas do arquivo

						}
					}
				};
			}

			//retorna as bolas mais sorteadas,  pode ser usado o top para pegar so os 3 mehores
			function getBolasMaisSorteadas(){
				var url = "../Controller/lotoInterface.php";
				var acao = "getBolasMaisSorteadas";
				var top = 3;

				ajax = new XMLHttpRequest();
				ajax.open("POST",url);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send("acao="+acao+"&top="+top);
				ajax.onload = function() {
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							alert(ajax.responseText);//objeto com as informacoes carregadas do arquivo
						}
					}
				};
			}

			//testa a jogada dos 15 numeros e retorna em quantos concursos faria 1,2,3,...15 pontos
			function testarJogada(){
				var url = "../Controller/lotoInterface.php";
				var acao = "testarJogada";
				var escolhas = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];

				ajax = new XMLHttpRequest();
				ajax.open("POST",url);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send("acao="+acao+"&escolhas="+JSON.stringify(escolhas));
				ajax.onload = function() {
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							alert(ajax.responseText);//objeto com as informacoes carregadas do arquivo
						}
					}
				};
			}

			//retorna a peorcentagem de ganhadores de cada estado, pode ser usado o top para pegar so os 3 mehores
			function getEstadosComMaisGanhadores(){
				var url = "../Controller/lotoInterface.php";
				var acao = "getEstadosComMaisGanhadores";
				var top = 3;

				ajax = new XMLHttpRequest();
				ajax.open("POST",url);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send("acao="+acao+"&top="+top);
				ajax.onload = function() {
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							alert(ajax.responseText);//objeto com as informacoes carregadas do arquivo
						}
					}
				};
			}

		</script>
	</head>
	<body>
		<input type="button" onclick="carregarJSON();" value="Carregar do html..e ja salvar no banco"/>
		<!-- <input type="button" onclick="sincronizarJSON();" value="Sincronizar"/><!-- lembrar de desabilitar esse botao ate o carregamento estar efetuado, para n bugar acontecendo os dois juntos; -->
		<input type="button" onclick="carregarTudoDoBanco();" value="Carregar td do banco"/>
		<input type="button" onclick="getBolasMaisSorteadas();" value="getBolasMaisSorteadas"/>
		<input type="button" onclick="testarJogada();" value="testarJogada"/>
		<input type="button" onclick="getEstadosComMaisGanhadores();" value="getEstadosComMaisGanhadores"/><br><br><br><h1>PART2</h1><br><br>
		<input type="button" onclick="getEstadosComMaisGanhadores();" value="getEstadosComMaisGanhadores"/>
	</body>
</html>
