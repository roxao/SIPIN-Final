<section class="dashboard_content sheets_paper">
  <section class="main_dashboard_slidetab">
    <div class="site-map">
      <a href="<?php echo base_url('dashboard') ?>">Dashboard</a>
      <span></span>Inbox
    </div>
    <h2 class="title_content">Inbox Status </h2>

    <div id="tableInbox" style=" margin: 0 -20px 0 -20px">
      <div class="opt-table clearfix">
        <div class="opt-table-filter float_right">
          <input class="search filter_search" placeholder="Search ..." />
        </div>
      </div>
      <div class="table_content">
        <table class="table_def tableInbox" style="width: 100%;">
          <thead>
            <tr>
              <th style="min-width:55px"  class="sort click_auto"  data-sort="id_no">No.</th>
              <th style="min-width:200px" class="sort" data-sort="id_name">Nama Pemohon</th>
              <th style="min-width:140px" class="sort" data-sort="id_type">Jenis Pengajuan</th>
              <th style="min-width:140px" class="sort" data-sort="id_date">Tanggal Pengajuan</th>
              <th style="min-width:140px" class="sort" data-sort="id_process">Proses Status</th>
              <th style="min-width:250px" class="sort" data-sort="id_status">Status Pengajuan</th>
              <th style="min-width:100px">Lihat Proses</th>
            </tr>
          </thead>
          <tbody class="list">
            <?php $i=1; foreach($applications as $data) { ?>
              <tr class="<?php echo $data->owner == "ADMIN" && $data->process_status == "PENDING" ? "get_process" : ""?>"
                  data-id="<?php  echo $data->id_application ?>"
                  data-id-status="<?php  echo $data->id_application_status ?>"
                  data-status="<?php  echo $data->display_name ?>"
                  data-step="<?php  echo $data->application_status_name ?>">
                <td class="id_no"><?php  echo $i ?></td>
                <td class=" ">
                  <div class="id_name"><?php  echo $data->applicant ?> </div>
                  <div class="id_pt"><?php echo $data->instance_name ?></div>
                </td>
                <td class="id_type"><?php  echo ($data->application_type == 'new' ? "Penerbitan IIN Baru": "Pengawasan IIN Lama") ?></td>
                <td>
                  <span class="id_date hidden"><?php echo $data->application_date?></span>
                  <?php  echo date("D, d M Y", strtotime($data->application_date)) ?></td>
                <td class="id_process"><?php  echo $data->process_status ?></td>
                <td class="id_status"><span class="<?php echo $data->owner ?> <?php echo $data->process_status ?>"><?php  echo $data->display_name ?></span></td>
                <td><a class="btn-see-process"target="_blank" href="<?= base_url().'SipinHome/submit_application?userIdSelected='.$data->id_user.'&header=hidden';?>">Detail</a></td>
              </tr>
            <?php $i++; } ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <script type="text/javascript" src="<?=base_url('assets/js/list.min.js')?>"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $.set_table_list();
    });
  </script>
</section>
