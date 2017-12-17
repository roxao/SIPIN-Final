<section section-id="0" class="section_iin float_right" >
	<h1 class="title_iin"><?php echo $title_iin0 ?></h1>
		<p>Silakan mengisi form di bawah ini untuk melakukan permohonan IIN baru. Sebelum anda mengirim surat ini melalui sistem dengan klik button <b>"Kirim"</b>, maka sebelumnya Anda harus mengunduh(download) surat permohonan yang sudah diisi yang akan akan digunakan kembali jika status data Anda sudah terverifikasi dan disetujui oleh Sekretariat Layanan.</p>
		<article style="margin-top: 50px">

			<form action="<?php echo base_url()?>submit_iin/step_0" method="post" onSubmit="return saveComment();">
				<div class="clearfix">
					<div class="clearfix">
						<label class="input_dashed float_left" style="width: 65%">
							Lokasi Pengajuan Surat
							<input   required  id="app_address" name="app_address" type="text" placeholder="Lokasi Pengajuan Surat" />
						</label>
						<label class="input_dashed float_right right-input" style="width: 35%">
							Tanggal Pengajuan Surat
							<input  required  id="app_date" name="app_date"  type="date" placeholder="dd/MM/yyyy"/>
						</label>
					</div>
					<label  required  class="input_dashed float_left" style="width: 100%">
						Nomor Surat
						<input  required  id="app_num" name="app_num"  type="text" placeholder="Nomor Surat"/>
					</label>
					<label class="input_dashed float_left" style="width: 100%">
						Nama Instansi Pemohon
						<input  required  id="app_instance" name="app_instance" type="text" placeholder="Nama Instansi Pemohon"/>
					</label>
					<label class="input_dashed float_left" style="width: 100%">
						E-mail Instansi Pemohon
						<input  required  id="app_mail" name="app_mail" type="text" placeholder="E-mail Instansi Pemohon"/>
					</label>
					<label class="input_dashed float_left" style="width: 100%">
						Nomor Telepon Instansi Pemohon
						<input  required  id="app_phone" name="app_phone" type="text" placeholder="Nomor Telepon Instansi Pemohon"/>
					</label>
					<label class="input_dashed float_left" style="width: 100%">
						Tujuan Permohonan IIN baru
						<input  required  id="app_purpose" name="app_purpose" type="text" placeholder="Tujuan Permohonan IIN baru" />
					</label>
					<label class="input_dashed float_left" style="width: 100%">
						Nama Direktur Utama/Manager/Kepala Divisi Pemohon
						<input   required id="app_div" name="app_div" type="text" placeholder="Nama Direktur Utama/Manager/Kepala Divisi Pemohon"/>
					</label>
					<label class="input_dashed float_left" style="width: 100%">
						Nama Pemohon
						<input  required  id="app_applicant" name="app_applicant"  type="text" placeholder="Nama Pemohon"/>
					</label>
					<label class="input_dashed float_left" style="width: 100%">
						Nomor Telepon Pemohon
						<input  required  id="app_no_applicant" name="app_no_applicant"  type="text" placeholder="Nomor Telepon" />
					</label>
					<input type="hidden" id="no_type" name="no_type" />
				</div>

				<div class="inputValidation2">
					<script src='https://www.google.com/recaptcha/api.js'></script>
					<div class="g-recaptcha googleCaptcha" data-sitekey="6LdDdjsUAAAAAA9zTbj6Rpf3qsNn0qsWqzJ6VMKi"></div>
					<div class="message-captcha" style="color:red; font-style:italic"></div>
					<div class="clearfix" style="margin-top: 20px;">
						<button id="btn-validate" style="background: #01923f" name="kirim" value="kirim" type="kirim">Kirim</button>
					</div>
				</div>
				
			</form>
		</article>


	<script type="text/javascript">
		$( '#btn-validate' ).click(function(){
	 		if (grecaptcha.getResponse().length === 0){
				$( '.message-captcha').text( "reCAPTCHA is mandatory" );
				return false;
			}
		})

		var btn_step0 = "<?=(isset($btn_step0) ? $btn_step0 : '')  ?>";
		if (btn_step0  == "hide") $(".inputValidation2").hide();

		$("#no_type").val('<?=$app_type?>');
		var app_type = "<?=(isset($app_type)) ? $app_type: '' ?>";
		var mailing_location = "<?=(isset($mailing_location)) ? $mailing_location: '' ?>";
		var application_date = "<?=(isset($application_date)) ? $application_date: '' ?>";
		var application_purpose = "<?=(isset($application_purpose)) ? $application_purpose: '' ?>";
		var mailing_number = "<?=(isset($mailing_number)) ? $mailing_number: '' ?>";
		var instance_name = "<?=(isset($instance_name)) ? $instance_name: '' ?>";
		var instance_email = "<?=(isset($instance_email)) ? $instance_email: '' ?>";
		var instance_phone = "<?=(isset($instance_phone)) ? $instance_phone: '' ?>";
		var instance_director = "<?=(isset($instance_director)) ? $instance_director: '' ?>";
		var applicant = "<?=(isset($applicant)) ? $applicant: '' ?>";
		var applicant_phone_number = "<?=(isset($applicant_phone_number)) ? $applicant_phone_number: '' ?>";

		if (mailing_location != "" && app_type == "new") $("#app_address").prop('disabled');
		if (application_date != "" && app_type == "new") $("#app_date").prop('disabled');
		if (application_purpose != "" && app_type == "new") $("#app_purpose").prop('disabled');
		if (mailing_number != "" && app_type == "new") $("#app_num").prop('disabled');
		if (instance_name != "" && app_type == "new") $("#app_instance").prop('disabled');
		if (instance_email != "" && app_type == "new") $("#app_mail").prop('disabled');
		if (instance_phone != "" && app_type == "new") $("#app_phone").prop('disabled');
		if (instance_director != "" && app_type == "new") $("#app_div").prop('disabled');
		if (applicant != "" && app_type == "new") $("#app_applicant").prop('disabled');
		if (applicant_phone_number != "" && app_type == "new") $("#app_no_applicant").prop('disabled');

		$("#app_address").val(mailing_location);
		$("#app_date").val(application_date);
		$("#app_purpose").val(application_purpose);
		$("#app_num").val(mailing_number);
		$("#app_instance").val(instance_name);
		$("#app_mail").val(instance_email);
		$("#app_phone").val(instance_phone);
		$("#app_div").val(instance_director);
		$("#app_applicant").val(applicant);
		$("#app_no_applicant").val(applicant_phone_number);
	</script>
</section>
