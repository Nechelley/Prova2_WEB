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

		</script>
	</head>
	<body>
		<input type="button" onclick="carregarJSON();" value="Carregar"/>
		<input type="button" onclick="sincronizarJSON();" value="Sincronizar"/><!-- lembrar de desabilitar esse botao ate o carregamento estar efetuado, para n bugar acontecendo os dois juntos; -->
		<input type="button" onclick="carregarTudoDoBanco();" value="Carregar td do banco"/>
		<div id="carregados">

		</div>
	</body>
</html>
