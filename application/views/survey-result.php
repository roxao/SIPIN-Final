

    <section id="cms-section" class="survey-section clearfix sheets_paper">
      <div class="cms-header">
        <br/>
        <h1 class="cms-header-title">HASIL SURVEI</h1>
      </div>
      <article style="max-width: 900px">
          <div class="survey-header">
            <div class="id_no">No.</div>
            <div class="id_qs">Pertanyaan</div>
            <div class="id_as">Nilai</div>
          </div>
          <?php
          if (isset($survey)) {
          for ($i=0; $i < count($survey['survey_questions']) ; $i++):
                $x=$survey['survey_questions'][$i]?>
          <div class="survey-row">
            <div class="id_no"> <?= $x['no'] ?> </div>
            <div class="id_qs"> <?= $x['question'] ?> </div>
            <div class="id_as td-stars">
              <div class="c-rating_no"><span><?=(number_format($x['average'],1)*2)?></span>/10</div>
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
