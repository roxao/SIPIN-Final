<div class="content-background"></div>
<section id="section-survey" class="sheets_paper">
	<div class="survey-desc">
		<h1 class="title-survey">Survei Kepuasan Pelanggan</h1>
		<p class="p-survey">Silakan mengisi survei kepuasan pelanggan terhadap pelayanan penerbitan dan pengawasan IIN oleh Sekretariat Layanan dan submit melalui sistem SIPIN ini. Dokumen Informasi Penerbitan IIN anda akan otomatis terunduh setelah Anda submit survei kepuasan pelanggan. Terima kasih.</p>
	</div>
	<br/>
	
	<article>
		<div id="questionnaire">
			<form action="<?=base_url('survey/insert-survey')?>" method="post" accept-charset="utf-8">

				<input class="hidden" name="survey" value="<?=$survey.'|'.sizeof($data)?>">
				<?php 
					for ($i=0; $i < sizeof($data); $i++) { 
					 	if($data[$i]['type'] == 'RATING') { ?>
							<div class="questionnaire rating">
								<div class="quiz-question">
									<div class="quiz-no"><?=$data[$i]['no']?>.</div>
									<div class="quiz-question-content"><?=$data[$i]['msg']?></div>
								</div>
								<div class="answer-choice">
									<div class="answer-hint">Nilai:</div>
									<div class="answer-rate display-flex">
										<?php for ($rate=1;$rate<6;$rate++) { ?>
											<label for="rate<?php echo $data[$i]['no'].$rate ?>"><?=$rate?></label>
											<input
												type="radio" 
												id="rate<?=$data[$i]['no'].$rate?>" 
												name="answer<?=$data[$i]['no']?>" 
												value="<?=$rate?>" 
												<?=$rate==5?'checked':''?>>
										<?php } ?>
									</div>
								</div>
							</div>
						<?php } else { ?>			
							<div class="questionnaire comment">
								<div class="quiz-question">
									<div class="quiz-no"><?php echo ($i+1); ?>.</div>
									<div class="quiz-question-content"><?php echo $data[$i]['msg']; ?></div>
								</div>
								<div class="quiz-answer-content">
									<textarea class="answer-comment" name="comment<?php echo $data[$i]['no'] ?>"></textarea>
								</div>
							</div>

				<?php }} ?>
				<button class="btn-submit-survey" name="submit_survey" type="submit">KIRIM</button>
			</form>
		</div>
	</article>
</section>	

