<?
@session_start();

include 'app/app.php';

// Load Controllers
include 'app/Controllers/HomeController.php';
include 'app/Controllers/UserController.php';
include 'app/Controllers/GuestController.php';
include 'app/Controllers/ItemController.php';

// Do routing
if (router()->getType() == 'controller') {
	try {
		router()->callObject('App\Controllers\%sController', true);
	} catch (Exception $e) {
		echo $e;
		echo blade()->run('404');
	}
}
?>