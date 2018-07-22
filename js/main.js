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
});