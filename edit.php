<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        body{
            background-color: rgb(212, 212, 212);
        }
    </style>
</head>
  
  
  <body>
   

      <h1 class="text-center" style="margin-top:40px;">Welcome to Bank of ITSafe</h1>

      <div id="error" style="width:50%; margin-left:500px; margin-top:100px;" role="alert"></div>

      <h2 class="text-center" style="margin-top:40px; margin-right:170px;">Edit details:</h2>
      <div class="text-center" style="max-width:350px; margin:auto; margin-top:20px;">
    
        <div class="form-group">
            <input id="firstName" class="form-control input-lg" id="exampleInputEmail1" style="margin-bottom:20px" aria-describedby="emailHelp" placeholder="First name">
          </div>
        <div class="form-group">
            <input id="lastName" class="form-control input-lg" id="exampleInputEmail1" style="margin-bottom:20px" aria-describedby="emailHelp" placeholder="Last name">
          </div>
        <div class="form-group">
          <input id="email" class="form-control input-lg" type="email" style="margin-bottom:20px" aria-describedby="emailHelp" placeholder="Email address">
        </div>
        <div class="form-group">
          <input id="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" style="margin-bottom:20px">
        </div>
          <button id="save" class="btn btn-primary" style="margin-top:30px;">Save</button>
          <a href="/bank/main.php" class="btn btn-primary" style="margin-top:30px;margin-left:100px">Home page</a>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
  <script>
    $("#save").click(function(){
       $.post("api.php",{"action":"edit","firstName":$("#firstName").val(),"lastName":$("#lastName").val(),"email":$("#email").val(),"password":$("#password").val()} ,function(data){
        console.log(data);
        $("#error").html(data);
  
    });
});

  </script>

  </body>
</html>