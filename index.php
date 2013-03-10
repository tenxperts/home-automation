<?php
require 'vendor/autoload.php';
require 'lib/redbean/rb.php';

$app = new \Slim\Slim();

R::setup('mysql:host=localhost;dbname=home_automation','home_automation','home_automation');
R::freeze(true);

$app->get('/home', function() {
    echo "Welcome - to home automation";
});


$app->get('/devices', function() use($app) {
    $devices = R::find('devices');
    $app->response()->header("Content-Type", "application/json");
    echo json_encode(R::exportAll($devices));
});

$app->get('/devices/:id', function($id) use($app) {
    $device = R::findOne('devices', 'id=?', array($id));
    if ($device) {
      $app->response()->header("Content-Type", "application/json");
      echo json_encode(R::exportAll($device));
    } else {
      $app->response()->status(404);
    }
   
});

$app->post('/devices', function() use($app) {
  $input = json_decode($app->request()->getBody());
  $new_device = R::dispense('devices');
  $new_device->name = $input->name;
  R::store($new_device);
  $app->response()->status(201);
  $app->response()->header("Content-Type", "application/json");
  $app->response()->header("Location", $app->request()->getUrl().$app->request()->getPath().'/'.$new_device->id);
  echo json_encode(R::exportAll($new_device));
});

$app->put('/devices/:id', function($id) use($app) {
  $input = json_decode($app->request()->getBody());
  $device = R::findOne('devices', 'id=?', array($id));
  if ($device) {
    $device->name = $input->name;
    R::store($device);
    $app->response()->status(204);
  } else {
    $app->response()->status(404);
  }
});

$app->delete('/devices/:id', function($id) use($app) {
  $input = json_decode($app->request()->getBody());
  $device = R::findOne('devices', 'id=?', array($id));
  if ($device) {
    R::trash($device);
    $app->response()->status(204);
  } else {
    $app->response()->status(404);
  }
});



$app->run();