<?php

require('lib/gantti.php'); 

date_default_timezone_set('UTC');
setlocale(LC_ALL, 'en_US');

$data = array();

$data[] = array(
  'label' => 'Project 1',
  'start' => '2012-04-20', 
  'end'   => '2012-05-12'
);

$data[] = array(
  'label' => 'Project 2',
  'start' => '2012-04-22', 
  'end'   => '2012-05-22', 
  'class' => 'important',
);

$data[] = array(
  'label' => 'Project 3',
  'start' => '2012-05-25', 
  'end'   => '2012-06-20'
  'class' => 'urgent',
);

$gantti = new Gantti($data, array(
  'title'      => 'Demo',
  'cellwidth'  => 25,
  'cellheight' => 35
));

echo $gantti;

?>