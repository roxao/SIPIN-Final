
    <section id="iin-section" class="clearfix sheets_paper">
      <div class="iin-header">
        <span class="site-map"><a href="<?php echo base_url() ?>" style="color:#3b9f58">HOME</a> > Penerima IIN</span>
        <h1 class="page-header-title">DAFTAR PENERIMA IIN</h1>
      </div>
      <article id="table-iin">

        <?php if(isset($download_iin)){ ?>
          <div class="clearfix">
            <a class="download-iin-file" href="<?php echo base_url();?>submit_iin/download?var1=<?php echo $download_iin[0]['path_file'];?>">Download IIN Anda</a>
          </div><br/>
        <?php } ?>
        <div class="table-list-iin">
        <table>
          <thead>
            <tr>
              <th class="sort" data-sort="id_1"><span>No</span></th>
              <th class="sort" data-sort="id_2"><span>Nama Perusahaan</span></th>
              <th class="sort" data-sort="id_3"><span>Email Perusahaan</span></th>
              <th class="sort" data-sort="id_4"><span>Tlp. Perusahaan</span></th>
              <th class="sort" data-sort="id_5"><span>Lokasi</span></th>
              <th class="sort" data-sort="id_6"><span>Pengesahan</span></th>
              <th class="sort" data-sort="id_7"><span>Kadaluarsa</span></th>
              <th class="sort" data-sort="id_iin_cell"><span>Nomor IIN</span></th>
            </tr>
          </thead>
          <tbody class="list">
          <?php foreach ($iin as $key => $row) {?>
            <tr>
              <td class="id_1"><?=($key)+1 ?> </td>
              <td class="id_2 id_iin_name"><?=$row->instance_name ?> </td>
              <td class="id_3"><?=$row->instance_email ?> </td>
              <td class="id_4"><?=$row->instance_phone ?> </td>
              <td class="id_5"><?=ucwords(strtolower($row->mailing_location)) ?></td>
              <td>
                <span class="id_6 hidden"><?=$row->iin_established_date?></span>
                <?=date("d M Y", strtotime($row->iin_established_date)) ?> </td>
              <td>
                <span class="id_7 hidden"><?=$row->iin_expiry_date?></span>
                <?=date("d M Y", strtotime($row->iin_expiry_date)) ?> </td>
              <td class="id_iin_cell"><span><?=$row->iin_number ?></span></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
      </article>
    </section>

<script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/list.min.js"></script>
  <script type="text/javascript">
    $('document').ready(function(){
      $.set_table_list('table-iin', 20);
    });
  </script>
