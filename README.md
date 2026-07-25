# PHX-TOOLS
Toolset for PHX

## Tools
| generate:iconset\n
    Generate Web/Socials/PWA/Android/iOS icons from an SVG

## Install from Source
```bash
git clone https://github.com/andreapeverelli/phx-tools.git
makepkg -si
```

## Install from Repository
```bash
sudo printf '[phx]\nServer = https://github.com/andreapeverelli/phx-repo.git\n' | sudo tee -a /etc/pacman.conf > /dev/null
sudo pacman -Syy phx-tools
```

## Usage
phx-tools --help\n
phx-tools --version\n

| generate:iconset\n
    phx-tools generate:iconset --help\n
    phx-tools generate:iconset --input logo.svg [--out custom/] [--verbose]
