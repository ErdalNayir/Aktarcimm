<?php
//oturumu başlat
session_start();
//eğer username adlı oturum değişkeni yok ise
//login sayfasına yönlendir
if (isset($_SESSION['email']) ) {
    header("Location: mainScreen.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/startingScreen.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="icon" href="images/favicon.png">
    <title>Aktarcım</title>
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container">
<!-- navbar başlangıcı -->
<nav class="navbar navbar-expand-md navbar-light fixed-top bg-white">
  <div class="container">
  <img src="images/favicon.png"  width="120" height="80" class="d-inline-block align-text-center">
    <a class="navbar-brand" href="#"><span class="fs-3 fw-bold navHeader">Aktarcım</span> </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ms-auto ">
            <span class="px-5"> Alternatif tıp, doğa, sağlıklı yaşam</span>
            <button type="button" class="btn btn-outline-success" onclick="redirectLoginRegister()">Giriş Yap/Kaydol</button>
            
        
      </div>
    </div>
  </div>
</nav>
<div class="row ">
<div class=" col-md-5 order-md-1 marginEkle mt-5 align-item-center  ">
        <img class="img-responsive center-block d-block mx-auto " src="images/sifaliCay.jpg" width=400px alt="...">
    </div> 
    <div class="col-md-7 mt-5 text-center text-md-start "> 
        <h1>Sağlıklı Hayat</h1>
        <p class="sizeofThis">için sizleri Türkiyenin her yerinden aktarlarla buluşturuyoruz. En güncel fiyatları bu siteden öğrenin </p>
    </div>    
</div>

<div class="row  ">
<div class="col-md-5 mt-5 align-item-center  ">
        <img class="img-responsive center-block d-block mx-auto " src="images/sifalibitkiler.jpg" width=400px alt="...">
    </div> 
    <div class="col-md-7  order-md-1 mt-5 text-center text-md-start "> 
        <h1>Sende Katıl</h1>
        <p class="sizeofThis">Hemen hesap oluşturup kendi sanal mağazanı aç. Sahip olduğun ürünlerle fark yarat!  </p>
    </div>    
</div>
<div class="row mt-5 align-item-center">
    <div class="col mt-5 mb-5 align-item-center  ">
        <button type="button" class="btn btn-outline-success  center-block d-block mx-auto" onclick="redirectLoginRegister()">Haydi Şimdi Sende Katıl!</button>
    </div>
    
</div>

</div>

<footer class="mt-auto bg-light" >
    <div class="container">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Erdal Nayir</a></li>
    </ul>
    <p class="text-center text-muted">© 2022 Aktarcım</p>
    </div>
    
  </footer>

<script src="js/validation.js"></script> 
</body>
</html>