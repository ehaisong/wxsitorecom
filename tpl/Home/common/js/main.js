$(function(){
	$("input[type='text']").each(function(){
		var $object=$(this);
		$object.placeholder();
	});

	$(window).scroll(function(event) {
		var t=$(window).scrollTop();
		if(t<500){
			$(".fixbar").hide();
		}else{
			$(".fixbar").show();
		}
	});

	$(".conttabbo1").each(function(){
		$(this).conttabbo1();
	});
	$(".conttabbo2").each(function(){
		$(this).conttabbo2();
	});
});

/*main*/
//

/*call*/
//
function scrollup(up){
	if(up){
		$(".scrollup").find('li:first').clone().appendTo('.scrollup ul');
		$(".scrollup").find('ul').animate({top: -1*$(".scrollup").find('li:first').height()}, 500, "linear", function(){
			$(".scrollup").find('li:first').remove();
			$(".scrollup").find('ul').css("top", 0);
		});
	}else{
		$(".scrollup").find('li:last').clone().prependTo('.scrollup ul');
		$(".scrollup").find('ul').css("top", -1*$(".scrollup").find('li:last').height());
		$(".scrollup").find('ul').animate({top: 0}, 500, "linear", function(){
			$(".scrollup").find('li:last').remove();
		});
	}
}
function setimg1(i, obj){
	$(".pt_img1").find('li').hide();
	$(".pt_img1").find('li').eq(i).show();
	$(".item").removeClass('on');
	$(obj).addClass('on');
	return false;
}