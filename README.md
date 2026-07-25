# PHX-TOOLS
Toolset for PHX

## Tools
### generate:iconset  
_Generate Web/Socials/PWA/Android/iOS icons from an SVG_

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
_phx-tools --help_  
_phx-tools --version_

### generate:iconset  
_phx-tools generate:iconset --help_  
_phx-tools generate:iconset --input logo.svg [--out custom/] [--verbose]_
