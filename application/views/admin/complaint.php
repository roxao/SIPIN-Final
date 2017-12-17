<section class="dashboard_content sheets_paper">
  <section class="main_dashboard_slidetab">
    <div class="site-map">
      <a href="<?= base_url('dashboard') ?>">Dashboard</a>
      <span></span>Pengaduan
    </div>
    <h2 class="title_content">Pengaduan</h2>
    <div id="tableInbox" style=" margin: 0 -20px 0 -20px">
      <div class="opt-table clearfix">
        <div class="opt-table-filter float_right">
          <input class="search filter_search" placeholder="Search ..." />
        </div> 
      </div>
      <table class="table_def tableInbox" style="width: 100%;">
        <thead>
          <tr>
            <th style="width:35px" class="sort" data-sort="id_no">#</th>
            <th class="sort" data-sort="id_pt">Pesan</th>
            <th style="width:125px" class="sort" data-sort="id_date">Tanggal</th>
          </tr>
        </thead>
        <tbody class="list">
          <?php $i=1; foreach($complaint as $data) { ?>
            <tr>
              <td class="id_no"><?=$i?></td>
              <td class="id_pt">
                <div class="complaint-user"><?=$data['username']?></div>
                <div class="complaint-msg"><?=$data['complaint_details']?></div>
              </td>
              <td>
                <span class="id_date hidden"><?=$data['created_date']?></span>
                  <?=date("D, d M Y", strtotime($data['created_date']))?>
              </td>
            </tr>
          <?php $i++;} ?>
        </tbody>
      </table>

      <div id="popup_box" style="display: none">
      </div>
    </div>
  </section>

  <script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/list.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $.set_table_list();
    });
  </script>
</section>




