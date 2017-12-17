<section class="clearfix content-approval">
	<?php echo form_open_multipart('admin_verifikasi_controller/UPL_RES_ASSESS_REQ_SUCCESS') ?>
		<input type="hidden" name="id_application_status">
		<input type="hidden" name="id_application">
		<input type="hidden" name="created_by">
		<label class="input_dashed_file float_left" style="width: 100%">
			Berita Acara
			<input name="bill[]"  type="file" placeholder="Masukan Dokumen Kode Billing SIMPONI" required />
			<span>Pilih</span><i class="float_right"></i>
		</label>
		<label class="input_dashed_file float_left" style="width: 100%">
			Hasil Assessment Lapangan
			<input name="bill[]"  type="file" placeholder="Masukan Surat Persetujuan Proses" required />
			<span>Pilih</span><i class="float_right"></i>
		</label>
		<input type="submit" name="submit_approval" style="display:none"/>
	</form>
</section>

<section class="clearfix content-revision" style="display:none">
	<div class="autocomplete-parent-approval">
		<input type="text" name="autocomplete" data-key="doc" placeholder="Ketik nama dokumen revisi ..." />
	</div>
	<br/>
	<!-- form_open_multipart('admin_verifikasi_controller/UPL_RES_ASSESS_REQ_REVISI') -->
	<?php echo form_open_multipart('admin_verifikasi_controller/UPL_RES_ASSESS_REQ_REVISI') ?>
		<input type="hidden" name="id_application_status">
		<input type="hidden" name="id_application">
		<input type="hidden" name="created_by">
		<div class="item-revision"></div>
		<input type="submit" name="submit_revision" onClick="return checkInput('.item-revision')" style="display:none"/>
	</form>
</section>








<script>
	$.set_value_data();
	$.base_config_approval();
	$.config_file_type();
	$.set_add_upload();

	function checkInput(x){
		if($(x).has('input').length < 1){
			alert('Silakan masukan dokumen revisi.');
		 	return false;
		}
	};
	var acresult = false;
	$("[name=autocomplete]").autocomplete({
      	source:function(request,response){$.ajax({
				url: "<?php echo base_url('dashboard/get_autocomplete/')?>" + $('[name=autocomplete]').attr('data-key'),
				dataType: "json",
				data:{term: $("[name=autocomplete]").val()},
				success: function( data ) {
					if (data.length == 0) {
	                    acresult = true;
	                } else {
	                	acresult = false;
	                }
					response(data);}
      		});
      	},
      	minLength: 2,
      	appendTo: ".autocomplete-parent-approval",
      	autoFocus: true,
      	select: function( event, ui ) {
	        $('.item-revision').append('<div><input type="hidden" name="doc[]" value="'+ui.item.label+'"/>'+ui.item.label+'<span class="item-revision-del"></span></div>');
	        $('.item-revision-del').on('click',function(event){$(this).parent().remove()});
          	$(this).val('');
      		event.preventDefault();
      	},
    });

	$("[name=autocomplete]").keydown(function(event){
	    if(event.keyCode == 13) {
	      	if($(this).val().length>0 && acresult == true) {
		        $('.item-revision').append('<div><input type="hidden" name="doc[]" value="'+$(this).val()+'"/>'+$(this).val()+'<span class="item-revision-del"></span></div>');
		        $('.item-revision-del').on('click',function(event){$(this).parent().remove()});
	          	$(this).val('');
	      }
	    }
 	});

</script>
	
