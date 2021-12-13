# Slim Framework 3 Skeleton Application

Use this skeleton application to quickly setup and start working on a new Slim Framework 3 application. This application uses the latest Slim 3 with the PHP-View template renderer. It also uses the Monolog logger.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

    php composer.phar create-project slim/slim-skeleton [my-app-name]

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writeable.

To run the application in development, you can run these commands 

	cd [my-app-name]
	php composer.phar start
	
Or you can use `docker-compose` to run the app with `docker`, so you can run these commands:

         cd [my-app-name]
	 docker-compose up -d
After that, open `http://localhost:8080` in your browser.

Run this command in the application directory to run the test suite

	php composer.phar test

That's it! Now go build something cool.

# API Documentation
Access via app like POSTMAN then access `http://localhost:8080/?key=123` for API authentication
	key = `123`

Put on POST method then choose form data for query parameter
	ktp = XXXXX123123
	email = ghXXXX@XXX.com
	tgl_lahir = DD-MM-YYYY
	provinsi = 1 / 2 / 3 / 4 / 5
		1 : DKI JAKARTA
		2 : JAWA BARAT
		3 : JAWA TIMUR
		4 : SUMATRA UTARA
		5 : JAWA TENGAH
	jml_pinjaman = 1 - 10
		1 : 1 milion
		2 : 2 milion dst...
	jangka_waktu = 3 / 6 / 9 / 12 / 24 for tenor in month
	nama_lengkap = GERXXXXX
	jk = L / P
	alamat = JL.XXXXX
	kebangsaan = WNI / WNA
	gmb_ktp = empty / X.jpg
	gmb_diri = empty / Z.jpg 
