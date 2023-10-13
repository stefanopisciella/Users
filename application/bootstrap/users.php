<?php
// require 'vendor/autoload.php';
require 'vendor/autoload.php';


// f3
$GLOBALS['f3'] = \Base::instance(); //we use a superglobsl because we don't  have f3 instance inside controller and models classes

// View
$GLOBALS['view']= new View();

// DB
$f3->set('DB', new DB\SQL(
    'mysql:host=localhost;port=3306;dbname=users', "root"
));

// CONSTANTS
$hostname = $f3->get('SCHEME') . "://" . $f3->get('HOST') . '/';
$GLOBALS['url_prefix'] = $hostname . 'Users/public/';
$GLOBALS['max_num_of_users_per_page'] = 5; // CHECK

$f3->set('AUTOLOAD','application/;');
$f3->set('ESCAPE',FALSE);

// ROUTES
$f3->route('GET /user/update/@id','controllers\User->update'); // for showing the UPDATE user modal

$f3->route('POST /user/save/@id','controllers\user->save'); // for UPDATING a user
// $f3->route('GET /user/view','controllers\user->view'); // for showing the CREATE user form
$f3->route('POST /user/save','controllers\user->save'); // for CREATING a new user
$f3->route('GET /user/remove/@id','controllers\User->remove');
$f3->route('GET /user','controllers\User->index');




$f3->run(); // it matches routes against incoming URI


