<?
namespace app\Controllers;

Use eftec\bladeone\BladeOne;

class ItemController {
	public function addAction() {
		// Verify REQUEST_METHOD
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			header("Location: ../404");
			die();
		}

		// Verify authentication
		if (!isset($_SESSION["user_id"])) {
			header("Location: ../");
			die();
		}
		$user_id = $_SESSION["user_id"];
		$gift_name = $_POST["gift_name"]; // TODO checking

		$stmt = db()->prepare("CALL gift_add(:i_user_id, :i_gift_name)");
		$stmt->bindValue("i_user_id", $user_id);
		$stmt->bindValue("i_gift_name", $gift_name);
		$stmt->execute();
	}

	public function deleteAction() {
		// Verify REQUEST_METHOD
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			header("Location: ../404");
			die();
		}

		// Verify authentication
		if (!isset($_SESSION["user_id"])) {
			header("Location: ../");
			die();
		}

		$gift_id = $_POST["gift_id"];

		$stmt = db()->prepare("CALL gift_delete(:i_gift_id)");
		$stmt->bindValue("i_gift_id", $gift_id);
		$stmt->execute();
	}

	public function moveAction() {
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			header("Location: ../404");
			die();
		}

		if (!isset($_SESSION["user_id"])) {
			header("Location: ../");
			die();
		}

		$gift_id = $_POST["gift_id"];
		$new_priority = $_POST["new_priority"];

		$stmt = db()->prepare("CALL gift_set_priority(:i_gift_id, :i_new_priority)");
		$stmt->bindValue("i_gift_id", $gift_id);
		$stmt->bindValue("i_new_priority", $new_priority);
		$stmt->execute();
	}

	public function getListAction() {
		// Verify authentication
		if (!isset($_SESSION["user_id"])) {
			header("Location: ../404");
			die();
		}

		$user_id = $_SESSION["user_id"];

		$stmt = db()->prepare("CALL wishlist_user_items(:i_id)");
		$stmt->bindValue("i_id", $user_id);
		$success = $stmt->execute();
		$items = $stmt->fetchAll();

		echo blade()->run("Giftlist-User", ["items" => $items]);
	}

	public function getListAsGuestAction() {
		// Verify authentication
		if (!isset($_SESSION["guest_wishlist_id"])) {
			header("Location: ../404");
			die();
		}

		$wishlist_ID = $_SESSION["guest_wishlist_id"];

		$stmt = db()->prepare("CALL wishlist_guest_gifts_unclaimed(:i_wishlist_id)");
		$stmt->bindValue("i_wishlist_id", $wishlist_ID);
		$success = $stmt->execute();
		$gifts_unclaimed = $stmt->fetchAll();
		$stmt = null;

		$stmt = db()->prepare("CALL wishlist_guest_gifts_claimed(:i_wishlist_id)");
		$stmt->bindValue("i_wishlist_id", $wishlist_ID);
		$success = $stmt->execute();
		$gifts_claimed = $stmt->fetchAll();
		$stmt = null;

		echo blade()->run("Giftlist-Guest", ["gifts_unclaimed" => $gifts_unclaimed, "gifts_claimed" => $gifts_claimed]);
	}

	public function claimAction() {
		// Verify authentication
		if (!isset($_SESSION["guest_name"])) {
			header("Location: ../404");
			die();
		}

		$gift_id = isset($_POST["gift_id"]) ? filter_var($_POST["gift_id"], FILTER_SANITIZE_NUMBER_INT)  : "";

		$guest_name = $_SESSION['guest_name'];

		$stmt = db()->prepare("CALL gift_claim(:i_gift_id, :i_claimed_by)");
		$stmt->bindValue("i_gift_id", $gift_id);
		$stmt->bindValue("i_claimed_by", $guest_name);
		$success = $stmt->execute();
		$stmt = null;
	}
}
?>