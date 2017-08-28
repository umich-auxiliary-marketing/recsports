<footer class="container footer">
  <div class="wrapper">
		<div class="footer__identification">
			<div class="footer__logo">
		    <a target="_blank" href="https://studentlife.umich.edu">University of Michigan Student Life</a>
			</div>
			<div class="footer__colophon">
				<h3 class="footer__organization">Recreational Sports</h3>
				<p class="footer__colophonline"><a title="Open in Google Maps" href="https://www.google.com/maps/place/Central+Campus+Recreation+Building,+401+Washtenaw+Ave,+Ann+Arbor,+MI+48109/data=!4m2!3m1!1s0x883cae430d21a9d3:0xe6578ff214d128a5?sa=X&ved=0ahUKEwia34GNvfvOAhXKpYMKHfabB9wQ8gEIGzAA" class="footer__colophonlink footer__colophonlink--address" target="_blank">401 Washtenaw Ave<br>Ann Arbor, MI 48109&#8209;2208</a></p>
				<p class="footer__colophonline"><a title="Call (734) 763-3084" class="footer__colophonlink footer__colophonlink--phone" href="tel:+17347633084">(734) 763&#8209;3084</a></p>
				<p class="footer__colophonline"><a title="Email recsports@umich.edu" class="footer__colophonlink footer__colophonlink--email" href="mailto:recsports@umich.edu">recsports@umich.edu</a></p>
			</div>
		</div>
		<?php wp_nav_menu(array(
			'theme_location' => 'footer_nav',
			'menu_class' => 'footer__links'));
		?>
  </div>
</footer>
