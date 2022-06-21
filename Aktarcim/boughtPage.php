<?php
//oturumu başlat
ob_start();
session_start();
$message = '';
$urunMessage='';
$productsCard='';
$degistimi=false;
require('settings.php');

$stmt = $baglanti->prepare("SELECT * FROM users WHERE email = (?)");
$stmt->bind_param('s', $_SESSION['email']);
$stmt->execute();

$result = $stmt->get_result();
       
$kullanici = $result->fetch_assoc();

$isim= ucwords(strtolower($kullanici['username']));
$soyisim=ucwords(strtolower($kullanici['user_surname']));


$stmt = $baglanti->prepare("SELECT * FROM satin_alinan_urunler WHERE email=(?)");
$stmt->bind_param('s',$_SESSION['email']);
$stmt->execute();

$Urunresult = $stmt->get_result();

$num_products=$Urunresult->num_rows;

if($num_products==0){
    $urunMessage="<div class='alert alert-danger' role='alert'>
    <h4 class='alert-heading'>Ürün Yok!</h4>
    <p>Şuan'a kadar satın aldığınız bir ürün bulunmamaktadır</p>
    <hr>
    <p class='mb-0'>Ürün satın aldığınızda burada görüntülenecektir</p>
  </div>";
    $toplamHarcama=0;
  }
  else{
    $urunMessage="<div class='alert alert-success' role='alert'>
    Satın aldığınız $num_products ürün bulunuyor $isim bey,
  </div>";

    $productsCard="<div class='row'>";
    $toplamHarcama=0;

    $flag=0;


    //-------------------------------
    $stmt = $baglanti->prepare("SELECT * FROM urunler INNER JOIN satin_alinan_urunler ON urunler.urun_id = satin_alinan_urunler.urun_id;");
    $stmt->execute();
    $result = $stmt->get_result();

    while($fromUrun=$result->fetch_assoc()){
        $urun_adeti=$fromUrun['alinan_adet'];
        $urun_adi=$fromUrun['urun_adi'];
        $urun_description=$fromUrun['urun_description'];
        $urun_fiyati=$fromUrun['urun_fiyati'];
        $satanEmail=$fromUrun['satan_email'];
        $alanEmail=$fromUrun['email'];

        

        $stmt = $baglanti->prepare("SELECT * FROM users WHERE email = (?)");
        $stmt->bind_param('s', $satanEmail);
        $stmt->execute();

        $result_nd = $stmt->get_result();
                
        $satici = $result_nd->fetch_assoc();

        $saticiIsmi=ucwords(strtolower($satici['username']) ." ".strtolower($satici['user_surname']));

        if($alanEmail==$_SESSION['email']){
          $toplamHarcama+=$urun_adeti*$urun_fiyati;
          $urunHtml="";

          $urunHtml=
          "<div class='col-md-3 text-center text-md-start d-flex  mb-4'> 
              <div class='card mx-auto mt-3' style='width: 18rem;'>
              <div class='card-body'>
              <h5 class='card-title'>$urun_adi</h5>
              <h6 class='card-subtitle mb-2 text-muted'>Satıcı: $saticiIsmi</h6>
              <p class='card-text'>$urun_description</p>
              </div>
              <ul class='list-group list-group-flush'>
              <li class='list-group-item'>Fiyat: $urun_fiyati TL</li>
              <li class='list-group-item'>Alınan adet: $urun_adeti</li>
              </ul>
              <div class='card-body mx-auto'>
          </div>
          </div>
              
          </div> ";
  
  
  
          if($flag>1 && $flag%4==0){
              $productsCard=$productsCard."</div>";
              $productsCard=$productsCard."<div class='row'>";
              
          }
          $productsCard=$productsCard . $urunHtml;
          $flag++;

        }
       

    


    }
    //-------------------------------------
    $productsCard=$productsCard."</div>";
  //-------------------------------------
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
    <link rel="stylesheet" href="css/bought.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="icon" href="images/favicon.png">
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
          <a class="nav-link active" href="boughtPage.php">Sepetim</a>
        </li>
        <li class="nav-item px-3">
          <a class="nav-link " href="soldPage.php">Sattıştaki Ürünlerim</a>
        </li>
        <li class="nav-item px-3">
          <a class="nav-link " href="profile.php">Profil</a>
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
  <div class="row ">
    <div class="col-md-7 mt-5 text-center text-md-start  "> 
        <h1>Toplam Harcamalarınız </h1>
    </div> 
    <div class="col-md-5 mt-5 text-center text-md-end  "> 
        <h3 id="value" class="px-3">Toplam Hacama:  <?php echo $toplamHarcama. " TL"; ?></h3>
    </div> 
    <hr class="bg-black border-2 border-top border-black mt-3">
</div>

<div class="row mt-1 ">
    <div class="col-md-12 mt-1 text-center text-md-start  "> 
      <?php echo $urunMessage?>
        
    </div> 

   
</div>
<?php echo $productsCard?>


</div>
<footer class="mt-auto bg-light mt-5" >
    <div class="container">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Erdal Nayir</a></li>
    </ul>
    <p class="text-center text-muted">© 2022 Aktarcım</p>
    </div>
    
  </footer>
</body>
</html>