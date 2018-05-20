

		</div>


		<div id="footer">

			<div id="footertop">

				<div id="footertop-wrapper">

					<ul id="footer-links-list">
						<li>link 1</li>
						<li>link 2</li>
						<li>link 3</li>
						<li>link 4</li>

					</ul>

					<ul id="footer-sponsors-list">
						<li>sponsor 1</li>
						<li>sponsor 2</li>
						<li>sponsor 3</li>
						<li>sponsor 4</li>

					</ul>

					<ul id ="footer-contact-list">
						<li>contact 1</li>
						<li>contact 2</li>
						<li>FB TWIT</li>
					</ul>

				</div>

			</div>

			<div id="footerbottom">
				DUFFA is a not for profit organsiation
			</div>

		</div>

</body>

<?php
	// including style header for items that require php. For all other css, it's in separate files
	//It's been placed at the bottom because otherwise it will mess with php header redirects 
?>

	<style>
		#banner {
			background-image: linear-gradient(
	    			rgba(0, 0, 0, 0.31),
	     			rgba(0, 0, 0, 0.31)
				), url('<?php echo $banner_image ?>');
		}

		#banner2 {
			background-image: linear-gradient(
	    			rgba(0, 0, 0, 0.31),
	     			rgba(0, 0, 0, 0.31)
				), url('<?php echo $banner_image2 ?>');
			display: none;
		}

		.banner-active {
			background-color: #ff9933;
		}

	</style>

</html>
<!-- Omar Farooq 2018 -->
