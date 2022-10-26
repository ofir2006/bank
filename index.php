<?php 
session_start();

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>body{
        background-color: rgb(212, 212, 212);
    }  
</style>
</head>
  
  
  <body>
   

      <h1 class="text-center" style="margin-top:40px;">Welcome to Bank of E</h1>

      <h2 class="text-center" style="margin-top:40px; margin-right:260px;">Login:</h2>
      <div class="text-center">
    
        
        <div class="form-group" style="max-width:350px; margin:auto; margin-top:20px;">
        <div>  
        <input id="email" type="email" name="email" class="form-control input-lg" id="email" style="margin-bottom:20px" aria-describedby="emailHelp" placeholder="Email address">
        </div>
        <div class="form-group">
          <input id="password" type="password" name="password" class="form-control" id="password" placeholder="Password">
        </div>
        <button id="login" class="btn btn-primary" style="margin-top:30px;margin-right:100px">Login</button>
        <a href="/bank/register.php" class="btn btn-primary" style="margin-top:30px;">Register</a>
    </div>
  </div>

  <div id="error" style="margin:auto" role="alert"></div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
  
<script>
    $("#login").click(function(){
       $.post("api.php",{"action":"login","email":$("#email").val(),"password":$("#password").val()} ,function(data){
     console.log(data);
        if (data == "success"){
      location.href="/bank/main.php"
     }else{
      $("#error").addClass("alert alert-danger");
      $("#error").text("Wrong credentials!");

     }
  });
    });
</script>



</body>
</html>