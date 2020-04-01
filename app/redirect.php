<?
class Redirect {
    public static function badRequest() {
        http_response_code(400);
        header("Location: ".path()."Error/");
        die();
    }
    
    public static function notAuthorized() {
        http_response_code(401);
        header("Location: ".path()."Error/");
        die();
    }
    
    public static function home() {
        header("Location: ".path());
        die();
    }
    
    public static function dashboardUser() {
        header("Location: ".path()."User/Dashboard/");
        die();
    }
    
    public static function dashboardGuest() {
        header("Location: ".path()."Guest/Dashboard/");
        die();
    }
}
?>