FROM golang:1.8-alpine

RUN apk add --no-cache git

RUN go get -u \
    github.com/aws/aws-sdk-go \
    github.com/Sirupsen/logrus \
    github.com/joho/godotenv \
    github.com/urfave/cli \
    github.com/mattn/go-zglob

COPY . .

RUN CGO_ENABLED=0 GOOS=linux GOARCH=amd64 go build -a -tags netgo -o /bin/ecs-deploy

ENV AWS_SDK_LOAD_CONFIG=1

CMD [ "/bin/ecs-deploy" ]
