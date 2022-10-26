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
  <a href="/bank/history.php" class="btn btn-primary" style="margin:auto; margin-top:30px;">See transaction history</a>
</div>

<div class="text-center">
  <a href="/bank/edit.php" class="btn btn-primary" style="margin:auto; margin-top:30px;">Edit Profile</a>
</div>
  <div id="error" style="width:50%; margin-left:500px; margin-top:100px;" role="alert"></div>

  <h3 class="text-center" style="margin-top:100px; margin-bottom:20px; margin-right:190px;">Send money: </h3>
  
  <div>
  <div class="form-group" style="max-width:350px; margin:auto; margin-top:10px;">
        <div>  
        <label for="email">Recipient email</label>
        <input id="email" type="email" name="amount" class="form-control input-lg" id="email" style="margin-bottom:20px" aria-describedby="emailHelp" placeholder="Email address">
        <label for="transfer">Amount to transfer:</label>
        <input id="transfer" name="amount" class="form-control input-lg" id="email" style="margin-bottom:20px" aria-describedby="emailHelp" placeholder="Amount">
        <button id="send" class="btn btn-primary">Send</button>
        </div>
  </div>
  <div class="form-group" style="max-width:350px; margin:auto; margin-top:10px;">
  <button id="logout" class="btn btn-primary">Log out</button>
</div>
  <script>
    $("#send").click(function(){
        $.post("api.php",{"action":"transfer","email":$("#email").val(),"amount":$("#transfer").val()},function(data){
            console.log(data);
            var result = $.parseJSON(data);
            //console.log(result);
            if(result[0] == "success"){
                $("#balance").text("Your balance is: $" + result[1]);
                $("#error").addClass("alert alert-success");
                $("#error").text("Transaction completed!");
            }else{
                $("#error").addClass("alert alert-danger");
                $("#error").text(result[0]);
            }

  });
    });

    $("#logout").click(function(){
        
        $.post("api.php",{"action":"logout"} ,function(data){
            location.reload();
    });
});
    </script>
</body>
</html>