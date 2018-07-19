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
		var parentHeadings = ["Родительская категория1", "Родительская категория2"]; //массив категорий для вывода в селект

		if (!state.id) {
		  return state.text;
		}
		var $state = $(
		  '<span>' + state.text + ' <span class="parents">/ ' + parentHeadings[0] + ',&nbsp;' + parentHeadings[1] + '</span>' + '</span>'
		);
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
		});
	}
});
