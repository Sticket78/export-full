<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$pathinfo = dirname(__FILE__);
define('ROOT', $pathinfo.DIRECTORY_SEPARATOR);
define('TADMIN', 'ADMIN');
include_once(ROOT.'config/config.php');
require_once(ROOT.'class/db.php');
require_once(ROOT.'class/review.php');
$db = new database();
$review=new review($db);
$categories=$review->get_all('categories');
$all_reviews=$review->get_all('reviews');

?>

<!DOCTYPE HTML PUBLIC>
<HTML>
 <HEAD>
  <TITLE> Модуль отзывов </TITLE>
  <META NAME="Generator" CONTENT="EditPlus">
  <META NAME="Author" CONTENT="">
  <META NAME="Keywords" CONTENT="">
  <META NAME="Description" CONTENT="">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" href="./css/main.css">
   <link rel="stylesheet" href="./css/media-queries.css">
	
 </HEAD>

<BODY>
<header>
	<div class="container">
		<div class="header-wrapper">
			<div class="header-logo">
				<div class="header-logo-inside">
					Лого
				</div>
			</div>
			<div class="header-menu">
				<div class="menu-wrap">
				<ul class="menu">
						<li><a href="#" id="show-tasks">Задание</a></li>
						<li><a href="#" id="show-solution">Описание реализации</a></li>
				</ul>
				</div>
			</div>
			<div class="header-add">
				<button class="btn btn-add">Оставить отзыв</button>
			</div>
		</div>
	</div>
</header>
<main>
	<div class="container">
		<h1>Отзывы</h1>
		<div class="reviews-wraper">
		<?php //выводим отзывы из базы
			if(!empty($all_reviews)) {
				$review_cats_string="";
				foreach($all_reviews as $revtmp) {
				$review_id=$revtmp['id']; // id отзыва
				// по id отзыва получаем категории привязанные к отзыву
				$review_cats_string="";
				$review_cats=$review->get_review_cats_values($review_id);
				foreach($review_cats as $str_cat) {
					$review_cats_string.=$str_cat['cat_name'].", ";
				}			

				$review_cats_string=substr($review_cats_string,0,-2);
				echo "<div class=\"reviews-item\" data-id=\"".$revtmp['id']."\" id=\"".$revtmp['id']."\">\n";
				echo "<h3>".$revtmp['surname']."</h3>\n";
				echo "<div class=\"reviews-item-subtitle\">".$revtmp['name']." ".$revtmp['middle_name']."</div>\n";
				echo "<div class=\"reviews-category\">Категория отзыва: <span>".$review_cats_string."</span></div>\n";
				echo "<div class=\"reviews-email\"><a href=\"mailto:".$revtmp['email']."\">".$revtmp['email']."</a></div>\n";
				echo "<p>".$revtmp['message']."</p>\n";
				echo "<button class=\"btn-del\" data-id=\"".$revtmp['id']."\" data-action=\"delete\">Удалить отзыв</button>\n";
				echo "</div>\n";
				}
			}
			else print "отзывов нет<br>\n";
		?>
			
		</div>
	</div>
</main>

<footer class="footer">
		<div class="container">
			<div class="footer-wrap">
				<div class="footer-details">
					
					<h6>Модуль отзывов</h6>
					
					<span class="footer-details-credits">
						© 2022 Модуль отзывов
					</span>
					<span class="footer-details-credits">
						Тестовое задание.
					</span>
				</div>
				<div class="footer-list-wrap">
					<div class="footer-list-content">
						<h6>
							Отзывы
						</h6>
						<ul class="footer-list">
						<?php
						if (!empty($all_reviews)) {
							foreach($all_reviews as $rev1) {
								echo "<li><a href=\"#".$rev1['id']."\">Отзыв от ".$rev1['name']." ".$rev1['surname']." ".$rev1['middle_name']."</a></li>";
							}
						}
						?>
						
					</ul>
					</div>
					<button type="button" class="btn btn-add btn-footer">
						Оставить отзыв
					</button>
					
					
				</div>
				<div class="">
					<div class="footer-list-content">
					<h6>
						Админка
					</h6>
					<ul class="footer-list">
						<li><a href="">Редактировать отзывы</a></li>
						<li><a href="">Редактировать категории</a></li>
					</ul>
					</div>
					
				</div>
			</div>
		</div>
	</footer>

<div class="modal">
</div>
<div class="overlay"></div>

		<div class="form-wrapper">
			<h3>Форма добавления отзыва</h3>
			<form action="fff.php" class="review-form-add" method="post">
				<div class="field-input_block">
					
					<input type="text" class="field_input" name="name">
					<div class="field_container"></div>
					<label class="field_label empty">Имя*</label>
					<div class="field_control"></div>
				</div>
				<div class="field-input_block">
					
					<input type="text" class="field_input" name="surname">
					<div class="field_container"></div>
					<label class="field_label empty">Фамилия*</label>
					<div class="field_control"></div>
				</div>
				<div class="field-input_block">
					
					<input type="text" class="field_input" name="middle_name">
					<div class="field_container"></div>
					<label class="field_label empty">Отчество*</label>
					<div class="field_control"></div>
				</div>
				<div class="field-input_block">
					
					<input type="email" class="field_input" name="email">
					<div class="field_container"></div>
					<label class="field_label empty">E-mail*</label>
					<div class="field_control"></div>
				</div>
				
				<div class="form_label">
				 
				  <div class="multiselect_block">
					<label for="select-1" class="field_multiselect">Категории</label>
					<input id="checkbox-1" class="multiselect_checkbox" type="checkbox">
					<label for="checkbox-1" class="multiselect_label"></label>
					<select id="select-1" class="field_select" name=cats[] multiple>
					<?php // выводим список категорий из базы в option
					foreach($categories as $cat) {
						echo "<option value=\"".$cat['cat_name']."\">".$cat['cat_name']."</option>\n";
					}
					?>
					 
					</select>
					
				  </div>
				  <div class="error_text field_control"></div>
				</div>
				<div class="field-input_block textarea">
					<textarea name="message" type="text" class="field_input"></textarea>
					<div class="field_container"></div>
					<label class="field_label empty">Текст отзыва*</label>
					<div class="field_control"></div>
				</div>
				<button type="submit" class="btn btn-submit">Отправить отзыв</button>
			</form>
		</div>
<div class="tasks">
	<div class="tasks-container">
		<h2>Задание</h2>
		<h3>Модуль отзывов</h3>
		<ul>
			<li>Реализация модуля приема отзывов от посетителей сайта</li>
			<li>Отзывы разделяются по категориям</li>
			<li>Форма приема отзывов состоит из полей: Ф.И.О. (тип текст), Email (тип email), Категория отзыва (выпадающий список, можно выбрать несколько категорий), Текст отзыва (текстовое поле). Все поля обязательны для заполнения.</li>
			<li>После успешной отправки, результаты заполнения сохраняются в базу.</li>
			<li>Задача - реализовать страницу добавления отзыва на основании спроектированной базы данных (php). Страница не должна выглядеть страшно.</li>
			<li>Требуется валидация данных на клиентской (можно использовать JS библиотеки) и серверной сторонах, добавление результатов формы в БД</li>
		</ul>
	</div>
</div>
<div class="solution">
	
		<h2>Реализация</h2>
		<p>Сторонних библиотек не использовалось. Весь код php/js написан с нуля</p>
		<ol>
			<li>форма добавления отзыва вызывается по клику по кнопкам</li>
			<li>сделал кастомное оформление полей input в форме добавления отзыва</li>
			<li>сделал свой множественный выбор категории (select multiple)</li>
			<li>кастомизация input multiple чтоб выбор был через клик без нажатия ctrl, + отображение выбранного</li>
			<li>написана проверка заполнения полей формы</li>
			<li>валидация поля email</li>
			<li>проверка всех полей на стороне клиента, после submit, перед отправкой данных на сервер</li>
			<li>отправка данных формы через json (в js)</li>
			<li>проверка данных на сервере</li>
			<li>написан класс работы с БД через PDO</li>
			<li>запись в mysql на сервере, и отправка данных обратно на клиента</li>
			<li>прием ответа сервера (без перезагрузки страницы), вывод уведомлений, отображение добавленного отзыва на странице</li>
			<li>данные отправки из формы и принятые с сервера отображаются в консоли</li>
			<li>реализован функционал удаления отзыва по клику на кнопку "Удалить"</li>
			<li>удаление "удаленного" отзыва в списке на странице, после удаления на сервере, без перезагрузки страницы</li>
			<li>адаптив в верстке не реализовывался</li>
		</ul>
	
</div>
<script src="./js/test.js"></script>

 </BODY>
</HTML>