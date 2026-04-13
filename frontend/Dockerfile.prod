# frontend/Dockerfile
FROM node:20-alpine AS builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM node:20-alpine
WORKDIR /app
COPY --from=builder /app/.output ./.output
# Nuxt 3/4 uses 3000 by default in the output server
EXPOSE 3000
CMD ["node", ".output/server/index.mjs"]
