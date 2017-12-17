
$(document).ready(function() {
    var stickyNav = function() {
        var scrollTop = $(window).scrollTop();
        if (scrollTop > 200) {
            $('nav .top_nav').slideUp('400');
            $('.stickyNavShow').fadeIn('400');
        } else {
            $('nav .top_nav').slideDown('400');
            $('.stickyNavShow').fadeOut('400');
        }
    };
    stickyNav();
    $(window).scroll(function() {
        stickyNav();
    });
    stepSet();
    $(window).resize(function() {
        stepSet()
        myFunction('.box_layout');
    });

    function stepSet() {
        stepSize = $(window).width() > 1100 ? 5 : $(window).width() > 650 ? 4 : $(window).width() > 550 ? 3 : 2;
        for (i = 0, y = false; i < $('#section_progress>li').length; i++) {

            $('#section_progress li').width($('#section_progress').width() / stepSize - 20);
            $('#section_progress').children().eq(i).removeClass('step_l step_r step_d');
            $('#section_progress').children().eq(i).addClass(y == false ? " step_l" : " step_r");
            $('#section_progress').children().eq(i).addClass((i + 1) % stepSize == 0 ? (y = !y, " step_d") : "");
        }
    }
    $('.btn_back').click(function(event) {
        $('html, body').animate({
            scrollTop: 0
        }, 2000);
        $(this).parents(".page section").fadeOut('fast');
        $(this).parents(".page section").prev().addClass('step_active')
        $(this).parents(".page section").prev().fadeIn('400');
    });

    myFunction('.box_layout');

    function myFunction(t) {
        var x = $(window).width() / 2 - $(t).width() / 2;
        var y = $(window).height() / 2 - $(t).height() / 2;
        $(t).css('margin-left', x + 'px');
        $(t).css('margin-top', y + 'px');
    }

    $('a[action="modal_pupop"]').click(function(event) {
        var target = $(this).attr('data-id');
        $('#popup_box').fadeIn();
        $('.content_frame').hide();
        $(target).show();
        $('.box_layout').show();
        myFunction(".box_layout");
        return false;
    });
    $('.box_btn_close').click(function(event) {
        $('#popup_box').fadeOut();
        $('.box_layout').hide();
        $('.content_frame').hide();
    });


    $("#section_progress>li").click(function(event) {
        if ($(this).hasClass('COMPLETED') || $(this).hasClass('PENDING')) {
            var stepOpen = $(this).attr('stepId');
            $("#section_progress>li").removeClass('step_active');
            $(this).addClass('step_active');
            $(".page section").fadeOut('fast');
            $("section[section-id='" + stepOpen + "']").fadeIn('400');
        }
    });

    $(".inputBox2 input").focus(function() {
        $(this).parent().addClass('focus');
    });
    $(".inputBox2 input").blur(function() {
        $(this).parent().removeClass('focus');
    });

    $("#dashboard_menu li").click(function(event) {
        var getId = $(this).attr('data-id');
        $('.dashboard_content').fadeOut();
        $('.dashboard_content[main-index="' + getId + '"]').fadeIn();
    });
    $("#tableInbox .btn_inbox_edit").click(function(event) {
        var getId = $(this).attr('data-id');
        $('#modal_content').fadeIn();
        $('section[modal-content="' + getId + '"]').fadeIn();
    });
    $(".close_modal").click(function(event) {
        $('#modal_content > section').fadeOut();
        $('#modal_content').fadeOut();
        $('.slide_comment').slideUp();
        $('.verify_section').slideDown();
    });
    $('.btn_reject').click(function(event) {
        $('.slide_comment').slideDown();
        $('.verify_section').slideUp();
    });
    $(".btn_send").click(function(event) {
        $('#modal_content > section').fadeOut();
        $('#modal_content').fadeOut();
        $('.slide_comment').slideUp();
        $('.verify_section').slideDown();
    });
    $('.btn_cancel_comment').click(function(event) {
        $('.verify_section').slideDown();
        $('.slide_comment').slideUp();
    });
    $('.costum_select').click(function(event) {
        $('.costum_select ul').slideDown();
    });
    $(".show-rev-assess-modal").click(function(event) {
        $('.modal-form-rev-assess').fadeIn('slow');
    });
    $(".btn-close-modal-rev-assess").click(function(event) {
        $('.modal-form-rev-assess').fadeOut('slow');
    });

    $.set_table_list = function(x,y){
  		$('#'+x).append('<ul class="main_pagination"><li class="listjsprev"><</li><ul class="pagination"></ul><li class="listjsnext">></li></ul>');
  		$('#filtertable .clickfilter').click(function(event){$('.filtertable').slideToggle()});
  		var datasort=[];
  		$('#'+ x + " thead tr [data-sort]").map(function(){return datasort.push($(this).attr('data-sort'))});
      console.log(x);
  		var SortTable = new List(x,{valueNames:datasort,page: y,pagination: true});
  		$('.listjsnext').on('click',function(){var list=$('.pagination').find('li');$.each(list,function(position,element){if($(element).is('.active')){$(list[position+1]).trigger('click')}})});
  		$('.listjsprev').on('click',function(){var list=$('.pagination').find('li');$.each(list,function(position,element){if($(element).is('.active')){$(list[position-1]).trigger('click')}})});
  	}

    function goBack() {
        window.history.back();
    }
    $('[type=date]').datepicker({
        dateFormat: "yy/mm/dd",
        setDate: new Date
    }).prop('type', 'text');
    $('.nav-notif').on('click', function(){
      if($(this).hasClass('active')) $(this).removeClass('active');
      else $(this).addClass('active');
    })
});



function carousel(data) {
    a = data['target'];
    b = data['interval'] * 1000;
    c = data['fadeTime'];
    d = a.children('.item_slideshow');
    i = 0;

	c_dot(a);

    run_();

    a.hover(function() {
        clearInterval(n)
    }, function() {
        run_()
    });

    function run_() {
        n = setInterval(function() {
            run_fx();
        }, b)
    }

    function run_fx(u, w) {
    	if(w == true) i = u;
    	if(i == d.length) i = 0;
    	if(i < 0 ) i = ((d.length) -1);
        d.fadeOut();
        d.eq(i).fadeIn(c);
        z.removeClass('active');
        z.eq(i).addClass('active');
        w = false;
		i++;
    }

    function c_dot(x) {
    	x.append($('<div>').addClass('slide-control'));
    	y = $('.slide-control');
    	for (var i = d.length - 1; i >= 0; i--) {
    		y.append($('<div>').addClass('item-slide-control'));
    	}
    	create_arrow(x);
    	z = y.children('.item-slide-control');
    	z.on('click', function() {
    		v = z.index(this);
    		run_fx(v, true)
    	});
    	d.hide();
   		d.eq(i).show();
   		z.eq(i).addClass('active');

    }

    function create_arrow(x){
    	x.append($('<div>').addClass('slide-nav-left'));
    	x.append($('<div>').addClass('slide-nav-right'));
    	$('.slide-nav-left').on('click', function() {
    		run_fx(i-2, true);
    	});
    	$('.slide-nav-right').on('click', function() {
    		run_fx(i++, true);
    	});
    }
};
