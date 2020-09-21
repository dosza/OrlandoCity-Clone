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


	//adciona um slider na classe thumbnails
	$(".thumbnails").owlCarousel({
		loop:true, 
		margin: 10,
	//	nav:true,
		//navText: ["Anterior","Próximo"], //define label dos botões voltar e proximo
		responsive: {
			0:{ //define itens para telas menores <480
				items: 1 //define um item por slide
			},

			480:{ 
				items: 3 //define 3 itens no sliddr
			},

			1000:{ //para resolução 1000dp define 4 itens
				items: 4 
			}
		}
	});
});