<?php
//oturumu başlat
ob_start();
session_start();
$message = '';
$urunMessage='';
$productsCard='';
$yorumlar='';
$changed=false;
$degistimi=false;
$openPanel=false;
$onemli="";
$productCurrentId=0;
require('settings.php');

//Kullanıcı Bilgisi
$stmt = $baglanti->prepare("SELECT * FROM users WHERE email = (?)");
$stmt->bind_param('s', $_SESSION['email']);
$stmt->execute();

$result = $stmt->get_result();
       
$kullanici = $result->fetch_assoc();

$isim= ucwords(strtolower($kullanici['username']));
$soyisim=ucwords(strtolower($kullanici['user_surname']));

//Urun listesi

$sifir=0;
$stmt = $baglanti->prepare("SELECT* FROM urunler WHERE urun_adeti=(?)");
$stmt->bind_param('i',$sifir);
$stmt->execute();

$zeroUrun = $stmt->get_result();
$num_of_zeroUrun=$zeroUrun->num_rows;

$stmt = $baglanti->prepare("SELECT * FROM urunler");
$stmt->execute();

$Urunresult = $stmt->get_result();

$num_products=$Urunresult->num_rows;

$num_products=$num_products-$num_of_zeroUrun;

if($num_products==0){
  $urunMessage="<div class='alert alert-danger' role='alert'>
    <h4 class='alert-heading'>Ürün Yok!</h4>
    <p>Maalesef şu anda sistemimizde satışa çıkartılan bir ürün bulunamadı</p>
    <hr>
    <p class='mb-0'>Sistemimize ürün yüklendiği anda sizlere sunacağız.</p>
  </div>";
}
else{
  $urunMessage="<div class='alert alert-success' role='alert'>
  Sistemde satılığa çıkartılan $num_products ürün bulunuyor
</div>";

$productsCard="<div class='row'>";

$flag=0;
while($urun=$Urunresult->fetch_assoc()){

  if($urun['urun_adeti']!=0){
    $urun_adi=$urun['urun_adi'];
  $urun_fiyati=$urun['urun_fiyati'];
  $urun_aciklama=$urun['urun_description'];
  $urun_adeti=$urun['urun_adeti'];

  $urun_id=$urun['urun_id'];

  $stmt = $baglanti->prepare("SELECT * FROM users WHERE email = (?)");
  $stmt->bind_param('s', $urun['satan_email']);
  $stmt->execute();

  $result = $stmt->get_result();     
  $satici = $result->fetch_assoc();

  $saticiIsmi=ucwords(strtolower($satici['username']) ." ".strtolower($satici['user_surname']));

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
    <form method=POST action='mainScreen.php' class='d-inline-block' id='myForm2'>
    <button class='btn btn-success' type='submit' name='buyProduct' value='$urun_id'>
    Satın Al
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
$productsCard=$productsCard."</div>";
}
if(isset($_POST['buyProduct'])){
  $toBeBougth =intval($_POST['buyProduct']);
  $openPanel=true;
}
if(isset($_POST['buyProductButton'])){
  $int_id=intval($_POST['buyProductButton']);
  extract($_POST);
  
  if($number_of_buy>0){
    $stmt = $baglanti->prepare("SELECT * FROM urunler WHERE urun_id=(?)");
    $stmt->bind_param('i', $int_id);
    $stmt->execute();

    $result=$stmt->get_result();
    $urun=$result->fetch_assoc();
    if($urun['satan_email']!=$_SESSION['email']){
      if($number_of_buy<=$urun['urun_adeti']){
        if($number_of_buy*$urun['urun_fiyati']<= $kullanici['bakiye']){
          $new_adet=$urun['urun_adeti']-$number_of_buy;
          $new_bakiye=$kullanici['bakiye']-$number_of_buy*$urun['urun_fiyati'];
  
          $stmt = $baglanti->prepare("UPDATE urunler SET urun_adeti=(?) WHERE urun_id=(?)");
          $stmt->bind_param('ii',$new_adet, $int_id);
          $stmt->execute();
  
          $stmt = $baglanti->prepare("INSERT INTO satin_alinan_urunler(email,urun_id,alinan_adet) VALUES ((?), (?),(?))");
          $stmt->bind_param('sii',$_SESSION['email'], $urun['urun_id'],$number_of_buy);
          $stmt->execute();
  
          $stmt = $baglanti->prepare("UPDATE users SET bakiye=(?) WHERE email=(?)");
          $stmt->bind_param('is',$new_bakiye, $_SESSION['email']);
          $stmt->execute();

          $kazanc=$number_of_buy*$urun['urun_fiyati'];
          $stmt = $baglanti->prepare("UPDATE users SET bakiye= bakiye+(?) WHERE email=(?)");
          $stmt->bind_param('is', $kazanc,$urun['satan_email']);
          $stmt->execute();
  
          $message="<div class='alert alert-success alert-dismissible fade show' role='alert'>
          Aldığınız ürünler sepetinize yükleniyor $isim bey...
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          $degistimi=true;
          header('Refresh: 3; URL=mainScreen.php');
        }
        else{
          $message="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
          Bütçeniz bu kadar ürün almaya yetmiyor $isim bey,
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          $degistimi=true;
          header('Refresh: 3; URL=mainScreen.php'); 
        }
      }
      else {
        $message="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
          Bu üründen almak istediğiniz adet kadar bulunmuyor. Lütfen daha az ürün alın $isim bey,
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          $degistimi=true;
          header('Refresh: 3; URL=mainScreen.php');
      }
    }else{
      $message="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                     Kendi ürününüzü satın alamazsınız $isim bey,
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
      $degistimi=true;
      header('Refresh: 3; URL=mainScreen.php');

    }
  }
  else{
    $message="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                     Satın almak istediğiniz adet 0 olamaz $isim bey,
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
      $degistimi=true;
      header('Refresh: 3; URL=mainScreen.php');
  }
}
//form yapisi
if (isset($_POST['confirmButton'])){
  extract($_POST);
  if($money>0 && $money!=NULL && $money<5000){
      $money=$money+$kullanici['bakiye'];
      $values=[$money,$_SESSION['email']];

      $stmt = $baglanti->prepare("UPDATE users SET bakiye=(?) WHERE email = (?)");
      $stmt->bind_param('is',...$values);
      $stmt->execute();
      
      $stmt = $baglanti->prepare("SELECT * FROM users WHERE email = (?)");
      $stmt->bind_param('s', $_SESSION['email']);
      $stmt->execute();

      $result = $stmt->get_result();          
      $kullanici = $result->fetch_assoc();

      $message="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Paranız hesabınıza yüklendi $isim bey
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
      $degistimi=true;
      header('Refresh: 3; URL=mainScreen.php');
  }
  else{
    $message="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
      Bu geçerli bir para miktarı değil, Para aralığı 1-5000 arası olmalıdır $isim bey
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header('Refresh: 3; URL=mainScreen.php');
    $degistimi=true;
    }
}
if(isset($_POST['putButton'])){
  extract($_POST);

  if(strlen($product_name)>5 && strlen($product_description)>10 && $product_price>0 && $product_amount>0){
    $values = [$product_name, $product_description, $product_price, $product_amount,$_SESSION['email']];

    $stmt = $baglanti->prepare("INSERT INTO urunler(urun_adi, urun_description, urun_fiyati, urun_adeti,satan_email) VALUES ((?), (?), (?), (?),(?))");
    $stmt->bind_param('ssiis', ...$values);
    $stmt->execute();

    $message="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Ürününüz sistemimize ekleniyor $isim bey...
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
      $degistimi=true;
      header('Refresh: 3; URL=mainScreen.php');
  }
  else{
    $message="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Ürün ismi 5 harften büyük olmalı, Ürün açıklaması 10 harften büyük olmalı, ürün fiyat ve adedi 1'den büyük olmalıdır.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
      $degistimi=true;
      header('Refresh: 3; URL=mainScreen.php');
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
    <link rel="stylesheet" href="css/mainScreen.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>
    <link rel="icon" href="images/favicon.png">
    <title>Aktarcım</title>
</head>

<body class="d-flex flex-column min-vh-100" >
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
          <a class="nav-link active" aria-current="page" href="mainScreen.php">Anasayfa</a>
        </li>
        <li class="nav-item px-3">
          <a class="nav-link" href="boughtPage.php">Sepetim</a>
        </li>
        <li class="nav-item px-3">
          <a class="nav-link" href="soldPage.php">Sattıştaki Ürünlerim</a>
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
        <h1>Merhaba <?php echo ucwords(strtolower($kullanici['username']) ." ".strtolower($kullanici['user_surname']).",") ; ?></h1>
    </div> 
    <div class="col-md-5 mt-5 text-center text-md-end  "> 
        <span class="px-3">Bakiyeniz:  <?php echo $kullanici['bakiye']. " TL"; ?></span>
        <button type="button" class="btn btn-outline-success" onclick="openMoneyDeposit()">Para yükle</button>
    </div> 
    <hr class="bg-black border-2 border-top border-black mt-3">
</div>
  <div id="getMoneyPanel" class="panelDesign">
    <div id ="littleContainer" class="littleContainer">  
    <div id="headerContainer" class="headerContainer">
            <h3>Para Yükle</h3>
            <a href="javascript:void(0)" id="closebtn" class="closebtn" onclick="closeMoneyDeposit()">×</a>
        </div>
            <form action="mainScreen.php" method="POST" accept-charset="utf-8">
               <div ><input type="number" id="inputMoney" class="inputs" placeholder="Para Miktarı" name="money" required></div>
               <div ><button id="confirmButton" class="confirmButton" type="submit" name="confirmButton">Para Yükle</button></div>
            </form>
          </div>    
        </div>
        <div class="row mt-5 ">
    <div class="col-md-7 mt-5 text-center text-md-start  "> 
        <h1>Ürünler</h1>
    </div> 
    <div class="col-md-5 mt-5 text-center text-md-end  "> 
        <button type="button" class="btn btn-outline-success" onclick="openProductDeposit()">Ürun Yükle</button>
    </div> 
    <hr class="bg-black border-2 border-top border-black mt-3">
</div>
<div id="putProductPanel" class="panelDesign">
    <div class="littleContainer">  
    <div class="headerContainer">
            <h3>Ürün Yükle</h3>
            <a href="javascript:void(0)" class="closebtn" onclick="closeProductDeposit()">×</a>
        </div>
            <form action="mainScreen.php" method="POST" accept-charset="utf-8">
               <div ><input type="text"  class="inputs" placeholder="Ürün Adı" name="product_name" required></div>
               <div ><input type="number"  class="inputs" placeholder="Ürün Fiyatı" name="product_price" required></div>
               <div ><input type="number"  class="inputs" placeholder="Ürün Adeti" name="product_amount" required></div>
               <div ><input type="text"  class="inputs" placeholder="Ürün Açıklaması" name="product_description" maxlength="100"  required></div>
               <div ><button id="putButton" type="submit" class="confirmButton" name="putButton">Ürün Yükle</button></div>
            </form>
          </div>    
        </div>
<div class="row mt-1 ">
    <div class="col-md-12 mt-1 text-center text-md-start  "> 
      <?php echo $urunMessage?>
        
    </div>  
</div>
<?php echo $productsCard?>
<?php 
if ($openPanel){
  echo "<div id='buyProductPanel' class='panelDesign'>
  <div class='littleContainer'>  
  <div class='headerContainer'>
          <h3>Ürün Satın Al</h3>
          <a href='javascript:void(0)' class='closebtn' onclick='closebuyProductPanel()'>×</a>
      </div>
          <form method='POST' accept-charset='utf-8'>
             <div ><input type='number'  class='inputs' placeholder='Almak İstediğiniz Adeti Girin' name='number_of_buy' required></div>         
             <div ><button id='putButton' type='submit' class='confirmButton' name='buyProductButton' value=$toBeBougth >Satın Al</button></div>
          </form>
        </div>    
      </div>";
      echo "<div id='overlayBlur_nd'></div>";
    $openPanel=false;
}
?>
</div>

<?php if ($onemli) {
        echo $onemli;
    } 
  ?>


<footer class="mt-auto bg-light mt-5" >
    <div class="container">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="https://github.com/ErdalNayir/Aktarcimm" class="nav-link px-2 text-muted">Erdal Nayir - Github</a></li>
    </ul>
    <p class="text-center text-muted">© 2022 Aktarcım</p>
    </div>
  </footer>
 
<div id="overlayBlur"></div>

<script src="js/mainScreen.js"></script> 
</body>
</html>
