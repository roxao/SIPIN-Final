

    <section id="section-survey" class="survey-section clearfix sheets_paper">
      <div class="survey-desc">
        <h1 class="title-survey" style="color: #3b9f57">Survei Kepuasan Pelanggan</h1>
        <p class="p-survey">Silakan mengisi survei kepuasan pelanggan terhadap pelayanan penerbitan dan pengawasan IIN oleh Sekretariat Layanan dan submit melalui sistem SIPIN ini. Dokumen Informasi Penerbitan IIN anda akan otomatis terunduh setelah Anda submit survei kepuasan pelanggan. Terima kasih.</p>
      </div>
      <article style="max-width: 950px; margin: 0px auto 20px auto">
      <?php
          $sum_total = 0;
          if (isset($survey)) {
          for ($i=0; $i < count($survey['survey_questions']) ; $i++):
                $x=$survey['survey_questions'][$i];
                $sum_total += $x['average'] ;
              endfor ?>
          <?php }?>
          <div class="survey-row" style="border-top: 3px solid #3b9f57">
            <!-- <div class="id_no">  </div> -->
            <div class="id_qs" style="color: #3b9f57;font-size: 14pt;line-height: 1.5;">IKP</div>
            <div class="id_as td-stars">
              <div class="c-rating_no"style="color: #3b9f57;"><span><?=(number_format(($sum_total/$i),1))?></span></div>
            </div>
          </div>
          <div class="survey-header">
            <!-- <div class="id_no"></div> -->
            <div class="id_qs">Pertanyaan</div>
            <div class="id_as">Nilai</div>
          </div>
          
          <?php

          if (isset($survey)) {
          for ($i=0; $i < count($survey['survey_questions']) ; $i++):
                $x=$survey['survey_questions'][$i]?>
          <div class="survey-row">
            <!-- <div class="id_no"></div> -->
            <div class="id_qs"> <?= $x['question'] ?> </div>
            <div class="id_as td-stars">
              <div class="c-rating_no"><span><?=(number_format($x['average'],1))?></span>/5</div>
              <div class="c-rating hidden">
                <div class="c-rating_bg"></div>
                <div class="c-rating_fg" style="width: <?=((100/5)*$x['average'])?>%"></div>
              </div>
              <div class="c-rating_pv"><?=$survey['participant']?> Partisipan</div>
            </div>
          </div>
          <?php endfor ?>
          <?php }?>

          
          
      </article>
    </section>
