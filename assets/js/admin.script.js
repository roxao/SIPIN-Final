$(document).ready(function() {
	var base_url = $('#base_url').val();
	$('.get_process').click(function(event) {
  		if($(this).attr('data-step')!=null) $.get_approval($(this).attr('data-id'), $(this).attr('data-id-status'),  $(this).attr('data-step').toLowerCase(), $(this).attr('data-status'));
	  });

    $.get_approval = function (id, id_status, step, status) {
		$("body").append("<div id='modal_approval' style='display:none'></div>");
		$("#modal_approval").fadeIn('fast', function() {
			$(this).load(base_url + "/set_view/approval/modal_approval", function () {
				$(".box-modal").fadeIn('fast', function() {

					$.ajax({ url: base_url + "/get_app_data", type: "POST", data: {'id_app': id, 'id_status': id_status, 'step': step}, dataType: 'json',
				        success: function (data) {
			        		respon=data;
			        		console.log(respon);
							$(".modal-content").load(base_url + "/set_view/approval/"+step, function () {
								$(".modal-title").html(status);
								$("#section-approval").slideDown('fast', function(){
									// $.set_modal_position();
								});
								
							});
						},
						error: function(data){
							console.log(data);
						}
					})
				})
			});
		});
    }

    get_excel = function(m){
		f='';t='';
		$($('#filtertable input:checked')).each(function(){f=f+this.value+'||';t=t+this.title+'||';});
		f=f.substring(0,f.length-2);
		t=t.substring(0,t.length-2);
    	window.location.href = (base_url + "/excel?f="+f+"&t="+t+"&m="+m);
    }

    get_iin_form = function(m){
    	$('.modal-form button').click();
    }

	$.set_value_data = function(){
		app=respon.application;
		doc_pay=respon.doc_pay;
		assess_role=respon.assessment_roles;
		doc=respon.revdoc_user;
		$("[name=id_application_status]").val(app.id_application_status);
		$("[name=id_application]").val(app.id_application);
		$("[name=created_by]").val(app.created_by);
		$("[name=id_user_iin]").val(app.id_user);
		$("[name=application_type]").val(app.application_type);
	}
	$.base_config_approval = function(){
		var formatted = $.datepicker.formatDate("dd M yy", new Date());
   		if($('[type=date]').length !== 0)
			$('[type=date]').prop({type: 'text'}).val(formatted).datepicker({dateFormat: "dd M yy", setDate: new Date()});
		else
			$('[type=date]').prop({type: 'text'}).datepicker({dateFormat: "dd M yy", setDate: new Date()});
		$('#btn-approval').on('click', function(event) {$('[name=submit_approval]').click()});
		$('#btn-revision-send').on('click', function(event) {$('[name=submit_revision]').click()});
		$('#btn-revision').on('click', function(event) {
			$('.content-approval').hide();$('.content-revision').slideDown();
			$('#section-approval').hide();$('#section-revision').slideDown(function(){});
			// $('#section-approval').hide();$('#section-revision').slideDown(function(){$.set_modal_position()});
			});
		$('#btn-revision-back').on('click', function(event) {
			$('.content-approval').slideDown();$('.content-revision').hide();
			// $('#section-approval').slideDown(function(){$.set_modal_position()});$('#section-revision').hide();
			$('#section-approval').slideDown(function(){});$('#section-revision').hide();
			});
	}
	$.config_file_type = function(e){
		$("[type=file]").prop('accept','doc,docx, application/pdf, image/*');
		$("[type=file]").change(function() {
			if(this.files[0].size > ((10*1024)*1024)){
				alert('Maksimum file yang harus di upload adalah 10 MB');
				$(this).val('');
			}
		    var fileName = $(this).val().split('/').pop().split('\\').pop();
		    $(this).next().next().html(fileName);
		});
	}

	$.set_assessment_roles_on_select = function(){
		for (var j = 0; j < assess_role.length; j++) {
			var select_roles = (j == 0 ? 'selected' : null );
			$('#a_roles').append($('<option>', {value: assess_role[j].id_assessment_team_title, text: assess_role[j].title}));
		}
	}

	$.set_add_upload = function(){
		html  = '<div class="item-upload-v2 clearfix"><label class="input_dashed_file float_left" >';
		html +=	'Pilih Dokumen';
		html +=	'<input name="doc[]" type="file" accept=".doc,.docx,.pdf,.png,.jpg" required/>';
		html +=	'<span>Pilih</span><i class="float_right"></i>';
		html +=	'</label><img fill="#fff" src="'+(base_url.replace("dashboard",""))+'/assets/delete.svg" class="img-del" alt="Hapus" height="16px" width="16px"></div>';
		$('.content-upload').append(html);
			$("[type=file]").change(function() {
		    var fileName = $(this).val().split('/').pop().split('\\').pop();
		    $(this).next().next().html(fileName);
		});
		$('.img-del').on('click', function(event) {
			$(this).parent().remove();
		});	
	}



	$.set_table_list = function(){
		$('#tableInbox').append('<ul class="main_pagination"><li class="listjsprev"><</li><ul class="pagination"></ul><li class="listjsnext">></li></ul>');
		$('#filtertable .clickfilter').click(function(event){$('.filtertable').slideToggle()});
		var datasort=[];
		$(".table_def thead tr [data-sort]").map(function(){return datasort.push($(this).attr('data-sort'))});
		var SortTable = new List('tableInbox',{valueNames:datasort,page: 10,pagination: true});
		$('.listjsnext').on('click',function(){var list=$('.pagination').find('li');$.each(list,function(position,element){if($(element).is('.active')){$(list[position+1]).trigger('click')}})});
		$('.listjsprev').on('click',function(){var list=$('.pagination').find('li');$.each(list,function(position,element){if($(element).is('.active')){$(list[position-1]).trigger('click')}})});
	}


	$.upload_process = function(file_data, location){
		var form_data = new FormData();
        form_data.append('file', file_data);
        return $.ajax({
            url: base_url + '/upload_image_acceptor/' + location, // point to server-side controller method
            dataType: 'json', // what to expect back from the server
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post'
        })        
	}




	$.set_add_row_survey_form = function(x,y,z){
		html = 	'<div class="the-question">'
				+ '<label>'
					+ '<select name="question_type[]">'
						+ '<option value="RATING"' +(y=='RATING'?'selected':'')+ '>Rating</option>'
						+ '<option value="date"'  +(y=='COMMENT'?'selected':'')+ '>Komentar</option>'
					+ '</select>'
				+ '</label>'
				+ '<label>'
					+ '<input name="question_message[]" type="text" placeholder="Pertanyaan" value="'+z+'">'
				+ '</label>'
				+ '<label class="del-row-survey">'
					+ '<img src="'+ (base_url.replace("dashboard",""))+'/assets/delete.svg' + '" alt="">'
				+ '</label>'
				+ '</div>';
		$(x).append(html);
		$('.del-row-survey').on('click', function(event) {
			$(this).parent().remove();
		});	
	}

	$.set_upload_cra = function(target){
		for (var j = 0; j < doc.length; j++) {
			html  = '<div class="item-upload-v2 clearfix"><label class="input_dashed_file float_left" style="padding-right:10px">';
			html +=	'Pilih Dokumen '+doc[j].display_name;
			html +=	'<input name="doc'+j+'[]" type="file" accept=".doc,.docx,.pdf,.png,.jpg" />';
			html +=	'<span>Pilih</span><i class="float_right"></i>';
			html +=	'</label></div>';
			$(target).append(html);
				$("[type=file]").change(function() {
			    var fileName = $(this).val().split('/').pop().split('\\').pop();
			    $(this).next().next().html(fileName);
			});
		}		
	}

	$.set_modal_position = function(){
		var x = $(window).width()/2 - $('.box-modal').width()/2;
		var y = $(window).height()/2 - $('.box-modal').height()/2;
		$('.box-modal').animate({ 'marginLeft': (x<0?0:x)+'px', 'marginTop': (y<0?0:y)+'px' }, 100);
	}

	$(function() {    
   		var formatted = $.datepicker.formatDate("dd MM yy", new Date());
   		if($('[type=date]').length !== 0)
			$('[type=date]').prop({type: 'text'}).val(formatted).datepicker({dateFormat: "dd MM yy", setDate: new Date()});
		else
			$('[type=date]').prop({type: 'text'}).datepicker({dateFormat: "dd MM yy", setDate: new Date()});
	});

});
   
function getParameterByName(name, url){
	if(!url) url=window.location.href;
	name = name.replace(/[\[\]]/g,"\\$&");
	var regex= new RegExp("[?&]"+ name + "(=([^&#]*)|&|#|$)"), results=regex.exec(url);
	if(!results) return null;
	if(!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g," "));
}