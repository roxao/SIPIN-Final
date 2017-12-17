<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<title><?= $web_title ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/style.css"/>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-3.2.1.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.slides.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/main.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.min.js')?>"></script>


</head>
<body>
	<header class="<?=($this->input->get('header')=='hidden' ? 'hidden' : '')?>">
		<nav class="clearfix">
			<div class="nav-menu float_left"><div>MENU</div></div>
			<div class="nav-logo float_left"><a href="<?php echo base_url();?>"><img src="<?php echo base_url();?>/assets/logo.png" alt="SIPIN"></a></div>

			<ul class="nav-list float_left nav-list-menu">
				<li class="nav-link"><a href="<?php echo base_url();?>">Halaman Depan</a></li>
				<?php if($this->session->userdata('status') == "login") {?>
				<li class="nav-link parent"><a>Layanan IIN</a>
					<ul>
						<?php if ($this->session->userdata('have_iin') == "Y") {?>
						<li class="nav-link"><a href="<?php echo base_url();?>Layanan-IIN">Pengawasan IIN Lama</a>
						<?php } else { ?>
						<li class="nav-link"><a href="<?php echo base_url();?>Layanan-IIN">Penerbitan IIN Baru</a>
						<?php } ?>
					</ul>
				</li>
				<?php } ?>
				<li class="nav-link parent"><a>Informasi IIN</a>
					<ul>
						<li class="nav-link"><a href="<?php echo base_url('informasi-iin/daftar-penerima-iin');?>">Daftar penerima IIN</a>
						<li class="nav-link"><a href="<?= base_url('informasi-iin/file_iso_7812')?>">File ISO 7812</a>
						<li class="nav-link"><a href="<?= base_url('survey/hasil-survei')?>">Hasil Survey</a>
						<?php for ($i=0; $i < count($cms_name) ; $i++) { ?>
							<li class="nav-link"><a href="<?= base_url('informasi-iin/'. $cms_name[$i]['url']) ?>"><?=$cms_name[$i]['title']?></a></li>
						<?php } ?>

						<?php if($this->session->userdata('status') == "login") {?>
							<li class="nav-link"><a href="<?= base_url('informasi-iin/pengaduan')?>">Pengaduan</a></li>
						<?php } ?>

					</ul>
				</li>
				<li class="nav-link"><a href="<?php echo base_url('contact-us');?>">Hubungi Kami</a></li>
			</ul>
			<ul class="nav-list float_right" style="padding-right: 20px">
				<?php if($this->session->userdata('status') != "login") {?>
				<li class="nav-sess"><a href="<?php echo base_url();?>" class="open_modal" action="login">Masuk</a></li>
				<li class="nav-sess register"><a href="<?php echo base_url();?>" class="open_modal" action="register">Daftar</a></li>

				<?php } else { ?>
				<li class="nav-sess">Hi, <a href="<?php echo base_url('user-detail');?>"><?=$this->session->userdata('username')?></a>
				</li>
				<li class="nav-notif"><a href="" style="pointer-events: none">Notifikasi <span id='unreadCount'></span></a>
					<ul class="box_notif">
					</ul>
				</li>
				<li class="nav-sess"><a href="<?php echo base_url();?>SipinHome/logout">Keluar</a></li>
				<?php } ?>
			</ul>
		</nav>
	</header>

	<script>
		function getNotification(){
			var baseUrl = '<?=base_url('Notification')?>';
			var baseUrlUser = '<?=base_url()?>';
			var unreadCount=0;
			$.ajax({
				url: baseUrl + "/getNotification",
				type: "GET",
				dataType: 'json',
				success: function (data) {
							$("#unreadCount").val(0);

					        for(var notif in data){(function(row){


					        	var state = row.Status;

					        	if(state != 'INACTIVE'){
					        		unreadCount = unreadCount + 1;
					        	}

					        	var linkId="linkNotif"+row.id_notification;

					        	var notifBuilder=[];

					        	var urlNotif = baseUrl + row.notification_url;

					        	notifBuilder.push('<li class="notif '+ row.Status + '">',
					        					   '<a id="'+  linkId +'" href="'+ baseUrlUser+row.notification_url +'">'+ row.message+'</a>',
					        					   '</li>');

					        	$(".box_notif").append(notifBuilder.join(''));

					        	$("#"+linkId).click(function(){
					        		//update status to INACTIVE
					        		$.ajax({
					        			url: baseUrl + "/updateNotificationStatus?notifId="+row.id_notification,
					        			type: "GET",
					        			dataType: 'json',
					        			success: function(data){
					        				if(state != INACTIVE){
					        					$("#unreadCount").html(("#unreadCount").val()-1);
					        				}
					        			}
					        		});
					        		// swal('Pesan', row.message, 'success');
					        	});


					        })(data[notif]);
					    }

					    $("#unreadCount").html(unreadCount);

					}
				});
		}
	</script>
<script>
	$(document).ready(function() {

		$('.nav-menu').on('click', function(event) {
			event.preventDefault();
			if($(this).hasClass('active')){
				$(this).removeClass('active')
			}else{
				$(this).addClass('active')
			};
			$('.nav-list-menu').slideToggle('fast', function() {
				$(this).addClass('active');
			});
		});
		$('.nav-link.parent').on('click', function(event) {
			$('.nav-link.parent').not(this).children('ul').slideUp();
			$(this).children('ul').slideToggle('fast');
			// return false;
		});
		$('.open_modal').on('click', function(event) {
			event.preventDefault();
			action = $(this).attr('action');
			$('#show_popup').remove();
			$('body').append('<span id="show_popup"><div class="frame_popup"></div><div class="popup_box"><div class="content_popup"></div></div></div>');
			$(".content_popup").load("<?php echo base_url() ?>/user/"+$(this).attr('action'));
		});

		$('.open_modal').on('click', function(event) {
			event.preventDefault();
			action = $(this).attr('action');
			$('#show_popup').remove();
			$('body').append('<span id="show_popup"><div class="frame_popup"></div><div class="popup_box"><div class="content_popup"></div></div></div>');
			$(".content_popup").load("<?php echo base_url() ?>/user/"+$(this).attr('action'));
		});
		// show_popup();
		function show_popup(){
		  	$('body').append('<div class="a-popup-frame"></div>');
		  	$(".a-popup-frame").load("<?php echo base_url() ?>/SipinHome/modal_popup/");
		  	$('.a-box-close').on('click', function(event) {
				event.preventDefault();
				$(".a-popup-frame").remove();
			});
		  }
	});

	getNotification();

</script>

	<?php //$this->load->view('component/modal') ?>
