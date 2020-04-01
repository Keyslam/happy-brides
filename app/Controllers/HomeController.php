<?
class HomeController {
	public function indexAction() {
		Middleware::homeAccess();

		$errors = Flash::get();

		echo blade()->run("Home", ["errors" => $errors]);
	}
}
?>