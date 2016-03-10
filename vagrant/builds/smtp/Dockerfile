FROM ruby
RUN apt-get update
RUN apt-get install -y sqlite3 libsqlite3-dev
RUN gem install mailcatcher -v 0.5.12 --no-ri --no-rdoc

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

EXPOSE 1025 1080

CMD ["mailcatcher", "-f", "--ip=0.0.0.0"]
