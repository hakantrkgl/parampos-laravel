<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## ParamPos Laravel Entegrasyonu

Bu proje, Laravel framework kullanılarak ParamPos ödeme sisteminin entegre edildiği bir uygulamadır. Bu proje sayesinde kredi kartı bilgilerini dinamik olarak bir kart üzerinde görüntüleyebilir ve ödeme işlemlerini gerçekleştirebilirsiniz.

### Özellikler

-   Kredi kartı bilgilerini dinamik olarak görüntüleme
-   ParamPos ödeme entegrasyonu
-   Kullanıcı dostu Bootstrap tabanlı arayüz
-   Test kartları seçimi ile kolay test imkanı

### Kurulum ve Kullanım

#### Gereksinimler

-   PHP 7.3 veya daha üstü
-   Composer
-   Laravel 8 veya daha üstü
-   ParamPos API bilgileri

#### Adımlar

1. **Projenizi klonlayın:**

    ```bash
    git clone https://github.com/hakantrkgl/parampos-laravel.git
    cd parampos-laravel
    Gerekli bağımlılıkları yükleyin:
    ```

#### bash
Kodu kopyala
composer install
.env dosyanızı oluşturun:

#### bash
Kodu kopyala
cp .env.example .env
Çevresel değişkenleri ayarlayın:

.env dosyanızda ParamPos API bilgilerinizi ve veritabanı ayarlarını yapın.

#### Uygulamanızı başlatın:

#### bash
Kodu kopyala
php artisan serve
Kullanım
Ana sayfaya gidin:

http://127.0.0.1:8000/

####  Ödeme sayfasına gidin:

http://127.0.0.1:8000/payment
Kredi kartı bilgilerini girin veya test kartlarından birini seçin ve ödeme işlemini gerçekleştirin.

#### Katkıda Bulunma
Projeye katkıda bulunmak için aşağıdaki adımları izleyebilirsiniz:

Fork yapın
Yeni bir dal (branch) oluşturun: git checkout -b my-feature-branch
Değişikliklerinizi yapın ve commitleyin: git commit -m 'Yeni özellik ekle'
Dalınıza push yapın: git push origin my-feature-branch
Bir Pull Request açın

#### Lisans
Bu proje MIT lisansı altında lisanslanmıştır.

#### İletişim 

Herhangi bir soru veya sorun için lütfen

https://www.facebook.com/khan8006 
https://twitter.com/hakantrkgl 
https://instagram.com/hakanturkgl 
https://www.linkedin.com/in/hakanturkgl
