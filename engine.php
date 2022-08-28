<?php
$pathinfo = dirname(__FILE__);
define('ROOT', $pathinfo.DIRECTORY_SEPARATOR);
define('TADMIN', 'ENGINE');
include_once(ROOT.'config/config.php');
require_once(ROOT.'class/db.php');
require_once(ROOT.'class/review.php');
$action=$_POST["action"];
$add_review["name"]=$_POST["name"];
$add_review["surname"]=$_POST["surname"];
$add_review["middle_name"]=$_POST["middle_name"];
$add_review["email"]=$_POST["email"];
$add_review["cats"]=$_POST["cats"];
$add_review["message"]=$_POST["message"];
//$add_review["email"]="";
/*
* если одно из значений сделать пустым 
* $add_review["email"]="";
* - отработает проверка, и клиенту вернется ответ с ошибкой 
*/

$db = new database();

$review=new review($db);

try {
    
     
    switch ($action) {
        case 'addreview':
			// проверяем на заполненность полей 
			$check=$review->validate_review_data($add_review);
			if(!empty($check)) { // найдены незаполненные поля
				$check_string="Ошибка, не заполнены поля: ".implode(", ", $check). ". Отзыв не добавлен";
				$result=array("error"=>'1', "responseText"=>$check_string);
				echo json_encode($result, JSON_UNESCAPED_UNICODE);
				die();
			}
			// добавляем отзыв
            $new_id=$review->add_review($add_review);
			$add_review['id']=$new_id; // добавляем к массиву id отзыва в базе
			echo json_encode($add_review, JSON_UNESCAPED_UNICODE);
			break;
        default:
            $result = 'unknown action';
            break;
    }
 
    
}
catch (Exception $e) {
    // Возвращаем клиенту ответ с ошибкой
    echo json_encode(array(
        'error' => 'error',
        'message' => $e->getMessage()
    ));
}

?>