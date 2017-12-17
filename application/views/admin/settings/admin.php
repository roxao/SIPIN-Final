<?php
  $page_title = 'Dashboard :: Pengaturan Administrator';
  $page_section = 'ADMINISTRATOR';
  $data_table = [
    ['id_admin', 'No', '20px'],
    ['username', 'Username', '150px'],
    ['email', 'Alamat Email', '250px'],
    ['admin_role', 'Jabatan Admin', '150px'],
    ['admin_status', 'Status Admin', '150px']
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
        <button id="btn-add" class="btn-flat float_left">TAMBAH ADMIN</button>
        <div class="opt-table-filter float_right">
          <input class="search filter_search" placeholder="Search ..." />
        </div>
      </div>

      <div id="targetExcel" class="parent_table">
        <table id="tableInbox" class="table_def tableInbox" style="width: 100%;">
          <thead>
            <tr>
              <th class="sort asc" data-sort="id_admin">No</th>
              <th class="sort"     data-sort="username">Username</th>
              <th class="sort"     data-sort="email">Alamat Email</th>
              <th class="sort"     data-sort="admin_role">Jabatan Admin</th>
              <th class="sort"     data-sort="admin_status">Status Admin</th>
            </tr>
          </thead>
          <tbody class="list">
            <?php foreach($data as $key=>$data) { ?>
              <tr class="row_select"
                  o-id_admin="<?=$data['id_admin']?>"
                  o-username="<?=$data['username']?>"
                  o-email="<?=$data['email']?>"
                  o-admin_role="<?=$data['admin_role']?>"
                  o-admin_status="<?=$data['admin_status']?>">
                  <td class="id_admin " width="20px" data-sort="id_admin"><?=($key)+1?></td>
                  <td class="username " width="150px" data-sort="username"><?=$data['username']?></td>
                  <td class="email " width="250px" data-sort="email"><?=$data['email']?></td>
                  <td class="admin_role " width="150px" data-sort="admin_role"><?=$data['admin_role']?></td>
                  <td class="admin_status <?=($data['admin_status']==='ACTIVE' ? 'active':'inactive') ?>" width="150px" data-sort="admin_status"><?=$data['admin_status']?></td>
                </tr>
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
    var url_u = "<?php echo base_url('dashboard/action_update/admin') ?>";
    var url_i = "<?php echo base_url('dashboard/action_insert/admin') ?>";

    $('document').ready(function(){
      document.title = '<?php echo $page_title ?>';
      $.set_table_list();

      $('.z-modal-close').on('click',function(){$('#z-modal-edit').slideUp('fast',function(){$('.z-modal-frame').fadeOut()});})

      $('#btn-add').on('click', function() {
        $('.z-modal-title').html('Tambah Administrator');
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
          $('.modal-form').find('[name=password]').val('');
        });
      })
  </script>
</section>





<div class="z-modal-frame" style="display: none;">
  <div id="z-modal-edit" style="display: none;">
    <div class="z-modal-header">
      <div class="z-modal-title">Ubah Administrator</div>
      <div class="z-modal-close"></div>
    </div>
    <div class="z-modal-content">
      <form  class="modal-form" action="<?php echo base_url('dashboard/set_action/user/update') ?>" method="post">
        <div class="z-modal-form">
            <input name="id_admin" type="hidden"/>
            <label>
                <span>Username</span>
                <input name="username" type="text" placeholder="Username" autocomplete="off"/>
            </label>

            <label>
                <span>Password</span>
                <input name="password" type="password" placeholder="Pa$$w0rd"  autocomplete="off"/>
            </label>

            <label>
                <span>E-mail</span>
                <input name="email" type="email" placeholder="example@mail.com"/>
            </label>

            <label>
                <span>Jabatan Admin</span>
                <select name="admin_role">
                  <option>Super Admin</option>
                  <option>Admin</option>
                </select>
              </label>

            <label>
                <span>Status Admin</span>
                <select name="admin_status">
                  <option>ACTIVE</option>
                  <option>INACTIVE</option>
                </select>
              </label>

            <button class="btn-flat">Lanjutkan</button>
          </div>
      </form>
    </div>
  </div>
</div>
