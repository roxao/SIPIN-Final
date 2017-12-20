<div class="content-background"></div>
<section id="section-survey" class="sheets_paper">
	<div class="survey-desc">
		<h1 class="title-survey">Survei Kepuasan Pelanggan</h1>
		<p class="p-survey">Silakan mengisi survei kepuasan pelanggan terhadap pelayanan penerbitan dan pengawasan IIN oleh Sekretariat Layanan dan submit melalui sistem SIPIN ini. Dokumen Informasi Penerbitan IIN anda akan otomatis terunduh setelah Anda submit survei kepuasan pelanggan. Terima kasih.</p>
		
		<p class="p-survey" style="text-align:left"><br/>
			Keterangan: 
			<br/>1 &nbsp; :  &nbsp; Sangat Tidak Setuju
			<br/>2 &nbsp; :  &nbsp; Tidak Setuju
			<br/>3 &nbsp; :  &nbsp; Ragu-ragu
			<br/>4 &nbsp; :  &nbsp; Setuju
			<br/>5 &nbsp; :  &nbsp; Sangat Setuju

		</p>
	</div>
	<br/>
	
	<article>
		<div id="questionnaire">
			<form action="<?=base_url('survey/insert-survey')?>" method="post" accept-charset="utf-8">

				<input class="hidden" name="survey" value="<?=$survey.'|'.sizeof($data)?>">
				<div class="questionnaire rating">
								<div class="quiz-question">
									<div class="quiz-no the-label">No.</div>
									<div class="quiz-question-content"><div class="the-label">Pertanyaan</div></div>
								</div>
								<div class="answer-choice">
									<div style="padding-bottom: 10px">Persepsi (Kenyataan yang diperoleh) Pemohon dalam menerima layanan </div>
									<div class="answer-rate" style="border-top: 1px solid #eee; padding-top: 10px">
											<div class="the-caption" ><div>1</div></div>
											<div class="the-caption" ><div>2</div></div>
											<div class="the-caption" ><div>3</div></div>
											<div class="the-caption" ><div>4</div></div>
											<div class="the-caption" ><div>5</div></div>
									</div>
								</div>
							</div>
				<?php 
					for ($i=0; $i < sizeof($data); $i++) { 
					 	if($data[$i]['type'] == 'RATING') { ?>
							<div class="questionnaire rating">
								<div class="quiz-question">
									<div class="quiz-no"><?=$data[$i]['no']?>.</div>
									<div class="quiz-question-content"><?=$data[$i]['msg']?></div>
								</div>
								<div class="answer-choice">
									<!-- <div class="answer-hint">Nilai:</div> -->
									<div class="answer-rate display-flex">
										<?php for ($rate=1;$rate<6;$rate++) { ?>
											
											<input
												type="radio" 
												id="rate<?=$data[$i]['no'].$rate?>" 
												name="answer<?=$data[$i]['no']?>" 
												value="<?=$rate?>" 
												<?=$rate==5?'checked':''?>>
												<label for="rate<?php echo $data[$i]['no'].$rate ?>"><?=$rate?></label>
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

