<form role="search" method="get" class="searchform content content--full" action="<?php echo home_url( '/' ); ?>">
	<label for="searchform__field" class="ada--visually-hidden-text">Search for:</label>
	<input autofocus type="search" id="searchform__field" class="searchform__field" placeholder="Search…" value="<?php echo get_search_query() ?>" name="s" />
	<input type="submit" class="searchform__submit" value="Search" />
</form>
