<?php
//oturumu başlat
ob_start();
session_start();
$message = '';
$urunMessage='';
$productsCard='';
$toBeUpdated=-1;
$openPanel=false;
$degistimi=false;
require('settings.php');

$stmt = $baglanti->prepare("SELECT * FROM users WHERE email = (?)");
$stmt->bind_param('s', $_SESSION['email']);
$stmt->execute();

$result = $stmt->get_result();
       
$kullanici = $result->fetch_assoc();

$isim= ucwords(strtolower($kullanici['username']));
$soyisim=ucwords(strtolower($kullanici['user_surname']));


$stmt = $baglanti->prepare("SELECT * FROM urunler WHERE satan_email=(?)");
$stmt->bind_param('s',$_SESSION['email']);
$stmt->execute();

$Urunresult = $stmt->get_result();

$num_products=0;
while($urun=$Urunresult->fetch_assoc()){
  $urun_adeti=$urun['urun_adeti'];
  if($urun_adeti!=0){
    $num_products++;
  }

}

if($num_products==0){
  $urunMessage="<div class='alert alert-danger' role='alert'>
    <h4 class='alert-heading'>Ürün Yok!</h4>
    <p>Maalesef şu anda sistemimizde satışa çıkarttığınız bir ürün bulunamadı</p>
    <hr>
    <p class='mb-0'>Sistemimize ürün yüklendiğiniz anda ürünleriniz burada gösterilecektir</p>
  </div>";
  $toplamKazanc=0;
}
else{
  $urunMessage="<div class='alert alert-success' role='alert'>
  Sistemde satılığa çıkartılan $num_products ürününüz bulunuyor $isim bey,
</div>";

$stmt = $baglanti->prepare("SELECT * FROM urunler WHERE satan_email=(?)");
$stmt->bind_param('s',$_SESSION['email']);
$stmt->execute();

$Urunresult = $stmt->get_result();

$productsCard="<div class='row'>";
$toplamKazanc=0;

$flag=0;
while($urun=$Urunresult->fetch_assoc()){

  $urun_id=$urun['urun_id'];
  $urun_adi=$urun['urun_adi'];
  $urun_fiyati=$urun['urun_fiyati'];
  $urun_aciklama=$urun['urun_description'];
  $urun_adeti=$urun['urun_adeti'];

  $toplamKazanc+=$urun_adeti*$urun_fiyati;

  $stmt = $baglanti->prepare("SELECT * FROM users WHERE email = (?)");
  $stmt->bind_param('s', $urun['satan_email']);
  $stmt->execute();

  $result = $stmt->get_result();
        
  $satici = $result->fetch_assoc();

  $saticiIsmi=ucwords(strtolower($satici['username']) ." ".strtolower($satici['user_surname']));

  if($urun_adeti!=0){
    $urunHtml="";

  $urunHtml=
  "<div class='col-md-3 text-center text-md-start d-flex  mb-4'> 
    <div class='card mx-auto mt-3' style='width: 18rem;'>
    <div class='card-body'>
      <h5 class='card-title'>$urun_adi</h5>
      <h6 class='card-subtitle mb-2 text-muted'>Satıcı: $saticiIsmi</h6>
      <p class='card-text'>$urun_aciklama</p>
    </div>
    <ul class='list-group list-group-flush'>
      <li class='list-group-item'>Fiyat: $urun_fiyati TL</li>
      <li class='list-group-item'>Adet: $urun_adeti</li>
    </ul>
    <div class='card-body mx-auto'>
    <form method=POST action='soldPage.php' class='d-inline-block'>
    <button class='btn btn-danger' type='submit' name='deleteProduct' value='$urun_id'>
    Ürünü Sil
    </button>
    <button class='btn btn-info' type='submit' name='updateButton' value='$urun_id'>
    Düzenle
    </button>
    </form>
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
if(isset($_POST['deleteProduct'])){
  $int_value=intval($_POST['deleteProduct']);

  $stmt = $baglanti->prepare("DELETE FROM urunler WHERE urun_id = (?)");
  $stmt->bind_param('i', $int_value);
  $stmt->execute();

  $message="<div class='alert alert-success' role='alert'>
  Seçtiğiniz ürün sistemden kaldırılıyor $isim bey...
  </div>";
  $degistimi=true;

  header('Refresh: 3; URL=soldPage.php');
}

if(isset($_POST['deleteProduct'])){
  $int_value=intval($_POST['deleteProduct']);

  $stmt = $baglanti->prepare("DELETE FROM urunler WHERE urun_id = (?)");
  $stmt->bind_param('i', $int_value);
  $stmt->execute();

  $message="<div class='alert alert-success' role='alert'>
  Seçtiğiniz ürün sistemden kaldırılıyor $isim bey...
  </div>";
  $degistimi=true;

  header('Refresh: 3; URL=soldPage.php');
}

if(isset($_POST['updateButton'])){
  $toBeUpdated =intval($_POST['updateButton']);
  $openPanel=true;
}

$productsCard=$productsCard."</div>";
}


if(isset($_POST['updateProductButton'])){
  $int_id=$_POST['updateProductButton'];
  extract($_POST);
  
  if(strlen($product_new_name)>5 && strlen($product_new_description)>10 && $product_new_price>0 && $product_new_amount>0){
    $values = [$product_new_name, $product_new_description, $product_new_price, $product_new_amount,$int_id];

    $stmt = $baglanti->prepare("UPDATE urunler SET urun_adi=(?), urun_description=(?), urun_fiyati=(?), urun_adeti=(?) WHERE urun_id=(?)");
    $stmt->bind_param('ssiii', ...$values);
    $stmt->execute();

    $message="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Ürününüz güncelleniyor $isim bey...
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
      $degistimi=true;
      header('Refresh: 3; URL=soldPage.php');

  }
  else{
    $message="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Ürün yeni ismi 5 harften büyük olmalı, Ürün yeni açıklaması 10 harften büyük olmalı, ürün yeni fiyat ve adedi 1'den büyük olmalıdır.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
      $degistimi=true;
      header('Refresh: 3; URL=soldPage.php');
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
    <link rel="stylesheet" href="css/soldPage.css?v=<?php echo time(); ?>">
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
          <a class="nav-link" href="boughtPage.php">Sepetim</a>
        </li>
        <li class="nav-item px-3">
          <a class="nav-link active" href="soldPage.php">Sattıştaki Ürünlerim</a>
        </li>
        <li class="nav-item px-3">
          <a class="nav-link" href="profile.php">Profil</a>
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
        <h1>Toplam Ürünleriniz </h1>
    </div> 
    <div class="col-md-5 mt-5 text-center text-md-end  "> 
        <h3 id="value" class="px-3">Toplam Değer:  <?php echo $toplamKazanc. " TL"; ?></h3>
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
<?php 
if ($openPanel){
  echo "<div id='putProductPanel' class='panelDesign'>
  <div class='littleContainer'>  
  <div class='headerContainer'>
          <h3>Ürün Güncelle</h3>
          <a href='javascript:void(0)' class='closebtn' onclick='closeUpdateProductPanel()'>×</a>
      </div>
          <form action='soldPage.php' method='POST' accept-charset='utf-8'>
             <div ><input type='text'  class='inputs' placeholder='Ürün Yeni Adı' name='product_new_name' required></div>
             <div ><input type='number'  class='inputs' placeholder='Ürün Yeni Fiyatı' name='product_new_price' required></div>
             <div ><input type='number'  class='inputs' placeholder='Ürün Yeni Adeti' name='product_new_amount' required></div>
             <div ><input type='text'  class='inputs' placeholder='Ürün Yeni Açıklaması' name='product_new_description' maxlength='100'  required></div>
             <div ><button id='putButton' type='submit' class='confirmButton' name='updateProductButton' value=$toBeUpdated >Ürün Güncelle</button></div>
          </form>
        </div>    
      </div>";
      echo "<div id='overlayBlur'></div>";
    $openPanel=false;
}


?>
<footer class="mt-auto bg-light mt-5" >
    <div class="container">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Erdal Nayir</a></li>
    </ul>
    <p class="text-center text-muted">© 2022 Aktarcım</p>
    </div>
    
  </footer>
  <script src="js/soldPage.js"></script>
</body>
</html>