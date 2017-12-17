<?php
  $page_title = 'Dashboard :: Pengaturan Tim Assessment';
  $page_section = 'TIM ASSESSMENT';
  $data_table = [
    ['id_assessment_team'     ,'No', '100px'],
    ['name'                   ,'Nama Anggota', '0'],
    ['status'                 ,'Status', '200px']
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
        <button id="btn-add" class="btn-flat float_left">TAMBAH ANGGOTA</button>

        <div class="opt-table-filter float_right">
          <input class="search filter_search" placeholder="Search ..." />
        </div>
      </div>

      <div id="targetExcel" class="parent_table">
        <table id="tableInbox" class="table_def tableInbox" style="width: 100%;">
          <tr><?php foreach($data_table as $x){echo '<th class="sort" data-sort="'.$x[0].'">'.$x[1].'</th>';}?></tr>
          <tbody class="list">
            <?php foreach($data_name as $key=>$data) {
              echo '<tr class="row_select"';
                  foreach($data_table as $x) {echo ' o-'.$x[0].'="'.$data[$x[0]].'"';}
              echo '>'; ?>
                <td class="id_assessment_team " width="100px" data-sort="id_assessment_team"><?=$key+1?></td>
                <td class="name " width="0" data-sort="name"><?=$data['name']?></td>
                <td class="status active" width="200px" data-sort="<?=strtolower($data['status'])?>"><?=$data['status']?></td>
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
    var url_u = "<?php echo base_url('dashboard/action_update/assessment') ?>";
    var url_i = "<?php echo base_url('dashboard/action_insert/assessment') ?>";
    $('document').ready(function(){
      document.title = '<?php echo $page_title ?>';

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

      $('#btn-add').on('click', function() {
        $('.z-modal-title').html('Tambah Anggota Assessment');
        $('.z-modal-frame').fadeIn('fast', function() {
          $('.z-modal-frame input').val('');
          $('#z-modal-edit').slideDown()
          $('.modal-form').attr('action', url_i);
        });
      })
   });

    $('.row_select').on('click', function() {
        <?php foreach($data_table as $x){echo '$("[name='.$x[0].']").val($(this).attr("o-'.$x[0].'"));';} ?>
        $('.z-modal-title').html('Ubah Administrator');
        $('.z-modal-frame').fadeIn('fast', function() {
          $('#z-modal-edit').slideDown()
          $('.modal-form').attr('action', url_u);
        });
      })
  </script>
  <style>
    .status{text-transform:uppercase;font-weight: bold !important;font-size: 11px !important;}
    .status.active:before,.admin.inactive:before{content:'';display:inline-block;width:7px;height:7px;margin-right:10px;border-radius:5px}
    .status.active:before{background:#01923f;}
    .status.inactive:before{background:#999;}
  </style>
</section>





<div class="z-modal-frame" style="display: none;">
  <div id="z-modal-edit" style="display: none;">
    <div class="z-modal-header">
      <div class="z-modal-title">Ubah Administrator</div>
      <div class="z-modal-close"></div>
    </div>
    <div class="z-modal-content">
      <form class="modal-form" action="<?php echo base_url('dashboard/action_update/assessment') ?>" method="post">
        <div class="z-modal-form">
            <input name="id_assessment_team" type="hidden"/>
            <label><span>Nama Lengkap</span>
                <input name="name" type="text" placeholder="Username"/>
            </label>
            <label>
                <span>Status</span>
                <select name="STATUS">
                  <option>ACTIVE</option>
                  <option>INACTIVE</option>
                </select>
              </label>

            <button class="btn-flat">LANJUTKAN</button>
          </div>
      </form>
    </div>
  </div>
</div>
