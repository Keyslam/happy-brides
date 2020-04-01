<?
class GiftController {
	public function addAction() {
		Middleware::postMethod();
		Middleware::userAccess();

		$user_id = $_SESSION["user_id"];
		$gift_name = isset($_POST["gift_name"]) ? filter_var(trim($_POST["gift_name"]), FILTER_SANITIZE_STRING) : "";

		if ($code !== "")
		{
			DB::giftAdd($user_id, $gift_name);
		}
	}

	public function deleteAction() {
		Middleware::postMethod();
		Middleware::userAccess();

		$gift_id = isset($_POST["gift_id"]) ? filter_var(trim($_POST["gift_id"]), FILTER_SANITIZE_NUMBER_INT) : "";

		if ($gift_id !== "")
		{
			DB::giftDelete($gift_id);
		}
	}

	public function moveAction() {
		Middleware::postMethod();
		Middleware::userAccess();

		$gift_id = isset($_POST["gift_id"]) ? filter_var(trim($_POST["gift_id"]), FILTER_SANITIZE_NUMBER_INT) : "";
		$new_priority = isset($_POST["new_priority"]) ? filter_var(trim($_POST["new_priority"]), FILTER_SANITIZE_NUMBER_INT) : "";

		if ($gift_id !== "" && $new_priority !== "")
		{ 
			DB::giftSetPriority($gift_id, $new_priority);
		}
	}

	public function getListAction() {
		Middleware::userAccess();

		$user_id = $_SESSION["user_id"];

		$gifts = DB::wishlistUserGifts($user_id);

		echo blade()->run("Giftlist-User", ["gifts" => $gifts]);
	}

	public function getListAsGuestAction() {
		Middleware::guestAccess();

		$wishlist_ID = $_SESSION["guest_wishlist_id"];

		$gifts_unclaimed = DB::wishlistGuestGiftsUnclaimed($wishlist_ID);
		$gifts_claimed = DB::wishlistGuestGiftsClaimed($wishlist_ID);

		echo blade()->run("Giftlist-Guest", ["gifts_unclaimed" => $gifts_unclaimed, "gifts_claimed" => $gifts_claimed]);
	}

	public function claimAction() {
		Middleware::guestAccess();

		$gift_id = isset($_POST["gift_id"]) ? filter_var($_POST["gift_id"], FILTER_SANITIZE_NUMBER_INT) : "";

		$guest_name = $_SESSION['guest_name'];

		DB::giftClaim($gift_id, $guest_name);
	}
}
?>