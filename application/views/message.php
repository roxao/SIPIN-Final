<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/style.css"/>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-3.2.1.min.js"></script>
</head>
<body style="background: #e2e5e6">
	<section id="section-message" class="sheets_paper">
		<div class="message-content">

			<!-- SEMUA IMAGE ICON DIBAWAH DI HIDDEN -->
			<!-- TAMBAHKAN CLASS 'show' SESUAI KONDISI PESAN (SUCCESS, FAILED, atau INFORMATION) -->
			<!-- CONTOH:  -->
			<!-- class="msg-icon" menjadi class="msg-icon show"  -->
			<div class="message-image">
				<img class="msg-icon show" src="<?php echo base_url('assets/checked.svg')?>" alt="">
				<img class="msg-icon" src="<?php echo base_url('assets/cancel-alert.svg')?>" alt="">
				<img class="msg-icon" src="<?php echo base_url('assets/information.svg')?>" alt="">
			</div>
			

			<h1 class="title-message">
				Type Title Message Here</h1>
			<p class="content-message">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore repellat aliquid eos recusandae voluptas nostrum vero mollitia voluptatibus quaerat nulla, ea alias commodi et, libero error quidem ad optio ullam.
			</p>

			<div class="footer-message">
				<a href="#">
					<button class="btn-message-ok">Type Button Text</button>
				</a>
			</div>
		</div>
	</section>	
</body>
</html>

