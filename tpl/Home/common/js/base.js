/*updated 20131216*/
/*call*/
//
$.fn.focusbox=function(setting){
	var $object=this;
	var during=setting.during;
	$object.data("stop", 0);
	$object.data("index", 0);
	$object.data("count", $object.find("a").length);

	$object.mouseenter(function(event) {
		$object.data("stop", 1);
	});
	$object.mouseleave(function(event) {
		$object.data("stop", 0);
	});

	$object.find("a").click(function(event) {
		var i=$object.find("a").index(this);
		$object.change(i);

		return false;
	});

	$object.change=function(index, auto){
		var src=$object.find("a").eq(index).attr("href");

		$object.find(".img").fadeOut(300, function() {
			$(this).css("background-image", "url(" +src+ ")");
			$(this).fadeIn(200);

			$object.find("a").removeClass('current');
			$object.find("a").eq(index).addClass('current');

			$object.data("index", index);
		});
	};

	$object.run=function(){
		if($object.data("stop")==1) {
			return;
		}

		var i=$object.data("index");
		var c=$object.data("count");
		i++;
		if(i>=c){
			i=0;
		}
		this.change(i, true);
	}
	window.focusbox=$object;

	$object.find("a").first().click();
	setInterval("window.focusbox.run()", during);
}

$.fn.imgsilder=function(setting){
	var $object=this;
	var speed=setting.speed;
	var width=$object.find(".img").outerWidth(true);
	$object.find(".wrap").width(($object.find(".img").length+1)*width);

	$object.find("a.narrow.prev").click(function(){
		var $img=$object.find(".img").last().clone();
		$object.find(".wrap").prepend($img);

		$object.find(".wrap").css("left", -1*width);
		$object.find(".wrap").animate({left: 0}, speed, function(){
			$object.find(".img").last().remove();
		});

		return false;
	});
	$object.find("a.narrow.next").click(function(){
		var $img=$object.find(".img").first().clone();
		$object.find(".wrap").append($img);

		$object.find(".wrap").animate({left: -1*width}, speed, function(){
			$object.find(".wrap").css("left", 0);
			$object.find(".img").first().remove();
		});

		return false;
	});

	var stopt=0;
	$object.hover(function() {
		stopt=1;
	}, function() {
		stopt=0;
	});
	setInterval(function(){
		if(stopt==1) return;
		//$object.find("a.narrow.next").click();
	}, 3000);
}

$.fn.conttabbo1 = function() {
	var $object = this;
	var $tabs = $object.find(".tab1 >a");
	var $cnts = $object.find(".cnt1 >.c");

	$tabs.click(function(event) {
		var i = $tabs.index(this);
		$cnts.hide();
		$cnts.eq(i).show();

		$tabs.removeClass('on');
		$(this).addClass('on');

		return false;
	});
	$tabs.first().click();
}

$.fn.conttabbo2 = function() {
	var $object = this;
	var $tabs = $object.find(".tab2 >a");
	var $cnts = $object.find(".cnt2 >.c");

	$tabs.click(function(event) {
		var i = $tabs.index(this);
		$cnts.hide();
		$cnts.eq(i).show();

		$tabs.removeClass('on');
		$(this).addClass('on');

		return false;
	});
	$tabs.first().click();
}

$.fn.placeholder = function() {
	var $object = this;
	var t = $object.attr("value");
	if (t == "") {
		return;
	}
	$object.data("t", t);

	$object.focus(function(event) {
		$object.val("");
	});
	$object.blur(function(event) {
		if ($object.val() == "") {
			$object.val($object.data("t"));
		}
	});
}