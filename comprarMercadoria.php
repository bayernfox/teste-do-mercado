<?php
    require_once("conexao.php");
	
    try {
		$conexao =  getConnection();
		/* efetuando a busca de tipos de mercadorias para preencher o campo tipoMercadoria */
		$sql = "UPDATE tb_mercadoria SET quantidade = ? WHERE id_mercadoria = ?";
        $stmt = $conexao->prepare($sql);
        /* preencher os parâmetros da query */
        $quantidade = $_POST['quantidadeAtual'] - $_POST['quantidadeCompra'];
        $stmt->bindParam(1, $quantidade);
        $stmt->bindParam(2, $_POST['mercadoria']);
		$stmt->execute();
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
</head>
<body>
    <div class="container">
        <h1>Compra realizada com sucesso!</h1>
        <a href="index.php"><button type="button" class="btn btn-primary" name="btnVoltar" id="btnVoltar">Continuar comprando</button></a>
    </div>
</body>
</html>