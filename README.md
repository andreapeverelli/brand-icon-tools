# PHX-TOOLS
Toolset for PHX

## Tools
| generate:iconset
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
phx-tools --help
phx-tools --version

| generate:iconset
    phx-tools generate:iconset --help
    phx-tools generate:iconset --input logo.svg [--out custom/] [--verbose]
