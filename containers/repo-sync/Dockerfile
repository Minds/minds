FROM alpine:latest

RUN apk add --no-cache python py-pip && \
	pip install awscli

COPY sync.sh .

RUN [ "chmod", "+x", "sync.sh" ]

ENTRYPOINT '/sync.sh'

CMD [ "" ]