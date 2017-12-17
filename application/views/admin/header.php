<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<title><?=$web_title?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url('favicon.ico')?>" />	
	<link rel="stylesheet" href="<?php echo base_url('assets/main-admin.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/js/swal.css'); ?>">
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-3.2.1.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/admin.script.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.min.js')?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/swal.js'); ?>"></script>
</head>
<body>
	<input type="hidden" id="base_url" value="<?php echo base_url('dashboard'); ?>">
	<header>
		<nav class="clearfix">
			<div class="nav-menu float_left"><div>MENU</div></div>
			<div class="nav-logo float_left"><a href="<?php echo base_url();?>"><img src="<?php echo base_url();?>/assets/logo.png" alt="SIPIN"></a></div>
			<ul class="nav-list float_right" style="padding-right: 20px">
				
				<li class="nav-sess"><span class="nav-welcome">Hai, <b><?php echo $this->session->userdata('admin_username') ?></b></span></li>
				<li class="nav-notif"><a href="#">Notifikasi <span id='unreadCount'></span></a>
					<ul class="box_notif">
						
					</ul>
				</li>
				<li class="nav-sess"><a href="<?php echo base_url('dashboard/user/logout');?>">Keluar</a></li>
			</ul>
		</nav>
	</header>

	<ul id="dashboard_menu">
		<li><a class="ic-adm ic-inbox " href="<?php echo base_url('dashboard') ?>">Dashboard / Inbox</a></li>
		<li><a class="ic-adm ic-submission" href="<?php echo base_url('dashboard/submission') ?>">Penerbitan IIN</a></li>
		<li><a class="ic-adm ic-submission" href="<?php echo base_url('dashboard/extend') ?>">Pengawasan IIN</a></li>
		<li><a class="ic-adm ic-iin " href="<?php echo base_url('dashboard/iinlist') ?>">Penerima IIN</a></li>
		<li><a class="ic-adm ic-report " href="<?php echo base_url('dashboard/report') ?>">Laporan</a></li>
		<li><a class="ic-adm ic-history " href="<?php echo base_url('dashboard/data_entry') ?>">Historical Data Entry</a></li>
		<li><a class="ic-adm ic-inbox " href="<?php echo base_url('dashboard/complaint') ?>">Pengaduan</a></li>
		<?php if($this->session->userdata('admin_role') == 'Super Admin') {?>
		<li><a class="ic-adm ic-setting parent">Pengaturan</a>
			<ul>
				<li><a class="ic-adm" href="<?php echo base_url('dashboard/settings/admin') ?>">Administrator</a></li>
				<li><a class="ic-adm" href="<?php echo base_url('dashboard/settings/cms') ?>">Content Management</a></li>
				<li><a class="ic-adm" href="<?php echo base_url('dashboard/settings/banner') ?>">Content Slideshow</a></li>
				<li><a class="ic-adm" href="<?php echo base_url('dashboard/settings/survey') ?>">Survey</a></li>
				<li><a class="ic-adm" href="<?php echo base_url('dashboard/settings/document_static') ?>">Dokumen Statis</a></li>
				<li><a class="ic-adm" href="<?php echo base_url('dashboard/settings/document_dynamic') ?>">Kelengkapan Dokumen</a></li>
				<li><a class="ic-adm" href="<?php echo base_url('dashboard/settings/assessment') ?>">Tim Assessment</a></li>

			</ul>
		</li>
		<?php } ?>
	</ul>
	<script>
		function getNotification(){
			var baseUrl = <?php echo "'".base_url('Notification')."'"?>;
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
					        					   '<a id="'+  linkId +'" href="#">'+ row.message+'</a>',
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
					        		swal('Pesan', row.message, 'success');					   
					        	});


					        })(data[notif]);
					    }

					    $("#unreadCount").html(unreadCount);	

					}
				});
		};

		$('.nav-menu').on('click', function(event) {
			if($('#dashboard_menu').hasClass('active')){
				$(this).removeClass('active');
				$('#dashboard_menu').removeClass('active')
			} else {
				$(this).addClass('active');
				$('#dashboard_menu').addClass('active')
			}
		});

		getNotification();
	</script>
	
