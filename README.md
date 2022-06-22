# Aktarcimm
Türkiyenin her yerinden lokman hekim ve aktarcılardan ürün alıp ve kendi bitkisel veya doğal ürünlerinizi satabileceğiniz ayrıca en güncel fiyatları görebileceğiniz Aktarcım sitesine hoşgeldiniz!

## Başlatma
İlk yapmanız gereken Xampp ile apache ve mysql başlatmanız gerekmektedir. Daha sonra Xampp'i kurmuş olduğunuz sürücüde, Xampp dosya klasöründeki htdocs klasörü içindeki tüm dosyaları silip yüklemiş olduğum 'Aktarcim' klasörü içindeki tüm dosya, resim ve klasörleri oraya yükleyiniz (sql klasörü hariç)

## Mysql
Web tarayıcınızda http://localhost:8080/phpmyadmin 'e gelerek oradan yeni bir database kurun. Kuracağınız database ismi sifregiris olmalıdır. Kuracağınız database'in collation kısmı 'utf8-general-ci' olmalıdır. Daha sonra bu database'i kurduktan sonra üzerine tıklayın ve yukardan import işlemini seçin ve oraya github'a yüklemiş olduğum 'Aktarcim/sql' klasöründeki sifregiris.sql' dosyasını koyup, import edin böylece veri tabanı bütün tablolarıyla kurulmuş olcaktır. Bundan sonra burada yapacağınız bir işlem kalmadı

## Çalıştırma
Database kurulduktan ve site .php dosyaları lokaldeki xampp htdocs klasörüne yüklendikten sonra  http://localhost:8080 'e gittiğiniz zaman sizi direk başlangıç ekranına(index.php) yönlendirecektir. Eğer otomatik yönlendirmezse http://localhost:8080/index.php yi deneyebilirsiniz.

## Kayıt Ve Giriş
Sisteme kayıt olduğunuzda direkt otomatik girişiniz yapılır daha sonra da bu email ve şifre ile girşi yapmaya devam edebilirsiniz.

## Kullanılan Teknolojiler

Bu projede 

* Mysql
* Boostrap 5
* PHP
* Html
* Css
* Javascrip

Kullanılmıştır

#### [Website'ye gitmek için tıklayınız](http://aktarcimm.rf.gd/)
