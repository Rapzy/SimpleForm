$(document).ready(function(){
	//sign-up tabs
	if($('.list-tabs').length) {
		$('.list-tabs a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
			$('.list-tabs a').parent("li").removeClass("active");
			$(this).parent("li").addClass("active");
		})
	}

	//Категории рубрик
	function formatState (state) {
		if (!state.id) {
		  return state.text;
		}
		if(state.parents.length > 0){
			var parentHeadings = "";
			for (var i = 0; i < state.parents.length; i++) {
				parentHeadings += state.parents[i]['name'];
				if(i+1 != state.parents.length){
					parentHeadings += ',&nbsp;';
				}
			}
		}
			var $state = $(
			  '<span>' + state.text + ' <span class="parents">/'+parentHeadings+'</span></span>'
			);
		return $state;
	};
	
	//select2
	if($(".select2").length) {
		$(".select2").select2({
			language: "ru",
			placeholder: "Выберите рубрику",
			closeOnSelect: true,
			multiple: false,
			templateResult: formatState,
			minimumInputLength: 3,
			ajax: {
			    url: 'script.php',
			    type:'GET',
			    datatype:'json',
			    data:function (params) {
			    	var query = {
			        	search: params.term,
			        	select:$(this).attr('id')
			  		}
			    	return query;
			    },
			    processResults: function (data) {
				  return {
				    "results": JSON.parse(data),
				    "pagination":{
				    	"more":false
				    }
				  }
				}
			}
		});
	}
	$('.select2-town').select2({
		placeholder: "Выберите город"
	});
	$('.btn[type=submit]').click(function(event){
		event.preventDefault();
		$form = $(this).closest('form');
		$tel = $form.find('[type=tel]');
		$email = $form.find('[type=email]');
		var validtel = CheckValidate($tel, /^\d[\d\(\)\ -]{4,14}\d$/);
		var validmail = CheckValidate($email, /^[\w-\.]+@[\w-]+\.[a-z]{2,4}$/i);
		if(validtel && validmail){
			SendRequest($form.serialize(), 'script.php', $form.attr('name'));
			$form.trigger('reset');
			$('.select2').val(null).trigger('change');
		}
		else return;
		function CheckValidate($input,reg){
			if(!reg.test($input.val())){
				$input.addClass('error');
				$input.siblings('.form-validator').removeClass('d-none');
				$input.siblings('.form-validator').addClass('d-block');
				return false;
			}
			return true;
		}

	});

	function SendRequest(data,url,name){
		var xhr = new XMLHttpRequest();
		data+='&form='+name;
		xhr.open("GET", 'script.php?'+data, true);
		xhr.send();
		xhr.addEventListener("load", function(event) {
			alert('Вы успешно зарегистрировались');
		});
	}
});