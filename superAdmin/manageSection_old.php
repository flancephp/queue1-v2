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
      background-color:#f1f2f6;
      overflow:inherit;
    }
    table {
      border-collapse: collapse;
      background: white;
      width: 100%;
    }
    th {
      position: sticky;
      background-color: #3bafda;
      top: 0;
      z-index: 1;
    }
    td {
      padding: 8px 20px;
      border:  none;
      /*overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;*/
    }
    .bc-color{
      background-color: #3bafda;
      color: #fff;
    }
     @media(max-width: 767px) {
        .table-responsive>.table-bordered {
          border: 1px solid #ddd!important;
      }
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
     <div width: 30%; style="display: flex; grid-gap: 15px;">
      <div><button class="btn btn-info" onclick="window.location.href= 'addSection.php' ">Add Section</button></div>
      <div><button class="btn btn-info" onclick="window.location.href= 'index.php' ">Back</button></div>
    </div>
  </div>

  <div class="col-md-10 col-sm-9 col-lg-10">
    <div class="table-responsive" style="height: 470px; overflow: scroll;">

          <table class="table table-bordered">

            <tr class="bc-color">
              <th scope="col">Serial no.</th>
              <th scope="col">Sections</th>
              <th scope="col">Default</th>
              <th scope="col">Action</th>
            </tr>

            <tr>
              <td>1</td>
              <td>Queue1 System</td>
              <td>Yes</td>
              <td><span class="glyphicon glyphicon-edit" title="Edit" onclick="window.location.href='editSection.php' "></span> | <span class="glyphicon glyphicon-trash" title="Delete"  onclick="window.location='' "></span></td>
            </tr>

        </table>
        
      </div>
  </div>

</div>

</div>

</body>
</html>