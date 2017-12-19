<section class="dashboard_content sheets_paper">
  <section class="main_dashboard_slidetab">
    <div class="site-map">
      <a href="<?php echo base_url('dashboard') ?>">Dashboard</a>
      <span></span>Penerima IIN
    </div>
    <h2 class="title_content">Historical Data Entry</h2>

    <div id="tableInbox" style=" margin: 0 -20px 0 -20px">
      <div class="opt-table clearfix">
        <button id="btn-new-iin" onclick="get_iin_form('new')" class="btn-flat float_left">MASUKAN DATA</button>
        <div class="opt-table-filter float_right">
          <input class="search filter_search" placeholder="Search ..." />
        </div> 
      </div>
      <div class="table_content">
        <table class="table_def tableInbox" style="width: 100%;">
          <tr>
            <th style="min-width:55px"  class="sort click_auto"  data-sort="id_no">No.</th>
            <th style="min-width:300px" class="sort" data-sort="id_name">Detail Pemohon</th>
            <th style="min-width:150px" class="sort" data-sort="id_date_est">Tanggal Pengesahan</th>
            <!-- <th style="min-width:150px" class="sort" data-sort="id_date_exp">Tanggal Kadaluarsa</th> -->
            <th style="min-width:130px" class="sort" data-sort="id_iin_no">Nomor IIN</th>
          </tr>
          <tbody class="list">
            <?php $i=1; foreach($applications as $data) { ?>
              <tr  class="row_select" data-id="<?php echo $data->id_user; ?>">
                <td class="id_no"><?php  echo $i ?></td>
                <td>
                  <div class="id_name"><?php  echo $data->applicant ?></div>
                  <div><?php echo $data->instance_email ?></div>
                  <div><?php echo $data->instance_name ?></div>
                  <div><?php echo $data->mailing_location ?></div>
                </td>
                <td>
                  <span class="id_date_est hidden"><?php echo $data->iin_established_date?></span>
                  <?php  echo date("D, d M Y", strtotime($data->iin_established_date)) ?></td>
                <!-- <td> -->
                  <!-- <span class="id_date_exp hidden"><?php echo $data->iin_expiry_date?></span> -->
                  <!-- <?php  echo date("D, d M Y", strtotime($data->iin_expiry_date)) ?></td> -->
                <td class="id_iin_no"><?php  echo $data->iin_number ?></td>
              </tr>
            <?php $i++; } ?>
          </tbody>
        </table>
      </div>

      <ul class="main_pagination">
        <li class="listjsprev"><</li>
        <ul class="pagination"></ul>
        <li class="listjsnext">></li>
      </ul>
    </div>
  </section>

  <script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/list.min.js"></script>
  <script type="text/javascript">
    $('document').ready(function(){
      var options = {valueNames: [ 'id_no', 'id_name', 'id_date_est', 'id_date_exp','id_iin_no'],page: 10,pagination: true};
      var inboxList = new List('tableInbox', options);
    });
    $('.listjsnext').on('click', function(){
    var list = $('.pagination').find('li');
    $.each(list, function(position, element){
        if($(element).is('.active')){
            $(list[position+1]).trigger('click');
        }
    })
    })
    $('.listjsprev').on('click', function(){
        var list = $('.pagination').find('li');
        $.each(list, function(position, element){
            if($(element).is('.active')){
                $(list[position-1]).trigger('click');
            }
        })
    })
     $('.row_select').on('click', function() {
        $('[name=id_entry').val($(this).attr('data-id'));
        $('.modal-form button').click();
      })
  </script>
</section>


<div class="z-modal-frame" style="display: none;">
  <form class="modal-form" action="<?php echo base_url('dashboard/data_entry_form') ?>" method="post">
    <input name="id_entry" type="text">    
    <button type="submit"></button>
  </form>
</div>