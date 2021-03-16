$(document).ready(function (){

	var carouselProps = {
		items:4
	};


	//evento para abrir menu ao clicar em btn-bars
	$("#btn-bars").on( 'click', function(){
		$('header').toggleClass('open-menu');
		$("#input-search-mobile").focus();
	});


	$("#btn-search").on('click', function(){
		$('header').toggleClass('open-search')

	});

	//evento para fechar menu
	$(".btn-close, #menu-mobile-mask").on('click', function(){
		$('header').removeClass('open-menu')
	});



		//captura evento passar mouse no logotipo 
	$("#logotipo").on("mouseover", function (){
		
		$("#banner h1").addClass("efeito");
		
		}).on("mouseout",function(){ //captura evento do mouse sair
			$("#banner h1").removeClass("efeito"); 
		});

	$("#input-search").on("focus", function (){ //evento input-search  ganha o foco

		$("li.search").addClass("ativo");

	}).on("blur",function(){ //evento input-search  perde o foco
	
		$("li.search").removeClass("ativo");

	});

	var thumbnails = $('.thumbnails');

	thumbnails.owlCarousel(carouselProps);

	$('.thumbnails').removeClass('owl-theme')

	var owl = $('.thumbnails').data('owlCarousel');
	$('#btn-news-prev').on('click',function(){
		owl.prev();
	})

	$('#btn-news-next').on('click',function(){
		owl.next();
	});


	//adiciona ação para botão voltar
	$("#page-up").on('click',function(event){

		$("html,body").animate({ scrollTop:0 },1000);
		event.preventDefault();	
	});
});
