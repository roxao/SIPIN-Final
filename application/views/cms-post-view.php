

    <section id="cms-section" class="clearfix sheets_paper">
      <div class="cms-header">
        <div class="cms-header-info">
          <div class="site-map">
            Halaman Depan > <span>Informasi Layanan IIN</span>
            <!-- <span> > <?=$cms[0]['title'] ?></span> -->
            <div class="cms-date">Tanggal: <span><?=date("d M Y", strtotime($cms[0]['created_date'])) ?></span></div>
          </div>
          <div class="cms-share">
            <div>Bagikan:</div>
            <div>
              <a class="shareToFB" href="#" title="Bagikan ke Twitter">
                <svg class="sprite_icon" fill="#3b5998"><use xlink:href="<?=base_url("assets/ic_socmed.svg#ic_fb")?>"/></svg></a>
              <a class="shareToTW" href="#" title="Bagikan ke Twitter">
                <svg class="sprite_icon" fill="#00aced"><use xlink:href="<?=base_url("assets/ic_socmed.svg#ic_tw")?>"/></svg></a>
            </div>
          </div>
        </div>
        <h1 class="cms-header-title"><?=$cms[0]['title'] ?></h1>
      </div>
      <article class="cms-content">
        <?=$cms[0]['contents'] ?>
      </article>
    </section>

<script>
    document.title = '<?=$cms[0]['title'] ?>';
    $('.shareToTW').on('click', function(){
      window.open("https://twitter.com/share?url="+escape(window.location.href)+"&text="+document.title+' - ', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
      return false;
    })
    $('.shareToFB').on('click', function(){
      window.open('https://www.facebook.com/sharer/sharer.php?u=' + document.URL, 'facebook-popup', 'height=350,width=600');
      return false;
    })
</script>
