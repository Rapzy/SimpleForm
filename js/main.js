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
		if(!state.text_parent){
			var $state = $(
			  '<span>' + state.text + '</span>'
			);	
		}
		else {
			var $state = $(
			  '<span>' + state.text + ' <span class="parents">/'+state.text_parent+'</span></span>'
			);
		}
		return $state;
	};
	
	//select2
	if($(".select2").length) {
		$(".select2").select2({
			language: "ru",
			placeholder: "Выберите рубрику",
			closeOnSelect: false,
			multiple: true,
			templateResult: formatState,
			minimumInputLength: 3,
			ajax: {
			    url: 'script.php',
			    type:'GET',
			    datatype:'json',
			    data:function (params) {
			    	var query = {
			        	search: params.term,
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
});