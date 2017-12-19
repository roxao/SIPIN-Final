<footer class="<?=($this->input->get('header')=='hidden' ? 'hidden' : '')?>">
	<div class="footer_container">
		<div class="footer_address">
			<div class="footer-contact-list">
				<div class="footer-item-address">
					<img src="<?=base_url('assets/location.svg')?>">
					<div>Pusat Kerjasama Stadardisasi BSN<br/>
						Gedung I BPPT Lt. 12, Jalan M.H. Thamrin No. 8 <br/>
						Jakarta Pusat, 10340
					</div>
				</div>
				<div class="footer-item-phone">
					<img src="<?=base_url('assets/phone.svg')?>">
					<div>(021) 391 7300</div>
				</div>
				<div class="footer-item-mail">
					<img src="<?=base_url('assets/email.svg')?>">
					<div>info.iin@bsn.go.id</div>
				</div>

			</div>
		</div>
		<div class="footer_socmed">
			<svg class="sprite_icon" fill="#bb0000"><use xlink:href="<?=base_url("assets/ic_socmed.svg#ic_yt")?>"/></svg>
			<svg class="sprite_icon" fill="#00aced"><use xlink:href="<?=base_url("assets/ic_socmed.svg#ic_tw")?>"/></svg>
			<svg class="sprite_icon" fill="#3b5998"><use xlink:href="<?=base_url("assets/ic_socmed.svg#ic_fb")?>"/></svg>
			<svg class="sprite_icon" fill="#bc2a8d"><use xlink:href="<?=base_url("assets/ic_socmed.svg#ic_ig")?>"/></svg>
		</div>
		<div class="footer_copyright">
			<div><img src="<?php echo base_url() ?>assets/logo.png" alt=""></div>
			<div class="footer_copyright">Hak Cipta 2017 © Standardisasi Nasional</div>
		</div>
	</div>
</footer>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/ui.min.js"></script>
