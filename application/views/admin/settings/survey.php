<?php
  $page_title = 'Dashboard :: Pengaturan Survei';
  $page_section = 'Pengaturan Survei';
?>

<section class="dashboard_content sheets_paper">
  <section class="main_dashboard_slidetab">
    <div class="site-map">
      <a href="<?php echo base_url('dashboard') ?>">Dashboard</a><span></span>
      <a href="<?php echo base_url('dashboard') ?>">Pengaturan</a><span></span>
      <?php echo $page_section ?>
    </div>
    <h2 class="title_content">Pengaturan Survei </h2>

    <div id="tableInbox" style=" margin: 0 -20px 0 -20px">
      <div class="opt-table clearfix">
        <button id="btn-add" class="btn-flat float_left">TAMBAH SURVEI</button>
        <div class="opt-table-filter float_right">
          <input class="search filter_search" placeholder="Search ..." />
        </div>
      </div>
      <div class="table_content">
        <table class="table_def tableInbox" style="width: 100%;">
          <tr>
            <th style="width:55px" class="sort" data-sort="id_version">No</th>
            <th style="min-width:400px" class="sort" data-sort="id_question">Pertanyaan</th>
            <th style="min-width:100px" class="sort" data-sort="id_date">Tanggal</th>
            <th style="min-width:100px" class="sort" data-sort="id_by">Dibuat Oleh</th>
            <th style="min-width:100px" class="sort" data-sort="id_status">Status</th>
            <th style="width:50px">&nbsp;</th>
          </tr>
          <tbody class="list table-survey">
            <?php foreach($data as $key=>$survey) { ?>
              <tr >
                <td class="id_version"><?=$key+1?></td>
                <td class="id_question">
                <?php

                  $question = json_decode($survey['question'], true);

                  for ($i=0; $i < count($question); $i++) {
                    echo '<div class="row-survey"><span>'.$question[$i]['no'].'.</span>'.$question[$i]['msg'].'</div>';
                  }
                ?>
                </td>
                <td>
                  <span class="id_date hidden"><?php echo $survey['created_date']?></span>
                  <?php echo date("D, d M Y", strtotime($survey['created_date'])) ?>
                  </td>
                <td class="id_by"><?php echo $survey['created_by'] ?></td>
                <td class="id_status"><?php echo ($survey['question_status']==1?'AKTIF':'TIDAK AKTIF')?></td>
                <td>
                  <button class="btn-survey-edit" data-id="<?php echo $survey['id_survey_question'] ?>"  ></button>
                </td>
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
    </div>
  </section>

  <script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/list.min.js"></script>
  <script type="text/javascript">
    $('document').ready(function(){
      var options = {valueNames: [ 'id_question', 'id_version', 'id_date', 'id_by', 'id_status' ],page: 10,pagination: true};
      var inboxList = new List('tableInbox', options);


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

    $('#btn-add').on('click', function()
    {
      $('[name=id_surv').val('insert');
      $('.modal-form button').click();
    })



    });

     $('.btn-survey-edit').on('click', function() {
        $('[name=id_surv').val($(this).attr('data-id'));
        $('.modal-form button').click();
      })
  </script>
</section>


<div class="z-modal-frame" style="display: none;">
  <form class="modal-form" action="<?php echo base_url('dashboard/settings/survey_form') ?>" method="post">
    <input name="id_surv" type="text">
    <button type="submit"></button>
  </form>
</div>
