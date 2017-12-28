	<div style="margin-top: 54px">
		<!-- SLIDESHOW -->

		<ul id="slideshow">
			<?php foreach ($banner as $key => $data) {
					if (empty(parse_url($data['url'])['scheme']))$data['url'] = 'http://' . ltrim($data['url']);
				?>
				<li class="item_slideshow" style="background-image: url(<?=$data['path']?>);">
					
					<div class="item_slideshow_caption">
						<a href="<?=$data['url']?>">
							<h1><?=$data['title']?></h1>
							<h2><?=$data['text']?></h2>
						</a>
					</div>
					
				</li>
			<?php } ?>
		</ul>
		<ul id="slideshow-control">
			<li></li>
			<li></li>
			<li style="width: 30px; background: #555"></li>
		</ul>

		<script>
			var slide_option = {
				target 	 : $('#slideshow'),
				interval : 3, // second units
				fadeTime : 'slow' // fast, 400, slow
			}
			carousel(slide_option);
			
		</script>

		<div class="content_welcome_world container_article" >	
			<article class="content_hello_world" style="margin-bottom: 50px" >
				<div class="welcome_title">
					<h1>Selamat Datang di <b>SIPIN</b></h1>
				</div>
				<img src="assets/logo.png" alt="" width="200px" >
				<div>
				Silakan mengisi form di bawah ini untuk melakukan permohonan IIN baru. Sebelum anda mengirim surat ini melalui sistem<br/><br/></div>
				<div style="margin-bottom: 50px" ><a href="<?php echo base_url();?>informasi-iin/layanan_issuer_identification_number_iin" class="next_home_article">Selengkapnya</a></div>
			</article>

			<div class="home-submit-iin-intro">
				<article class="home-submit-iin-intro-item green-item">
					
					<div>
						<h1>PENERBITAN IIN BARU</h1>
						Silakan mengisi form di bawah ini untuk melakukan permohonan IIN baru. Sebelum anda mengirim surat ini melalui sistem
						<a href="#" onclick="checkDaftar('new')">
							<span>DAFTAR</span>
						</a>
					</div>
				</article>
				<article class="home-submit-iin-intro-item blue-item">
					<div>
						<h1>PENGAWASAN IIN LAMA</h1>
						Silakan mengisi form di bawah ini untuk melakukan permohonan IIN baru. Sebelum anda mengirim surat ini melalui sistem
						<a href="#" onclick="checkDaftar('extend')">
							<span>DAFTAR</span>
						</a>
					</div>
				</article>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function checkDaftar(type){
		var isLogin = '<?php echo $this->session->userdata('status') ?>';

		var isContinue = false;
		var url="SipinHome/submit_application/";

		if(!isLogin || isLogin != 'login'){
			url="login";
			swal({
				  title: 'Pesan',
				  text: "Anda harus login terlebih dahulu",
				  type: 'warning',
				  confirmButtonColor: '#3085d6',
				  confirmButtonText: 'Login'
				}).then((result) => {
				  if (result.value) {
				    window.location.replace(url);
				  }
				});
		} else {
			var isHavingIIN = '<?php echo $this->session->userdata('have_iin') ?>';
			switch(type){
			case "new":
			if(isHavingIIN=='Y'){
				url = '#';
				swal('Pesan', 'Anda telah memiliki IIN dan tidak dapat melakukan pendaftaran IIN baru', 'error');
			}
				break;
			case "extend":
			if(isHavingIIN!='Y'){
				url = '#';
				swal('Pesan', 'Anda belum memiliki IIN silahkan melakukan pendaftaran IIN baru', 'error');
			}
				break;
			}
			window.location.replace(url);
		}
		}
	</script>