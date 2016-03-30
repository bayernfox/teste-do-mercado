var app = angular.module('myApp', []);

app.controller('myCtrl', function($scope) {
	
	$scope.codigoNegociacao = 1;
	$scope.itens = [];
	
    $scope.limparCampos = function(){
		$scope.item.codigoNegociacao = $scope.item.codigoMercadoria = $scope.item.tipoMercadoria = $scope.item.nome = $scope.item.quantidade = $scope.item.preco = $scope.item.preco = $scope.item.tipoNegocio = '';
	}
	
	$scope.adicionarItem = function(){
		$scope.itens.push({
			codigoNegociacao: $scope.codigoNegociacao,
			codigoMercadoria: $scope.item.codigoMercadoria,
			tipoMercadoria: $scope.item.tipoMercadoria,
			nome: $scope.item.nome,
			quantidade: $scope.item.quantidade,
			preco: $scope.item.preco,
			valorTotal: ($scope.item.preco * $scope.item.quantidade),
			tipoNegocio: $scope.item.tipoNegocio
		});
		$scope.codigoNegociacao++
	};
	
	$scope.excluirItem = function(index){
		$scope.itens.splice(index, 1)
	};

});