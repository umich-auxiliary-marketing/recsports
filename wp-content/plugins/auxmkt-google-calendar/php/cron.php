<?php
include_once("cachebuilder.php");

$calendars = [
	"groupx" => "umich.edu_03trdc696kl0cjkdhpf69ui38g@group.calendar.google.com",
	"ccrb" => "umich.edu_3f5h0rs9r3422rgsefdkeueoqs@group.calendar.google.com",
	"bellpool" => "umich.edu_1ou2dak29975ftdj9pl0i3nkg4@group.calendar.google.com",
	"imsb" => "umich.edu_i2ie64trqplb1p9k1m5pohb86c@group.calendar.google.com",
	"ncrb" => "umich.edu_03ksu9naojgm9q3ru5e5j35gf4@group.calendar.google.com",
	"ncrbpool" => "umich.edu_7boi038dp0ansqp836797fqbbc@group.calendar.google.com",
	"coliseum" => "umich.edu_8g3vk8gqr3nhu2c1tgnbno3hh0@group.calendar.google.com",
	"rentalcenter" => "umich.edu_j38o592smt3ks0pua865h42cl8@group.calendar.google.com",
	"oatrips" => "umich.edu_rajei872o8vjedc7birj2mdg6o@group.calendar.google.com",
	"elbel" => "umich.edu_g24lji49dvcjibjnipp9mvv234@group.calendar.google.com",
	"bursley" => "umich.edu_pe8anju5n1g4ia2ioja8el7o7o@group.calendar.google.com",
	"pierpont" => "umich.edu_c1jubbmfjt4jqvvd3k00omoin4@group.calendar.google.com"
];

foreach($calendars as $calendar_type => $calendar) {
	AuxMkt_Google_Calendar_Cacher::build_calendar_cache($calendar, $calendar_type);
}

$connection = ssh2_connect('dev-recsports.studentlife.umich.edu', 22);
ssh2_auth_password($connection, 'jmatula', 'PASSWORDHERE');

foreach($calendars as $calendar) {
	ssh2_scp_send(
		$connection,
		'/Users/Graphics/Sites/auxmkt-google-calendar/output/' . $calendar . '.json',
		'../../www/html/recsports/wp-content/plugins/auxmkt-google-calendar/output/' . $calendar . '.json',
		0644
	);
}

?>
