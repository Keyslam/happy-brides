<?
include __DIR__."/../vendor/autoload.php";

// Load libraries
Use eftec\bladeone\BladeOne;
Use eftec\routeone\RouteOne;

// Blade container
function blade() {
	global $blade;

	if ($blade == null) {
		$blade = new BladeOne(__DIR__."/Views", __DIR__."/Compiles", BladeOne::MODE_DEBUG);
		$blade->setBaseUrl("http://localhost/happy_brides/");
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

// Database container
function db() {
	global $db;

	if ($db == null) {
		$host    = "localhost";
		$db      = "happy_brides";
		$user    = "root";
		$pass    = "";
		$charset = "utf8mb4";

		$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

		$options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
	  	];

		$db = new PDO($dsn, $user, $pass, $options);
	}

	return $db;
}

function flash($array) {
	$_SESSION["flash"] = $array;
}

function flash_clear() {
	$_SESSION["flash"] = array();
}
?>