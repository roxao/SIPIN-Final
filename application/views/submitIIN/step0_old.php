<section section-id="0" class="section_iin float_right" >
	<h1 class="title_iin">Pengajuan Surat Permohonan IIN Baru</h1>
		<p>Silakan mengisi form di bawah ini untuk melakukan permohonan IIN baru. Sebelum anda mengirim surat ini melalui sistem dengan klik button <b>"Kirim"</b>, maka sebelumnya Anda harus mengunduh(download) surat permohonan yang sudah diisi yang akan akan digunakan kembali jika status data Anda sudah terverifikasi dan disetujui oleh Sekretariat Layanan.</p>
		<article style="margin: 50px">
		
	<form action="<?php echo base_url()?>submit_iin/insert_letter_submission" method="post">
			<div class="clearfix">
				<label class="input_dashed float_left" style="width: 65%">
					Lokasi Pengajuan Surat
					<input id="app_address" name="app_address" type="text" placeholder="Lokasi Pengajuan Surat"  />
				</label>
				<label class="input_dashed float_right right-input" style="width: 35%">
					Tanggal Pengajuan Surat
					<input id="app_date" name="app_date"  type="date" placeholder="dd/MM/yyyy" />
				</label>
			</div>
			<label class="input_dashed float_left" style="width: 100%">
				Nomor Surat
				<input id="app_num" name="app_num"  type="text" placeholder="Nomor Surat" />
			</label>
			<label class="input_dashed float_left" style="width: 100%">
				Nama Instansi Pemohon
				<input id="app_instance" name="app_instance" type="text" placeholder="Nama Instansi Pemohon" />
			</label>
			<label class="input_dashed float_left" style="width: 100%">
				E-mail Instansi Pemohon
				<input id="app_mail" name="app_mail" type="text" placeholder="E-mail Instansi Pemohon" />
			</label>
			<label class="input_dashed float_left" style="width: 100%">
				Nomor Telepon Instansi Pemohon
				<input id="app_phone" name="app_phone" type="text" placeholder="Nomor Telepon Instansi Pemohon" />
			</label>
			<label class="input_dashed float_left" style="width: 100%">
				Tujuan Permohonan IIN baru
				<input id="app_purpose" name="app_purpose" type="text" placeholder="Tujuan Permohonan IIN baru" />
			</label>
			<label class="input_dashed float_left" style="width: 100%">
				Nama Direktur Utama/Manager/Kepala Divisi Pemohon
				<input id="app_div" name="app_div" type="text" placeholder="Nama Direktur Utama/Manager/Kepala Divisi Pemohon" />
			</label>
			<label class="input_dashed float_left" style="width: 100%">
				Nama Pemohon
				<input id="app_applicant" name="app_applicant"  type="text" placeholder="Nama Pemohon" />
			</label>
			<label class="input_dashed float_left" style="width: 100%">
				Nomor Telepon Pemohon
				<input id="app_no_applicant" name="app_no_applicant"  type="text" placeholder="Nomor Telepon" />
			</label>
			

			<div class="inputValidation2">
				<div class="g-recaptcha" style="background: #ddd;width: 250px;display: table;vertical-align: middle;text-align: center;color:#aaa;font-size: 28px;margin: 0 auto;padding: 20px;" data-sitekey="6LerwS0UAAAAAF27mC7K-XWf-IYBMyrZcTKbhEeB" > </div>
				<input type="text" placeholder="Type the character you see ..." style="width: 200px; margin: 10px auto"><br/> <br/><br/><br/>
				<div class="clearfix">
					<button class="float_left" style="background: red" name="kirim" value="batal" type="batal">Batal</button>
					<button class="float_right" style="background: #01923f" name="kirim" value="kirim" type="kirim">Kirim</button>
				</div>
			</div>
		</article>
	</form>

	<!-- form  -->
</section>
