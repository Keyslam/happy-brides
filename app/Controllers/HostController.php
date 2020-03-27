<?
namespace app\Controllers;

Use eftec\bladeone\BladeOne;
Use eftec\PdoOne;

class HostController {
	public function dashboardAction() {
		if (!isset($_SESSION['event_id'])) {
			header("Location: ../");
			die();
		}

		$event_id = $_SESSION['event_id'];

		$fields = db()
			->select('couple_name, access_code')
			->from('event')
			->where('id', $event_id)
			->first();

		if ($fields) {
			echo blade()->run('Dashboard-Host', [
				'couple_name' => $fields['couple_name'],
				'access_code' => $fields['access_code']
			]);
		}
		else {
			die();
		}
	}

	public function logoutAction() {
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			header("Location: ../404");
			die();
		}

		$_SESSION['event_id'] = null;

		header("Location: ../");
		die();
	}

	public function loginAction() {
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			header("Location: ../404");
			die();
		}

		$email_address = isset($_POST['email-address']) ? filter_var(trim($_POST['email-address'], FILTER_SANITIZE_EMAIL))  : '';
		$password      = isset($_POST['password'])      ? filter_var(trim($_POST['password'],      FILTER_SANITIZE_STRING)) : '';

		{
			$errors = array();

			if ($email_address === '') {
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

		$id = "Poep";

		$stmt = db()->prepare("CALL user_login(?, ?, ?)");
		$stmt->bindValue(1, "test");
		$stmt->bindValue(2, "1234");
		$stmt->bindParam(3, $id);
		$stmt->execute();

		echo $id;

		die();

		if ($id) {
			$_SESSION['event_id'] = $id;

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

		$couple_name   = isset($_POST['couple-name'])   ? filter_var(trim($_POST['couple-name'],   FILTER_SANITIZE_STRING)) : '';
		$email_address = isset($_POST['email-address']) ? filter_var(trim($_POST['email-address'], FILTER_SANITIZE_EMAIL))  : '';
		$password      = isset($_POST['password'])      ? filter_var(trim($_POST['password'],      FILTER_SANITIZE_STRING)) : '';
		$end_date      = isset($_POST['end-date'])      ?                 $_POST['end-date']                                : '';

		{
			$errors = array();

			if ($couple_name === '') {
				array_push($errors, "Field 'couple-name' is empty");
			}

			if ($email_address === '') {
				array_push($errors, "Field 'email-address' is empty");
			}

			if ($password === '') {
				array_push($errors, "Field 'password' is empty");
			}

			$email_taken = db()
				->count()
				->from('event')
				->where('email_address', $email_address)
				->firstScalar() > 0;

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
		$access_code = self::createAccessCode();

		db()
			->insert('event', [
				'couple_name'   => $couple_name,
				'email_address' => $email_address,
				'password'      => $password_encrypted,
				'end_date'      => $end_date,
				'access_code'   => $access_code
			]
		);

		header("Location: ../");
		die();
	}

	private function createAccessCode() {
		// This method uses a brute force method to create an access code.
		// This could possibly done by MySQL instead.
		// However, a better approach would be to use different kinds of access codes.
		//
		// You could of course use an incrementing access code (AAAA, AAAB, AAAC, etc) for each user
		// but this isn't particularly secure

		$allowed_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$access_code_length = 4;

		$access_code = '';

		do {
			$access_code = '';

			for ($i = 0; $i < $access_code_length; $i++) {
				$access_code .= $allowed_chars[rand(0, strlen($allowed_chars) - 1)];
			}
		} while (self::isAccessCodeTaken($access_code));

		return $access_code;
	}

	private function isAccessCodeTaken(string $access_code) {
		$count = db()
			->count()
			->from('event')
			->where('access_code', $access_code)
			->firstScalar();

		return $count > 0;
	}

	private function encryptPassword(string $password) {
		// Simple encryption by adding a 8 character salt and hashing with sha256

		return self::hashPassword(self::saltPassword($password));
	}

	private function saltPassword(string $password) {
		$salt = 'YcWPtmkW';

		return $password .= $salt;
	}

	private function hashPassword(string $password) {
		return hash('sha256', $password);
	}
}
?>