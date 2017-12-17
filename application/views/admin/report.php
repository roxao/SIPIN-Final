<?php
  $page_title = 'Dashboard :: Laporan';
  $page_section = 'LAPORAN';
  $data_table = [
    ['id_application'         ,'No'                    ,'50'],
    ['applicant'              ,'Nama Pemohon'         ,'200'],
    ['applicant_phone_number' ,'Telepon Pemohon'      ,'120'],
    ['application_date'       ,'Tanggal Pengajuan'    ,'120'],
    ['instance_name'          ,'Nama Perusahaan'      ,'250'],
    ['instance_email'         ,'E-mail Perusahaan'    ,'200'],
    ['instance_director'      ,'Direktur Perusahaan'  ,'200'],
    ['mailing_location'       ,'Lokasi Pengajuan'     ,'200'],
    ['mailing_number'         ,'Nomor Surat'          ,'100'],
    ['application_type'       ,'Jenis Pengajuan'      ,'100'],
    ['display_name'           ,'Status Pengajuan'     ,'250']
  ];
?>

<section class="dashboard_content sheets_paper">
  <section class="main_dashboard_slidetab">
    <div class="site-map">
      <a href="<?php echo base_url('dashboard') ?>">Dashboard</a><span></span>
      <a href="<?php echo base_url('dashboard') ?>">Pengaturan</a><span></span>
      <?php echo $page_section ?>
    </div>
    <center><h2 class="title_content"><?php echo $page_section ?></h2></center>
    <div id="tableInbox" style=" margin: 0 -20px 0 -20px">
      <div class="opt-table clearfix">
        <button id="btn-export" onclick="get_excel('get_applications')" class="btn-flat float_left">DOWNLOAD</button>
        <div class="opt-table-filter float_right">
          <input class="search filter_search" placeholder="Search ..." />
          <div id="filtertable">
            <div class="clickfilter">Filter... </div>
            <div class="filtertable filters">
              <?php foreach($data_table as $x){ if($x[0]!=$data_table[0][0]) echo '<label><input type="checkbox" checked value="'.$x[0].'" title="'.$x[1].'">'.$x[1].'</label>';}?>
            </div>
          </div>
        </div>
      </div>

      <div id="targetExcel" class="parent_table">
        <table id="tableInbox" class="table_def tableInbox" style="width: 100%;table-layout: fixed">
          <tr><?php foreach($data_table as $x){echo '<th class="sort" data-sort="'.$x[0].'" style="width:'.$x[2].'px !important">'.$x[1].'</th>';}?></tr>
          <tbody class="list">
            <?php foreach($applications as $key=>$data) {
              echo '<tr class="row_select"';
                  foreach($data_table as $x) {echo ' o-'.$x[0].'="'.$data[$x[0]].'"';}
              echo '>';?>
                <td class="id_application"><?=$key+1?></td>
                <td class="applicant"><?=$data['applicant']?></td>
                <td class="applicant_phone_number"><?=$data['applicant_phone_number']?></td>
                <td class="application_date">
                  <span class="id_date hidden"><?=$data['application_date']?></span>
                  <?=date("D, d M Y", strtotime($data['application_date']))?></td>
                <td class="instance_name"><?=$data['instance_name']?></td>
                <td class="instance_email"><?=$data['instance_email']?></td>
                <td class="instance_director"><?=$data['instance_director']?></td>
                <td class="mailing_location"><?=$data['mailing_location']?></td>
                <td class="mailing_number"><?=$data['mailing_number']?></td>
                <td class="application_type"><?=$data['application_type']?></td>
                <td class="display_name"><?=$data['display_name']?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <ul class="main_pagination">
        <li class="listjsprev"><</li>
        <ul class="pagination"></ul>
        <li class="listjsnext">></li>
      </ul>

      <div id="popup_box" style="display: none">
      </div>
    </div>
  </section>

  <script type="text/javascript" src="<?php echo base_url('/assets/js/list.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('/assets/js/export.js')?>"></script>
  <script type="text/javascript">
    $('document').ready(function(){
      document.title = '<?php echo $page_title ?>';
      var url_u = "<?php echo base_url('dashboard/action_update/admin') ?>";
      var url_i = "<?php echo base_url('dashboard/action_insert/admin') ?>";
      $('#filtertable input').click(function(event) {
        if($("input[type=checkbox]:checked").length<5){alert('Anda harus memilih minimal 5 kolom');return false;};
        $('th[data-sort="' + $(this).attr('value') + '"]').toggle();
        $('td[data-sort="' + $(this).attr('value') + '"]').toggle();
      });
      $('#filtertable .clickfilter').click(function(event){$('.filtertable').slideToggle()});
      var datasort = [<?php foreach($data_table as $key=>$x) {echo '"'.$x[0].'",';}?>]
      var SortTable = new List('tableInbox',{valueNames:datasort,page: 10,pagination: true});
      $('.listjsnext').on('click',function(){var list=$('.pagination').find('li');$.each(list,function(position,element){if($(element).is('.active')){$(list[position+1]).trigger('click')}})});
      $('.listjsprev').on('click',function(){var list=$('.pagination').find('li');$.each(list,function(position,element){if($(element).is('.active')){$(list[position-1]).trigger('click')}})});
      $('.tableInbox tr th:first-child').click();

      $('.z-modal-close').on('click',function(){$('#z-modal-edit').slideUp('fast',function(){$('.z-modal-frame').fadeOut()});})
   });
  </script>
  <style>
    .mailing_location {text-transform:capitalize}
  </style>
</section>
