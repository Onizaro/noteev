# Secure Note Manager (Full Stack + DevOps + Kubernetes)

## Objective
A web application that allows users to create, edit, and delete personal notes with basic encryption for added security.

## Main Features
- **Authentication** (JWT or session-based)
- **CRUD for notes** (Title, content, creation date)
- **Note encryption** (AES on the backend or WebCrypto on the frontend)
- **Dark/light mode**

## Tech Stack
### Frontend
- React (Next.js) 

### Backend
- Node.js (Express/NestJS)

### Database
- PostgreSQL or SQLite

### DevOps
- Containerization with Docker
- Deployment with Kubernetes (Minikube or k3s for local testing)
- CI/CD with GitHub Actions
- Basic logging and monitoring with Prometheus

## Installation & Execution
### Prerequisites
- Docker & Docker Compose
- Node.js 20+
- PostgreSQL (for production use)

### Installation
1. Clone the repository:
   ```sh
   git clone https://github.com/Onizaro/noteev.git
   cd noteev
   ```
2. Install dependencies:
   ```sh
   npm install
   ```

### Running Locally
...

### Deployment with Kubernetes
1. Apply the Kubernetes manifests:
   ```sh
   kubectl apply -f k8s/
   ```
2. Access the application via the exposed service.

## Possible Improvements
- Add object storage (S3, MinIO) for attachments
- Integrate OAuth (Google, GitHub) for authentication
- Implement tags and advanced search functionality

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

