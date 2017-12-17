<section class="clearfix content-approval">
	<?php echo form_open_multipart('admin_verifikasi_controller/UPL_RES_ASSESS_REQ_SUCCESS') ?>
		<input type="hidden" name="id_application_status">
		<input type="hidden" name="id_application">
		<label class="input_dashed_file float_left" style="width: 100%">
			Berita Acara
			<input name="bill[]"  type="file" placeholder="Masukan Dokumen Kode Billing SIMPONI"/>
			<span>Pilih</span><i class="float_right"></i>
		</label>
		<label class="input_dashed_file float_left" style="width: 100%">
			Hasil Assessment Lapangan
			<input name="bill[]"  type="file" placeholder="Masukan Surat Persetujuan Proses"/>
			<span>Pilih</span><i class="float_right"></i>
		</label>
		<input type="submit" name="submit_approval" style="display:none"/>
	</form>
</section>

<section class="clearfix content-revision" style="display:none">
	<div class="autocomplete-parent-approval">
		<input type="text" name="autocomplete" data-key="assessment_team" placeholder="Ketik nama dokumen revisi ..." />
	</div>
	<br/>
	<?php echo form_open_multipart('admin_verifikasi_controller/UPL_RES_ASSESS_REQ_SUCCESS') ?>
		<input type="hidden" name="id_application_status">
		<input type="hidden" name="id_application">
		<div class="item-revision">
		</div>
		<input type="submit" name="submit_revision" style="display:none"/>
	</form>
</section>








<script>
	value=respon.application;
	$("[name=id_application_status]").val(value.id_application_status);
	$("[name=id_application]").val(value.id_application);
	$("input[type=file]").change(function() {
	    var fileName = $(this).val().split('/').pop().split('\\').pop();
	    $(this).next().next().html(fileName);
	});
	$("[name=autocomplete]").autocomplete({
      	source:function(request,response){$.ajax({
				url: "<?php echo base_url('dashboard/get_autocomplete/')?>" + $('[name=autocomplete]').attr('data-key'),
				dataType: "json",
				data:{term: $("[name=autocomplete]").val()},
				success: function( data ) {response(data);}
      		});
      	},
      	minLength: 2,
      	appendTo: ".autocomplete-parent-approval",
      	select: function( event, ui ) {
        	$('.item-revision').append('<div><input type="hidden" name="doc[]" value="'+ui.item.label+'"/>'+ui.item.label+'<span class="item-revision-del"></span></div>');
    	 	$('.item-revision-del').on('click',function(event){$(this).parent().remove()});
      	}
    });

   	$('#btn-approval').on('click', function(event) {
   		$('[name=submit_approval]').click()
   		});
   	$('#btn-revision-back-send').on('click', function(event) {
   		$('[name=submit_revision]').click()
   		});
	$('#btn-revision').on('click', function(event) {
		$('.content-approval').hide();
		$('.content-revision').slideDown();
		$('#section-approval').hide();
		$('#section-revision').slideDown();
		});
	$('#btn-revision-back').on('click', function(event) {
		$('.content-approval').slideDown();
		$('.content-revision').hide();
		$('#section-approval').slideDown();
		$('#section-revision').hide();
		});
</script>
