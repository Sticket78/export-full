// check inputs 
const inputs=Array.from(document.querySelectorAll('.field-input_block'))
const modalwindow=document.querySelector('.modal')
var errors=0
if (inputs.length) {
	for (let i=0; i<inputs.length; i++ ) {
		inputs[i].addEventListener('input', (Ans)=>{
			var curInput=inputs[i].getElementsByTagName('input')[0]
			//console.log(curInput.value)
		})
		inputs[i].addEventListener('focusout', (event)=>{
			form_validate(inputs[i])
		})
	}
}
//show form 
const showformbtn=Array.from(document.querySelectorAll('.btn-add'))
const reviewform=document.querySelector('.form-wrapper')
	for (let i=0; i<showformbtn.length; i++) {
		showformbtn[i].addEventListener('click', ()=>{
			reviewform.classList.toggle('active')
		})
	}
//form submit
const formsubmit=document.querySelector('.review-form-add')
formsubmit.addEventListener('submit', function(event) {
	event.preventDefault()
	errors=0
	for (let i=0; i<inputs.length; i++ ) {
		form_validate(inputs[i])
	}
	validate_multiple()
	if (!(errors===0)) {
		console.log ('не отправляем - ' + errors + ' ошибок')
		return
	}
	else 
	{
		console.log('Все корректно, отправляем форму')
		var data = serializeForm(formsubmit)
		data.append('action', 'addreview')
		//console.log(Array.from(data.entries()))
		
		var request = new XMLHttpRequest();
	    request.open('POST', 'engine.php');
	    // при изменении состояния запроса        
	    request.addEventListener('readystatechange', function() {
	      
	      if (this.readyState == 4 && this.status == 200) {
	        console.log('Ответ сервера: ' + this.responseText) // ответ одной стройкой
	        var resdata = JSON.parse(this.responseText)
	        // выводим в консоль то, что приехало с сервера, построчно, наглядно )
	        for (var key in resdata) {
	          console.log (key + ' - ' + resdata[key])
	        }
	        modalwindow.classList.toggle ('active')
	        const $mod_inside = document.createElement('div');
	        $mod_inside.classList='modal-inside'
	        if (resdata.error) {
	        	$mod_inside.innerHTML='<p>' + resdata.responseText + '</p>'
	        	modalwindow.append($mod_inside)
	        	setTimeout(()=>{
	        	modalwindow.classList.toggle('active')
	    		}, 3000)
	    		setTimeout(()=>{
		        	reviewform.classList.toggle('active')
		    	}, 4000)
		    	return
	        }
	        else{
	        // если есть данные - закрываем форму, выводим уведомление, добавляем новый отзыв на страницу
	       
		        $mod_inside.innerHTML='<p>Ваш отзыв добавлен. Через секунду он появится на странице.</p>'
		        modalwindow.append($mod_inside)
		        setTimeout(()=>{
		        	modalwindow.classList.toggle('active')
		    	}, 3000)
		        setTimeout(()=>{
		        	reviewform.classList.toggle('active')
		    	}, 4000)
		    	setTimeout(()=>{
		        add_new_review_on_page(resdata)
		    	}, 5000)
	    	}

	      }
	    })
	    
	    request.send(data)

		/*if (response.status == 200) {
			onSuccess(event.target)
			console.log(response.status)
			console.log(response)
		} else {
			console.log(response)
			//onError(error)
		}*/


	}


})


//multiselect
let multiselect_block = document.querySelector(".multiselect_block");
    
    let label = multiselect_block.querySelector(".field_multiselect");
    let select = multiselect_block.querySelector(".field_select");
    var myOptions = select.getElementsByTagName('option');
    var trueOptions=new Array()
    //console.log (myOptions.length)
    let text = label.innerHTML;
        select.addEventListener('click',function(element) {
	    	let selectedOptions = this.selectedOptions;
	    	const target1=element.target;
	    	label.innerHTML = "";
	    	for (let i=0; i<myOptions.length; i++) {
	    		if (trueOptions[i]==1) {
	    			myOptions[i].selected=true
				}
	    		
	    		if (myOptions[i]==target1) {
	    			if (trueOptions[i] == 1) 
	    			{
	    				myOptions[i].selected=false
	    				trueOptions[i]=0
	    			}
	    			else {
	    			myOptions[i].selected=true
	    			trueOptions[i]=1
	    			}
	    		}

	    		if (myOptions[i].selected==true) {
		    		let button = document.createElement("button");
	                button.type = "button";
	                button.className = "btn_multiselect";
	                button.textContent = myOptions[i].value;
	                button.onclick = () => {
	                    //console.log (i + ' - ' + trueOptions[i])
	                    myOptions[i].selected = false;
	                    trueOptions[i]=0
	                    button.remove();
	                }
	                label.append(button)
	    		}

	    		
	    	}
	    	if (label.innerHTML==='') label.innerHTML='Категории'
	    	validate_multiple()
	    	//console.log(myOptions.selected.length)
	    })
	

// --------------- functions ---------------------------
const validateEmail = (email) => {
  return email.match(
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  );
};

function form_validate (my_checkform) {
var curInput1=my_checkform.querySelector('.field_input')
var elControl=my_checkform.querySelector('.field_control')
var elLabel=my_checkform.querySelector('.field_label')
var elContainer=my_checkform.querySelector('.field_container')
//console.log(curInput1.value)
//console.log('type = ' + curInput1.type)

if ((curInput1.type=='text') || (curInput1.type=='textarea')){
	if (!curInput1.value.length) {
		if (!elControl.classList.contains('alert')) elControl.classList.add('alert')
		elLabel.classList.remove('checked')
		elContainer.classList.remove('checked')
		elControl.innerHTML='* Поле не должно быть пустым'
		errors+=1
	}
	else {
		if (elControl.classList.contains('alert')) elControl.classList.remove('alert')
			elLabel.classList.add('checked')
			elContainer.classList.add('checked')
			elControl.innerHTML='';
		}
	}
if (curInput1.type=='email'){
	if (!curInput1.value.length) {
		if (!elControl.classList.contains('alert')) elControl.classList.add('alert')
		elLabel.classList.remove('checked')
		elContainer.classList.remove('checked')
		if (!elLabel.classList.contains('empty')) elLabel.classList.add('empty')
		elControl.innerHTML='* Поле не должно быть пустым'
		errors+=1
	}
	else {
		if (elLabel.classList.contains('empty')) elLabel.classList.remove('empty')
		if (!validateEmail(curInput1.value)) {
			elControl.innerHTML='* Некорректно введен e-mail адрес'
			if (!elControl.classList.contains('alert')) elControl.classList.add('alert')
			errors+=1
			return
		}
		if (elControl.classList.contains('alert')) elControl.classList.remove('alert')
			elLabel.classList.add('checked')
			elContainer.classList.add('checked')
		}
	}
}
function validate_multiple () {
	var mult_error=document.querySelector('.error_text.field_control')
	if (!trueOptions.includes(1)) {
		mult_error.classList.add('alert')
		mult_error.innerHTML='* не выбрано ни одной категории'
		errors+=1
	}
	else {
		mult_error.innerHTML=''
		mult_error.classList.remove('alert')

	}
}
function serializeForm(formNode) {
	return new FormData(formNode)
}

function onError(error) {
  alert(error.message)
}
function onSuccess(formNode) {
  alert('Ваша заявка отправлена!')
  formNode.classList.toggle('active')
}
function add_new_review_on_page(resdata) {
	const reviewsList=document.querySelector('.reviews-wraper')
	const $new_rev = document.createElement('div');
	$new_rev.className='reviews-item new'
	var outer='<h3>'+resdata['name']+'</h3>'
	outer+='<div class="reviews-item-subtitle">'+resdata['surname']+' '+resdata['middle_name']+'</div>'
	outer+='<div class="reviews-category">Категория отзыва: <span>'
	resdata.cats=resdata.cats.join(', ')
	outer+=resdata.cats
	outer+='</span></div>'
	outer+='<div class="reviews-email"><a href="#">'+resdata['email']+'</a></div>'
	outer+='<p>'+resdata['message']+'</p>'
	$new_rev.innerHTML=outer
	reviewsList.append($new_rev)
}