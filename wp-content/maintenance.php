<?php
$protocol = $_SERVER["SERVER_PROTOCOL"];
if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol )
    $protocol = 'HTTP/1.0';
header( "$protocol 503 Service Unavailable", true, 503 );
header( 'Content-Type: text/html; charset=utf-8' );
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Maintenance &bull; Recreational Sports</title>
	<style>
	body, html {
		box-sizing: border-box;
		margin: 0;
		padding: 1.5rem;
		width: 100%;
		height: 100%;
	}
	
	@media screen and (max-width: 400px) {
		body, html {
			font-size: 13px;
			padding: .75rem;
		}
	}
	
	.message {
		width: 100%;
		max-width: 525px;
		box-sizing: border-box;
		padding: 1.5rem;
		margin: 0 auto;
		border: 1px solid #ddd; 
		border-radius: 6px;
		position: relative;
		top: 50%;
		transform: translateY(-50%);
	}
	p {
		font-size: 1.25rem;
		color: #777;
		font-weight: normal;
		font-family: sans-serif;
		text-align: center;
	}
	p.secondary {
		font-size: .875rem;
		color: #9a9a9a;
	}
	</style>
</head>
<body>
	<div class="message">
    <p>The Rec Sports website is undergoing scheduled maintenance. Please check back in a few minutes.</p>
    <p class="secondary">For immediate help, call (734)&nbsp;763&#8209;3084 or email recsports@umich.edu.</p>
</body>
</html>
<?php die(); ?>