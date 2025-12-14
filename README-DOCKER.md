# Docker Setup for WordPress Project

## Ports Used
- **WordPress**: http://localhost:8081
- **MySQL**: localhost:3307
- **phpMyAdmin**: http://localhost:8082

## Quick Start

### 1. Start the containers
```bash
docker compose up -d
```

### 2. Access the application
- **WordPress Site**: http://localhost:8081
- **phpMyAdmin**: http://localhost:8082
  - Username: `u160821579_PLz6S`
  - Password: `K69czUnlNw`

### 3. Stop the containers
```bash
docker compose down
```

### 4. Stop and remove volumes (clean slate)
```bash
docker compose down -v
```

## Useful Commands

### View logs
```bash
# All services
docker compose logs -f

# Specific service
docker compose logs -f wordpress
docker compose logs -f db
```

### Restart services
```bash
docker compose restart
```

### Access container shell
```bash
# WordPress container
docker exec -it rlg_wordpress bash

# MySQL container
docker exec -it rlg_mysql bash
```

### Import database manually
```bash
docker exec -i rlg_mysql mysql -u u160821579_PLz6S -pK69czUnlNw u160821579_qQ7j0 < u160821579_qQ7j0.sql
```

### Backup database
```bash
docker exec rlg_mysql mysqldump -u u160821579_PLz6S -pK69czUnlNw u160821579_qQ7j0 > backup.sql
```

## Configuration

### Database Connection
The `wp-config.php` will need to be updated to use the Docker database host:
- Host: `db` (instead of `127.0.0.1`)
- This is already configured in the docker-compose.yml environment variables

### Updating wp-config.php for Docker
If you need to manually update wp-config.php, change:
```php
define( 'DB_HOST', '127.0.0.1' );
```
to:
```php
define( 'DB_HOST', 'db' );
```

## Troubleshooting

### Port already in use
If you get a port conflict error, edit `docker-compose.yml` and change the port mapping:
```yaml
ports:
  - "NEW_PORT:80"  # Change NEW_PORT to an available port
```

### Database connection issues
1. Make sure the database container is running: `docker compose ps`
2. Check database logs: `docker compose logs db`
3. Verify database credentials in docker-compose.yml match wp-config.php

### Permission issues
```bash
docker exec -it rlg_wordpress chown -R www-data:www-data /var/www/html
```

## Notes
- The SQL file (`u160821579_qQ7j0.sql`) will be automatically imported on first run
- All WordPress files are mounted from the current directory
- Database data persists in a Docker volume named `db_data`

