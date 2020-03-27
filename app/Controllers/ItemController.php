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
		if (!isset($_SESSION['event_id'])) {
			header("Location: ../");
			die();
		}
		$event_ID = $_SESSION['event_id'];

		$item_name = $_POST["itemName"];

		db()
			->insert("gift", [
				'event_ID' => $event_ID,
				'name' => $item_name,
				'taken' => 0
			]);

		echo self::getListAction();
	}

	public function deleteAction() {
		// Verify REQUEST_METHOD
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			header("Location: ../404");
			die();
		}

		// Verify authentication
		if (!isset($_SESSION['event_id'])) {
			header("Location: ../");
			die();
		}

		$event_ID = $_SESSION['event_id'];
		$item_id = $_POST["item_id"];

		db()
			->delete("gift", [
				'ID' => $item_id,
				'event_ID' => $event_ID,
			]);

		echo self::getListAction();
	}

	public function moveAction() {
		// Verify REQUEST_METHOD
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			header('Location: ../404');
			die();
		}

		// Verify authentication
		if (!isset($_SESSION['event_id'])) {
			header('Location: ../');
			die();
		}

		$event_ID = $_SESSION['event_id'];
		$item_id = $_POST['item_id'];
		$new_priority = $_POST['new_priority'];

		$old_priority = db()
			->select('priority')
			->from('gift')
			->where('ID', $item_id)
			->firstScalar();

		if ($old_priority == $new_priority) {
			// Early out
			echo self::getListAction();
			die();
		}
		
		/*

		$direction = $new_index > $old_index ? 1 : -1; 

		for ($i = $old_index + $direction; 
			  ($direction == -1 && $i < 0) || ($direction == 1 && $i > 0); 
			  $i += $direction) {

			echo $i;
		}
		*/

		/*
		db()
			->delete('gift', [
				'ID' => $item_id,
				'event_ID' => $event_ID,
			]);
		*/

		echo self::getListAction();
	}

	public function getListAction() {
		// Verify authentication
		if (!isset($_SESSION['event_id'])) {
			header("Location: ../404");
			die();
		}

		$event_ID = $_SESSION['event_id'];

		$items = db()
			->select('ID, name')
			->from('gift')
			->where('event_ID', $event_ID)
			->order('priority asc')
			->toList();

		echo blade()->run('Gift-List', ['items' => $items]);
	}

	public function getListAsGuestAction() {
		// Verify authentication
		if (!isset($_SESSION['event_id_guest'])) {
			header("Location: ../404");
			die();
		}

		$event_ID = $_SESSION['event_id_guest'];

		$items = db()
			->select('ID, name')
			->from('gift')
			->where([
				'event_ID' => $event_ID,
				'taken'    => false
			])
			->order('priority asc')
			->toList();

		$items_taken = db()
			->select('name, taken_by')
			->from('gift')
			->where([
				'event_ID' => $event_ID,
				'taken'    => true
			])
			->order('priority asc')
			->toList();

		echo blade()->run('Gift-List-Guest', ['items' => $items, 'items_taken' => $items_taken]);
	}

	public function claimAction() {
		// Verify authentication
		if (!isset($_SESSION['event_id_guest'])) {
			header("Location: ../404");
			die();
		}

		$item_id = isset($_POST['item_id']) ? filter_var($_POST['item_id'], FILTER_SANITIZE_NUMBER_INT)  : '';

		{
			if ($item_id == '')
			{
				die();
			}
		}

		$guest_name = $_SESSION['guest_name'];

		db()
			->update('gift', [
				'taken' => true,
				'taken_by' => $guest_name
			], [
				'ID' => $item_id
			]);

		echo self::getListAsGuestAction();
	}
}
?>