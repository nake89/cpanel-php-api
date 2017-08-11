<?php

include 'cpanel.class.php';

$cpanel = new Cpanel("YOUR-API-KEY");
$cpanel->setServer("yourserver.com");

$cpanel->setCpanelUser("someuser");
$cpanel->setAPI("5");
$cpanel->query("PasswdStrength::get_required_strength", array("app" => "passwd" ));
$result = $cpanel->send();

var_dump($result);

?>
