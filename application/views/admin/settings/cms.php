<?php
  $page_title = 'Dashboard :: Pengaturan Konten';
  $page_section = 'Content Management System';
  $data_table = [
    ['id_cms'                 ,'No', '50'],
    ['title'                  ,'Judul Content', '0'],
    ['url'                    ,'URL', '200'],
    ['status'                 ,'Status', '200'],
    ['created_by'             ,'Dibuat Oleh', '100']
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
        <button id="btn-add" class="btn-flat float_left">TAMBAH CMS</button>

        <div class="opt-table-filter float_right">
          <input class="search filter_search" placeholder="Search ..." />
        </div>
      </div>

      <div id="targetExcel" class="parent_table">
        <table id="tableInbox" class="table_def tableInbox" style="width: 100%;">
          <thead>
            <tr>
              <th class="sort" data-sort="id_cms">#</th>
              <th class="sort" data-sort="title">Judul Content</th>
              <th class="sort" data-sort="url">URL</th>
              <th class="sort" data-sort="status">Status</th>
              <th class="sort" data-sort="created_by">Dibuat Oleh</th></tr>
          </thead>
          <tbody class="list">
            <?php foreach($data as $key=>$data){?>
              <tr class="row_select" data-id="<?=$data['id_cms']?>">
                <td class="id_cms" width="50"   data-sort="id_cms"><?=$key+1?></td>
                <td class="title" width="0"     data-sort="title"><?=($data['title'])?></td>
                <td class="url" width="200"     data-sort="url"><?=($data['url'])?></td>
                <td class="status <?=strtolower($data['status'])?>" width="200" data-sort="status"><?=($data['status']==='Y'?'Aktif':'Tidak Aktif')?></td>
                <td class="created_by" width="100" data-sort="created_by"><?=$data['created_by']?></td>
              </tr>
            <?php  } ?>
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
    $('document').ready(function(){
      document.title = '<?php echo $page_title ?>';
      $.set_table_list();
      $('#btn-add').on('click', function() {
        $('[name=id_cms').val('insert');
        $('.modal-form button').click();
      })
   });

    $('.row_select').on('click', function() {
        $('[name=id_cms').val($(this).attr('data-id'));
        $('.modal-form button').click();
      })
  </script>
</section>





<div class="z-modal-frame" style="display: none;">
  <form class="modal-form" action="<?php echo base_url('dashboard/settings/cms_editor') ?>" method="post">
    <input name="id_cms" type="text">
    <button type="submit"></button>
  </form>
</div>
