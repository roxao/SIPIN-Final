<?php
  $data_page = 'USER';
  $data_table = [
    ['id_assessment_team'     ,'#'], 
    ['name'                   ,'Nama Anggota'], 
    ['status'                 ,'Status']
  ];
?>



<section class="dashboard_content sheets_paper">
  <section class="main_dashboard_slidetab">
    <div class="site-map">
      <a href="<?php echo base_url('dashboard') ?>">Dashboard</a><span></span>
      <a href="<?php echo base_url('dashboard') ?>">Pengaturan</a><span></span>
      <?php echo $data_page ?>
    </div>
    <center><h2 class="title_content"><?php echo $data_page ?></h2></center>
    <div id="tableInbox" style=" margin: 0 -20px 0 -20px">
      <div class="opt-table clearfix">
        <button id="btnExport" class="btn-flat float_left">EXPORT</button>

        <div class="opt-table-filter float_right">
          <input class="search filter_search" placeholder="Search ..." />
          <div id="filtertable" style="display:none">
            <div class="clickfilter">Filter... </div>
            <div class="filtertable filters">
              <?php foreach($data_table as $x){ if($x[0]!=$data_table[0][0]) echo '<label><input type="checkbox" checked value="'.$x[0].'">'.$x[1].'</label>';}?>
            </div>
          </div>
        </div> 
      </div>

      <div id="targetExcel" class="parent_table">
        <table id="tableInbox" class="table_def tableInbox" style="width: 100%;">
          <tr><?php foreach($data_table as $x){echo '<th class="sort" data-sort="'.$x[0].'">'.$x[1].'</th>';}?></tr>
          <tbody class="list">
            <?php foreach($data_name as $key=>$data) {
              echo '<tr class="row_select"';
                  foreach($data_table as $x) {echo ' o-'.$x[0].'="'.$data[$x[0]].'"';}
              echo '>';
                  foreach($data_table as $x) {
                    echo '<td class="'.$x[0].' '. ($x[0]=='status'? $data[$x[0]] : '') .'" data-sort="'.$x[0].'">'.$data[$x[0]].'</td>';
                  }
              echo '</tr>';
            } ?>
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
      var url_u = "<?php echo base_url('dashboard/action_update/admin') ?>";
      var url_i = "<?php echo base_url('dashboard/action_insert/admin') ?>";

      $('#filtertable input').click(function(event) {
        if($("input[type=checkbox]:checked").length < 5 ) {
          alert('Anda harus memilih minimal 5 kolom');return false;
          };
        fdv = $(this).attr('value');
        $('th[data-sort="' + fdv + '"]').toggle();
        $('td[data-sort="' + fdv + '"]').toggle();
      });

      $('#filtertable .clickfilter').click(function(event){$('.filtertable').slideToggle()});
      $('.row_select').on('click', function() {
        <?php foreach($data_table as $x) { echo '$("#'.$x[0].'").val($(this).attr("o-'.$x[0].'"));';} ?>
        $('.z-modal-frame').fadeIn('fast', function() {
          $('#z-modal-edit').slideDown()
          $('.modal-form').attr('action', url_u);
        });
      })
      $('.z-modal-close').on('click',function(){$('#z-modal-edit').slideUp('fast',function(){$('.z-modal-frame').fadeOut()});})
      




      $('#expor-btn').on('click', function() {
        
      })
      var datasort = [<?php foreach($data_table as $key=>$x) {echo '"'.$x[0].'",';}?>]
      var SortTable = new List('tableInbox',{valueNames:datasort,page: 10,pagination: true});
      $('.listjsnext').on('click',function(){var list=$('.pagination').find('li');$.each(list,function(position,element){if($(element).is('.active')){$(list[position+1]).trigger('click')}})});
      $('.listjsprev').on('click',function(){var list=$('.pagination').find('li');$.each(list,function(position,element){if($(element).is('.active')){$(list[position-1]).trigger('click')}})});
      $('.tableInbox tr th:first-child').click();
   });
  </script>
  <style>
    tr th:first-child{text-align: center !important}
    .status{text-transform:uppercase;}
    .status.active:before,.status.inactive:before{content:'';display:inline-block;width:7px;height:7px;margin-right:10px;border-radius:5px}
    .status.active:before{background:#01923f;}
    .status.inactive:before{background:#999;}
  </style>
</section>



<div class="z-modal-frame" style="display: none;">
  <div id="z-modal-edit" style="display: none;">
    <div class="z-modal-header">
      <div class="z-modal-title">Edit User</div>
      <div class="z-modal-close"></div>
    </div>
    <div class="z-modal-content">
      <form action="<?php echo base_url('dashboard/set_action/user/update') ?>" method="post">
        <div class="z-modal-form">
            <input name="id_assessment" type="hidden" id="id_assessment"/>
            <label>Nama Lengkap       <input name="name" type="text" id="name"/></label>
            <button name="insert" value="insert" class="btn-flat">Ubah</button>
          </div>
      </form>
    </div>
  </div>
</div>
