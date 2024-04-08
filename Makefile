.PHONY: start-dev build-bd stop

start-dev:
	docker-compose up -d

build-bd:
	docker-compose exec db mysql -u nikita -p pizzafabrika_db -e "CREATE TABLE IF NOT EXISTS orders (id VARCHAR(16) PRIMARY KEY, items JSON, status BOOLEAN, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);"

stop:
	docker-compose down
