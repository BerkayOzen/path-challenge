# Symfony 4 Restful Api Challenge
ABC Company tedarikçi bir firmadır ve 3 müşterisi vardır. Bu müşterilerin kendilerine ait kullanıcı adları ve şifreleri vardır.
Müşteriler Restful servislerini kullanarak sipariş oluşturabilir ve görebilirler. 
Bu beklentiler doğrultusunda aşağıda listelenen servisleri hazırlamanı bekliyoruz.

İstenen servisler:
-   Sisteme login olma ve JWT Token alma
-   Yeni sipariş oluşturma (orderCode, productid, quantity, address, shippingDate)
-   Siparişi güncelleme (shippingDate henüz gelmediyse)
-   Sipariş detayını görme
-   Tüm siparişlerini listeleme

Artı puan kazandıracak şeyler:
-   Docker image ın yapılması (LEMP)
-   Postman Collection veya Swagger-ui
-   Unit Tests
-   Readme

# Proje
Proje Symfony 4.4 ile oluşturulmuştur. Authentication için ise jwt kullanılmıştır.

## Kurulum

```bash
git clone https://github.com/BerkayOzen/PathChallenge.git myProject
```

Composer paket yüklemeleri

```bash
cd myProject

composer install
```

Veritabanı için tercihen postgresql kullandım. Tercihe göre `.env` dosyası içinde ilgili kısımdan veritabanı ayarları yapılandırılmalıdır;

```dotenv
DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=13&charset=utf8"
```

Veritabanı entegresi için aşağıdaki komutların çalıştırılması gerekmektedir.
```bash
php bin/console doctrine:schema:validate
php bin/console doctrine:migration:migrate
php bin/console doctrine:fixtures:load
```

JWT Key oluşturmak için;
```bash
#jwt key generate
php bin/console lexik:jwt:generate-keypair
```

Postman Collection'ı proje içerisinde PostmanCollection.json içerisindedir.