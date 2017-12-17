<section class="dashboard_content sheets_paper">
  <section class="main_dashboard_slidetab">
    <div class="site-map">
      <a href="<?php echo base_url('dashboard') ?>">Dashboard</a>
      <span></span>Penerbitan IIN Baru
    </div>
    <h2 class="title_content">Penerbitan IIN Baru</h2>
    <div id="tableInbox" style=" margin: 0 -20px 0 -20px">
      <table class="table_def tableInbox" style="width: 100%;">
        <tr>
          <th class="sort" data-sort="id_no"><center>#</center></th>
          <th class="sort" data-sort="id_name">Nama Pemohon</th>
          <th class="sort" data-sort="id_pt">Nama Instansi</th>
          <th class="sort" data-sort="id_type">Jenis Pengajuan</th>
          <th class="sort" data-sort="id_date">Tanggal Pengajuan</th>
          <th class="sort" data-sort="id_status"><center>Status Pengajuan</center></th>
          <th><center>Lihat</center></th>
        </tr>
        <tbody class="list">
          <?php foreach($applications as $data) { ?>
            <tr class="<?php echo $data->owner == "ADMIN" && $data->process_status == "PENDING" ? "get_process" : ""?>" 
            data-id="<?php echo $data->id_application ?>" 
            data-status="<?php echo $data->display_name ?>" 
            data-id-status="<?php  echo $data->id_application_status ?>"
            data-step="<?php echo $data->application_status_name ?>">
              <td class="id_no"><?php echo $data->id_application ?></td>
              <td class="id_name"><?php echo $data->applicant ?></td>
              <td class="id_pt"><?php echo $data->instance_name ?></td>
              <td class="id_type"><?php  echo (strtolower($data->application_type) == 'new' ? "Penerbitan IIN Baru": "Pengawasan IIN Lama") ?></td>
              <td class="id_date"><?php echo $data->application_date ?></td>
              <td class="id_status"><span class="<?php echo $data->owner ?>"><?php echo $data->display_name ?></span></td>
              <td><a target="_blank" href="<?php echo base_url().'SipinHome/submit_application?userIdSelected='.$data->id_user;?>">lihat</a></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

      <div id="popup_box" style="display: none">
      </div>
    </div>
  </section>

  <script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/list.min.js"></script>
  <script type="text/javascript">
    $('document').ready(function(){
      var options = {valueNames: [ 'id_no', 'id_name', 'id_pt', 'id_type', 'id_date' ]};
      var inboxList = new List('tableInbox', options);
    });
  </script>
</section>




