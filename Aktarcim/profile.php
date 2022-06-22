<?php
//oturumu başlat
ob_start();
session_start();
$message = '';
$urunMessage='';
$productsCard='';
$degistimi=false;
$statusMessage="";
$imageSrc="images/sifalibitkiler.jpg";
require('settings.php');

//kullanıcı bilgileri
$stmt = $baglanti->prepare("SELECT * FROM users WHERE email = (?)");
$stmt->bind_param('s', $_SESSION['email']);
$stmt->execute();

$result = $stmt->get_result();
       
$kullanici = $result->fetch_assoc();

$isim= ucwords(strtolower($kullanici['username']));
$soyisim=ucwords(strtolower($kullanici['user_surname']));
$tarih=$kullanici['kayitTarihi'];

/*resim yükleme

if(isset($_POST["submit"])){  
        // Get file info 
    $dosyaİsmi = basename($_FILES["image"]["name"]); //dosya ismini döner FILES ile POST olarak yüklenen dosyalara erişebiliyoruz
    $dosyaUzantisi = pathinfo($dosyaİsmi, PATHINFO_EXTENSION);  //dosya uzantısını dödürür

         
        // Allow certain file formats 
    $izinVerilenTip = array('jpg','png','jpeg'); //bu tipteki resimler kullanılabilir
    if(in_array($dosyaUzantisi, $izinVerilenTip)){ //in array ile bu verilen tipi array içindemi kontorlü yapılır içerdeyse işleme devam edilir
    $image = $_FILES['image']['tmp_name']; 
    $imgContent = addslashes(file_get_contents($image)); 
         
    // Insert image content into database 
    $email=$_SESSION['email'];
    $result = $baglanti->prepare("INSERT INTO profileimages (image, created ,email) VALUES ((?), NOW(),(?))");
    $result->bind_param('ss',$imgContent,$email);
    $result->execute();
                
    }    

    $result = $baglanti->prepare("SELECT image FROM profileimages WHERE email=(?)");
    $result->bind_param('s',$email);
    $result->execute();

    $mainResult=$result->get_result();

    $resim=$mainResult->fetch_assoc();
    
    $imageSrc="data:image/jpg;base64,".base64_encode($resim['image']);
 
 
// Display status message 
*/

if(isset($_POST['confirmPassButton'])){
  extract($_POST);

  $oldPass=hash('sha256', $oldPass);
  $newPass=hash('sha256', $newPass);

  if($oldPass==$kullanici['user_password']){
    $stmt = $baglanti->prepare("UPDATE users SET user_password=(?) WHERE email = (?)");
    $stmt->bind_param('ss',$newPass,$_SESSION['email']);
    $stmt->execute();

    $message="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Şifreniz değiştirildi $isim bey
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
      $degistimi=true;
      header('Refresh: 3; URL=profile.php');
  }
  else{
    $message="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Eski şifrenizi yanlış girdiniz $isim bey
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
      $degistimi=true;
      header('Refresh: 3; URL=profile.php');

    }

}


if ( !isset($_SESSION['email']) ) {
    header('Refresh: 3; URL=index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="icon" href="images/favicon.png">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <title>Aktarcım</title>
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container">
<nav class="navbar navbar-expand-md navbar-light fixed-top bg-white">
  <div class="container">
  <img src="images/favicon.png"  width="120" height="80" class="d-inline-block align-text-center">
    <a class="navbar-brand" href="#"><span class="fs-3 fw-bold navHeader">Aktarcım</span> </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <ul class="navbar-nav ms-auto">
        <li class="nav-item px-3">
          <a class="nav-link" aria-current="page" href="mainScreen.php">Anasayfa</a>
        </li>
        <li class="nav-item px-3">
          <a class="nav-link" href="boughtPage.php">Sepetim</a>
        </li>
        <li class="nav-item px-3">
          <a class="nav-link" href="soldPage.php">Sattıştaki Ürünlerim</a>
        </li>
        <li class="nav-item px-3">
          <a class="nav-link active" href="profile.php">Profil</a>
        </li>
        <button type="button" class="btn btn-outline-danger" onclick="location.href='logout.php'">Çıkış Yap</button>
      </ul>
    </div>
  </div>
</nav>
<?php if ($message && $degistimi) {
        echo $message;
        $degistimi=false;
    } 
  ?>
<div class="row  ">
    <div class="col-md-6 mt-5  mx-auto text-center justify-content-center  "> 
        <img id="profilepics" src="images/sifalibitkiler.jpg" class="rounded-circle d-block mx-auto align-text-center  ">
    </div> 
</div>
<div class="row">
<div class="col-md-6 mt-5 mx-auto text-center  "> 
    <h1 ><?php echo ucwords(strtolower($kullanici['username']) ." ".strtolower($kullanici['user_surname'])) ; ?></h1>
    </div>
</div>
<div class="row">
<div class="col-md-6 mt-5 mx-auto text-center  "> 
        <span class="px-3 fs-5 fst-italic fw-lighter">Kayıt Tarihi:  <?php echo $tarih; ?></span>
        
    </div> 
    <hr class="bg-black border-2 border-top border-black mt-3">
</div>
<div class="row mb-5">
    <div class="col-md-6 mt-3  mx-auto text-center justify-content-center  "> 
    <button class='btn btn-primary' type='button' onclick='openPasswordPanel()'>
    Şifre Değiştir
    </button>
    </div> 
</div>
<!--
<form action="profile.php" method="POST" enctype="multipart/form-data">
    <label>Select Image File:</label>
    <input type="file" name="image" required>
    <input type="submit" name="submit" value="Upload">
</form>
-->
<div id="changePassword" class="panelDesign">
    <div id ="littleContainer" class="littleContainer">  
    <div id="headerContainer" class="headerContainer">
            <h3>Şifre Değiştir</h3>
            <a href="javascript:void(0)" id="closebtn" class="closebtn" onclick="closePasswordPanel()">×</a>
        </div>
            <form action="profile.php" method="POST" accept-charset="utf-8">
               <div ><input type="password" id="inputPass_one" class="inputs" placeholder="Eski şifrenizi girin" name="oldPass" required></div>
               <div ><input type="password" id="inputPass_two" class="inputs" placeholder="Yeni şifrenizi girin" name="newPass" required></div>
               <div ><button id="confirmButton" class="confirmButton" type="submit" name="confirmPassButton">Şifre Değiştir</button></div>
            </form>
          </div>    
        </div>
</div>
<footer class="mt-auto bg-light mt-5" >
    <div class="container">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="https://github.com/ErdalNayir/Aktarcimm" class="nav-link px-2 text-muted">Erdal Nayir - Github</a></li>
    </ul>
    <p class="text-center text-muted">© 2022 Aktarcım</p>
    </div>
    
  </footer>
  <div id="overlayBlur"></div>
  <script src="js/profile.js"></script>
</body>
</html>
