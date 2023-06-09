<?php
	/**
	 * Populate the slideshow
	 */
	function populateSlideshowView() {
		?>
		<div id="slideshow">
			<div id="slideshow-carousel">
			</div>
			<div class="slideshow-arrow left" onclick="moveSlideshowLeft()"></div>
			<div class="slideshow-arrow right" onclick="moveSlideshowRight()"></div>
			<div id="slider">
			</div>
			<!--
			<div id="view">
				<a href="#offers">View Current Offers!</a>
			</div>
			-->
		</div>
		<?php
	}

	/**
	 * Fill slideshow with images
	 */
	function fetchSlideshowImagesScript() {
		?>
			<script>
				const slideshow = document.getElementById("slideshow");
				const carousel = document.getElementById("slideshow-carousel");

				const size = 10;
				let index = 0;

				// Generate slider
				const slider = document.getElementById("slider");
				for (let i = 0; i < size; i++) {
					const circle = document.createElement("div");
					circle.setAttribute("tabindex", 0);
					circle.setAttribute("onclick", `moveSlideshowToIndex(${i})`);
					circle.classList.add("circle");
					slider.appendChild(circle);
				}
				const circles = document.querySelectorAll("#slider .circle");
				
				// Generate slideshow images
				image_srcs = [];
				$(document).ready(function(){
					$.ajax({
						type: "POST",
						url: "BackEnd/Controllers/recipe-controller.php",
						data: {action: "Fetch_Slideshow_Recepies"},
						success:function(data){
							data = JSON.parse(data);
							for (let i = 0; i < size; i++) {
								// create actual images
								
								const img = document.createElement("div");
								image_srcs[i] = data[i].Image;
								img.classList.add("slideshow-image");
								img.style.backgroundImage = `url(${image_srcs[i]})`;
								img.setAttribute("recipe_id", data[i].Recipe_ID);
								img.style.right = `-${i*100}%`;
								const recipe = document.createElement("a");
								recipe.classList.add("slide-show-image-name");
								// recipe.setAttribute("href", "");
								$(".slide-show-image-name").hover(function() {
									$(this).css('cursor','pointer');
								}, function() {
									$(this).css('cursor','auto');
								});
								recipe.innerText = data[i].Recipe_Name;
								img.appendChild(recipe);
								carousel.appendChild(img);
							}
						}
					});
				});

				$(document).on("click", ".slide-show-image-name", function(e){
					let img_id = $(this).parent().attr("recipe_id");
					window.location.href = "Pages/browse-recipes.php?recipe_id=" + img_id;
				});
				
				
				const images = document.getElementById("slideshow-carousel").children;
			</script>
		<?php
	}

	/**
	 * Move slideshow
	 */
	function moveSlideshowScript() {
		?>
		<script>
			circles[0].classList.add("fill");
			const updateSpeed = 3500;
			let moveSlideshowInterval = setInterval(moveSlideshowRight, updateSpeed);
			function moveSlideshowLeft() {
				circles[index].classList.remove("fill");
				index -= 1;
				if (index < 0) index = size-1;
				moveSlideshow();
			}
			function moveSlideshowRight() {
				circles[index].classList.remove("fill");
				index = (index+1)%size;
				moveSlideshow();
			}
			function moveSlideshowToIndex(current_index) {
				circles[index].classList.remove("fill");
				index = current_index;
				moveSlideshow();
			}
			function moveSlideshow() {
				clearInterval(moveSlideshowInterval);
				circles[index].classList.add("fill");
				for (let i = 0; i < size; i++) {
					const offset = index - i;
					images[i].style.right = `${offset*100}%`;
				}
				moveSlideshowInterval = setInterval(moveSlideshowRight, updateSpeed);
			}
		</script>
		<?php
	}

	/**
	 * Populate offers view
	 */
	function populateOffersView() {
		?>
			<script src="Libraries/jquery.min.js"> </script>
			

			<div id="offers">
				<div class="offer">
					<div class="overlay">
						<a href="">Night Changes</a>
					</div>
					<span class="sticker">-10%</span>
				</div>
				<div class="offer">
					<div class="overlay">
						<a href="">Derniere Danse</a>
					</div>
					<span class="sticker">-20%</span>
				</div>
				<div class="offer">
					<div class="overlay">
						<a href="">Set Fire To The Rain</a>
					</div>
					<span class="sticker">HOT</span>
				</div>
			</div>

			
			<script> 
			

			$(document).ready(function(){
				$.ajax({
					type: "POST",
					url: "BackEnd/Controllers/ingredient-controller.php",
					data: {action: "Fetch_Offers"},
					success:function(data){
						console.log(data);
						data = JSON.parse(data);
						for(var i in data){
							data[i].Status = parseInt((1-data[i].Status)*100);
						}
						
						$("#main #offers .offer:nth-child(1)").css("background-image",'url(' + data[0].Image +')');
						$("#main #offers .offer:nth-child(1) a").text(data[0].Ingredient_Name);
						$("#main #offers .offer:nth-child(1) .sticker").text(data[0].Status + "%");
						$("#main #offers .offer:nth-child(2)").css("background-image",'url(' + data[1].Image +')');
						$("#main #offers .offer:nth-child(2) a").text(data[1].Ingredient_Name);
						$("#main #offers .offer:nth-child(2) .sticker").text(data[1].Status + "%");
						$("#main #offers .offer:nth-child(3)").css("background-image",' url( '+ data[2].Image +')');
						$("#main #offers .offer:nth-child(3) a").text(data[2].Ingredient_Name);
						$("#main #offers .offer:nth-child(3) .sticker").text(data[2].Status + "%");
						
					}
				});
			});
		
			</script>
		<?php
	}

?>
