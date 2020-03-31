<?
namespace app\Controllers;

Use eftec\bladeone\BladeOne;

class GuestController {
	public function dashboardAction() {
		if (!isset($_SESSION["guest_wishlist_id"])) {
			header("Location: ../");
			die();
		}

		echo blade()->run("Dashboard-Guest");
	}

	public function loginAction() {
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			header("Location: ../404");
			die();
		}

		$name = isset($_POST["name"]) ? filter_var(trim($_POST["name"], FILTER_SANITIZE_STRING))  : "";
		$code = isset($_POST["code"]) ? filter_var(trim($_POST["code"], FILTER_SANITIZE_STRING))  : "";

		$stmt = db()->prepare("CALL guest_login(:i_access_code)");
		$stmt->bindValue("i_access_code", $code);
		$success = $stmt->execute();
		$results = $stmt->fetch();

		if ($results) {
			$_SESSION["guest_wishlist_id"] = $results['ID'];
			$_SESSION["guest_name"] = $name;

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

		$_SESSION["guest_wishlist_id"] = null;
		$_SESSION["guest_name"] = null;

		echo blade()->run("Home");
	}
}
?>