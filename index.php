<?php
	require_once("conexao.php");
	
	try {
		$conexao =  getConnection();
		/* efetuando a busca de tipos de mercadorias para preencher o campo tipoMercadoria */
		$buscaTipoMercadoria = $conexao->prepare("SELECT * FROM tb_tipo_mercadoria");
		$buscaTipoMercadoria->execute();
		/* construindo a consulta das mercadorias*/
		$queryMercadorias = "SELECT
					id_mercadoria,
					nm_tipo_mercadoria,
					nm_mercadoria,
					quantidade,
					preco
				FROM tb_mercadoria m
				INNER JOIN tb_tipo_mercadoria t ON m.id_tipo_mercadoria = t.id_tipo_mercadoria
				WHERE quantidade > 0
				";
		/* inserindo o filtro para tipoMercadoria */
		if (isset($_GET['tipoMercadoria']) && $_GET['tipoMercadoria'] != "") {
			$queryMercadorias .= " AND m.id_tipo_mercadoria = " . $_GET['tipoMercadoria'];
		}
		/* inserindo filtro para nomeMercadoria */
		if (isset($_GET['nomeMercadoria']) && $_GET['nomeMercadoria'] != "") {
			$queryMercadorias .= " AND nm_mercadoria LIKE '%" . $_GET['nomeMercadoria'] . "%'";
		}
		/* efetuando a busca de mercadorias para preencher a tabela */
		$buscaMercadoria = $conexao->prepare($queryMercadorias);
		$buscaMercadoria->execute();
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
	<title>teste-do-mercado</title>
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.maskMoney.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('span').click(function(){
				$('#mercadoria').val($(this).attr('id_mercadoria'));
				$('#quantidadeAtual').val($(this).attr('quantidade'));
				$('#estoque').text($(this).attr('quantidade'));
				$('#modalComprar').modal('toggle');
			});			
			$('#btnComprar').click(function(){
				if ($("#quantidadeCompra").val() < 1 || $("#quantidadeCompra").val() > $('#quantidadeAtual').val()) {
					$("#quantidadeCompra").css({"border" : "1px solid #F00", "padding": "2px"});
				} else {					
					$('#compra').submit();
				}
			});
		});
	</script>	
</head>
<body>
	
<div class="container">

	<h1>teste-do-mercado</h1>

	<div class="panel panel-default">
		<div class="panel-body">
			<form method="get" action="index.php" name="filter" id="filter">
				
				<div class="form-group">
					<label for="tipoMercadoria">Tipo de Mercadoria</label>
					<select class="form-control" name="tipoMercadoria" id="tipoMercadoria">
						<option value=""></option>
						<?php
							while ($registroTipo = $buscaTipoMercadoria->fetch(PDO::FETCH_OBJ)) {
								echo '<option value="' . $registroTipo->id_tipo_mercadoria . '">' . $registroTipo->nm_tipo_mercadoria . '</option>';
							}
						?>
					</select>
				</div>
				
				<div class="form-group">
					<label for="nomeMercadoria">Nome da Mercadoria</label>
					<input type="text" class="form-control" name="nomeMercadoria" id="nomeMercadoria" maxlength="64" />
				</div>
				
				<button type="submit" class="btn btn-primary" name="filtrar" id="filtrar">Filtrar</button>
				<a href="inserirAnuncio.php"><button type="button" class="btn btn-primary" name="inserirAnuncio" id="inserirAnuncio">Inserir Anúncio</button></a>
			</form>
		</div><!-- /panel-body -->
	</div><!-- /panel -->

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Tipo da Mercadoria</th>
					<th>Nome da Mercadoria</th>
					<th>Quantidade</th>
					<th>Valor Unitário (R$)</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php
				while ($registroMercadoria = $buscaMercadoria->fetch(PDO::FETCH_OBJ)) {
					echo '<tr>';
						echo '<td>' . $registroMercadoria->nm_tipo_mercadoria . '</td>';
						echo '<td>' . $registroMercadoria->nm_mercadoria . '</td>';
						echo '<td>' . $registroMercadoria->quantidade . '</td>';
						$valor = str_replace(".", ",", $registroMercadoria->preco);
						echo '<td>' . $valor . '</td>';
						/* ícone de um carrinho de compras; guarda o id_mercadoria e a quantidade em estoque; quando clicado aciona uma janela para solicitar a quantidade a ser comprada; */
						echo '<td><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true" id_mercadoria="'. $registroMercadoria->id_mercadoria .'" quantidade="' . $registroMercadoria->quantidade . '"></span></td>';
					echo '</tr>';
				}
			?>
			</tbody>
		</table>
	</div><!-- /table-responsive -->
</div><!-- /container -->

<!-- janela para efetuar a compra de uma mercadoria -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalComprar" id="modalComprar">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			 <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Quantas unidades?</h4>
			</div>
			<div class="modal-body">
				<form method="post" action="comprarMercadoria.php" name="compra" id="compra">
					Quantidade disponível: <span id="estoque"></span>.
					<input type="hidden" name="mercadoria" id="mercadoria"/>
					<input type="hidden" name="quantidadeAtual" id="quantidadeAtual" />
					<input type="number" class="form-control" name="quantidadeCompra" id="quantidadeCompra" min="1" max="99" value="1"/>					
				</form>
			</div>
			<div class="modal-footer">						
				<button type="button" class="btn btn-primary" id="btnComprar">Comprar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>
