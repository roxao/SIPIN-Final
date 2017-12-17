
<div id="gmap">

</div>
<div id="o-contact" class="clearfix">
	<div class="o-contact-title">
		Hubungi Kami
	</div>
	<div class="float_left form-contact">
		<form class="o-form" action="<?php echo base_url('SipinHome/contact_us_prossess') ?>" method="post" >
			<label>
				<div>Alamat E-mail:</div>
				<input name="email" type="email" autocomplete="off"></label>
			<label>
				<div>Nama Lengkap:</div>
				<input name="name" type="text" autocomplete="off"></label>
			<label>
				<div>Pesan:</div>
				<textarea name="message" id="" cols="30" rows="10"></textarea>
			</label>
			<button class="float_right">Kirim</button>
			<br/><br/><br/>
		</form>
	</div>
	<div class="float_right detail-contact">
		<div>
			<h3>Pusat Pendidikan dan Pemasyarakatan Standardisasi BSN</h3>
			<span>Gedung I BPPT Lt. 10</span>
			<span>Jalan M.H. Thamrin No. 8, </span>
			<span>Jakarta Pusat, 10340</span>
			<span>021 1234 1234 - 021 1234 1234</span>
			<span>kerjasama@bsn.go.id</span>
		</div>
	</div>
</div>

    <script>
      function initMap() {
        var uluru = {lat: -6.184888, lng: 106.822746};
        var map = new google.maps.Map(document.getElementById('gmap'), {
          zoom: 16,
          center: uluru,
          disableDefaultUI: true
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCOSn6UcpkQ8yyJ4NqlXsohwrTINCGW-hI&callback=initMap">
    </script>
    <style>
    	[href^="http://maps.google.com/maps"]{display:none !important}
a[href^="https://maps.google.com/maps"]{display:none !important}

.gmnoprint a, .gmnoprint span, .gm-style-cc {
    display:none;
}
.gmnoprint div {
    background:none !important;
}
    </style>
<!-- 
<script>
      function initMap() {
        var map = new google.maps.Map(document.getElementById('gmap'), {
          center: {lat: -34.397, lng: 150.644},
          zoom: 8
        });
      }
    </script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script> -->
