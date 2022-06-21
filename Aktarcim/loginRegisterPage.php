<?php
    ob_start();
    session_start();
    $message = '';
    require('settings.php');

    if (isset($_POST['loginPage'])){
        extract($_POST);

        $password = hash('sha256', $password); //şifrelenme

        $stmt = $baglanti->prepare("SELECT * FROM users WHERE email = '$email' and user_password = '$password'");
        $stmt->execute();

        $result = $stmt->get_result();
       
        $row_no = $result->num_rows;

        if ($row_no == 1){
            $_SESSION['email'] = $email;
            $message="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        Giriş Başarılı Ana sayfaya yönlendiriliyorsunuz... 
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
        }else{
            $message="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                     Giriş Başarısız böyle bir kullanıcı bulunmamaktadır.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
                 
        }
    
    }
    if(isset($_POST['registerPage'])){
        extract($_POST);

        $password_one=hash('sha256', $password_one);
        $password_two=hash('sha256', $password_two);

        if($password_one==$password_two){
            $stmt = $baglanti->prepare("SELECT * FROM users WHERE email = '$reg_email'");
            $stmt->execute();
            $result = $stmt->get_result();
            

            $row_no = $result->num_rows;

            if($row_no==0){
                $values = [$user_name, $user_surname, $reg_email, $password_one];

                $stmt = $baglanti->prepare("INSERT INTO users(username, user_surname, email, user_password) VALUES ((?), (?), (?), (?))");
                $stmt->bind_param('ssss', ...$values);
                $stmt->execute();

                $_SESSION['email'] = $reg_email;
                
                $message="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Kaydınız Başarılı anasayfaya yönlendiriliyorsunuz...
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
            }
            else{
                $message="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                   Bu email başka bir kullanıcı tarafından kullanılmaktadır.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
            }
        }
        else{
            $message="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                   Girdiğiniz şifreler uyuşmamakta.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
            }

    }

    if (isset($_SESSION['email'])){
        header('Refresh: 3; URL=mainScreen.php');
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="icon" href="images/favicon.png">
    <title>Aktarcım</title>
</head>
<body style="background-color: #F0F2F5;">
    <?php if ($message) {
        echo $message;
    } ?>
    <div id="mainContainer">
        <div id="textContainer">
            <h1 id="header">Aktarcım</h1>
            <h2 id="descriptionText">Alternatif tıp, Bitkiler ve Doğanın Mucizeleri bir tık uzağında!</h2>
        </div>
        <div id="formContainer">
            <form action="loginRegisterPage.php" method="POST" accept-charset="utf-8">
               
                <input type="email" id="inputEmail" placeholder="Email" name="email" required>
                <input type="password" id="inputPassword" placeholder="Password" name="password" required>
                <button id="loginButton" type="submit" name="loginPage" onclick="deleteText()">Giriş Yap</button>
            </form>
            <hr id="divider">
            <button id="registerButton" onclick="openRegister()">Yeni Hesap Oluştur</button>
            </form>
        </div>
    </div>
    <div id=registerContainer class="visible">

        <div id="headerContainer">
            <h1 id=registerHeader>Kayıt Ol</h1>
            <a href="javascript:void(0)" id="closebtn" onclick="closeRegister()">×</a>
        </div>
        <hr id="divider">
        <form action="loginRegisterPage.php" method="POST" accept-charset="utf-8">

            <div class="registerElementContainer" id="nameSurname">
                <input class="registerInput" type="text" id="registerName" placeholder="Name" name="user_name" required>
                <input class="registerInput" type="text" id="registerSurname" placeholder="Surname" name="user_surname" required>
            </div>
            <div class="registerElementContainer notFocus" id="emailContainer">
                <input type="email" id="registerEmail" placeholder="Email" name="reg_email" required>
            </div>

            <div class="registerElementContainer" id="passwordContainer">
                <input class="registerInput" type="password" id="registerPassword" placeholder="Enter Password" name="password_one" required>
                <input class="registerInput" type="password" id="registerPasswordAgain" placeholder="Enter Password Again" name="password_two" required>
            </div>
            <div class="registerElementContainer" id="checkboxContainer">
                <input  type="checkbox" id="userAgreement" name="userAgreement" value="beMember" required>
                <label for="userAgreement"> I have read user agreement  </label><br>
            </div>
            
            <div id="registerBttnContainer">
                <button id="doneRegisterBtton" type="submit" name="registerPage" >Kayıt Ol</button>
            </div>
        </form>
    </div>
    <div id="overlayBlur"></div>
    <script src="js/validation.js"></script> 
    <script src="js/openCloseRegisterPage.js"></script>
</body>
</html>
