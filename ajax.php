<?php 
error_reporting( E_ALL );

$action = $_POST['action'];

function get_file() {
	$file = getcwd() . "/data.json";

	return $file;
}

function get_content() {
	$file = get_file();

	$json = json_decode(file_get_contents($file), true);
	return $json;
}

function save_content($data) {
	$file = get_file();

	file_put_contents($file,  json_encode($data));

	return get_content();
}	

function update_status($id, $status) {
	$data = get_content();

	foreach ($data as $key => $record) {
		if($record['id'] == $id) {
			$data[$key]['status'] = $status;
			break;
		}
	}

	return save_content(array_values($data));
}

function update_record($id, $newData) {
	$data = get_content($newData);

	foreach ($data as $key => $record) {
		if($record['id'] == $id) {
			$data[$key] = $newData;
			break;
		}
	}
	return save_content(array_values($data));
}

function delete_record($id) {
	$data = get_content();

	foreach ($data as $key => $record) {
		if($record['id'] == $id) {
			unset($data[$key]);
			break;
		}
	}

	return save_content(array_values($data));
}

function new_record($newData) {
	$data = get_content();

	$contactList = $newData['contactList'];
    $message = $newData['message'];

    $phoneNamePair = explode("\n", $contactList);
    $multiInsertValues = [];

    foreach($phoneNamePair as $pair) {
        $pairValues = explode(';', $pair);
        $name = trim($pairValues[0]);
        $phone = trim($pairValues[1]);

        array_push($data,[
        	"id" => md5(time().rand(2, 40000)),
        	"date" => "NULL",
        	"name" => $name,
        	"phone" => $phone,
        	"message" => $message,
        	"status" => "pending"
        ]);
    }

	return save_content(array_values($data));
}

switch ($action) {
	case "GET":
		$data = get_content();

		echo json_encode(["status" => true, "data" => $data]);
		die();
	break;

	case "POST":
		$data = $_POST;
		unset($data['action']);

		$data = new_record($data);

		echo json_encode(["status" => true, "data" => $data]);
		die();
	break;

	case 'PATCH':
		$data = $_POST;
		unset($data['action']);

		$data = update_record($data['id'], $data);

		echo json_encode(["status" => true, "data" => $data]);
		die();
	break;

	case "DELETE":
		$data = $_POST;
		unset($data['action']);

		$data = delete_record($data['id']);

		echo json_encode(["status" => true, "data" => $data]);
	break;

	case "STATUSPATCH":
		$data = $_POST;
		unset($data['action']);

		$data = update_status($data['id'], 'contacted');

		echo json_encode(["status" => true, "data" => $data]);
	break;
	
	default:
		echo json_encode(["status" => false]);
		die();
	break;
}

?>