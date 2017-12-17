


<div class="o-layout">
  <!-- LOGIN FORM -->
  <div id="box-login" class="box-layout">
      <div class="o-title">
        <div class="o-text-title">MASUK</div>
        <div class="o-close">x</div>
      </div>
      <div class="o-header">
        <div class="o-logo">
          <img src="<?php echo base_url(); ?>/assets/logo.png" alt="SIPIN">
          <div class="o-sub-logo">Silakan masuk ke dalam akun</div>
        </div>
      </div>
      <div class="o-content">
        <form class="o-form" action="<?php echo base_url();?>user-login" onSubmit="return saveInputLogin();" method = "POST">
            <!-- TAMPILKAN ERROR MESSAGE DISINI -->
            <div class="o-error" data-msg="login">Tampilkan error message disini</div>
            <input required type="username" id="username_login" name="username" placeholder="Username">
            <input required type="password" name="password" placeholder="Password">
            <div class="o-url float_right" o-data="box-forgot">Lupa Kata Sandi?</div>
            <button type="submit">Masuk</button>
        </form>
      </div>
      <div class="o-footer">
        <div class="o-separator">
          <span>ATAU</span>
        </div>
        <div class="o-link-footer">
          Belum Punya Akun?
          <div class="o-url" o-data="box-register">Daftar Sekarang</div>
        </div>
    </div>
  </div>

  <!-- REGISTER FORM -->
  <div id="box-register" class="box-layout" style="display:none" >
    <div class="o-title">
        <div class="o-text-title">DAFTAR</div>
        <div class="o-close">x</div>
      </div>
      <div class="o-header">
        <div class="o-logo">
          <img src="<?php echo base_url(); ?>/assets/logo.png" alt="SIPIN">
          <div class="o-sub-logo">Daftar akun baru sekarang</div>
        </div>
      </div>
      <div class="o-content">
        <form class="o-form" action="<?php echo base_url();?>SipinHome/register" onSubmit="return saveInputRegis();" method = "POST">
            <!-- TAMPILKAN ERROR MESSAGE DISINI -->
            <div class="o-error" data-msg="register">Tampilkan error message disini</div>
            <div class="autocomplete-parent">
              <input required required type="text" id= "fullname" name="fullname" class="autocomplete" data-key="instance_name" placeholder="Nama Instansi">
              <span><i style="color:red" >*</i> Tidak Boleh Disingkat</span>
            </div>
            <input required type="username" id= "username" name="username" placeholder="Username">
            <input  type="text" pattern="\d*" maxlength="6" id= "iin-number" name="iin-number"  placeholder="Nomor IIN" >
            <span><i style="color:red" >*</i> Jika sudah memiliki IIN</span>
            <input required type="email" id= "email" name="email" placeholder="E-mail">
            <input required type="password" name="password" placeholder="Kata Sandi">
            <input required type="password" name="retype-password" placeholder="Ulang Kata Sandi">

            <div class="captchaForm"></div>

            <button class="btn-validate" type="submit">Daftar</button>
        </form>
      </div>
      <div class="o-footer">
        <div class="o-separator">
          <span>ATAU</span>
        </div>
        <div class="o-link-footer">
          Sudah Punya Akun?
          <div class="o-url" o-data="box-login">Masuk</div>
        </div>
    </div>
  </div>

    <!-- FORGOT FORM -->
  <div id="box-forgot" class="box-layout" style="display:none">
    <div class="o-title">
        <div class="o-text-title">LUPA KATA SANDI</div>
        <div class="o-close">x</div>
      </div>
      <div class="o-header">
        <div class="o-logo">
          <img src="<?php echo base_url(); ?>/assets/logo.png" alt="SIPIN">
          <div class="o-sub-logo">Link untuk merubah kata sandi akan di kirimkan ke email Anda</div>
        </div>
      </div>
      <div class="o-content">
        <form class="o-form" action="<?php echo base_url();?>lupa-kata-sandi" method = "POST">
            <!-- TAMPILKAN ERROR MESSAGE DISINI -->
            <div class="o-error" data-msg="forgot">Tampilkan error message disini</div>
            <input required type="email" name="E-mail" placeholder="E-mail">
            <span><i style="color:red" >*</i> Pastikan E-mail yang Anda masukkan sudah benar</span>
            <br/>
            <div class="captchaForm"></div>
            <button class="btn-validate" type="submit">Reset Kata Sandi</button>
        </form>
      </div>
      <div class="o-footer">
        <div class="o-separator">
          <span>ATAU</span>
        </div>
        <div class="o-link-footer">
          Kembali ke halaman
          <div class="o-url" o-data="box-login">Login</div>
        </div>
    </div>
  </div>

<!-- RESET FORM -->
<div id="box-reset" class="box-layout" style="display:none">
    <div class="o-title">
        <div class="o-text-title">LUPA KATA SANDI</div>
        <div class="o-close">x</div>
      </div>
      <div class="o-header">
        <div class="o-logo">
          <img src="<?php echo base_url(); ?>/assets/logo.png" alt="SIPIN">
          <div class="o-sub-logo">Silakan masukkan kata sandi baru Anda :</div>
        </div>
      </div>
      <div class="o-content">
        <form class="o-form" action="<?php echo base_url();?>reset-kata-sandi" method = "POST">
            <!-- TAMPILKAN ERROR MESSAGE DISINI -->
            <div class="o-error" data-msg="reset">Tampilkan error message disini</div>
            <input required type="password" name="password-new" placeholder="Kata Sandi Baru">
            <span><i style="color:red" >*</i> Masukkan kata sandi baru Anda</span>
            <input required type="password" name="retype-password-new" placeholder="Ulang Kata Sandi Baru">
            <br/>
            <div class="captchaForm"></div>
            <button class="btn-validate" type="submit">Reset Kata Sandi</button>
        </form>
      </div>

</div>

<!-- USER DETAIL -->
<div id="box-userdetail" class="box-layout" style="display:none">
    <div class="o-title">
        <div class="o-text-title">LUPA KATA SANDI</div>
        <div class="o-close">x</div>
      </div>
      <div class="o-header">
        <div class="o-logo">
          <img src="<?php echo base_url(); ?>/assets/logo.png" alt="SIPIN">
          <div class="o-sub-logo">Profil akun Anda</div>
        </div>
      </div>
      <div class="o-content">
        <form class="o-form" action="<?php echo base_url();?>ganti-kata-sandi" method = "POST">
            <label class="input_dashed float_left" style="width: 100%">
              Username
              <input   required id="userdetail_usrname" name="userdetail_usrname" type="text" placeholder="Username" disabled
              value=<?php echo $this->session->userdata('username');?> />
            </label>
            <label class="input_dashed float_left" style="width: 100%">
              Email
              <input   required id="userdetail_email" name="userdetail_email" type="text" placeholder="Email" disabled
              value=<?php echo $this->session->userdata('email');?> />
            </label>
            <button type="submit">Ganti Kata Sandi</button>
        </form>
      </div>
      <div class="o-footer">
        <div class="o-separator">
          <span>ATAU</span>
        </div>
        <div class="o-link-footer">
          Kembali ke
          <div >
            <a href="<?php echo base_url();?>">Halaman Depan</a>
          </div>
        </div>
    </div>
</div>

<!-- CHANGE PASSWORD -->
<div id="box-changepassword" class="box-layout" style="display:none">
    <div class="o-title">
        <div class="o-text-title">GANTI KATA SANDI</div>
        <div class="o-close">x</div>
      </div>
      <div class="o-header">
        <div class="o-logo">
          <img src="<?php echo base_url(); ?>/assets/logo.png" alt="SIPIN">
          <div class="o-sub-logo">Silakan masukkan kata sandi baru Anda</div>
        </div>
      </div>
      <div class="o-content">
        <form class="o-form" action="<?php echo base_url();?>ganti-kata-sandi" method = "POST">
            <!-- TAMPILKAN ERROR MESSAGE DISINI -->
            <div class="o-error" data-msg="changepassword">Tampilkan error message disini</div>
            <input required type="password" name="password-change" placeholder="Kata Sandi Baru">
            <input required type="password" name="retype-password-change" placeholder="Ulang Kata Sandi Baru">
            <br/>
            <div class="captchaForm"></div>
            <button class="btn-validate" type="submit">Ganti Kata Sandi</button>
        </form>
      </div>
      <div class="o-footer">
        <div class="o-separator">
          <span>ATAU</span>
        </div>
        <div class="o-link-footer">
          Kembali ke halaman
          <div class="o-url" o-data="box-userdetail">Profil</div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="<?php echo base_url() ?>/assets/style.css">
<script type="text/javascript" src="<?php echo base_url() ?>/assets/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.min.js')?>"></script>
<script type="text/javascript">

    $(document).ready(function() {
      $(".autocomplete").autocomplete({
        source:function(request,response){$.ajax({
        url: "<?php echo base_url('dashboard/get_autocomplete/')?>" + $('.autocomplete').attr('data-key'),
        dataType: "json",
        data:{term: $(".autocomplete").val()},
        success: function( data ) {
          response(data);}
          });
        },
        minLength: 2,
        appendTo: ".autocomplete-parent",
        autoFocus: true,
        select: function( event, ui ) {
          event.preventDefault();
        },
      });
    });

  $('.o-url').on('click', function(event) {
    event.preventDefault();
    show_box($(this).attr('o-data'));
  });

  // show_box("box-login");
  function show_box(x){
    $('.box-layout').hide();
    $('#'+x).fadeIn('slow');
    $('.captchaForm').empty();
    $('#'+x).find('.captchaForm').append($('<script>').prop('src', 'https://www.google.com/recaptcha/api.js')).append('<div class="g-recaptcha googleCaptcha" data-sitekey="6LdDdjsUAAAAAA9zTbj6Rpf3qsNn0qsWqzJ6VMKi"></div><div class="message-captcha" style="color:red; font-style:italic"></div>');
    $('.btn-validate').click(function(){
      if (grecaptcha.getResponse().length === 0){
        $( '.message-captcha').text( "reCAPTCHA is mandatory" );
        return false;
      }
    })
    }

  console.log("<?php echo $type ?> <?php echo $message ?>");
  show_action("<?php echo $type ?>", "<?php echo $message ?>");
  $('.o-close').on('click', function(event) {
    event.preventDefault();
    $('#show_popup').remove();
  });
  $('[name=iin-number]').keydown(function(e) {
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
          (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
          (e.keyCode >= 35 && e.keyCode <= 40)) {
               return;
      }
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
      }
  });

  function show_action(a,b){
    if(a!=""){
      show_box("box-"+a+"");
    }
    else {
      show_box("box-login");
    }
    if(b!=""){
      show_box("box-"+a+"");
      $('div[data-msg="'+a+'"').slideDown();
      $('div[data-msg="'+a+'"').html(b);
    }
  }



  document.getElementById("username_login").value = localStorage.getItem("loginUsername");
  function saveInputLogin() {
      var ulogin = document.getElementById("username_login").value;
      localStorage.setItem("loginUsername", ulogin);
  }

  document.getElementById("fullname").value = localStorage.getItem("regisFullname");
  document.getElementById("username").value = localStorage.getItem("regisUsername");
  document.getElementById("iin-number").value = localStorage.getItem("regisIin");
  document.getElementById("email").value = localStorage.getItem("regisMail");


  function saveInputRegis() {

      var fname = document.getElementById("fullname").value;
      var uname = document.getElementById("username").value;
      var iin = document.getElementById("iin-number").value;
      var mail = document.getElementById("email").value;

      localStorage.setItem("regisFullname", fname);
      localStorage.setItem("regisUsername", uname);
      localStorage.setItem("regisIin", iin);
      localStorage.setItem("regisMail", mail);
      // alert("Your comment has been saved!");
  }


</script>
<style>
  .popup_box{position:fixed;margin:10vh auto;left:0;right:0;top:0;max-width:400px;min-width:300px;z-index:20000;background:#fff;max-height:80vh;overflow:auto;border-radius:2px}
  .popup_box .o-header{ display: none  }
  .popup_box .o-title{ display: block  }
  .popup_box .o-content{ padding: 20px 25px 0 25px  }
  .frame_popup{background: rgba(0,0,0,0.5); position: fixed; top:0;left:0;right:0;bottom: 0;z-index: 19999}
</style>
