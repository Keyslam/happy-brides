<?
namespace app\Controllers;

Use eftec\bladeone\BladeOne;

class HomeController {
	public function indexAction() {
		if (isset($_SESSION['user_id'])) {
			header('Location: User/Dashboard');
			die();
		}

		$errors = null;
		if (isset($_SESSION['flash'])) {
			$errors = $_SESSION['flash'];
			flash_clear();
		}

		echo blade()->run('Home', ['errors' => $errors]);
	}
}
?>