<?
class UserController {
	public function dashboardAction() {
		Middleware::userAccess();

		$result = DB::wishlistUserData($_SESSION["user_id"]);

		if ($result) {
			echo blade()->run("Dashboard-User", [
				"couple_name" => $result["name"],
				"access_code" => $result["access_code"]
			]);
		}
		else {
			Redirect::badRequest();
		}
	}

	public function logoutAction() {
		Middleware::postMethod();

		$_SESSION["user_id"] = null;

		Redirect::home();
	}

	public function loginAction() {
		Middleware::postMethod();

		$email_address = isset($_POST["email-address"]) ? filter_var(trim($_POST["email-address"]), FILTER_SANITIZE_EMAIL)  : "";
		$password      = isset($_POST["password"])      ? filter_var(trim($_POST["password"]),      FILTER_SANITIZE_STRING) : "";

		{
			$errors = array();

			if ($email_address === "") {
				array_push($errors, "Field 'email_address' is empty");
			}

			if ($password === '') {
				array_push($errors, "Field 'password' is empty");
			}

			if (count($errors) > 0)
			{
				Flash::put($errors);

				Redirect::home();
				die();
			}
		}

		$password_encrypted = self::encryptPassword($password);

		$result = DB::userLogin($email_address, $password_encrypted);

		$id = $result["id"];

		if ($id) {
			$_SESSION["user_id"] = $id;

			Redirect::dashboardUser();
			die();
		}
		else {
			$error = array();

			array_push($error, "Email address or password incorrect");

			if (count($error) > 0) {
				Flash::put($error);

				Redirect::home();
				die();
			}
		}
	}

	public function registerAction() {
		Middleware::postMethod();

		$couple_name   = isset($_POST["couple-name"])   ? filter_var(trim($_POST["couple-name"]),   FILTER_SANITIZE_STRING) : "";
		$email_address = isset($_POST["email-address"]) ? filter_var(trim($_POST["email-address"]), FILTER_SANITIZE_EMAIL)  : "";
		$password      = isset($_POST["password"])      ? filter_var(trim($_POST["password"]),      FILTER_SANITIZE_STRING) : "";

		{
			$errors = array();

			if ($couple_name === "") {
				array_push($errors, "Field 'couple-name' is empty");
			}

			if ($email_address === "") {
				array_push($errors, "Field 'email-address' is empty");
			}

			if ($password === "") {
				array_push($errors, "Field 'password' is empty");
			}

			$email_taken = DB::userEmailTaken($email_address);

			if ($email_taken) {
				array_push($errors, "Email is already in use");
			}
		
			if (count($errors) > 0) {
				Flash::put($errors);

				Redirect::home();
				die();
			}
		}
		
		$password_encrypted = self::encryptPassword($password);

		$success = DB::userRegister($couple_name, $email_address, $password_encrypted);

		if (!$success) {
			Flash::put(["Something unexpected went wrong"]);

			Redirect::home();
			die();
		}

		Flash::put(["Register succesful!"]);
		Redirect::home();
		die();
	}

	private function encryptPassword(string $password) {
		return self::hashPassword(self::saltPassword($password));
	}

	private function saltPassword(string $password) {
		$salt = "YcWPtmkW";

		return $password .= $salt;
	}

	private function hashPassword(string $password) {
		return hash("sha256", $password);
	}
}
?>