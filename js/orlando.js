//orlando.css


$(document).ready(function (){
		//captura evento passar mouse no logotipo 
	$("#logotipo").on("mouseover", function (){
		
		$("#banner h1").addClass("efeito");
		
		}).on("mouseout",function(){ //captura evento do mouse sair
		
			$("#banner h1").removeClass("efeito"); 
		}) ;

	$("#input-search").on("focus", function (){ //evento input-search  ganha o foco

		$("li.search").addClass("ativo");

	}).on("blur",function(){ //evento input-search  perde o foco
	
		$("li.search").removeClass("ativo");

	});
});