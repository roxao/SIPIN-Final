<div class="box-modal" style="display: none;">
	<div class="modal-header">
	  <div class="modal-title"></div>
	  <div class="modal-close"><img fill="#fff" src="<?php echo base_url('assets/cancel.svg')?>" alt="Tutup" height="12px" width="12px"></div>
	</div>
	<div class="modal-content">
		<div class="the-skeleton">
			<div class="the-skeleton-1" style="width: 60%"></div>
			<div class="the-skeleton-1" style="width: 90%"></div>
			<div class="the-skeleton-1" style="width: 80%"></div>
			<div class="the-skeleton-1" style="width: 80%"></div>
			<div class="the-skeleton-1" style="width: 40%"></div>
		</div>
	</div>
	<div id="section-approval" class="modal-footer clearfix" style="display: none">
		<button id="btn-revision" class="modal-footer-button float-left" style="background: red">REVISI</button>
		<button id="btn-approval" class="modal-footer-button float-right">SETUJUI</button>
	</div>
	<div id="section-revision" class="modal-footer clearfix" style="display: none">
		<button id="btn-revision-back" class="modal-footer-button float-left" style="background: red">BATAL</button>
		<button id="btn-revision-send" class="modal-footer-button float-right">KIRIM REVISI</button>
	</div>
</div>

<script>
	$('.modal-close').on('click', function(event) {
		$('.box-modal').addClass('box-gone');
		$('#modal_approval').fadeOut('400', function() {
			$(this).remove();
		});
	});
</script>
