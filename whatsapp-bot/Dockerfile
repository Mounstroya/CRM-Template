FROM node:20-bookworm-slim

RUN apt-get update && apt-get install -y --no-install-recommends \
    python3 build-essential git ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# @whiskeysockets/baileys depends on a git+ssh github URL; rewrite it to
# https so `npm install` works without an SSH key baked into the image.
RUN git config --global url."https://github.com/".insteadOf "ssh://git@github.com/"

WORKDIR /app
COPY package*.json ./
RUN npm install --omit=dev

COPY . .

CMD ["node", "src/server.js"]
