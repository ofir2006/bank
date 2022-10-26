<?php 
session_start();

if(!isset($_SESSION['firstName'])){
    header("Location: /bank/index.php");
}
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
   
  <h1 class="text-center" style="margin-top:40px;">Welcome <?php echo $_SESSION["firstName"] . ' ' . $_SESSION["lastName"]; ?> </h1>
  <h2 id="balance" class="text-center" style="margin-top:40px;">Your balance is: $<?php echo $_SESSION['balance']; ?> </h2>
  <div class="text-center">
  <a href="/bank/main.php" class="btn btn-primary" style="margin:30px;">Home page</a>
  </div>
  <h3 id="balance" class="text-center" style="margin-top:40px;">Transactions: </h3>


  <div id="history"></div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

  <script>
    
        $.post("api.php",{"action":"history"},function(data){
            console.log(data);
            $("#history").html(data);
  });
    

    </script>
</body>
</html>