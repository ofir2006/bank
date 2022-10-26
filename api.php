<?php
session_start();

$MySQLdb = new PDO("mysql:host=127.0.0.1;dbname=bank", "root", "");
$MySQLdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if (isset($_POST['action'])){

    
    switch ($_POST['action']){
        case "login":
            $email = $_POST["email"];
            $password = $_POST["password"];
            $cursor = $MySQLdb->prepare("SELECT * FROM users WHERE email=:email");
            $cursor->execute( array(":email"=>$email));
            
            
           
         
            if($cursor->rowCount()){
                $output = $cursor->fetchAll(PDO::FETCH_ASSOC);
                $hashed_password = $output[0]['password'];
                if(password_verify($password, $hashed_password)){
                $_SESSION["firstName"] = $output[0]['firstname'];
                $_SESSION["lastName"] = $output[0]['lastname'];
                $_SESSION["balance"] = $output[0]['money'];
                $_SESSION["email"] = $output[0]['email'];
                echo "success";    
               
                }
                else{
                    echo "Wrong credentials!";
                }
            }
            else{
                echo "Wrong credentials!";
            }
            break;
        
            case "register":
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];
            $password = $_POST["password"];
            $verifiedpassord = $_POST["password2"];
            $email = $_POST["email"];
           
            if(strlen($firstName) < 2){
                echo "First name must be atleast 2 characters long";
            }
            else if(strlen($lastName) < 2){
                echo "Last name must be atleast 2 characters long";
            }
            else if (!preg_match("/^[a-zA-Z-' ]*$/",$firstName)) {
                echo "First name contains illegal characters";
           }
           else if (!preg_match("/^[a-zA-Z-' ]*$/",$lastName)) {
            echo "Last name contains illegal characters";
           }  
           else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email format";
            } 
            else if ($password != $verifiedpassord){
                   echo "passwords do not match!";
            }
            else if(strlen($password) < 7){
                    echo "Password must be atleast 7 characters long";
                }
            
            else {
                $cursor = $MySQLdb->prepare("SELECT * FROM users WHERE email=:email");
                $cursor->execute( array(":email"=>$email));
                $output = $cursor->fetchAll(PDO::FETCH_ASSOC);

                if(!isset($output[0]['email'])){
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $cursor = $MySQLdb->prepare("INSERT INTO users (firstname, lastname, email, password,money) value (:firstname,:lastname,:email,:password,:money)");
                $cursor->execute(array(":email"=>$email, ":password"=>$hashed_password,":firstname"=>$firstName,":lastname"=>$lastName, ":money"=>5000));
                  echo "success";
                } 
                else{
                    echo "Email already exists!";
                }
        }
                break;

        case "transfer":
                    $recEmail = $_POST['email'];
                    $amount = $_POST['amount'];
                    
                    $cursor = $MySQLdb->prepare("SELECT * FROM users WHERE email=:email");
                    $cursor->execute( array(":email"=>$recEmail));
                    

                    if($cursor->rowCount()){
                        $rec = $cursor->fetchAll(PDO::FETCH_ASSOC);
                        


                        $current = $MySQLdb->prepare("SELECT * FROM users WHERE email=:email");
                        $current->execute( array(":email"=>$_SESSION['email']));
                        $currentdetails = $current->fetchAll(PDO::FETCH_ASSOC);
                        $currentMoney = $currentdetails[0]['money'];
                        

                        if($currentdetails[0]['email'] == $rec[0]['email']){
                            echo json_encode(array("You cannot transfer money to yourself!"));
                        }
    
                        else if($currentMoney >= $amount && $amount > 1){
                            $_SESSION['balance'] = $currentMoney - $amount;
                            $updateSelf = $MySQLdb->prepare("UPDATE users SET money=:money WHERE email=:email");
                            $updateSelf->execute( array(":money"=>$currentMoney - $amount ,":email"=>$_SESSION['email']));
                            $updateRec = $MySQLdb->prepare("UPDATE users SET money=:money WHERE email=:email");
                            $updateRec->execute( array(":money"=>$rec[0]['money'] + $amount ,":email"=>$rec[0]['email']));


                            $cursor = $MySQLdb->prepare("INSERT INTO transactions (sender, recipient, date,amount) value (:sender,:recipient,:date,:amount)");
                            $cursor->execute(array(":sender"=>$currentdetails[0]['id'], ":recipient"=>$rec[0]['id'],":date"=>date('Y-m-d H:i:s'),":amount"=>$amount));
                            
                            echo json_encode(array("success", $currentMoney - $amount));
                        }
                        else if(!is_numeric($amount)){
                            echo json_encode(array("Amount must be a number!"));
                        }
                        else if($amount < 1){
                            echo json_encode(array("Amount cannot be lower than one dollar"));
                        }

                        else{
                            echo json_encode(array("Not enough money!"));
                        }

                    } 
                        else{
                            echo json_encode(array("Recipient does not exist!"));
                            
                        }
                    
                    break;

                    case "logout":
                        session_destroy();
                    break;
           
                    case "history":
                        $current = $MySQLdb->prepare("SELECT * FROM users WHERE email=:email");
                        $current->execute( array(":email"=>$_SESSION['email']));
                        $currentdetails = $current->fetchAll(PDO::FETCH_ASSOC);

                        

                        $id = $currentdetails[0]['id'];
                        $retval="";
                        $transactions = $MySQLdb->prepare("SELECT * FROM transactions WHERE sender=:id OR recipient=:id ORDER BY id DESC");
                        $transactions->execute( array(":id"=>$id));
                        $users = $MySQLdb->prepare("SELECT * FROM users WHERE id=:id");
                        $i = 0;
                        foreach($transactions->fetchAll() as $transaction){
                            $i = $i +1;
                            if($transaction['sender'] == $id){
                            $type = "Outgoing";
                        }
                        else{
                            $type="Incoming";
                        }
                            $users->execute( array(":id"=>$transaction['recipient']));
                            $output = $users->fetchAll();
                            $recipient = $output[0]['firstname'] . ' ' . $output[0]['lastname'];
                            $retval = $retval . "<div class='text-center' style='margin-bottom:30px;'>Type: ". $type ."</br>Recipient: ".$recipient."</br>Amount: $". $transaction['amount'] . "</br>Date: " . $transaction['date'] ."</div>";
 
                        
                }
                echo $retval;
                break;
               
                case "edit":
                    
                    $email = $_SESSION['email'];
                    $res = '';
                    
                
                    if(!empty($_POST['firstName'])){
                        if(!preg_match ("/^[a-zA-z]*$/", $_POST['firstName'])){
                            $res = $res . "<div id='error' style='width:50%; margin-left:250px; margin-top:20px;' class='alert alert-danger' role='alert'>First name contains illegal characters!</div>";
                        }
                        
                        else{
                        $cursor = $MySQLdb->prepare("UPDATE users SET firstname=:firstname WHERE email=:email");
                        $cursor->execute( array(":firstname"=>$_POST['firstName'], ":email"=>$email));
                        $_SESSION['firstName'] = $_POST['firstName'];
                        $res = $res . "<div id='error' class='alert alert-success' style='width:50%; margin-left:250px; margin-top:20px;' role='alert'>First name changed successfully!</div>";
                        }
                    }
                   
                    if(!empty($_POST['lastName'])){
                        if(!preg_match ("/^[a-zA-z]*$/", $_POST['lastName'])){
                            $res = $res . "<div id='error' style='width:50%; margin-left:250px; margin-top:20px;' class='alert alert-danger' role='alert'>Last name contains illegal characters!</div>";
                        }
                        
                        else{
                        $cursor = $MySQLdb->prepare("UPDATE users SET firstname=:firstname WHERE email=:email");
                        $cursor->execute( array(":firstname"=>$_POST['firstName'], ":email"=>$email));
                        $_SESSION['lastName'] = $_POST['lastName'];
                        $res = $res . "<div id='error' class='alert alert-success' style='width:50%; margin-left:250px; margin-top:20px;' role='alert'>Last name changed successfully!</div>";
                        }
                    }
                    if(!empty($_POST['email'])){
                       
                        $cursor = $MySQLdb->prepare("SELECT * FROM users WHERE email=:email");
                        $cursor->execute( array(":email"=>$_POST['email']));
                        $output = $cursor->fetchAll(PDO::FETCH_ASSOC);
        
                        if(isset($output[0]['email'])){
                            $res = $res . "<div id='error' class='alert alert-danger' style='width:50%; margin-left:250px; margin-top:20px;' role='alert'>Email already exists!</div>";

                        }else{

                       
                        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                            $res = $res . "<div id='error' class='alert alert-danger' style='width:50%; margin-left:250px; margin-top:20px;' role='alert'>Invalid email!</div>";
                        }   
                    
                        else{
                        $cursor = $MySQLdb->prepare("UPDATE users SET email=:newemail WHERE email=:email");
                        $cursor->execute( array(":newemail"=>$_POST['email'], ":email"=>$email));
                        $res = $res . "<div id='error' class='alert alert-success' style='width:50%; margin-left:250px; margin-top:20px;' role='alert'>Email changed successfully!</div>";
                        $_SESSION['email'] = $_POST['email'];
                    }
                }
            }
                    if(!empty($_POST['password'])){
                        if(strlen($_POST['password']) < 7){
                        $res = $res . "<div id='error' class='alert alert-danger' style='width:50%; margin-left:250px; margin-top:20px;' role='alert'>New password must be at least 7 characters long!</div>";
                        }
                        else{
                        $cursor = $MySQLdb->prepare("UPDATE users SET password=:password WHERE email=:email");
                        $cursor->execute(array(":password"=>password_hash($_POST['password'], PASSWORD_DEFAULT), ":email"=>$email));
                        $res = $res . "<div id='error' class='alert alert-success' style='width:50%; margin-left:250px; margin-top:20px;' role='alert'>Password changed successfully!</div>";

                        }
                    }
                   
                
                    echo $res;
                break;
   
            
}
}
?>