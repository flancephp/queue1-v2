  <!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <title>Queue1.com</title>
      <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
      <link rel="shortcut icon" href="favicon_16.ico"/>
      <link rel="bookmark" href="favicon_16.ico"/>
      <!-- site css -->
      <link rel="stylesheet" href="dist/css/site.min.css">
  	  <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,700,400italic,600italic,700italic,800italic,300italic" rel="stylesheet" type="text/css">
      <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'> 
     
      <script type="text/javascript" src="dist/js/site.min.js"></script>

      <style>
        .icheckbox_flat {
          position: relative;
        }
        .icheckbox_flat input{
          position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;
        }
        .icheckbox_flat ins {
          position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;
        }
        .forgetLink{
          margin-top: 20px;
        }
      </style>
    </head>
    <body>

    <?php require_once('header.php'); ?>

  	 <div class="container-fluid">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"> Login</h3>
          </div>
          <br>
          <br>
  			  
  			<div class="container">
  	 	
        <form class="form-signin" role="form" action="" method="post">

          <h3 class="form-signin-heading">Please sign in</h3>
          <!----username field---->
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="glyphicon glyphicon-envelope"></i>
              </div>
              <input type="text" class="form-control" name="username" id="username" value="" placeholder="username" autocomplete="off" required />
            </div>
          </div>
          <!----password field---->
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class=" glyphicon glyphicon-lock"></i>
              </div>
              <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="" autocomplete="off" />
            </div>
          </div>
          <!----remember me field---->
      		<div class="form-group">
              <div class="input-group" >
  			        <div class="checkbox">
                    <label class="">
                      <div class="icheckbox_flat">
                        <input type="checkbox" name="cookieChk" value="1" >
                        <ins class="iCheck-helper"></ins>
                      </div>&nbsp;Remember me
                    </label>
                </div>
             </div>
           </div>
  		   
          <button class="btn btn-lg btn-primary" type="submit">Sign in</button> <br>

          <div class="forgetLink"><a href="forgotPass.php"><strong>Forgot Password?</strong></a></div>

        </form>

        </div>
  	<!-- panel body -->
      </div>
  </div><!-- content -->
      

  <div class="clearfix"></div>

  </body>
  </html>
