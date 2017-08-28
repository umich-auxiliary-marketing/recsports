<?php use Roots\Sage\Titles; ?>

<div class="container--pagetitle">
	<div class="content <?= (isset($full) && $full) ? "content--full" : "" ?> pagetitle">
	  <h1><?= Titles\title(); ?></h1>
	</div>
</div>
