<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../objects/allClass.php';

$methods = new methods();

print_r($methods->methodControl("GET"));

$FileID = $_GET['id'];

if (isset($FileID)){
    $db->where ("id", $FileID);
    $File = $db->getOne ("files", "id,path,count(*) as cnt");
    if ($File['cnt']){
        $FilePath = "http://localhost/PHP-Rest-API-File-Upload/".$File['path'];
        $curl = curl_init($FilePath);
        $fopen = fopen($FilePath,'w');
        curl_setopt($curl, CURLOPT_HEADER,0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_FILE, $fopen);
        curl_exec($curl);
        curl_close($curl);
        fclose($fopen);
    }else{
        echo json_encode(array("message" => "File not found.", "status" => false, "id" => $FileID));
    }
}else{
    echo json_encode(array("message" => "File ID is not set.", "status" => false, "id" => $FileID));
}
