<?php
class review {

	public function __construct($db) {
        $this->conn = $db;
		
    }
	function get_all($table) { // получаем все отзывы
		$query='select * from `'.$table.'` order by id';
		$result=$this->conn->query($query);
		return $result;
	}
	function delete_category ($cat_id) { // to do
	}
	function delete_review ($id) { // to do
	}
	function get_review_cats_values($id) { // получаем названия привязанных к отзыву категорий по id отзыва
		$query="select cat_name from reviews_cat_values join categories cat on cat_id=cat.id where review_id=:id;";
		$params = ['id' => $id];
		$result=$this->conn->query($query, $params);
		return $result;
	}
	function add_review_cats_value($id, $prod_cats) {
		//после добавления отзыва, добавляем в reviews_cat_values id привязанных категорий для данного отзыва по id отзыва
		//категории из формы названиями, а в таблицу пишем id категории
		$query_in=str_repeat('?,', count($prod_cats)-1).'?';
		foreach($prod_cats as $cats) {
		$arr_in[]=$cats;
		}
		$in  = str_repeat('?,', count($prod_cats) - 1) . '?';
		$query="insert into reviews_cat_values (review_id, cat_id) select ?, categories.id from categories where categories.cat_name IN ($in);";
		$params=array_merge([$id], $arr_in);
		//print $query;
		$this->conn->query_multi_params($query, $params);
	}

	function add_review($args) {		//добавляем отзыв в таблицу reviews

		$query="INSERT INTO reviews (`name`, `surname`, `middle_name`, `email`, `message`)  VALUES (:name, :surname, :middle_name, :email, :message);";
		$params=[
			'name'=>$args["name"],
			'surname'=>$args["surname"],
			'middle_name'=>$args["middle_name"],
			'email'=>$args["email"],
			'message'=>$args["message"]
		];
		$result=$this->conn->query($query, $params);
		$new_id=$this->conn->last_id();
		//print $new_id;
		//var_dump($args['cats']);
		//для добавленного отзыва прописываем значения категорий в таблицу reviews_cat_values
		$this->add_review_cats_value($new_id, $args['cats']);
		return $new_id;
	}
	function validate_review_data($fields) {
	// проверяем что из формы пришли все поля
		foreach ($fields as $key =>$value) { 
			if (empty($value)) $check[]=$key;
		}
		return $check;
	}
}

?>