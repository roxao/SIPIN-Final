<!DOCTYPE html>
<html>
<head>
	<title>Dashboard :: Login</title>
</head>
<body>

<div class="o-layout" style="margin: 25vh auto">
  <div id="box-login" class="box-layout">
      <div class="o-title">
        <div class="o-text-title">MASUK</div>
        <div class="o-close">x</div>
      </div>
      <div class="o-header">
        <div class="o-logo">
          <img src="<?php echo base_url(); ?>/assets/logo.png" alt="SIPIN">
          <div class="o-sub-logo">Masuk Dashboard</div>
        </div>
      </div>
      <div class="o-content">
        <form class="o-form" action="<?php echo base_url('dashboard/user/authorize') ?>" method = "POST">
            <div class="o-error" data-msg="login">Tampilkan error message disini</div>
            <input required type="username" name="username" placeholder="Username">
            <input required type="password" name="password" placeholder="Password">
            <button type="submit">Masuk</button>
        </form>
      </div>
  </div>
</div>

	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/style.css">
</body>
</html> 
