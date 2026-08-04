# PHX-TOOLS
Toolset for PHX

## Tools
### init
Generates a project configuration interactivelly
### generate:iconset
Generates favicon/Apple/Android/Microsoft/OpenGraph/Twitter icons from an SVG.
### generate:palette
Generates sRGB/Display P3/Rec. 2020 tonal palettes using HCT Material You core palette based on an Hex source color.
### generate:metadata-files
Generates manifest/browserconfig/robots/security/humans metadata files based on configurations
### generate:typescale
Generate Material You based typescale

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
