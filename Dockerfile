FROM modpreneur/trinity-test

MAINTAINER Martin Kolek <kolek@modpreneur.com>

WORKDIR /var/app

ENTRYPOINT ["fish", "entrypoint.sh"]