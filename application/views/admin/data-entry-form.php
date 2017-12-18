<section class="dashboard_content sheets_paper" style="max-width: 800px">
  <section class="main_dashboard_slidetab">
    <div class="site-map">
      <a href="<?php echo base_url('dashboard') ?>">Dashboard</a>
      <span></span>Penerima IIN
    </div>
    <h2 class="title_content sort-center">Historical Data Entry</h2>


    <form action="<?php echo base_url('dashboard/historycal_data_entry/'.(($data) ? 'update': 'insert')); ?>" method="post" accept-charset="utf-8">
       <section class="clearfix" style="margin: 0 -21px">
          <!-- APPLICANT DETAIL -->
          <h4 class="title-form-section">Data Pemohon</h4>
          <div class="data-entry-form">    
            <div class="display-flex">
            <input type="hidden" name="id_iin" value="<?php echo ($data) ? $data[0]['id_iin']: ''?>">
            <input type="hidden" name="id_application" value="<?php echo ($data) ? $data[0]['id_application']: ''?>">
              <label class="input-data" style="flex:2">
                Nama Pemohon
                <input name="applicant" type="text" value="<?php echo ($data) ? $data[0]['applicant']: ''?>" />
            </label>
              <label class="input-data">
                Nomor Telepon Pemohon
                <input name="applicant_phone_number" type="text" value="<?php echo ($data) ? $data[0]['applicant_phone_number']: ''?>" />
              </label>
              <label class="input-data">
                Tanggal Pengajuan
                <input name="application_date" type="date" value="<?php echo ($data) ? $data[0]['application_date']: ''?>" required/>
              </label>
            </div>
          </div>


          <!-- INSTANCE DETAIL -->
          <h4 class="title-form-section">Data Perusahaan</h4>   
          <div class="data-entry-form">       
            <label class="input-data">
                Nama Instansi
                <input name="instance_name" type="text" value="<?php echo ($data) ? $data[0]['instance_name']: ''?>" />
            </label>
            <label class="input-data">
                Nama Direktur Utama/Manager/Kepala Divisi Pemohon
                <input name="instance_director" type="text" value="<?php echo ($data) ? $data[0]['instance_director']: ''?>" />
            </label>
            <div class="display-flex">
              <label class="input-data">
                  Email Instansi
                  <input name="instance_email" type="email" value="<?php echo ($data) ? $data[0]['email']: ''?>" />
              </label>
              <label class="input-data">
                  Nomor Telepon
                  <input name="instance_phone" type="text" value="<?php echo ($data) ? $data[0]['instance_phone']: ''?>" />
              </label>
            </div>
            <label class="input-data">
                Nomor Surat
                <input name="mailing_number" type="text" value="<?php echo ($data) ? $data[0]['mailing_number']: ''?>" />
            </label>
            <label class="input-data">
                Lokasi Pengajuan
                <input name="mailing_location" type="text" value="<?php echo ($data) ? $data[0]['mailing_location']: ''?>" />
            </label>
          </div>

          <!-- IIN DETAIL -->
          <h4 class="title-form-section">Data IIN</h4>
          <div class="data-entry-form">
            
            <div class="display-flex">
              <label class="input-data">
                Nomor IIN
                <input name="iin_number" type="text" value="<?php echo ($data) ? $data[0]['iin_number']: ''?>" required/>
              </label>
              <label class="input-data">
                Tanggal Pengesahan
                <input name="iin_established_date" type="date" value="<?php echo ($data) ? $data[0]['iin_established_date']: ''?>" required/>
              </label>
              <label class="input-data">
                Tanggal Kadaluarsa
                <input name="iin_expiry_date" type="date" value="<?php echo ($data) ? $data[0]['iin_expiry_date']: ''?>" required/>
              </label>
            </div>
          </div>
          
        </section>

        <div class="clearfix">
          <button type="submit" style="float: right; margin: 20px">Masukan Data</button>  
        </div>
        
    </form>
  </section>
</section>




