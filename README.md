# PHX-TOOLS
Toolset for PHX

## Tools
### generate:iconset  
Generate Web/Socials/PWA/Android/iOS icons from an SVG

## Install from Source
```bash
git clone https://github.com/andreapeverelli/phx-tools.git
makepkg -si
```

## Install from Repository
```bash
# Add Repository to Pacman
sudo printf '[phx]\nServer = https://andreapeverelli.github.io/phx-repo/\n' | sudo tee -a /etc/pacman.conf > /dev/null
wget -O /tmp/phx-repo-key.asc https://andreapeverelli.github.io/phx-repo/key.asc
sudo pacman-key --add /tmp/phx-repo-key.asc
sudo pacman-key --lsign-key CAF1FE155FED7B2F6E05EC6BD88ABED0A94852EC

# Update Repositories and install phx-tools
sudo pacman -Syy phx-tools
```

## Usage
```bash
phx-tools --help
```
```bash
phx-tools --version
```
### generate:iconset
```bash
phx-tools generate:iconset --help
```
```bash
phx-tools generate:iconset --input logo.svg [--out custom/] [--verbose]
```
