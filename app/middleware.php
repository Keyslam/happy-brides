<?
class Middleware {
    public static function userAccess() {
        if (!isset($_SESSION["user_id"])) {
            Redirect::notAuthorized();
        }
    }
    
    public static function guestAccess() {
        if (!isset($_SESSION["guest_wishlist_id"])) {
            Redirect::notAuthorized();
        }
    }

    public static function homeAccess() {
        if (isset($_SESSION["user_id"])) {
            Redirect::dashboardUser();
        } 

        if (isset($_SESSION["guest_wishlist_id"])) {
            Redirect::dashboardGuest();
        } 
    }
    
    public static function postMethod() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            Redirect::badRequest();
        }
    }
}
?>