<div class="content-background"></div>
	<header>
	<script src='https://www.google.com/recaptcha/api.js'></script>
		<nav id="wrapper">
			<div id="nav">
				<div class="top_nav  container clearfix">
					<div class="nav_left float_left">
						<div class="nav_logo">
							<img src="<?=base_url()?>assets/logo.png" alt="" class="float_left">
							<div class="float-right">
								<h1>Sistem Informasi Penerbitan Issuer Identification Number (SIPIN)</h1>
								<h2>Badan Standarisasi Nasional</h2>
								<h2>National Standardization Agency of Indonesia</h2>
							</div>
						</div>
					</div>
					<div class="nav_right float_right" style="margin-top: 10px">
						<ul class="user_nav clearfix">

						<?php if ($this->session->flashdata('falidasi-login') == '') {?>
						<li><div id="btn_register" action="btnPopUp" data-id="register_frame">DAFTAR</div></li>
						<li><div href="" id="btn_login" action="btnPopUp"   data-id="login_frame">MASUK</div></li>
						<?php } else {?>  <li><div href="" id="btn_logout" action="btnPopUp"  data-id="login_frame">KELUAR</div<?php }?>

							
						</ul>
					</div>
				</div>
				<div class="bot_nav clearfix">
					<img src="<?=base_url()?>assets/logo.png" alt="" class="stickyNavShow float_left" height="30px" style="margin: 7px; display: none" >
					<ul class="page_nav float_left clearfix">
						<li><a href="index.html">Halaman Depan</a></li>
						<li class="nav_parent"><a href="">Layanan IIN</a>
							<ul>
								<li><a href="iin-new.html"> Daftar Penerbitan IIN baru </a></li>
								<li><a href="iin-publish-lama.html"> Daftar Pengawasan IIN Lama </a></li>
							</ul>
							</li>
						<li class="nav_parent"><a href="">Informasi IIN</a>
							<ul>
								<li><a href="iin-publish.html">Daftar penerima IIN</a></li>
								<li><a href="#"> File ISO 7812</a></li>
								<li><a href="#"> Hasil Survey</a></li>
							</ul>
						</li>
						<li><a href="contact-us.html">Hubungi Kami</a></li>
					</ul>
					<ul class="user_nav float_right stickyNavShow" style="margin-top: 13px; margin-right: 10px; display: none">
						<li><div id="btn_register" action="btnPopUp" data-id="register_frame">DAFTAR</div></li>
						<li><div id="btn_login" action="btnPopUp"   data-id="login_frame">MASUK</div></li>
					</ul>
				</div>
			</div>
		</nav>
	</header>

	

	<div class="page" style="margin-top: 150px">
		<div class="clearfix">
			<ul id="section_progress" style="width: 100%">
				<li stepId="0" class="verifiedStep"><button>Pengajuan Surat Permohonan ke BSN</button></li>
				<li stepId="1" class="verifiedStep"><button>Hasil Verifikasi Status Permohonan</button></li>
				<li stepId="2" class="verifiedStep"><button>Submit Kelengkapan Dokumen</button></li>
				<li stepId="3" class="processStep"><button>Proses Verifikasi dan Validasi</button></li>
				<li stepId="4" class="waitingStep"><button>Konfirmasi Surat Lulus Kelengkapan dan Kode Billing</button></li>
				<li stepId="5" class="waitingStep"><button>Submit Bukti Transfer Pembayaran</button></li>
				<li stepId="6" class="waitingStep"><button>Menerima IIN Baru Berserta Kelengkapan Dokumen</button></li>
				<li stepId="7" class="waitingStep"><button>Assessment Lapangan</button></li>
				<li stepId="8" class="waitingStep"><button>Proses Permohonan ke CRA</button></li>
				<li stepId="9" class="waitingStep"><button>Menerima Konfirmasi Tim Verifikasi Lapangan</button></li>
			</ul>

				
			<section section-id="0" class="section_iin float_right" style="width: 70%;">
				<h1 class="title_iin">Pengajuan Surat Permohonan IIN Baru</h1>
				<!-- Foorm -->
				<form  method="post">
				<p>Silakan mengisi form di bawah ini untuk melakukan permohonan IIN baru. Sebelum anda mengirim surat ini melalui sistem dengan klik button "Kirim", maka sebelumnya Anda harus mengunduh(download) surat permohonan yang sudah diisi yang akan akan digunakan kembali jika status data Anda sudah terverifikasi dan disetujui oleh Sekretariat Layanan.</p>
				<article style="margin: 50px">
					<div class="clearfix">
						<div class="inputBox2 imp float_left" style="width: 65%">
							<label>Lokasi Pengajuan Surat</label>
							<input id="input1" type="text" placeholder="Lokasi Pengajuan Surat"/>
						</div>
						<div class="inputBox2 imp float_right" style="width: 35%">
							<label>Tanggal Pengajuan Surat</label>
							<input id="input1" type="text" placeholder="dd/MM/yyyy"/>
						</div>
					</div>
					<div class="inputBox2 imp">
						<label>Nomor Surat</label>
						<input id="input2" type="text" placeholder="Nomor Surat"/>
					</div>
					<div class="inputBox2 imp">
						<label>Nama Instansi Pemohon</label>
						<input id="input1" type="text" placeholder="Lokasi Pengajuan Surat"/>
					</div>
					<div class="inputBox2 imp">
						<label>E-mail Instansi Pemohon</label>
						<input id="input1" type="text" placeholder="Lokasi Pengajuan Surat"/>
					</div>
					<div class="inputBox2 imp">
						<label>Nomor Telepon Instansi Pemohon</label>
						<input id="input1" type="text" placeholder="Lokasi Pengajuan Surat"/>
					</div>	
					<div class="inputBox2 imp">
						<label>Nama Direktur Utama/Manager/Kepala Divisi Pemohon</label>
						<input id="input1" type="text" placeholder="Lokasi Pengajuan Surat"/>
					</div>

					<div class="inputValidation2">

						<div class="g-recaptcha" style="background: #ddd;width: 250px;display: table;vertical-align: middle;text-align: center;color:#aaa;font-size: 28px;margin: 0 auto;padding: 20px;" data-sitekey="6LerwS0UAAAAAF27mC7K-XWf-IYBMyrZcTKbhEeB" > </div>

						<input type="text" placeholder="Type the character you see ..." style="width: 200px; margin: 10px auto"><br/> <br/><br/><br/>
						
						<div class="clearfix">
							<button class="float_left" style="background: red" >Batal</button>
							
							<button class="float_right" style="background: #01923f" insert>Kirim</button>
							<button class="float_right" style="background: #00a8cf">Download Surat</button>
						</div>
					</div>
				</article>
				<!-- form  -->
				</form>
			</section>

			<!-- Section 2 -->
			<section section-id="1" class="section_iin float_right" style="width: 70%; display:none">
				<h1 class="title_iin">Hasil Verifikasi Status Permohonan</h1>
				<p>Status Permohonan IIN Baru Anda telah di Verifikasi dan telah disetujui. Berikut ini merupakan surat yang diterbitkan oleh Sekretariat Layanan terkait permohonan Anda. Silakan diunduh (download) sebagai bukti untuk bisa melanjutkan permohonan ke tahap selanjutnya.</p>

				<ul class="section_iin_download">
					<li>Informasi Persyaratan Pendaftaran Sponsoring Authority (kode: F.PKS.8.0.2)			<a href="" class="btn_download">Download</a></li>
				</ul>

				<p>Silakan unduh (download) beberapa dokumen berikut dan diunggah (upload) kembali setelah dilengkapi.</p>

				<ul class="section_iin_download">
					<li>1. Term & Condition (kode: F.PKS.8.0.3)												<a href="" class="btn_download">Download</a></li>
					<li>2. Form Aplikasi (Form Annex B) ISO IEC 7812-2_2015 (kode: DP.PKS.30)				<a href="" class="btn_download">Download</a></li>
				</ul>

				<p >Setelah mengunduh dan melengkapi isi dari masing-masing dokumen, silakan klik button link di bawah ini untuk melanjutkan proses permohonan dengan melengkapi dokumen-dokumen yang dibutuhkan untuk diproses oleh Sekretariat Layanan.</p>
					<br/>
					<br/>

				<div class="clearfix">
					<button style="background: red" class="btn_back float_left">Kembali</button>	
					<button style="background: #01923f" class="float_right">Lanjutkan Proses</button>	
				</div>
			</section>

			<!-- Section 3 -->
			<section section-id="2" class="section_iin float_right" style="width: 70%; display:none">
				<h1 class="title_iin">Submit Kelengkapan Dokumen Permohonan IIN</h1>
				<p>Silakan mengunggah dokumen-dokumen yang sudah dilengkapi dan dipersiapkan ke dalam berdasarkan urutan di bawah ini.</p>

				<ul class="section_iin_download">
					<li><input type="checkbox"/> 1. Surat Permohonan Pengajuan Nomor IIN / BIN
						<button class="btn_upload">Upload</button></li>
					<li><input type="checkbox"/> 2. Informasi Persyaratan Pendaftaran Sponsoring Authority
						<button class="btn_upload">Upload</button></li>
					<li><input type="checkbox"/> 3. Term & Condition
						<button class="btn_upload">Upload</button></li>
					<li><input type="checkbox"/> 4. Form Aplikasi (Form Annex B) ISO IEC 7812-2_2015
						<button class="btn_upload">Upload</button></li>
					<li><input type="checkbox"/> 5. Akta Notaris Perusahaan / Peraturan Pemerintah / SK Kepala Daerah ( Gubernur atau Bupati ) *
						<button class="btn_upload">Upload</button></li>
					<li><input type="checkbox"/> 6. Surat Izin Usaha Perdagangan (SIUP) *
						<button class="btn_upload">Upload</button></li>
					<li><input type="checkbox"/> 7. Tanda Daftar Perusahaan (TDP) *
						<button class="btn_upload">Upload</button></li>
					<li><input type="checkbox"/> 8. Nomor Pajak Wajiib Pokok (NPWP)
						<button class="btn_upload">Upload</button></li>
					<li><input type="checkbox"/> 9. Foto Copy Identitas Pimpinan ( direktur utama / top manajemen perusahaan ) *
						<button class="btn_upload">Upload</button></li>
					<li><input type="checkbox"/> 10. SK Kementerian Hukum dan HAM ( jika ada / optional )
						<button class="btn_upload">Upload</button></li>
					<li><input type="checkbox"/> 11. Izin dari Bank Indonesia terkait izin Alat Pembayaran Menggunakan Kartu (APMK) (jika ada / optional)
						<button class="btn_upload">Upload</button></li>
					<li><input type="checkbox"/> 12. Izin dari Bank Indonesia terkait izin E-Commerce (jika ada / optional)
						<button class="btn_upload">Upload</button></li>
				</ul>

				<p >*Dokumen yang wajib disertakan</p>
					<br/>
					<br/>

				<div class="clearfix">
					<button id="btn_back" style="background: red" class=" btn_back float_left">Kembali</button>	
					<button style="background: #01923f" class="float_right">Lanjutkan Proses</button>	
				</div>
			</section>

			<!-- Section 4 -->
			<section section-id="3" class="section_iin float_right" style="width: 70%; display:none">
				<h1 class="title_iin">Proses Verifikasi dan Validasi</h1>
				<p>Berdasarkan permohonan yang telah anda ajukan, saat ini permohonan IIN anda sudah memasuki tahapan Verifikasi dan Validasi. Pada tahapan ini membutuhkan waktu kurang lebih selama 3 hari..</p>

				<p >Silakan klik tombol “Lanjutkan Proses Permohonan IIN Baru” untuk melanjutkan ke proses pembayaran penerbitan IIN baru..</p>
					<br/>
					<br/>

				<div class="clearfix">
					<button style="background: red" class="btn_back float_left">Kembali</button>	
					<button style="background: #01923f" class="float_right">Lanjutkan Proses</button>	
				</div>
			</section>

			<!-- Section 5 -->
			<section section-id="4" class="section_iin float_right" style="width: 70%; display:none">
				<h1 class="title_iin">Konfirmasi Surat Lulus Kelengkapan dan Kode Billing</h1>
				<p>Berdasarkan persetujuan pada proses verifikasi dan validasi terhadap status permohonan anda, dengan ini terlampir suat persetujuan untuk dokumen-dokumen terlampir.</p>

				<p>Silakan unduh (download) beberapa dokumen berikut dan diunggah (upload) kembali setelah dilengkapi.</p>

				<ul class="section_iin_download">
					<li>1. Surat Persetujuan Proses												<a href="" class="btn_download">Download</a></li>
					<li>2. Surat Permohonan Layanan PNBP										<a href="" class="btn_download">Download</a></li>
					<li>3. Kode Billing SIMPONI													<a href="" class="btn_download">Download</a></li>
				</ul>

				<p >Silakan klik tombol “Lanjutkan Proses Permohonan IIN Baru” untuk melanjutkan ke proses pembayaran penerbitan IIN baru.</p>
					<br/>
					<br/>

				<div class="clearfix">
					<button style="background: red" class="float_left">Kembali</button>	
					<button style="background: #01923f" class="float_right">Lanjutkan Proses</button>	
				</div>
			</section>

			<!-- Section 6 -->
			<section section-id="5" class="section_iin float_right" style="width: 70%; display:none">
				<h1 class="title_iin">Konfirmasi Pembayaran</h1>
				<p>Silakan mengunggah bukti transfer yang telah anda lakukan melalui SIMPONI :.</p>

				<button>Unggah</button>
				<div>Bukti Transfer PT. Codysseia</div>

				<p >Silakan klik tombol “Lanjutkan Proses Permohonan IIN Baru” untuk melanjutkan ke proses pembayran penerbitan IIN baru..</p>
					<br/>
					<br/>

				<div class="clearfix">
					<button style="background: red" class="float_left">Kembali</button>	
					<button style="background: #01923f" class="float_right">Lanjutkan Proses</button>	
				</div>
			</section>

			<!-- Section 7 -->
			<section section-id="6" class="section_iin float_right" style="width: 70%; display:none">
				<h1 class="title_iin">Konfirmasi Tim Verifikasi Lapangan</h1>
				<p>Bersama ini kami informasikan bahwa Badan Standardisasi Nasional (BSN) akan melaksanakan verifikasi lapangan dalam rangka penerbitan nomor Issuer Identification Number (IIN) sesuai dengan ISO/IEC 7812 pada tanggal 29 Maret 2017.</p>

				<table>
					<thead>
						<th>No</th>
						<th>Nama</th>
						<th>Posisi</th>
					</thead>
					<tr>
						<td>1</td>
						<td>Novalen Ramadan</td>
						<td>Lead Verificator</td>
					</tr>
					<tr>
						<td>2</td>
						<td>Dicki Dharma Saputra</td>
						<td>Member</td>
					</tr>
					<tr>
						<td>3</td>
						<td>Akhmad Andaru</td>
						<td>Member</td>
					</tr>
				</table>

				<p >Konfirmasi atas persetujuan Saudara terhadap pelaksanaan dan tim verifikasi tersebut di atas, mohon dapat disampaikan kepada kami sebelum tanggal 25 Maret 2017.
		Demikian kami sampaikan, atas perhatian dan kerjasama yang diberikan, kami mengucapkan terima kasih
		Usulan Tim Verifikasi Lapangan IIN
		Surat Informasi Tim Verifikasi Lapngan IIN .</p>
					<br/>
					<ul class="section_iin_download">
					
					<li>1. Usulan Tim Verifikasi Lapangan IIN (kode: F.PKS.8.0.14)				<a href="" class="btn_download">Download</a></li>
					<li>2. Surat Invormasi Tim Verifikasi Lapangan IIN (kode: F.PKS.8.0.15)		<a href="" class="btn_download">Download</a></li>
					
					</ul>
					<br/>

				<div class="clearfix">
					<button style="background: red" class="float_left">Kembali</button>	
					<button style="background: #01923f" class="float_right">Lanjutkan Proses</button>	
				</div>
			</section>

			<!-- Section 8 -->
			<section section-id="7" class="section_iin float_right" style="width: 70%; display:none">
				<h1 class="title_iin">Assessment Lapangan</h1>
				<p>Permohonan IIN baru yang anda ajukan sudah memasuki tahapan Assessment Lapangan oleh tim yang ditunjuk Sekretariat Layanan dan berikut terlampir surat penugasan untuk kegiatan terkait. Dilakan diunduh jika diperlukan sebagai dokumen pendukung.</p>

				<ul class="section_iin_download">
					
					<li>Surat Penugasan Assessment Lapangan				<a href="" class="btn_download">Download</a></li>
					
				</ul>

				<p >Hasil dari kegiatan Assement Lapangan ini akan dilakukan verifikasi. Jika instansi anda telah memnuhi persyaratan permohonan IIN, maka silakan anda menunggu dalam waktu maksimal 9 hari kerja untuk menerima informasi penerbitan IIN. Namun, jika persyaratan permohonan IIN anda belum terpenuhi, maka anda harus melakukan perbaikan hasil Assessment yang akan diinformasikan setelah rapat pembahsan hasil verifikasi lapangan oleh Sekretariat Layanan melalui aplikasi SIPIN ini..</p>
				<br>
				</br>
				<p >
				Silakan klik tombol “Selanjutnya” jika anda sudah memahami alur proses di tahap ini dan siap untuk melanjutkan ke tahapan proses penerbitan IIN selanjutnya.
				</p>
					<br/>
					<br/>

				<div class="clearfix">
					<button style="background: red" class="float_left">Kembali</button>	
					<button style="background: #01923f" class="float_right">Lanjutkan Proses</button>	
				</div>
			</section>

			<!-- Section 8 -->
			<section section-id="8" class="section_iin float_right" style="width: 70%; display:none">
				<h1 class="title_iin">Proses Permohonan ke CRA</h1>
				<p>Berdasarkan permohonan yang telah anda ajukan, saat ini permohonan IIN anda sudah memasuki tahapan permohonan ke CRA.</p>
				<p>
				Silakan tunggu selama kurang lebih 3 hari untuk proses pada tahapan ini.
				</p>
				<br>
				Silakan klik tombol “Lanjutkan Proses Permohonan IIN Baru” untuk ke proses pembayaran penerbitan IIN baru.
				</br>

					<br/>
					<br/>

				<div class="clearfix">
					<button style="background: red" class="float_left">Kembali</button>	
					<button style="background: #01923f" class="float_right">Lanjutkan Proses</button>	
				</div>
			</section>

			<!-- Section 8 -->
			<section section-id="9" class="section_iin float_right" style="width: 70%; display:none">
				<h1 class="title_iin">Menerima IIN Baru</h1>
				<p>Permohonan penerbitan IIN yang sudah anda ajukan, sudah disetujui dan dikeluarkan oleh Otoritas Registrasi dalam hal ini ABA.</p>
				<br>
				<p>Silakan klik tombol “Download IIN” untuk mengunduh informasi penerbitan nomor IIN. Namun sebelumnya aka nada halaman pengisian questioner / survey kepuasan pelanggan untuk meningkatkan kualitas pelayanan kami sebagai Sekretariat Layanan Otoritas Sponsor.</p>
				</br>
				<p>
				Demikian kami sampaikan, atas perhatian dan kerjasama yang diberikan, kami mengucapkan terima kasih.
				</p>

					<br/>
					<br/>

				<div class="clearfix">
					<button style="background: red" class="float_left">Kembali</button>	
					<button style="background: #01923f" class="float_right">Download IIN</button>	
				</div>
			</section>


		</div>
	</div>
	
	<div id="popup_box" style="display: none">
		<!-- LOG IN -->
		<div id="login_frame" class="box_layout" style="display: none">
			<div class="box_title">
				<h1>Masuk</h1>
				<div class="box_btn_close"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMS4xLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDIxMi45ODIgMjEyLjk4MiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjEyLjk4MiAyMTIuOTgyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCI+CjxnIGlkPSJDbG9zZSI+Cgk8cGF0aCBzdHlsZT0iZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7IiBkPSJNMTMxLjgwNCwxMDYuNDkxbDc1LjkzNi03NS45MzZjNi45OS02Ljk5LDYuOTktMTguMzIzLDAtMjUuMzEyICAgYy02Ljk5LTYuOTktMTguMzIyLTYuOTktMjUuMzEyLDBsLTc1LjkzNyw3NS45MzdMMzAuNTU0LDUuMjQyYy02Ljk5LTYuOTktMTguMzIyLTYuOTktMjUuMzEyLDBjLTYuOTg5LDYuOTktNi45ODksMTguMzIzLDAsMjUuMzEyICAgbDc1LjkzNyw3NS45MzZMNS4yNDIsMTgyLjQyN2MtNi45ODksNi45OS02Ljk4OSwxOC4zMjMsMCwyNS4zMTJjNi45OSw2Ljk5LDE4LjMyMiw2Ljk5LDI1LjMxMiwwbDc1LjkzNy03NS45MzdsNzUuOTM3LDc1LjkzNyAgIGM2Ljk4OSw2Ljk5LDE4LjMyMiw2Ljk5LDI1LjMxMiwwYzYuOTktNi45OSw2Ljk5LTE4LjMyMiwwLTI1LjMxMkwxMzEuODA0LDEwNi40OTF6IiBmaWxsPSIjYTFhMWExIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" /></div>
			</div>
			<div class="box_content">
				<form action="<?base_url()?>submit_iin/login" method="post">
					<div class="panel_edit_input">
						<label class="panel_label">Username/ Email:</label>
						<input class="panel_input" type="text" id="username" name="username" autocomplete="off" style="width: 250px" required>
						<label class="panel_alert">Namanya jangan rinaldysam ganteng!</label>
					</div>
					<div class="panel_edit_input">
						<label class="panel_label">Kata Sandi:</label>
						<input class="panel_input" type="text" id="password" name="password" autocomplete="off" style="width: 250px" required>
					</div>
					<div class="panel_edit_input panel_edit_button">
						<label class="panel_label"></label>
						<button class="panel_button_ok login">Masuk</button>
						<div class="panel_button_text" action="btnPopUp" data-id="forgot_frame"><i>Lupa Password?</i></div>
					</div>
				</form>
			</div>
		</div>
		<!-- REGISTER -->
		<div id="register_frame" class="box_layout" style="display: none">
			<div class="box_title">
				<h1>Daftar</h1>
				<div class="box_btn_close">
					<img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMS4xLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDIxMi45ODIgMjEyLjk4MiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjEyLjk4MiAyMTIuOTgyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCI+CjxnIGlkPSJDbG9zZSI+Cgk8cGF0aCBzdHlsZT0iZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7IiBkPSJNMTMxLjgwNCwxMDYuNDkxbDc1LjkzNi03NS45MzZjNi45OS02Ljk5LDYuOTktMTguMzIzLDAtMjUuMzEyICAgYy02Ljk5LTYuOTktMTguMzIyLTYuOTktMjUuMzEyLDBsLTc1LjkzNyw3NS45MzdMMzAuNTU0LDUuMjQyYy02Ljk5LTYuOTktMTguMzIyLTYuOTktMjUuMzEyLDBjLTYuOTg5LDYuOTktNi45ODksMTguMzIzLDAsMjUuMzEyICAgbDc1LjkzNyw3NS45MzZMNS4yNDIsMTgyLjQyN2MtNi45ODksNi45OS02Ljk4OSwxOC4zMjMsMCwyNS4zMTJjNi45OSw2Ljk5LDE4LjMyMiw2Ljk5LDI1LjMxMiwwbDc1LjkzNy03NS45MzdsNzUuOTM3LDc1LjkzNyAgIGM2Ljk4OSw2Ljk5LDE4LjMyMiw2Ljk5LDI1LjMxMiwwYzYuOTktNi45OSw2Ljk5LTE4LjMyMiwwLTI1LjMxMkwxMzEuODA0LDEwNi40OTF6IiBmaWxsPSIjYTFhMWExIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" />
				</div>
			</div>
			<div class="box_content">
				<form action="<?base_url()?>submit_iin/register" method="post">
				
					<div class="panel_edit_input">
						<label class="panel_label">Nama Lengkap:</label>
						<input class="panel_input" type="text" id="nama" name="nama" autocomplete="off" style="width: 250px" required>
						<label class="panel_alert">Namanya jangan rinaldysam ganteng!</label>
					</div>
					<div class="panel_edit_input">
						<label class="panel_label">Username:</label>
						<input class="panel_input" type="text" id="username" name="username" autocomplete="off" style="width: 250px" required >
						<label class="panel_alert">Namanya jangan rinaldysam ganteng!</label>
					</div>
					<div class="panel_edit_input">
						<label class="panel_label">E-mail:</label>
						<input class="panel_input" type="email" id="email" name="email" autocomplete="off" style="width: 250px" required>
						<label class="panel_alert">Namanya jangan rinaldysam ganteng!</label>
					</div>
					<div class="panel_edit_input">
						<label class="panel_label">Kata Sandi:</label>
						<input class="panel_input" type="password" id="password" name="password" autocomplete="off" style="width: 250px" required>
					</div>
					<div class="panel_edit_input">
						<label class="panel_label">Ulang Kata Sandi:</label>
						<input class="panel_input" type="password" id="password_confirm" name="password_confirm" autocomplete="off" style="width: 250px" required>
					</div>

					<div class="g-recaptcha" style="background: #ddd;width: 250px;display: table;vertical-align: middle;text-align: center;color:#aaa;font-size: 28px;margin: 0 auto;padding: 20px;" data-sitekey="6LerwS0UAAAAAF27mC7K-XWf-IYBMyrZcTKbhEeB" > </div>
					



					<div class="panel_edit_input">
						<label class="panel_label"></label>
						<input type="checkbox" id="register_check" name="registercheck" value="Confirm Registration" required>
    					<label class="panel_label_checkbox" for="register_check">Saya setuju untuk mendaftar?</label>
					</div>
					<div class="panel_edit_input panel_edit_button">
						<label class="panel_label"></label>
						<button type="submit" class="panel_button_ok" value="register"/>Daftar</button>
					</div>
				</form>
			</div>
		</div>
		<!-- FORGOT PASSWORD -->
		<div id="forgot_frame" class="box_layout" style="display: none">
			<div class="box_title">
				<h1>Lupa Kata Sandi</h1>
				<div class="box_btn_close">
					<img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMS4xLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDIxMi45ODIgMjEyLjk4MiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjEyLjk4MiAyMTIuOTgyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCI+CjxnIGlkPSJDbG9zZSI+Cgk8cGF0aCBzdHlsZT0iZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7IiBkPSJNMTMxLjgwNCwxMDYuNDkxbDc1LjkzNi03NS45MzZjNi45OS02Ljk5LDYuOTktMTguMzIzLDAtMjUuMzEyICAgYy02Ljk5LTYuOTktMTguMzIyLTYuOTktMjUuMzEyLDBsLTc1LjkzNyw3NS45MzdMMzAuNTU0LDUuMjQyYy02Ljk5LTYuOTktMTguMzIyLTYuOTktMjUuMzEyLDBjLTYuOTg5LDYuOTktNi45ODksMTguMzIzLDAsMjUuMzEyICAgbDc1LjkzNyw3NS45MzZMNS4yNDIsMTgyLjQyN2MtNi45ODksNi45OS02Ljk4OSwxOC4zMjMsMCwyNS4zMTJjNi45OSw2Ljk5LDE4LjMyMiw2Ljk5LDI1LjMxMiwwbDc1LjkzNy03NS45MzdsNzUuOTM3LDc1LjkzNyAgIGM2Ljk4OSw2Ljk5LDE4LjMyMiw2Ljk5LDI1LjMxMiwwYzYuOTktNi45OSw2Ljk5LTE4LjMyMiwwLTI1LjMxMkwxMzEuODA0LDEwNi40OTF6IiBmaWxsPSIjYTFhMWExIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" />
				</div>
			</div>
			<div class="box_content">
				<div class="box_content_note">Silahkan masukan alamat E-mail anda dan kami akan mengirimkan alamat tautan untuk melakukan pengaturan untuk kata sandi anda.</div>
				<form action="<?base_url()?>submit_iin/forgot_password" method="post">
					<div class="panel_edit_input">
						<label class="panel_label">Alamat E-mail:</label>
						<input class="panel_input" type="text" id="username" name="username_forgot" autocomplete="off" style="width: 250px" required>
						<label class="panel_alert">Namanya jangan rinaldysam ganteng!</label>
					</div>
					<div class="panel_edit_input panel_edit_button">
						<label class="panel_label"></label>
						<button class="panel_button_ok">Lanjutkan</button>
					</div>
				</form>
			</div>
		</div>
	</div>