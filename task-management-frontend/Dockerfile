# Base image
FROM node:18-alpine

# Set working directory
WORKDIR /app

# Install dependencies
COPY package*.json ./
RUN npm install

# Copy project files
COPY . .

# Build for production
RUN npm run build

# Expose port
EXPOSE 3000

ENV NUXT_PUBLIC_API_BASE=https://task-management-backend-507237580453.us-east1.run.app/api

# Start Nuxt in production mode
CMD ["npm", "run", "preview"]
