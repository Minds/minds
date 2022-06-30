# Use official node alpine image on v14.17.1 (fernium).
FROM node:fermium-alpine

# Set workdir to root Minds folder.
WORKDIR /var/www/Minds/

# Install deps / clone git.
RUN apk add curl docker git chromium nss freetype harfbuzz ca-certificates ttf-freefont
RUN git clone https://gitlab.com/minds/developers/metascraper-server.git/

# Set workdir to cloned dir.
WORKDIR /var/www/Minds/metascraper-server

# Set Pupeteer to skip Chromium download.
ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true \
    PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium-browser

# Install Node
RUN npm install

# Add user so we don't need --no-sandbox.
RUN addgroup -S pptruser && adduser -S -G pptruser pptruser \
    && mkdir -p /home/pptruser/Downloads /app \
    && chown -R pptruser:pptruser /home/pptruser \
    && chown -R pptruser:pptruser /app \
    && chown -R pptruser:pptruser .

# Run everything after as non-privileged user.
USER pptruser

# Start.
CMD npm run build && npm run start
