<!DOCTYPE html>
<html>
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="favicon_16.ico"/>
  <link rel="bookmark" href="favicon_16.ico"/>
  <!-- site css -->
  <link rel="stylesheet" href="dist/css/site.min.css">
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,700,400italic,600italic,700italic,800italic,300italic" rel="stylesheet" type="text/css">
  <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="dist/js/site.min.js"></script>
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      font: 14px/1 Arial, sans-serif;
      background-color:#f1f2f6 ;
    }
    .full-panel {
      border: 1px solid grey;
      border-radius: 4px;
    }
    .panel-heading {
        background: #ddd; border: 1px solid #bbb1b1; border-radius: 4px 4px 0px 0px; border-bottom: none;
    }
   td{
    padding: 6px 0px 6px 10px!important;
    border: none!important;
    }
    label {
          padding: 0px 40px 0px 0px;
    }
    
    .button-group {
        margin: 20px 0px 20px 0px;
    }
  </style>
</head>
<body>

 <?php require_once('header.php'); ?>

 <div class="container-fluid">
  <div class="row">
    <div class="col-md-2 col-sm-3 col-lg-2">
      <?php require_once('nav.php');?>
    </div>

    <div class="col-md-10 col-sm-9 col-lg-10">
      <div class="full-panel">
      <div class="panel-heading">
          <h3 class="panel-title">Edit Currency</h3>
      </div>
      <div class="table-responsive" style="padding: 40px 30px;">
      <table class="table">
       <tr>
                  <td>Currency : </td>
                  <td>
                    <input type="text" name="currency" id="currency">
                  </td>
                </tr>

                <tr>
                  <td>Currency Code : </td>
                  <td>
                    <input type="text" name="currencyCode" id="currencyCode">
                  </td>
                </tr>
            
                <tr class="button-group">
                  <td>
                    <button class="btn btn-info" onclick="window.location.href= 'manageCurrency.php' ">Save</button>
                  </td>
                  <td>
                    <button class="btn btn-info"  onclick="window.location.href= 'manageCurrency.php' ">Back</button>
                  </td>
                </tr>  
    

      </table>
    </div>
    </div>
    </div>


  </div>

</div>

</body>
</html>