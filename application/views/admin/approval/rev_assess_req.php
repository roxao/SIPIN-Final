<section class="clearfix content-approval">
	

	<div id="assessment_team">
		<div style="flex:1">
			<select id="a_roles" class="a_roles" name="a_roles"></select>
		</div>
		<div style="flex:2" class="autocomplete-parent-assessment">
			<input type="text" name="assessment_list" data-key="assessment_team" placeholder="Ketik nama anggota ..." />
		</div>
	</div>

	<?php echo form_open_multipart('admin_verifikasi_controller/REV_ASSESS_REQ_PROSESS') ?>
		<input type="hidden" name="id_application_status">
		<input type="hidden" name="id_application">
		<input type="hidden" name="created_by">
		<ul id="assessment-team-list" class="assessment-team-list">
			<li class="team-header">
				<div class="x1">POSISI</div><div class="x2">NAMA ANGGOTA</div><div class="x3"></div>
			</li>
		</ul>
		<div class="section_list_file">
			<p class="notes_modal">Pemohon mengajukan pemindahan jadwal assessment lapangan pada :</p>
		</div>
		<!-- TANGGAL PELAKSANAAN -->
		<label class="input_dashed float_left" style="width: 100%">
			Tanggal Pelaksanaan
			<input name="expired_date" type="date" placeholder="Masukan Tanggal Pelaksanaan Assessment"/>
		</label>

		<!-- DOKUMEN PENDUKUNG -->
		<label class="input_dashed_file float_left" style="width: 100%">
			Masukan Usulan Tim Verifikasi Lapangan
			<input name="images[]" type="file" placeholder="Masukan Surat Persetujuan Proses" required />
			<span>Pilih</span><i class="float_right"></i>
		</label>
		<!-- DOKUMEN PENDUKUNG -->
		<label class="input_dashed_file float_left" style="width: 100%">
			Masukkan Surat Informasi Tim Verifikasi Lapangan IIN
			<input name="images[]" type="file" placeholder="Masukan Surat Persetujuan Proses" required />
			<span>Pilih</span><i class="float_right"></i>
		</label>
		<input type="submit" name="submit_approval" onClick="return checkInput('#assessment-team-list')" style="display:none"/>
	</form>
</section>




<section class="clearfix content-revision" style="display:none">
	<p>Masukan keterangan perbaikan dokumen yang harus di unggah oleh Pemohon</p>
	<?php echo form_open_multipart('admin_verifikasi_controller/VERIF_NEW_REQ_ETC') ?>
		<input type="hidden" name="id_application_status">
		<input type="hidden" name="id_application">
		<input type="hidden" name="created_by">
		<textarea name="coment" cols="30" rows="10" class="text_comment"></textarea>
		<input type="submit" name="submit_revision" style="display:none"/>
	</form>
</section>








<script>
	$.set_value_data();
	$.base_config_approval();
	$.config_file_type();
	$.set_assessment_roles_on_select();
	
	function checkInput(x){
		if($(x).children().length < 2){
			alert('Silakan masukkan anggota assessment.');
		 	return false;
		}
	};
	// SET AUTOCOMPLETE ASSESSMENT LIST NAME
	$("[name=assessment_list]").autocomplete({
      	source:function(request,response){$.ajax({
				url: "<?php echo base_url('dashboard/get_autocomplete/')?>" + $('[name=assessment_list]').attr('data-key'),
				dataType: "json",
				data:{term: $("[name=assessment_list]").val()},
				success: function( data ) {response(data);console.log(data)}
      		});
      	},
      	minLength: 2,
      	appendTo: ".autocomplete-parent-assessment",
      	select: function( event, ui ) {
      		$('#assessment-team-list').append($('<li>')
      				.append($('<div>').addClass('x1')
      					.append($('<input>')
      						.prop('type', 'hidden')
      						.prop('name', 'assessment_title[]')
      						.prop('value', $(".a_roles option:selected").val()))
      					.append($(".a_roles option:selected").text()))
      				.append($('<div>')
      					.addClass('x2')
      					.append($('<input>')
      						.prop('type', 'hidden')
      						.prop('name', 'assessment_name[]')
      						.prop('value', ui.item.id_team))
      					.append(ui.item.label))
      				.append($('<div>')
      					.addClass('x3')
      					.append('Hapus')));
    	 	$('.x3').on('click',function(event){$(this).parent().slideUp('400',function(){$(this).remove()})});
      	}
    });

	$('#btn-revision').remove();
	$('#section-revision').remove();

	$('[name=expired_date]').val(respon.date_req[0].value);
	
</script>
