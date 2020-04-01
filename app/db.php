<?
class DB {
    private static $instance = null;
    private $conn = null;

    private function __construct()
    {
        $host    = "localhost";
		$db      = "happy_brides";
		$user    = "root";
		$pass    = "";
		$charset = "utf8mb4";

		$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

		$options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
	  	];

		$this->conn = new PDO($dsn, $user, $pass, $options);
    }

    private static function Instance()
    {
        if (self::$instance == null)
        {
            self::$instance = new DB();
        }
 
        return self::$instance;
    }

    private static function Connection() {
        return DB::Instance()->conn;
    }

    public static function wishlistUserData($id) {
        $stmt = DB::Connection()->prepare("CALL wishlist_user_data(:i_id)");

        $stmt->bindValue(":i_id", $id);
        
		$success = $stmt->execute();
        $result = $stmt->fetch();
        
        return $result;
    }

    public static function userLogin($email_address, $password) {
        $stmt = DB::Connection()->prepare("CALL user_login(:email_address, :password)");

		$stmt->bindValue(":email_address", $email_address);
		$stmt->bindValue(":password", $password);
        
        $success = $stmt->execute();
        $result = $stmt->fetch();

        return $result;
    }

    public static function userRegister($name, $email_address, $password) {
        $stmt = DB::Connection()->prepare("CALL user_register(:i_name, :i_email_address, :i_password)");

		$stmt->bindValue("i_name", $name);
		$stmt->bindValue("i_email_address", $email_address);
        $stmt->bindValue("i_password", $password);
        
		$success = $stmt->execute();
        $result = $stmt->fetchColumn();

        return $result;
    }

    public static function userEmailTaken($email_address) {
        $stmt = DB::Connection()->prepare("CALL user_email_taken(:i_email_address)");
        
		$stmt->bindValue("i_email_address", $email_address);
        
        $stmt->execute();
		$success = $result = $stmt->fetchColumn();
        
        return $result;
    }

    public static function giftAdd($user_id, $gift_name) {
        $stmt = DB::Connection()->prepare("CALL gift_add(:i_user_id, :i_gift_name)");

		$stmt->bindValue("i_user_id", $user_id);
        $stmt->bindValue("i_gift_name", $gift_name);
        
        $result = $stmt->execute();

        return $result;
    }

    public static function giftDelete($gift_id) {
        $stmt = DB::Connection()->prepare("CALL gift_delete(:i_gift_id)");

        $stmt->bindValue("i_gift_id", $gift_id);
        
        $result = $stmt->execute();
        
        return $result;
    }

    public static function giftSetPriority($gift_id, $new_priority) {
        $stmt = DB::Connection()->prepare("CALL gift_set_priority(:i_gift_id, :i_new_priority)");

		$stmt->bindValue("i_gift_id", $gift_id);
        $stmt->bindValue("i_new_priority", $new_priority);
        
        $result = $stmt->execute();
        
        return $result;
    }

    public static function wishlistUserGifts($user_id) {
        $stmt = DB::Connection()->prepare("CALL wishlist_user_gifts(:i_id)");

		$stmt->bindValue("i_id", $user_id);
        
        $success = $stmt->execute();
        $gifts = $stmt->fetchAll();
        
        return $gifts;
    }

    public static function wishlistGuestGiftsUnclaimed($wishlist_ID) {
        $stmt = DB::Connection()->prepare("CALL wishlist_guest_gifts_unclaimed(:i_wishlist_id)");

        $stmt->bindValue("i_wishlist_id", $wishlist_ID);
        
		$success = $stmt->execute();
        $gifts_unclaimed = $stmt->fetchAll();
        
        return $gifts_unclaimed;
    }

    public static function wishlistGuestGiftsClaimed($wishlist_ID) {
        $stmt = DB::Connection()->prepare("CALL wishlist_guest_gifts_claimed(:i_wishlist_id)");

        $stmt->bindValue("i_wishlist_id", $wishlist_ID);
        
		$success = $stmt->execute();
        $gifts_claimed = $stmt->fetchAll();
        
        return $gifts_claimed;
    }

    public static function giftClaim($gift_id, $guest_name) {
        $stmt = DB::Connection()->prepare("CALL gift_claim(:i_gift_id, :i_claimed_by)");

		$stmt->bindValue("i_gift_id", $gift_id);
        $stmt->bindValue("i_claimed_by", $guest_name);
        
		$success = $stmt->execute();
        
        return $success;
    }

    public static function guestLogin($code) {
        $stmt = DB::Connection()->prepare("CALL guest_login(:i_access_code)");

        $stmt->bindValue("i_access_code", $code);
        
		$success = $stmt->execute();
        $result = $stmt->fetch();
        
        return $result;
    }
}
?>