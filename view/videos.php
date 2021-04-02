<?php 
	include_once('header.php');
?>

<link rel="stylesheet" type="text/css" href="lib/node_modules/plyr/dist/plyr.css">
<section> <!-- corpo da pagina -->

	<div id="call-to-action">
		<div class="container">
			<div class="row text-center">
				<h2>Videos</h2>
				<hr>
			</div>


			<div class="row">
				
				<div class="videoplayer">
					<video id="player" src="mp4/highlights.mp4" autoplay playsinline controls data-poster="img/highlights.jpg">
						<track kind="captions"  label="Português (Brasil)" src="vtt/legendas.vtt" srclang="pt-br" default/> 
					</video> <!-- tag do html para play de vídeos. controls: exibe controles do player. autoplay: toca o vídeo automaticamente. poster: permite voce substituir previa do vídeo por uma imagem ou frame.-->

					<input type="range" min="0" max="1" step="0.01" value="1" id="volume">

					<button type="button" id="btn-play-pause" class="btn btn-success">PLAY</button>
				</div>

			</div>

			<div id="news" class="container" >

				<div class="row text-center">
					<h2 class="lastest-videos">Latest News</h2>
					<hr>
				</div>

				<div class="row thumbnails owl-carousel owl-theme">

					<div class="item" data-video="highlights">

						<div class="item-inner">

							<img src="img/highlights.jpg" alt="highlights">
							<h3>highlights</h3>

						</div>
					</div>

					<div class="item" data-video="Orlando_City_Foundation_2015">

						<div class="item-inner">

							<img src="img/Orlando_City_Foundation_2015.jpg" alt="Orlando_City_Foundation_2015">
							<h3>Orlando City Foundation 2015</h3>
				
						</div>

					</div>

					<div class="item" data-video="highlights">

						<div class="item-inner">

							<img src="img/highlights.jpg" alt="highlights">
							<h3>highlights</h3>

						</div>
					</div>

					<div class="item" data-video="Orlando_City_Foundation_2015">

						<div class="item-inner">

							<img src="img/Orlando_City_Foundation_2015.jpg" alt="Orlando_City_Foundation_2015">
							<h3>Orlando City Foundation 2015</h3>
				
						</div>

					</div>


				</div>

			</div>
		</div>

	</div>


</section>

<?php include_once('footer.php');
?>


		<script type="text/javascript" src="lib/node_modules/plyr/dist/plyr.js"></script>

		<script>


			$(function(){

				//adiciona um listerner para o evento de clique 
				$(".thumbnails .item").on("click",function(){
					

					$("video").attr({
						 "src":"mp4/"+$(this).data('video')+".mp4",
						 "data-poster":"img/"+$(this).data('video')+".jpg"
					});

				


				});


				//adiciona um listerner onChange no input
				$("#volume").on("mousemove",function(){
						$("video")[0].volume = parseFloat($(this).val());  //obtem da tag video o atributo volume e define seu valor de acordo com input
				});


				$("#btn-play-pause").on("click", function(){

					var video = $("video")[0];

					if ( $(this).hasClass("btn-success")){
						$(this).text("PLAY");	
						video.play();
					}else{
						$(this).text("PAUSE");
						video.pause();
					}

					$(this).toggleClass("btn-success btn-danger");
				});

				const player = new Plyr("#player");

			});
	</script>
	
</html>