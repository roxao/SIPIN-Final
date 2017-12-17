<section class="dashboard_content sheets_paper">
  <section class="main_dashboard_slidetab">
    <div class="site-map">
      <a href="<?php echo base_url('dashboard') ?>">Dashboard</a><span></span>
      <a href="<?php echo base_url('dashboard') ?>">Pengaturan</a><span></span>
      Pengaturan IIN
    </div>
    <h2 class="title_content">PENGATURAN IIN</h2>
    <div id="tableInbox" style=" margin: 0 -20px 0 -20px">
      <?php
        $s_name = [
          ['id_assessment_team', 'ID Anggota'],
          ['name', 'Nama Anggota'],
          ['status', 'Status Anggota']
        ];
      ?>
      <div class="clearfix"  style="margin: 0 15px">
        <div class="float_left">
          <button class="btn-flat">SAMPLE BUTTON</button>
        </div>
        <div id="filtertable" class="float_right">
          <div class="clickfilter">Filter... </div>
          <div class="filtertable">
            <?php foreach($s_name as $x) {echo '<label><input type="checkbox" checked value="'.$x[0].'">'.$x[1].'</label>';} ?>
          </div>
        </div>
      </div>


      <div class="parent_table">
        <table class="table_def tableInbox" style="width: 100%;">
          <tr>
            <?php foreach($s_name as $x) {
          	echo '<th class="sort" data-sort="'.$x[0].'">'.$x[1].'</th>';
            } ?>
          </tr>
          <tbody class="list">
            <?php foreach($data as $key=>$data) {
              echo '<tr class="row_select"';
                foreach($s_name as $x) {
                  echo ' o-'.$x[0].'="'.$data[$x[0]].'"';
                }
              echo '>';
              	foreach($s_name as $x) {
           			echo '<td class="'.$x[0].'" data-sort="'.$x[0].'">'.$data[$x[0]].'</td>';
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

  <script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/list.min.js"></script>
  <script type="text/javascript">
    $('document').ready(function() {
       var datasort = [<?php foreach($s_name as $key=>$x) {echo '"'.$x[0].'",';}?>]
       var options = {
           valueNames: datasort,
           page: 10,
           pagination: true
       };
       var inboxList = new List('tableInbox', options);

       fd = $('#filtertable :checked');

       $('#filtertable input').click(function(event) {
           fdv = $(this).attr('value');
           $('th[data-sort="' + fdv + '"]').toggle();
           $('td[data-sort="' + fdv + '"]').toggle();
       });
       $('#filtertable .clickfilter').click(function(event) {
           $('.filtertable').slideToggle();
       });
       $('.row_select').on('click', function() {
           <?php foreach($s_name as $x) {
              echo '$("#'.$x[0].'").val($(this).attr("o-'.$x[0].'"));';
            } ?>
           $('.z-modal-frame').fadeIn('fast', function() {
               $('#z-modal-edit').slideDown()
           });
       })
       $('.z-modal-close').on('click', function() {
           $('#z-modal-edit').slideUp('fast', function() {
               $('.z-modal-frame').fadeOut()
           });
       })
       $('.listjsnext').on('click', function() {
           var list = $('.pagination').find('li');
           $.each(list, function(position, element) {
               if ($(element).is('.active')) {
                   $(list[position + 1]).trigger('click');
               }
           })
       })
       $('.listjsprev').on('click', function() {
           var list = $('.pagination').find('li');
           $.each(list, function(position, element) {
               if ($(element).is('.active')) {
                   $(list[position - 1]).trigger('click');
               }
           })
       })
   });
  </script>
  <style> tr th:first-child{text-align: center !important} </style>
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
            <input name="id_user" type="hidden" id="id_user"/>
            <input name="survey_status" type="hidden" id="survey_status"/>
            <label>Username
                <input name="username" type="text" id="username"/>
            </label>
            <label>Password
                <input name="password" type="text" id="password"/>
            </label>
            <label>E-Mail
                <input name="email" type="text" id="email"/>
            </label>
            <label>Nama Lengkap
                <input name="name" type="text" id="name"/>
            </label>
            <label>
                <input name="status_user" type="hidden" id="status_user"/>
            </label>
            <button type="insert" name="insert" value="insert" class="btn-flat">Ubah Data User</button>
          </div>
      </form>
    </div>
  </div>
</div>
