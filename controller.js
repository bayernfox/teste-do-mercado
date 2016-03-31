var app = angular.module('myApp', []);

app.controller('myCtrl', function($scope){
	
	/* lista de negociacoes */
	$scope.itens = [];
	/* contador do código da negociacao */
	$scope.codigoNegociacao = 1;	
	/* auxiliar do indice da negociacao para excluir */
	$scope.index = "";
	
	/* limpa todos os campos do formulário */
    $scope.limparCampos = function(){
		$scope.item.codigoNegociacao = $scope.item.codigoMercadoria = $scope.item.tipoMercadoria = $scope.item.nome = $scope.item.quantidade = $scope.item.preco = $scope.item.preco = $scope.item.tipoNegocio = '';
	}
	
	/* adiciona uma nova negociacao a lista */
	$scope.adicionarItem = function(){
		/* retirar máscara do campo preco*/
		$scope.valor = $scope.item.preco.replace('.','');
		$scope.valor = $scope.item.preco.replace(',','.');
		/* o valor total com base na quantidade x valor unitario */
		$scope.valorTotal = ($scope.valor * $scope.item.quantidade);
		
		$scope.itens.push({
			codigoNegociacao: $scope.codigoNegociacao,
			codigoMercadoria: $scope.item.codigoMercadoria,
			tipoMercadoria: $scope.item.tipoMercadoria,
			nome: $scope.item.nome,
			quantidade: $scope.item.quantidade,
			preco: $scope.item.preco,			
			valorTotal: $scope.valorTotal,
			tipoNegocio: $scope.item.tipoNegocio
		});
		/* incrementa o contador de codigos */
		$scope.codigoNegociacao++
	};
	
	/* define qual o indice do item a ser excluido */
	$scope.definirIndex = function(index){
		$scope.index = index;
		$('#modalExcluir').modal('toggle');
	}
	
	/* exclui o item com base no indice definido anteriormente */
	$scope.excluirItem = function(){
		$scope.itens.splice($scope.index, 1);
	};
	
	/* valida se todos os campos do formulario estao preenchidos */
	$scope.validar = function(){
		var cont = 0;
		/* caso nao esteja preenchido coloca o campo em destaque com uma borda vermelha */
		$("#frmNegociacao input").each(function(){
			if($(this).val() == ""){
				$(this).css({"border" : "1px solid #F00", "padding": "2px"});
				/* caso algum campo nao esteja preenchido, incrementa o contador */
				cont++;
			}else{
				/* caso o campo tenha sido preenchido, retira a borda */
				$(this).css({"border" : ""});
			}
		});
		/* realizado o mesmo processo acima para o Tipo do Negocio, pois ele nao e um select */
		$("#slcTipoNegocio").each(function(){
			if($(this).val() != "Compra" && $(this).val() != "Venda"){
				$(this).css({"border" : "1px solid #F00", "padding": "2px"});
				cont++;
			}else{
				$(this).css({"border" : ""});
			}
		});
		
		/* verifica se o contador esta vazio, ou seja, todos os campos foram preenchidos */
		if(cont == 0){
			/* se todos os campos foram preenchidos, adiciona a negociacao a lista e limpa todos os campos */
			$scope.adicionarItem();
			$scope.limparCampos();
		} else{
			/* caso contrario, exibe uma modal/janela que tem a funcao de um alert */
			$('#modalValidarCampos').modal('toggle');
		}
	};

});
