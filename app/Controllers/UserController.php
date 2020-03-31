<?
namespace app\Controllers;

Use eftec\bladeone\BladeOne;
Use eftec\PdoOne;

class UserController {
	public function dashboardAction() {
		if (!isset($_SESSION["user_id"])) {
			header("Location: ../");
			die();
		}

		$user_id = $_SESSION["user_id"];

		$stmt = db()->prepare("CALL wishlist_user_data(:i_id, @o_name, @o_access_code)");
		$stmt->bindValue(":i_id", $user_id);
		$stmt->execute();
		$result = $stmt->fetch();

		if ($result) {
			echo blade()->run("Dashboard-User", [
				"couple_name" => $result["o_name"],
				"access_code" => $result["o_access_code"]
			]);
		}
		else {
			die();
		}
	}

	public function logoutAction() {
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			header('Location: ../404');
			die();
		}

		$_SESSION["user_id"] = null;

		header("Location: ../");
		die();
	}

	public function loginAction() {
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			header("Location: ../404");
			die();
		}

		$email_address = isset($_POST["email-address"]) ? filter_var(trim($_POST["email-address"], FILTER_SANITIZE_EMAIL))  : "";
		$password      = isset($_POST["password"])      ? filter_var(trim($_POST["password"],      FILTER_SANITIZE_STRING)) : "";

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
				flash($errors);

				header("Location: ../");
				die();
			}
		}

		$password_encrypted = self::encryptPassword($password);

		$stmt = db()->prepare("CALL user_login(:email_address, :password, @o_id)");
		$stmt->bindValue(":email_address", $email_address);
		$stmt->bindValue(":password", $password_encrypted);
		$stmt->execute();
		$result = $stmt->fetch();

		$id = $result["o_id"];

		if ($id) {
			$_SESSION["user_id"] = $id;

			header("Location: Dashboard");
			die();
		}
		else {
			$error = array();

			array_push($error, "Email address or password incorrect");

			if (count($error) > 0) {
				flash($error);

				header("Location: ../");
				die();
			}
		}
	}

	public function registerAction() {
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			header("Location: ../404");
			die();
		}

		$couple_name   = isset($_POST["couple-name"])   ? filter_var(trim($_POST["couple-name"],   FILTER_SANITIZE_STRING)) : "";
		$email_address = isset($_POST["email-address"]) ? filter_var(trim($_POST["email-address"], FILTER_SANITIZE_EMAIL))  : "";
		$password      = isset($_POST["password"])      ? filter_var(trim($_POST["password"],      FILTER_SANITIZE_STRING)) : "";

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

			$taken;

			$stmt = db()->prepare("CALL user_email_taken(:i_email_address, @o_taken)");
			$stmt->bindValue("i_email_address", $email_address);
			$stmt->execute();
			$email_taken = $stmt->fetch()["o_taken"];
			unset($stmt);

			if ($email_taken) {
				array_push($errors, "Email is already in use");
			}
		
			if (count($errors) > 0) {
				flash($errors);

				header("Location: ../");
				die();
			}
		}
		
		$password_encrypted = self::encryptPassword($password);

		$stmt = db()->prepare("CALL user_register(:i_name, :i_email_address, :i_password, @o_success)");
		$stmt->bindValue("i_name", $couple_name);
		$stmt->bindValue("i_email_address", $email_address);
		$stmt->bindValue("i_password", $password_encrypted);
		$stmt->execute();
		$success = $stmt->fetchColumn();

		if ($success) {
			flash(["Something unexpected went wrong"]);

			header("Location: ../");
			die();
		}

		flash(["Register succesful!"]);
		header("Location: ../");
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