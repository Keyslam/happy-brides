<?
namespace app\Controllers;

Use eftec\bladeone\BladeOne;

class GuestController {
	public function dashboardAction() {
		if (!isset($_SESSION['event_id_guest'])) {
			header("Location: ../");
			die();
		}

		echo blade()->run('Dashboard-Guest');
	}

	public function loginAction() {
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			header("Location: ../404");
			die();
		}

		$name = isset($_POST['name']) ? filter_var(trim($_POST['name'], FILTER_SANITIZE_STRING))  : '';
		$code = isset($_POST['code']) ? filter_var(trim($_POST['code'], FILTER_SANITIZE_STRING))  : '';

		$id = db()
			->select('id')
			->from('event')
			->where([
				'access_code' => $code,
			])
			->firstScalar();

		if ($id) {
			$_SESSION['event_id_guest'] = $id;
			$_SESSION['guest_name'] = $name;

			header("Location: Dashboard");
			die();
		}
		else {
			$error = array();

			array_push($error, "Code incorrect");

			header("Location: ../");
			die();
		}
	}

	public function logoutAction() {
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			header("Location: ../404");
			die();
		}

		$_SESSION['event_id_guest'] = null;
		$_SESSION['guest_name'] = null;

		echo blade()->run("Home");
	}
}
?>