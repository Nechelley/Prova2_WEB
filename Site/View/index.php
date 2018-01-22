<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home</title>
	</head>
	<body>
		<form action="lotoInterface.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="acao" value="carregarHTML">
			Arquivo HTML:
			<input type="file" name="arquivo"><br/>
			<input type="submit" value="Enviar">
		</form>
	</body>
</html>
