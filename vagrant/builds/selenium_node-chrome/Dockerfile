FROM selenium/node-chrome-debug:2.44.0
USER root
RUN wget -q https://www.ubuntulinux.jp/ubuntu-ja-archive-keyring.gpg -O- | apt-key add -
RUN wget -q https://www.ubuntulinux.jp/ubuntu-jp-ppa-keyring.gpg -O- | apt-key add -
RUN wget https://www.ubuntulinux.jp/sources.list.d/quantal.list -O /etc/apt/sources.list.d/ubuntu-ja.list
RUN apt-get -y update
RUN apt-get -y install ubuntu-defaults-ja
