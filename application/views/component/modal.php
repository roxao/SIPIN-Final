<div id="popup_box" style="display: none;">
	<div class="box_layout">
		<div class="box_title">
			<!-- ||||  TITLE DINAMIS TERGANTUNG CONTENT YANG DITAMPILKAN |||| -->
			<h1> $$DINAMIS TITLE$$ </h1>
			<!-- ||||  END |||| -->
			<div class="box_btn_close"><img src="assets/cancel.svg" /></div>

		</div>
		<div class="box_content">
			<!-- |||| Insert Content Modal ||||-->
				<!-- Content  Log In -->
				<form id="login_frame" class="content_frame" action="<?php base_url()?>SipinHome/login" method="post" style="display: none; padding: 0 30px; width: 300px">
					<label class="input_class">
						<input type="text" id="username" name="username" autocomplete="off" placeholder="Username/Email/No. IIN:" required>
					</label>
					<label class="input_class">
						<input type="password" id="password" name="password" autocomplete="off" placeholder="Password:" required>
					</label>	
					<button type="submit" class="login btn_modal_flat" style="width: 100%; margin: 10px 0">Masuk</button>
					<div class="clearfix" style="padding: 10px 0; border-top: 1px solid #ddd">
						<div class="float_left" action="modal_pupop" data-id="forgot_frame" style="line-height: 32px"><small><i>Lupa Password?</i></small></div>
						<div class="float_right btn_modal_flat_line"><a href="#" action="modal_pupop" data-id="#register_frame">DAFTAR</a></div>
					</div>
				</form>

				<!-- Content  Register -->
				<script src='https://www.google.com/recaptcha/api.js'></script>
				<form id="register_frame" class="content_frame" action="<?base_url()?>SipinHome/register" method="post" style="display: none">
					<label class="input_class">
						<input type="text" id="nama" name="nama" autocomplete="off" placeholder="Nama Lengkap:" required>
					</label>
					<label class="input_class">
						<input type="text" id="username" name="username" autocomplete="off" placeholder="Username:" required>
					</label>
					<label class="input_class">
						<input type="text" id="iin_num" name="iin_num" autocomplete="off" placeholder="Nomor IIN: " required>
						<small><span style="color:red">*</span>Jika sudah memiliki IIN</small>
					</label>
					<label class="input_class">
						<input type="email" id="email" name="email" autocomplete="off" placeholder="E-mail:" required>
					</label>
					<label class="input_class">
						<input type="password" id="password" name="password" autocomplete="off" placeholder="Kata Sandi:" required>
					</label>
					<label class="input_class">
						<input type="password" id="password_confirm" name="password_confirm" autocomplete="off" placeholder="Ulang Kata Sandi:" required>
					</label>
					<div class="g-recaptcha" style="background: #ddd;width: 250px;display: table;vertical-align: middle;text-align: center;color:#aaa;font-size: 28px;margin: 0 auto;padding: 20px;" data-sitekey="6LerwS0UAAAAAF27mC7K-XWf-IYBMyrZcTKbhEeB" > </div>
					<button type="submit" class="login btn_modal_flat" style="width: 100%; margin: 10px 0">DAFTAR</button>
				</form>


				<!-- Content  Forgot Password -->
				<form id="forgot_frame" class="content_frame" action="<?base_url()?>SipinHome/forgot_password" method="post" style="display: none">
					<div class="box_content_note">Silahkan masukan alamat E-mail anda dan kami akan mengirimkan alamat tautan untuk melakukan pengaturan untuk kata sandi anda.</div>
					<div class="panel_edit_input">
						<label class="panel_label">E-mail / Username / Nomor IIN:</label>
						<input class="panel_input" type="email" id="username_forgot" autocomplete="off" style="width: 250px">
					</div>
					<div class="panel_edit_input panel_edit_button">
						<label class="panel_label"></label>
						<button class="panel_button_ok">Lanjutkan</button>
					</div>
				</form>

				<!-- Content  Log Out -->
				<form id="forgot_frames" class="content_frame" style="display: none">
					<div class="panel_edit_input">
						<label class="panel_label">Alamat E-mail:</label>
						<input class="panel_input" type="email" id="username" autocomplete="off" style="width: 250px">
					</div>
					<div class="panel_edit_input panel_edit_button">
						<a href="localhost/sipin/logout">Keluar</a>
					</div>
				</form>

			<!-- ||||  END |||| -->

		</div>
	</div>
</div>






