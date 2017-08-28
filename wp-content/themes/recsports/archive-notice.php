
<div class="container main" role="document">
	<div class="wrapper">
		<div class="container--pagetitle">
			<div class="content <?= (isset($full) && $full) ? "content--full" : "" ?> pagetitle">
			  <h1>Active Notices</h1>
				<p>
			</div>
		</div>

		<!-- query all notices. ignore ones that are not hooked up anywhere. i guess we can show home page ones -->
		<?
		// WP_Query arguments
		$args = array (
			'post_type'    => array( 'notice' ),
			'post_status'  => array( 'publish' ),
			'order'        => 'DESC',
			'orderby'      => 'modified',
		);

		// The Query
		$noticequery = new WP_Query( $args );
		$noneprinted = true;
		?>

		<div class="content content--full">
			<? while($noticequery->have_posts()): ?>
				<? $noticequery->the_post();

				$connections = get_post_meta($post->ID, "notices", true);
				if(empty($connections) || count($connections) === 0) {
					continue;
				}

				$noneprinted = false;
				$connecttitles = array();
				foreach($connections as $connection) {
					$connecttitles[] = get_the_title($connection);
				}

				asort($connecttitles);

				$needle = array_search("Home Page", $connecttitles);
				if($needle !== false) {
					$connecttitles[$needle] = "the home page";
				}

				inject_page_notices(array(get_the_ID())); ?>
				<? array() ?>
				<p class="content--full paragraph--fineprint">Currently displaying on <?= format_array_to_sentence($connecttitles) ?>.</p>
			<? endwhile ?>

		<? if($noneprinted): ?>
			<div class="paragraph--copy content--toppad">
				<p>There are no active notices currently displaying on the site.</p>
			</div>
		<? endif; ?>

		<? wp_reset_postdata(); ?>
		</div>
	</div>
</div>
