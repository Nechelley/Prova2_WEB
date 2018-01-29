<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home</title>
		<link rel="stylesheet" href="style.css">
		<script type="text/javascript">
			window.onload = init;


			// variavei globais
			var retorno;
			var idAreaPergunta;
			var divAreaPergunta;

			var idAreaResposta;
			var divAreaResposta;

			var idConcursoCriado;

			function init() {
				desabilitarBotaoSincronizar();

				idAreaPergunta = 'areaPerguntas';
				divAreaPergunta = document.getElementById(idAreaPergunta);

				idAreaResposta = 'areaResposta';
				divAreaResposta = document.getElementById(idAreaResposta);

				hideBtnEncerra();
			}

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
							habilitarBotaoSincronizar();
							alert("Dados Carregados!");
						}
					}
				};
			}

			function habilitarBotaoSincronizar() {
				var btn = document.getElementById('btn-sync');
				btn.style.display = 'inline';
			}

			function desabilitarBotaoSincronizar() {
				var btn = document.getElementById('btn-sync');
				btn.style.display = 'none';
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
							var answer = JSON.parse(ajax.responseText).resposta;
							showFile(answer);
						}
					}
				};
			}

			//retorna as bolas mais sorteadas,  pode ser usado o top para pegar so os 3 mehores
			function getBolasMaisSorteadas(){
				var url = "../Controller/lotoInterface.php";
				var acao = "getBolasMaisSorteadas";

				var valor = document.getElementById("qtdBolas").value;
				var top;
				if(valor > 0){
					top = valor;
				} else{
					top = 3;
				}

				ajax = new XMLHttpRequest();
				ajax.open("POST",url);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send("acao="+acao+"&top="+top);
				ajax.onload = function() {
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							// alert(ajax.responseText);//objeto com as informacoes carregadas do arquivo
							showBolasMaisSorteadas(ajax.responseText);
						}
					}
				};
			}

			//testa a jogada dos 15 numeros e retorna em quantos concursos faria 1,2,3,...15 pontos
			function testarJogada(){
				var url = "../Controller/lotoInterface.php";
				var acao = "testarJogada";
				var escolhas = new Array;

				for (let i = 0; i < 15; i++) {
					escolhas[i] = document.getElementById('jogadaTeste'+i).value;
				}

				ajax = new XMLHttpRequest();
				ajax.open("POST",url);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send("acao="+acao+"&escolhas="+JSON.stringify(escolhas));
				ajax.onload = function() {
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							// alert(ajax.responseText);//objeto com as informacoes carregadas do arquivo
							showJogadasTestadas(ajax.responseText,escolhas);
						}
					}
				};
			}

			//retorna a peorcentagem de ganhadores de cada estado, pode ser usado o top para pegar so os 3 mehores
			function getEstadosComMaisGanhadores(){
				var url = "../Controller/lotoInterface.php";
				var acao = "getEstadosComMaisGanhadores";

				var valor = document.getElementById("qtdEstados").value;
				var top;
				if(valor > 0){
					top = valor;
				} else{
					top = 3;
				}

				ajax = new XMLHttpRequest();
				ajax.open("POST",url);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send("acao="+acao+"&top="+top);
				ajax.onload = function() {
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							// alert(ajax.responseText);//objeto com as informacoes carregadas do arquivo
							showEstadosComMaisGanhadores(ajax.responseText);
						}
					}
				};
			}

			//cadastrar um novo concurso sem nada
			function criarNovoConcurso(){
				var url = "../Controller/lotoInterface.php";
				var acao = "criarNovoConcurso";

				var concurso = {
					dataSorteio:'',
					arrecadacaoTotal:'',
					estimativaPremio:'',
					valorAcumuladoEspecial:''
				};

				var data = document.getElementById("ano").value+"-"+document.getElementById("mes").value+"-"+document.getElementById("dia").value;
				concurso.dataSorteio = data;
				concurso.arrecadacaoTotal = document.getElementById("arrecadacaoTotal").value;
				concurso.estimativaPremio = document.getElementById("estimativaPremio").value;
				concurso.valorAcumuladoEspecial = document.getElementById("valorAcumuladoEspecial").value;

				// var concurso = {dataSorteio:"1997-03-31",arrecadacaoTotal:1,estimativaPremio:2,valorAcumuladoEspecial:3};

				ajax = new XMLHttpRequest();
				ajax.open("POST",url);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send("acao="+acao+"&concurso="+JSON.stringify(concurso));
				ajax.onload = function() {
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							//se status esta TRUE entao a resposta contem o id do concurso inserido
							idConcursoCriado = JSON.parse(ajax.responseText).resposta;
							// alert(idConcursoCriado);

							localStorage.setItem("concurso",JSON.stringify(concurso));
							showConcursoCadastrado(ajax.responseText);
						}
					}
				};
			}

			//adicona a jogada em um concurso, o concurso nao pode estar concluido
			function addJogadaNoConcurso(){
				var url = "../Controller/lotoInterface.php";
				var acao = "addJogadaNoConcurso";
				var jogada = {
					id:idConcursoCriado,
					nome:"John",
					cidade:"Lavras",
					uf:"MG",
					bolas:[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
				};

				var escolhas = new Array;

				for (let i = 0; i < 15; i++) {
					escolhas[i] = document.getElementById('jogadaReal'+i).value;
				}

				jogada.nome = document.getElementById('nomeJogador').value;
				jogada.cidade = document.getElementById('cidadeJogador').value;
				jogada.uf = document.getElementById('ufJogador').value;
				jogada.bolas = escolhas;

				console.log("cheguei aqui");
				var jogadas = new Array;
				jogadas.push(jogada);
				localStorage.setItem("jogadas",JSON.stringify(jogadas));

				ajax = new XMLHttpRequest();
				ajax.open("POST",url);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send("acao="+acao+"&jogada="+JSON.stringify(jogada));
				ajax.onload = function() {
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							// alert(ajax.responseText);//objeto com as informacoes carregadas do arquivo
							showAdicionouJogada(ajax.responseText);
						}
					}
				};
			}

			//retorna 15 numeros aletorios de 1 a 25
			function getNumerosDoSorteio(){
				var numeros = [];
				var num = -1;//so inicializa
				while(numeros.length < 15){
					num = Math.floor((Math.random() * 25) + 1);
					if(numeros.indexOf(num) == -1)
						numeros.push(num);
				}				
				// guardo bolas sorteadas e escondo o botao de sortear de novo
				localStorage.setItem("bolasSorteadas",JSON.stringify(numeros));
				hideBtnSorteio();
				showNumSorteio(numeros);
			}

			//encerrar
			function encerrarConcurso(){
				var url = "../Controller/lotoInterface.php";
				var acao = "encerrarConcurso";
				var concurso = {
					concurso: idConcursoCriado,
					bolas: JSON.parse(localStorage.getItem("bolasSorteadas")),
					// calculos que vem ser feios
					qntGanhadores11: 23,
					rateio11: 24,
					qntGanhadores12: 25,
					rateio12: 26,
					qntGanhadores13: 27,
					rateio13: 28,
					qntGanhadores14: 29,
					rateio14: 3,
					qntGanhadores15: 3,
					rateio15: 321,
					acumulado15: 1000,
					cidades: ["uba","lav","pudim"],//3 cidades pq tem 3 ganhadores
					ufs: ["MG","SP","PU"]
				}

				// calculo os calculos dos ganhadores
				var bolasSorteadas = JSON.parse(localStorage.getItem("bolasSorteadas"));
				var jogadas = JSON.parse(localStorage.getItem("jogadas"));


				var ganhadores15 = 0;
				var ganhadores14 = 0;
				var ganhadores13 = 0;
				var ganhadores12 = 0;
				var ganhadores11 = 0;

				var ufGanhadores = new Array;
				var cidadesGanhadores = new Array;

				for (let i = 0; i < jogadas.length; i++) {					
					var pontos = 0;
					var jogadaCompleta = jogadas[i];
					var bolasJogadas = jogadas[i].bolas;

					for(let j = 0; j < bolasJogadas.lenght; j++){
						for(let k = 0; k < 15; k++){
							if(bolasJogadas[j] == bolasSorteadas[k]){
								pontos += 1;
							}
						}						
					}

					// calculo qtd de ganhadores
					if(pontos == 15){
						ganhadores15 += 1;
						ufGanhadores.push(jogadaCompleta.uf);
						cidadesGanhadores.push(jogadaCompleta.cidade);
					} else if(pontos == 14){
						ganhadores14 += 1;
					} else if(pontos == 13){
						ganhadores13 += 1;
					} else if(pontos == 12){
						ganhadores12 += 1;
					} else if(pontos == 11){
						ganhadores11 += 1;
					}
				}

				//guardo os dados no objeto
				concurso.qntGanhadores15 = ganhadores15;
				concurso.qntGanhadores14 = ganhadores14;
				concurso.qntGanhadores13 = ganhadores13;
				concurso.qntGanhadores12 = ganhadores12;
				concurso.qntGanhadores11 = ganhadores11;

				concurso.cidades = cidadesGanhadores;
				concurso.ufs = 	ufGanhadores;

				ajax = new XMLHttpRequest();
				ajax.open("POST",url);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send("acao="+acao+"&obj="+JSON.stringify(concurso));
				ajax.onload = function() {
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							// alert(ajax.responseText);//objeto com as informacoes carregadas do arquivo
							showBtnSorteio();
							showEncerrouConcurso(ajax.responseText,concurso);
						}
					}
				};				
			}

			// funcoes para exibir respostas na tela

			//Função que mostra se encerrou concurso ou nao
			function showEncerrouConcurso(responseText,concurso) {
				var resposta = JSON.parse(responseText);
				if(responseText){
					showBtnConcurso();
					hideBtnEncerra();
					alert("Concurso encerrado com sucesso!");
					alert("Ganhadores com 15 pontos: "+concurso.qntGanhadores15
						+"\nGanhadores com 14 pontos: "+concurso.qntGanhadores14
						+"\nGanhadores com 13 pontos: "+concurso.qntGanhadores13
						+"\nGanhadores com 12 pontos: "+concurso.qntGanhadores12
						+"\nGanhadores com 11 pontos: "+concurso.qntGanhadores11);
				} else{
					alert("Houve um erro ao encerrar concurso! Por favor tente novamente");
				}
			}

			// funcao para exibir todo o arquivo na tela
			function showFile(resposta) {

			}

			function showNumSorteio(numeros) {
				cleanChilds(divAreaPergunta);
				cleanChilds(divAreaResposta);
				var titulo = document.createElement("h2");
				titulo.innerHTML = "Resultado do Sorteio:";
				divAreaResposta.appendChild(titulo);

				var areaResultado = document.createElement("div");
				var id = 'resultadoSorteio';
				areaResultado.setAttribute("id",id);
				divAreaResposta.appendChild(areaResultado);

				showBalls(numeros,id);
			}

			// funcao para montar e exibir as bolas
			function showBalls(numeros,idOndeSeraInserido){
				console.log(idOndeSeraInserido);
				var areaBolas = document.getElementById(idOndeSeraInserido);

				cleanChilds(areaBolas);
				console.log(numeros.length);
				for (let i = 0; i < numeros.length; i++) {
					inserirBola(numeros[i],areaBolas);
				}
			}


			function inserirBola(num,areaBolas) {
				var bola = document.createElement('div');
				bola.setAttribute('class','bola');
				bola.innerHTML = num;
				areaBolas.appendChild(bola);
			}

			function cleanChilds(nodeDOM) {
				while (nodeDOM.firstChild) {
    				nodeDOM.removeChild(nodeDOM.firstChild);
				}
			}

			function perguntaQtdBolas() {
				showAreaPergunta();

				cleanChilds(divAreaPergunta);

				var titulo = document.createElement("h1");
				titulo.innerHTML = "Quantidade de bolas:";
				divAreaPergunta.appendChild(titulo);

				var form = document.createElement("section");
				form.setAttribute("class","sectionPergunta")

				var input = document.createElement("input");
				input.setAttribute("type","number");
				input.setAttribute("id","qtdBolas");
				input.setAttribute("class","formInput");

				form.appendChild(input);

				var botao = document.createElement("button");
				botao.setAttribute("onclick","getBolasMaisSorteadas();");
				botao.innerHTML = "Verificar";

				form.appendChild(botao);

				divAreaPergunta.appendChild(form);
			}

			function perguntaQtdEstados() {
				showAreaPergunta();

				cleanChilds(divAreaPergunta);

				var titulo = document.createElement("h1");
				titulo.innerHTML = "Quantidade de Estados:";
				divAreaPergunta.appendChild(titulo);

				var form = document.createElement("section");
				form.setAttribute("class","sectionPergunta")

				var input = document.createElement("input");
				input.setAttribute("type","number");
				input.setAttribute("id","qtdEstados");
				input.setAttribute("class","formInput");

				form.appendChild(input);

				var botao = document.createElement("button");
				botao.setAttribute("onclick","getEstadosComMaisGanhadores();");
				botao.innerHTML = "Verificar";

				form.appendChild(botao);

				divAreaPergunta.appendChild(form);
			}

			function perguntaJogadaParaTeste() {
				showAreaPergunta();

				cleanChilds(divAreaPergunta);

				var titulo = document.createElement("h1");
				titulo.innerHTML = "Escolha 15 numeros e veja em quantos concursos você acertaria 1,2,...,15 pontos:";
				divAreaPergunta.appendChild(titulo);

				var form = document.createElement("section");
				form.setAttribute("class","sectionPergunta")

				// crio inputs para cada num
				for (let i = 0; i < 15; i++) {
					var input = document.createElement("input");
					input.setAttribute("type","number");
					input.setAttribute("id","jogadaTeste"+i);
					input.setAttribute("class","formInput");
					input.setAttribute("min","1");
					input.setAttribute("max","25");

					form.appendChild(input);
				}

				var botao = document.createElement("button");
				botao.setAttribute("onclick","testarJogada();");
				botao.innerHTML = "Testar";

				form.appendChild(botao);

				divAreaPergunta.appendChild(form);

			}

			function perguntaJogadaReal() {
				showAreaPergunta();

				cleanChilds(divAreaPergunta);

				var titulo = document.createElement("h1");
				titulo.innerHTML = "Escolha 15 numeros e tente a sorte no concurso "+idConcursoCriado+":";
				divAreaPergunta.appendChild(titulo);

				// dados do participante
				var section = document.createElement("section");
				section.setAttribute("class","sectionPergunta")

				//nome
				var inp = document.createElement("input");
				inp.setAttribute("type","text");
				inp.setAttribute("id","nomeJogador");
				inp.setAttribute("placeholder","nome");
				inp.setAttribute("class","formInput");				

				section.appendChild(inp);

				//cidade
				inp = document.createElement("input");
				inp.setAttribute("type","text");
				inp.setAttribute("id","cidadeJogador");
				inp.setAttribute("placeholder","cidade");
				inp.setAttribute("class","formInput");				

				section.appendChild(inp);
				//uf
				inp = document.createElement("input");
				inp.setAttribute("type","text");
				inp.setAttribute("id","ufJogador");
				inp.setAttribute("class","formInput");				
				inp.setAttribute("placeholder","UF");
				inp.setAttribute("maxlength","2");

				section.appendChild(inp);

				divAreaPergunta.appendChild(section);

				var form = document.createElement("section");
				form.setAttribute("class","sectionPergunta")

				// crio inputs para cada num
				for (let i = 0; i < 15; i++) {
					var input = document.createElement("input");
					input.setAttribute("type","number");
					input.setAttribute("id","jogadaReal"+i);
					input.setAttribute("class","formInput");
					input.setAttribute("min","1");
					input.setAttribute("max","25");

					form.appendChild(input);
				}

				var botao = document.createElement("button");
				botao.setAttribute("onclick","addJogadaNoConcurso();");
				botao.innerHTML = "Jogar";

				form.appendChild(botao);

				divAreaPergunta.appendChild(form);

			}

			function perguntaDadosDoConcurso() {
				showAreaPergunta();

				cleanChilds(divAreaPergunta);

				var titulo = document.createElement("h1");
				// todo here
				titulo.innerHTML = "Cadastre um concurso agora mesmo";
				divAreaPergunta.appendChild(titulo);

				var form = document.createElement("section");
				form.setAttribute("class","sectionPergunta")

				// dia
				var input = document.createElement("input");
				input.setAttribute("type","number");
				input.setAttribute("id","dia");
				input.setAttribute("class","formInput");
				input.setAttribute("min","01");
				input.setAttribute("placeholder","Dia");
				input.setAttribute("max","31");

				form.appendChild(input);
				//mes
				input = document.createElement("input");
				input.setAttribute("type","number");
				input.setAttribute("id","mes");
				input.setAttribute("class","formInput");
				input.setAttribute("min","01");
				input.setAttribute("placeholder","Mes");
				input.setAttribute("max","12");

				form.appendChild(input);

				// ano
				input = document.createElement("input");
				input.setAttribute("type","number");
				input.setAttribute("id","ano");
				input.setAttribute("class","formInput");
				input.setAttribute("min","2018");
				input.setAttribute("placeholder","Ano");

				form.appendChild(input);

				// estimativa
				input = document.createElement("input");
				input.setAttribute("type","number");
				input.setAttribute("id","estimativaPremio");
				input.setAttribute("class","formInput");
				input.setAttribute("placeholder","Estimativa");

				form.appendChild(input);

				// valor acumulador
				input = document.createElement("input");
				input.setAttribute("type","number");
				input.setAttribute("id","valorAcumuladoEspecial");
				input.setAttribute("class","formInput");
				input.setAttribute("placeholder","Valor acumulado");

				form.appendChild(input);

				// arrecadação total
				input = document.createElement("input");
				input.setAttribute("type","number");
				input.setAttribute("id","arrecadacaoTotal");
				input.setAttribute("class","formInput");
				input.setAttribute("placeholder","Arrecadação Total");

				form.appendChild(input);

				var botao = document.createElement("button");
				botao.setAttribute("onclick","criarNovoConcurso();");
				botao.innerHTML = "Criar Concurso";

				form.appendChild(botao);

				divAreaPergunta.appendChild(form);
			}

			function showAreaPergunta() {
				divAreaPergunta.style.display = 'block';
			}

			function hideAreaPergunta() {
				divAreaPergunta.style.display = 'none';
			}

			// funcao que faz pop se adicionou jogado ou nao
			function showAdicionouJogada(responseText) {
				var resposta = JSON.parse(responseText);
				if(resposta.status == true){					
					alert("Jogada adicionada com Sucesso!");
				}
				else{
					alert("Falha ao realizar jogada!");
				}
			}

			function showJogadasTestadas(responseText,escolhas) {
				hideAreaPergunta();
				console.log(responseText);

				var cabecalhos = ["Número de Pontos","Quantidade de Concursos"];
				var tabela = criaTabela(cabecalhos);

				// crio e insiro no tbody
				var tbody = document.createElement("tbody");

				var respostaObj = JSON.parse(responseText);
				var arrayRespostas = respostaObj.resposta;

				console.log(arrayRespostas.length);

				var jogo = document.createElement("div");

				for (let i = 0; i < arrayRespostas.length; i++) {
					var tr = document.createElement("tr");

					// insiro valores

					var tdBola = document.createElement("td");
					tdBola.innerHTML = i;

					var tdQtn = document.createElement("td");
					tdQtn.innerHTML = arrayRespostas[i];

					tr.appendChild(tdBola);
					tr.appendChild(tdQtn);

					// insiro no tbody
					tbody.appendChild(tr);

					// crio os números do jogo
					if(i != arrayRespostas.length-1){
						var span = document.createElement("span");
						span.innerHTML = escolhas[i]+", 	";
						jogo.appendChild(span);
					}
				}

				// insiro o body na tablea
				tabela.appendChild(tbody);

				// insiro na areaResposta
				cleanChilds(divAreaResposta);
				var titulo = document.createElement("h2");
				titulo.innerHTML = "Com o jogo abaixo, você fez essa quantidade de pontos";
				divAreaResposta.appendChild(titulo);

				divAreaResposta.appendChild(jogo);

				divAreaResposta.appendChild(tabela);
			}

			function showConcursoCadastrado(responseText) {
				var resposta = JSON.parse(responseText);
				if(resposta.status == true){
					cleanChilds(divAreaPergunta);
					cleanChilds(divAreaResposta);
					hideBtnConcurso();
					showBtnEncerra();
					alert("Concurso Criado com Sucesso!");
				}
				else{
					alert("Falha ao criar concurso!");
				}
			}

			function hideBtnConcurso(){
				var btn = document.getElementById('btn-concurso');
				btn.style.display = "none";
			}

			function showBtnConcurso(){
				var btn = document.getElementById('btn-concurso');
				btn.style.display = "inline";
			}

			function hideBtnSorteio(){
				var btn = document.getElementById('btn-sorteio');
				btn.style.display = "none";
			}

			function showBtnSorteio(){
				var btn = document.getElementById('btn-sorteio');
				btn.style.display = "inline";
			}

			function hideBtnEncerra(){
				var btn = document.getElementById('btn-encerra');
				btn.style.display = "none";
			}

			function showBtnEncerra(){
				var btn = document.getElementById('btn-encerra');
				btn.style.display = "inline";
			}

			// funcao que exibe os estados com mais ganhadores
			function showEstadosComMaisGanhadores(responseText) {
				hideAreaPergunta();
				console.log(responseText);

				var cabecalhos = ["UF","Porcentagem"];
				var tabela = criaTabela(cabecalhos);

				// crio e insiro no tbody
				var tbody = document.createElement("tbody");

				var respostaObj = JSON.parse(responseText);
				var arrayRespostas = respostaObj.resposta;

				console.log(arrayRespostas.length);


				for (let i = 0; i < arrayRespostas.length; i++) {
					var tr = document.createElement("tr");

					// insiro valores

					var tdBola = document.createElement("td");
					tdBola.innerHTML = arrayRespostas[i].uf;

					var tdQtn = document.createElement("td");
					tdQtn.innerHTML = arrayRespostas[i].qnt;

					tr.appendChild(tdBola);
					tr.appendChild(tdQtn);

					// insiro no tbody
					tbody.appendChild(tr);
				}

				// insiro o body na tablea
				tabela.appendChild(tbody);

				// insiro na areaResposta
				cleanChilds(divAreaResposta);
				var titulo = document.createElement("h2");
				titulo.innerHTML = "Estados mais sorteados";
				divAreaResposta.appendChild(titulo);
				divAreaResposta.appendChild(tabela);
			}

			// funcao que exibe as bolas mais sorteadas
			function showBolasMaisSorteadas(responseText) {
				hideAreaPergunta();
				console.log(responseText);

				var cabecalhos = ["Bola","Quantidade"];
				var tabela = criaTabela(cabecalhos);

				// crio e insiro no tbody
				var tbody = document.createElement("tbody");

				var respostaObj = JSON.parse(responseText);
				var arrayRespostas = respostaObj.resposta;

				console.log(arrayRespostas.length);


				for (let i = 0; i < arrayRespostas.length; i++) {
					var tr = document.createElement("tr");

					// insiro valores

					var tdBola = document.createElement("td");
					tdBola.innerHTML = arrayRespostas[i].valor;

					var tdQtn = document.createElement("td");
					tdQtn.innerHTML = arrayRespostas[i].qnt;

					tr.appendChild(tdBola);
					tr.appendChild(tdQtn);

					// insiro no tbody
					tbody.appendChild(tr);
				}

				// insiro o body na tablea
				tabela.appendChild(tbody);

				// insiro na areaResposta
				cleanChilds(divAreaResposta);
				var titulo = document.createElement("h2");
				titulo.innerHTML = "Bolas mais sorteadas";
				divAreaResposta.appendChild(titulo);
				divAreaResposta.appendChild(tabela);
			}

			// funcao que cria tabela e é utilizada por muitos metodos
			function criaTabela(cabecalhos) {
				var table = document.createElement("table");

				// cria theads
				var thead = document.createElement("thead");

				for (let i = 0; i < cabecalhos.length; i++) {
					var th = document.createElement("th");
					th.innerHTML = cabecalhos[i];
					thead.appendChild(th);
				}

				// insiro thedas
				table.appendChild(thead);
				return table;
			}

		</script>
	</head>
	<body>
		<header>
			<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQWPF27fmaM7hjaCoPjhp89VYfAe4ckCB5MjdTu0wgcke5kAFj4" alt="Logo">
			<h2>Analisador de Resultados</h2>
		</header>
		<nav>
			<input type="button" onclick="carregarJSON();" value="Carregar do HTML e já salvar no banco"/>
			<!-- lembrar de desabilitar esse botao ate o carregamento estar efetuado, para n bugar acontecendo os dois juntos; -->
			<input id="btn-sync" type="button" onclick="sincronizarJSON();" value="Sincronizar"/>
			<input type="button" onclick="carregarTudoDoBanco();" value="Carregar tudo do banco"/>
			<input type="button" onclick="perguntaQtdBolas();" value="Bolas mais sorteadas"/>
			<input type="button" onclick="perguntaJogadaParaTeste();" value="Testar Jogada"/>
			<input type="button" onclick="perguntaQtdEstados();" value="Estados com mais ganhadores"/>
			<input id="btn-concurso" type="button" onclick="perguntaDadosDoConcurso();" value="Criar concurso"/>
			<input type="button" onclick="perguntaJogadaReal();" value="Add jogada no concurso"/>
			<input id="btn-sorteio" type="button" onclick="getNumerosDoSorteio();" value="Realizar Sorteio"/>
			<input id="btn-encerra" type="button" onclick="encerrarConcurso();" value="Encerrar concurso"/>
		</nav>
		<section>
			<!-- area das perguntas -->
			<article id='areaPerguntas'>

			</article>
			<!-- area que sera preenchida com as respostas das requisições -->
			<article id="areaResposta">

			</article>
		</section>

		<!-- pegar dados para adicionar jogada -->

		<!-- pegar dados para adicionar concurso -->
		<footer>
			<p> Feito por Diego Sousa and Nechelley Alves &copy; 2018 - Prova 2 Web </p>
		</footer>
	</body>
</html>
