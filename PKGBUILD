pkgname=phx-cli
pkgver=3.0.0
pkgrel=1
pkgdesc="CLI companion for PHX framework"
arch=('any')
url="https://github.com/andreapeverelli/phx-cli.git"
license=('GPL-3.0')

depends=(
	'php'
	'composer'
	'frankenphp'
	'librsvg'
	'imagemagick'
	'inkscape'
	'svgo'
	'phx-core-palette'
	'phx-tonal-palette'
	'phx-font-metrics'
)

build() {
	composer install \
		--working-dir=.. \
		--no-dev \
		--no-interaction \
		--prefer-dist \
		--optimize-autoloader
}

package() {
	install -dm755 "$pkgdir/usr/share/$pkgname"
	cp -r \
		../composer.json \
		../LICENSE \
		../vendor \
		../src \
		$pkgdir/usr/share/$pkgname
	install -Dm755 ../bin/phx $pkgdir/usr/bin/phx
}
