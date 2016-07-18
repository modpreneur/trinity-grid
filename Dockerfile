FROM modpreneur/trinity-test:alpine

MAINTAINER Martin Kolek <kolek@modpreneur.com>

ADD . /var/app

WORKDIR /var/app

RUN chmod +x entrypoint.sh

ENTRYPOINT ["sh", "entrypoint.sh"]