<?
include __DIR__."/../vendor/autoload.php";

// Load libraries
Use eftec\bladeone\BladeOne;
Use eftec\routeone\RouteOne;

// Load Controllers
include "app/Controllers/HomeController.php";
include "app/Controllers/UserController.php";
include "app/Controllers/GuestController.php";
include "app/Controllers/GiftController.php";

// Load helpers
include "app/flash.php";
include "app/db.php";
include "app/middleware.php";
include "app/redirect.php";

function path() {
	return "http://localhost/happy_brides/";
}

// Blade container
function blade() {
	global $blade;

	if ($blade == null) {
		$blade = new BladeOne(__DIR__."/Views", __DIR__."/Compiles", BladeOne::MODE_DEBUG);
		$blade->setBaseUrl(path());
	}

	return $blade;
}

// Router container
function router() {
	global $router;

	if ($router == null) {
		$router = new RouteOne();
		$router->fetch();
	}

	return $router;
}
?>