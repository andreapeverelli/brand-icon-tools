pkgname=phx-tools
pkgver=2.4.0
pkgrel=1
pkgdesc="Toolset for PHX"
arch=('any')
url="https://github.com/andreapeverelli/phx-tools.git"
license=('GPL-3.0')

depends=(
	'php'
	'composer'
)

optdepends=(
	'librsvg: SVG manipulation support'
	'imagemagick: raster image manipulation support'
	'inkscape: monochrome and normalize SVG support'
	'svgo: SVG optimization support'
	'phx-core-palette: Material You palette generation support'
	'phx-tonal-palette: Material You palette generation support'
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
		../LICENSE \
		../vendor \
		../src \
		$pkgdir/usr/share/$pkgname
	install -Dm755 ../bin/phx-tools $pkgdir/usr/bin/phx-tools
}
