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

Ketikan perintah berikut untuk mengenerate app key anda:

```sh
$ php artisan wallet-sederhana:init
```

Setelah selesai setting api ini di file .env pada folder client kamu dengan mengetikan url berikut pada variable REACT_APP_API_URL:

-   http://localhost/restapp/public/api/

Untuk login anda bisa menggunakan User Account ini:

|     Email     | Password |
| :-----------: | :------: |
| demoa@demo.id |  123456  |
| demob@demo.id |  123456  |
