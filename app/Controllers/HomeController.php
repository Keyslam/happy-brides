<?
namespace app\Controllers;

Use eftec\bladeone\BladeOne;

class HomeController {
	public function indexAction() {
		if (isset($_SESSION['event_id'])) {
			header("Location: Host/Dashboard");
			die();
		}

		$errors = null;
		if (isset($_SESSION['flash'])) {
			$errors = $_SESSION['flash'];
			flash_clear();
		}

		echo blade()->run("Home", ['errors' => $errors]);
	}
}
?>