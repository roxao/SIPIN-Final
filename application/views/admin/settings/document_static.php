<?php
  $page_title = 'Dashboard :: Pengaturan Dokumen Statis';
  $page_section = 'DOKUMEN STATIS';
  $data_table = [
    ['id_document_config', 'ID', '50'],
    ['keys', 'Keys', '50'],
    ['display_name', 'Nama Dokumen', '400'],
    ['file_url', 'Dokumen', '120'],
    ['mandatory', 'Mandatory', '50']
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
        <!-- <button id="btn-add" class="btn-flat float_left">TAMBAH DOKUMEN</button> -->

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
          <thead>
              <tr>
                  <th class="sort asc" data-sort="id_document_config">ID</th>
                  <th class="sort" data-sort="keys">Keys</th>
                  <th class="sort" data-sort="display_name">Nama Dokumen</th>
                  <th class="sort" data-sort="file_url">Dokumen</th>
                  <th class="sort" data-sort="mandatory">Mandatory</th>
              </tr>
          </thead>
          <tbody class="list">
            <?php foreach($data as $keys=>$data) {
              echo '<tr class="row_select"';
                  foreach($data_table as $x) {echo ' o-'.$x[0].'="'.$data[$x[0]].'"';}
              echo '>';
             ?>
              
                  <td class="id_document_config" width="50" data-sort="id_document_config"><?=$keys+1?></td>
                  <td class="keys" width="50" data-sort="keys"><?=$data['keys']?></td>
                  <td class="display_name" width="400" data-sort="display_name"><?=$data['display_name']?></td>
                  <td class="file_url" width="120" data-sort="file_url"><?=$data['file_url']?></td>
                  <td class="mandatory" width="50" data-sort="mandatory"><?=($data['mandatory']==='1'? 'Ya':'Tidak')?></td>
              
            <?php } ?>
          </tbody>
        </table>
      </div>

      <div id="popup_box" style="display: none">
      </div>
    </div>
  </section>

  <script type="text/javascript" src="<?php echo base_url('/assets/js/list.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('/assets/js/export.js')?>"></script>
  <script type="text/javascript">
    var url_u = "<?php echo base_url('dashboard/action_update/document') ?>";
    var url_i = "<?php echo base_url('dashboard/action_insert/document') ?>";

    $('document').ready(function(){
      document.title = '<?php echo $page_title ?>';

      $('#filtertable input').click(function(event) {
        if($("input[type=checkbox]:checked").length<5){alert('Anda harus memilih minimal 5 kolom');return false;};
        $('th[data-sort="' + $(this).attr('value') + '"]').toggle();
        $('td[data-sort="' + $(this).attr('value') + '"]').toggle();
      });
      $('#filtertable .clickfilter').click(function(event){$('.filtertable').slideToggle()});
      $.set_table_list();


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
        $('.z-modal-title').html('Ubah Dokumen Statis');
        $('.z-modal-frame').fadeIn('fast', function() {
          $('#z-modal-edit').slideDown()
          $('.modal-form').attr('action', url_u);
        });
      })
  </script>
  <style>
    tr th:first-child{text-align: center !important}
    .status{text-transform:uppercase;font-weight: bold !important;font-size: 11px !important;}
    .status.active:before,.admin.inactive:before{content:'';display:inline-block;width:7px;height:7px;margin-right:10px;border-radius:5px}
    .status.active:before{background:#01923f;}
    .status.inactive:before{background:#999;}
  </style>
</section>



<!--  Nama Dokumen | file_url  -->


<div class="z-modal-frame" style="display: none;">
  <div id="z-modal-edit" style="display: none;">
    <div class="z-modal-header">
      <div class="z-modal-title">Ubah Administrator</div>
      <div class="z-modal-close"></div>
    </div>
    <div class="z-modal-content">
      <?php echo form_open_multipart('dashboard/action_update/document', 'class="modal-form"') ?>
        <div class="z-modal-form">
            <input name="id_document_config" type="hidden"/>
            <input name="type_doc" type="hidden" value="STATIC"/>
            <label><span>Keys</span>
                <input name="keys" type="text" placeholder="Username"/>
            </label>
            <label><span>Nama </span>
                <input name="display_name" type="text" placeholder="Username"/>
            </label>
            <label><span>Dokumen</span>
                <input name="file_url[]" type="file" placeholder="Username"/>
            </label>
            <label>
                <span>Mandatory</span>
                <select name="mandatory">
                  <option value="0">Ya</option>
                  <option value="1">Tidak</option>
                </select>
              </label>
            <button class="btn-flat">LANJUTKAN</button>
          </div>
      </form>
    </div>
  </div>
</div>
