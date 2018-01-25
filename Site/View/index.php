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

			//sincroniza com o banco de acordo com o id do concurso
			function sincronizarJSON(){
				//pegar dados da tabela
				var dadosParaSincronizar = getDados();

				var url = "../Controller/lotoInterface.php";
				var acao = "sincronizarHTML";

				ajax = new XMLHttpRequest();
				ajax.open("POST",url);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send("acao="+acao+"&dadosParaSincronizar="+dadosParaSincronizar);
				ajax.onload = function() {
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							alert(JSON.parse(ajax.responseText).status);//objeto com as informacoes carregadas do arquivo
						}
					}
				};
			}
			//cria o json com as informacoes da tabela
			function getDados(){
				return JSON.stringify(retorno);
			}

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

			function getBolasMaisSorteadas(){
				var url = "../Controller/lotoInterface.php";
				var acao = "getBolasMaisSorteadas";

				ajax = new XMLHttpRequest();
				ajax.open("POST",url);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send("acao="+acao+"&top=3");
				ajax.onload = function() {
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							alert(ajax.responseText);//objeto com as informacoes carregadas do arquivo
						}
					}
				};
			}

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

		</script>
	</head>
	<body>
		<input type="button" onclick="carregarJSON();" value="Carregar"/>
		<input type="button" onclick="sincronizarJSON();" value="Sincronizar"/><!-- lembrar de desabilitar esse botao ate o carregamento estar efetuado, para n bugar acontecendo os dois juntos; -->
		<input type="button" onclick="carregarTudoDoBanco();" value="Carregar td do banco"/>
		<input type="button" onclick="getBolasMaisSorteadas();" value="getBolasMaisSorteadas"/>
		<input type="button" onclick="testarJogada();" value="testarJogada"/>
	</body>
</html>
