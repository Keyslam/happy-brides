<?
class Flash {
    public static function put($data) {
        $_SESSION["flash"] = $data;
    }

    public static function get() {
        $data = null;

        if (isset($_SESSION["flash"])) {
            $data = $_SESSION["flash"];
            Flash::clear();
        }

        return $data;
    }

    public static function clear() {
        $_SESSION["flash"] = array();
    }
}
?>