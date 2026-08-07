# PHX-CLI
CLI companion for PHX framework.

## Install from Source
```bash
git clone https://github.com/andreapeverelli/phx-cli.git
makepkg -si
```

## Install from Repository
```bash
# Add Repository to Pacman
sudo printf '[phx]\nServer = https://andreapeverelli.github.io/phx-repo/\n' | sudo tee -a /etc/pacman.conf > /dev/null
wget -O /tmp/phx-repo-key.asc https://andreapeverelli.github.io/phx-repo/key.asc
sudo pacman-key --add /tmp/phx-repo-key.asc
sudo pacman-key --lsign-key CAF1FE155FED7B2F6E05EC6BD88ABED0A94852EC

# Update Repositories and install phx-cli
sudo pacman -Syy phx-cli
```

## Usage
```bash
phx --help
phx COMMAND --help
```
