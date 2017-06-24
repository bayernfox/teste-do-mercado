<?php
	require_once("conexao.php");
	
	try {
		$conexao =  getConnection();
		/* verificar se deve ser exibida a tela de cadastro ou persistir no banco de dados */
		if (isset($_REQUEST['action']) && $_REQUEST['action'] == "save") {
			$sql = "INSERT INTO tb_mercadoria (id_tipo_mercadoria, nm_mercadoria, quantidade, preco) VALUES (?, ?, ?, ?);";
			$stmt = $conexao->prepare($sql);
			/* preencher os parâmetros da query */
			$stmt->bindParam(1, $_POST['tipoMercadoria']);
			$stmt->bindParam(2, $_POST['nomeMercadoria']);
			$stmt->bindParam(3, $_POST['quantidade']);
			/* converter a formatação do campo valor para o padrão americano */
			$valor = str_replace(".", "", $_POST['valor']);
			$valor = str_replace(",", ".", $valor);
			$stmt->bindParam(4, $valor);
			/* inserir a nova mercadoria no banco de dados */
			$stmt->execute();
			/* redirecionar para a página principal */
			header("location:index.php");
		} else {
			/* efetuar a busca de tipos de mercadorias para preencher o campo tipoMercadoria */
			$rs = $conexao->prepare("SELECT * FROM tb_tipo_mercadoria");
			$rs->execute();
		}		
	}  catch (Exception $e) {
		echo "Error: " . $e->getMessage();
	} finally {
		closeConnection($conexao);
	}
	
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<title>cadastrar-novo-anúncio</title>
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	
	<script src="js/jquery.min.js"></script>
	<script src="js/jquery.maskMoney.js"></script>	
	
	<script type="text/javascript">
		
		$(document).ready(function(){
			
			$("#valor").maskMoney();
			
			function invalidarCampo(campo){
				campo.css({"border" : "1px solid #F00", "padding": "2px"});
			}
			
			function validarCampo(campo) {
				campo.css({"border" : ""});
			}
			
			$("#btnCadastrar").click(function(){
				var cont = 0;				
				/* verificar se o campo tipoMercadoria está em branco */
				$("#tipoMercadoria").each(function(){
					if($(this).val() === ""){
						invalidarCampo($(this));
						cont++;
					} else {
						validarCampo($(this));
					}
				});				
				/* verificar inputs em branco */
				$("#cadastro input").each(function(){
					if($(this).val() === ""){
						invalidarCampo($(this));
						cont++;
					}else{
						validarCampo($(this));
					}
				});				
				/* verificar se o campo quantide possui valores que não estão entre 1 e 99 */
				$("#quantidade").each(function(){
					if($(this).val() < 1 || $(this).val() > 99) {
						invalidarCampo($(this));
						cont++;
					}
				});				
				/* caso todos os campos estejam corretamente preechidos, envia o formulário */
				if (cont === 0) {
					$("#cadastro").submit();
				}
			});

		});		
		
	</script>	
</head>
<body>
<div class="container">
	<h1>cadastrar-novo-anúncio</h1>
	
	<form method="post" action="inserirAnuncio.php?action=save" name="cadastro" id="cadastro">
		
		<div class="form-group">
			<label for="tipoMercadoria">Tipo de Mercadoria</label>
			<select class="form-control" name="tipoMercadoria" id="tipoMercadoria">
				<option value=""></option>
				<?php
					while ($registro = $rs->fetch(PDO::FETCH_OBJ)) {
						echo '<option value="' . $registro->id_tipo_mercadoria . '">' . $registro->nm_tipo_mercadoria . '</option>';
					}
				?>
			</select>
		</div>
		
		<div class="form-group">
			<label for="nomeMercadoria">Nome da Mercadoria</label>
			<input type="text" class="form-control" name="nomeMercadoria" id="nomeMercadoria" maxlength="64" />
		</div>
		
		<div class="form-group">
			<label for="quantidade">Quantidade (1-99)</label>
			<input type="number" class="form-control" name="quantidade" id="quantidade" min="1" max="99"/>
		</div>
		
		<div class="form-group">
			<label for="valor">Valor Unitário (R$)</label>
			<input type="text" class="form-control" name="valor" id="valor" data-thousands="." data-decimal=","/>
		</div>
		
		<button type="button" class="btn btn-primary" name="btnCadastrar" id="btnCadastrar">Cadastrar</button>
		<a href="index.php"><button type="button" class="btn btn-primary" name="btnCancelar" id="btnCancelar">Cancelar</button></a>

	</form>
</div><!-- /container -->
</body>
</html>