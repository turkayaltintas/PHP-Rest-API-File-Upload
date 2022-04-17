<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../objects/allClass.php';


$methods = new methods();
print_r($methods->methodControl("POST"));

$data = json_decode(file_get_contents("php://input"),true);

$fileName = @Rename::renameFile($_FILES['sendimage']['name']);
$tempPath = @$_FILES['sendimage']['tmp_name'];
$fileSize = @$_FILES['sendimage']['size'];
$fileType = @$_FILES['sendimage']['type'];
$ReName   = rand(1,9999999999)."_".$fileName;

if (empty($fileName)) {
    http_response_code(503);
    echo json_encode(array("message" => "please select image", "status" => false));
} else {
    $upload_path = '../../upload/';
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
    if (in_array($fileExt, $valid_extensions)) {
        if ($fileSize < 5000000) {
            $data = Array (
                'path' => substr($upload_path . $ReName,6),
                'file_info' => json_encode($_FILES),
                'name' => $fileName,
                'created_at' => $db->now()
            );
            $id = $db->insert ('files', $data);
            if ($id){
                move_uploaded_file($tempPath, $upload_path . $ReName);
                echo json_encode(array("message" => "File is upload.", "status" => true,"id" => $id));
            }else{
                echo json_encode(array("message" => "File is upload.", "status" => true,"fail" => $db->getLastError()));
            }
        } else {
            echo json_encode(array("message" => "Sorry, your file is too large, please upload 5 MB size", "status" => false));
        }
    } else {
        echo json_encode(array("message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed", "status" => false));
    }
}