<html>
<head>
   <title>404</title>
   <style>
      body {
         margin: 0;
         padding: 0;
         font-family: Tahoma, Verdana, Helvetica, Arial;
         font-size: 15px;
         font-weight: bold;
         color: #444;
      }

      h1 {
         font-size: 30px;
      }

      .center {
         width:350px;
         height:150px;
         position:absolute;
         left:50%;
         top:40%;
         margin:-75px 0 0 -165px;
         text-align: center;
      }

      p {
         font-style: italic;
      }

      .footer {
         font-size: 13px;
         width: 100%;
         position: fixed;
         bottom: 0;
         background-color: #336699;
         padding: 20px 40px;
         text-align: center;
         color: #ECECEC;
      }
   </style>
</head>
<body>


<div class="center">

   <div>
      <img src="/packages/cednet/laravel-cms-addon/css/images/smc_404.png" />
   </div>
   <h1>Sidan hittades inte</h1>
   <p>
      <?php switch(rand(1,3)) {
         case '1':
            echo 'Sidan du försökte nå har fått punka...';
            break;
         case '2':
            echo 'Usch, vi glömde packningen...';
            break;
         case '3':
            echo 'Sidan är borta med vinden...';
            break;
      }
      ?>
   </p>

</div>

<div class="footer">
   &copy; <?php echo date("Y"); ?> Sveriges MotorCyklister
</div>


</body>
</html>

