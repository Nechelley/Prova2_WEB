<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home</title>
		<script type="text/javascript">
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
							var retorno = JSON.parse(ajax.responseText);//objeto com as informacoes carregadas do arquivo
						}
					}
				};
			}
		</script>
	</head>
	<body>
		<input type="button" onclick="carregarJSON();" value="Carregar"/>
	</body>
</html>
