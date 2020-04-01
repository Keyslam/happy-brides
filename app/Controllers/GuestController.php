<?
class GuestController {
	public function dashboardAction() {
		Middleware::guestAccess();

		echo blade()->run("Dashboard-Guest");
	}

	public function loginAction() {
		Middleware::postMethod();

		$name = isset($_POST["name"]) ? filter_var(trim($_POST["name"]), FILTER_SANITIZE_STRING) : "";
		$code = isset($_POST["code"]) ? filter_var(trim($_POST["code"]), FILTER_SANITIZE_STRING) : "";

		$result = DB::guestLogin($code);

		if ($result) {
			$_SESSION["guest_wishlist_id"] = $result["ID"];
			$_SESSION["guest_name"] = $name;

			Redirect::dashboardGuest();
			die();
		}
		else {
			Flash::put(["Code incorrect"]);

			Redirect::home();
			die();
		}
	}

	public function logoutAction() {
		Middleware::postMethod();

		$_SESSION["guest_wishlist_id"] = null;
		$_SESSION["guest_name"] = null;

		Redirect::home();
	}
}
?>