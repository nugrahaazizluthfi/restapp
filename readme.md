### TECH

Aplikasi ini dibangun menggunakan:

|       Tech        | Version |
| :---------------: | :-----: |
|        PHP        | 7.2.11  |
| Laravel Framework | 5.7.28  |
|     Composer      |  1.8.4  |

### DOKUMENTASI API

untuk dokumentasi api dapat diliat disini.

https://walletsederhana.docs.apiary.io/#

### DEMO APLIKASI

Untuk login anda bisa menggunakan User Account ini:

|     Email     | Password |
| :-----------: | :------: |
| demoa@demo.id |  123456  |
| demob@demo.id |  123456  |

### INSTALASI

Untuk langkah pertama ketikan perintah berikut pada command line anda:

```sh
$ git clone https://gitlab.com/nugrahaazizluthfi/restapp.git
```

setelah itu lalu ketikan ini di command line anda:

```sh
$ cd restapp
$ composer install
```

copy rename .env.example menjadi .env dengan perintah berikut:

```sh
$ copy .env.example .env
```

Ubah konfigurasi database pada file .env sesuai dengan database anda:

> > > DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=databasemu

DB_USERNAME=root

DB_PASSWORD=

> > >

Ketikan perintah berikut untuk migrasi dan inisialisasi database anda:

```sh
$ php artisan wallet-sederhana:init
```

### SETUP CLIENT ENV

Setelah selesai, setting url api ini di file .env pada aplikasi client ( https://github.com/nugrahaazizluthfi/clientapp ) dengan mengetikan url berikut pada variable REACT_APP_API_URL:

jika diletakan di webserver seperti XAMPP/Laragon :

-   http://localhost/restapp/public/api/

jika menggunakan php artisan serve nya laravel:

-   http://localhost:8000/api/

ENDPOINT dimulai dengan menggunakan prefix /api/
