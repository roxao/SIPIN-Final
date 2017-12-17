<?php
  $page_title = 'Dashboard :: Pengaturan Survey :: Ubah Survey';
  $page_section = 'Survey Form';
?>



<section class="dashboard_content sheets_paper" style="max-width: 800px">
  <section class="main_dashboard_slidetab">
    <div class="site-map">
      <a href="<?php echo base_url('dashboard')?>">Dashboard</a><span></span>
      <a href="<?php echo base_url('dashboard')?>">Pengaturan</a><span></span>
      <?php echo $page_section ?>
    </div>
    <center><h2 class="title_content"><?php echo $page_section ?></h2></center>

    <div id="survey-form">
      <form action="<?php echo base_url('dashboard/survey_question/'.(($data) ? 'insert': 'insert')); ?>" method="post">
        <input class="hidden" name="id_survey" value="<?php echo ($data) ? $data[0]['id_survey_question']: ''?>">

        <div class="survey-option">
          <label class="hidden" style="display: none">
            <span>Status Survei:</span>
            <select name="question_status">
              <option value="1" selected>Aktif</option>
              <option value="0" >Tidak Aktif</option>
            </select>
          </label>
          <label>
            <span id="btn-add-row-survey">Tambah Pertanyaan</span>
          </label>

        </div>
        <div class="survey-form"></div>
        <div class="survey-footer">
          <button class="btn-submit-survey" type="submit">MASUKAN SURVEY</button>
        </div>

      </form>
    </div>
  </section>

  <script type="text/javascript">
    $('document').ready(function(){
      document.title = '<?php echo $page_title ?>';
      <?php

          $question = json_decode($data[0]['question'], true);
          for ($i=0; $i < count($question); $i++) {
            echo "$.set_add_row_survey_form('.survey-form', '".$question[$i]['type']."', '".$question[$i]['msg']."');";
          }
       ?>
      // $.set_add_row_survey_form('.survey-form', 'RATING', '');

      $('#btn-add-row-survey').on('click', function(event) {
        $.set_add_row_survey_form('.survey-form', 'RATING', '');
      });
   });
  </script>
</section>
