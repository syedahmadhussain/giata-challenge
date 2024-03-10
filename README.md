# GIATA Hotel Rating Calculator

The project involves accessing giata hotel directory, parsing data into redis cache and then calculate hotel ratings.

### Tech Stack:

- **PHP Version**: 8.1
- **Framework**: Symfony
- **Containerization**: Docker
- **Version Control**: Git

### Prerequisites:

- **Docker**: Ensure Docker is installed on your system.
- **Git**: Ensure Git is installed on your system.


## Setup

To get started with the project, follow these steps:

1. **Clone the repository**:
    ```bash
    git clone https://github.com/syedahmadhussain/giata-challenge
    ```

2. **Navigate to the project directory**:
    ```bash
    cd giata-challenge
    ```

3. **Install Dependencies**:
    ```bash
    make install
    ```

4. **Start the Application**:
    ```bash
    make run
    ```

5. **Stop the Application**:
    ```bash
    make stop
    ```

6. **Run Tests**:
    ```bash
    make test
    ```

## Makefile Commands

- **install**: Sets up the environment, builds Docker images, installs PHP dependencies, and runs database migrations.

- **run**: Starts the Docker containers for the Symfony application.

- **stop**: Stops the Docker containers.

- **test**: Runs PHPUnit tests.

- **calculate-ratings**: calculate the hotel ratings based on giataId and rating value