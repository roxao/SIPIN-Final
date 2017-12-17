<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<title></title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />	
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/style.css"/>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.slides.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/script.min.js"></script>

</head>
<body>
	
	<header>
		<nav class="clearfix">
			<div class="nav-menu float_left"><div>MENU</div></div>
			<div class="nav-logo float_left"><a href="<?php echo base_url();?>"><img src="<?php echo base_url();?>/assets/logo.png" alt="SIPIN"></a></div>
			
			<ul class="nav-list float_left nav-list-menu">

				<li class="nav-link"><a href="<?php echo base_url();?>">Halaman Depan</a></li>
				<?php if($this->session->flashdata('validasi-login') != '') {?>
				<li class="nav-link parent"><a href="<?php echo base_url();?>">Layanan IIN</a>
					<ul>
						<?php if ($this->session->flashdata('validasi-menu') != 'Pengajuan Baru') {?>
						<li class="nav-link"><a href="<?php echo base_url();?>submit-iin">Penerbitan IIN Baru</a>
						<?php } else { ?>
						<li class="nav-link"><a href="<?php echo base_url();?>extend-iin">Pengawasan IIN Lama</a>
						<?php } ?>
					</ul>
				</li>
				<?php } ?>
				<li class="nav-link parent"><a href="<?php echo base_url();?>">Informasi IIN</a>
					<ul>
						<li class="nav-link"><a href="<?php echo base_url();?>">Daftar penerima IIN</a>
						<li class="nav-link"><a href="<?php echo base_url();?>SipinHome/file_iso_7812">File ISO 7812</a>
						<li class="nav-link"><a href="<?php echo base_url();?>">Hasil Survey</a>
					</ul>
				</li>
				<li class="nav-link"><a href="<?php echo base_url();?>">Hubungi Kami</a></li>
			</ul>
			<ul class="nav-list float_right" style="padding-right: 20px">
				<?php if($this->session->flashdata('validasi-login') == '') {?>
				<li class="nav-sess"><a href="<?php echo base_url();?>" class="open_modal" action="login">Masuk</a></li>
				<li class="nav-sess register"><a href="<?php echo base_url();?>" class="open_modal" action="register">Daftar</a></li>
				<?php } else { ?>
				<li class="nav-notif"><a href="<?php echo base_url();?>">Notifikasi <span>2</span></a>
					<ul class="box_notif">
						<li class="false"><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tempore, facilis.</a></li>
						<li class="false"><a href="#">Ipsum vitae quos at esse cumque obcaecati ullam temporibus ex eveniet totam</a></li>
						<li class="true"><a href="#">Similique quae nisi, quibusdam recusandae accusantium non consectetur dignissimos </a></li>
						<li class="true"><a href="#">Eius saepe expedita, eum dolor nostrum aspernatur voluptas quo eaque aperiam error </a></li>
						<li class="true"><a href="#">Magnam consectetur fugit recusandae tenetur ipsum cupiditate ipsam inventore dolor,</a></li>
					</ul>
				</li>
				<li class="nav-sess"><a href="<?php echo base_url();?>">Keluar</a></li>
				<?php } ?>
			</ul>
		</nav>
	</header>

<script>
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
	  
</script>
	<?php //$this->load->view('component/modal') ?>
