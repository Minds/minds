# Setup and Config

To set up the web3 server locally, you should cd into this directory and copy the env example file, to a new env file:

```
cp .env.example .env
```

Following that, edit the env file and fill out the fields with the correct details.

From there you can run:

```
docker-compose build --no-cache web3-server
docker-compose up web3-server
```

Which should spin up the docker container locally.
