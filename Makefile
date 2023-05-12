#!/bin/bash

UID = $(shell id -u)
DOCKER_BE = my-app-container-be
DOCKER_POSTGRES = my-app-container-postgresql
DOCKER_MYSQL = my-app-container-mysql
DOCKER_RABBIT = my-app-container-rabbitmq

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

start: ## Start the containers
	docker network create global-mysql-network || true
	docker network create global-rabbitmq-network || true
	docker network create global-postgresql-network || true
	cp -n docker-compose.yml.dist docker-compose.yml || true
	U_ID=${UID} docker-compose up -d

stop: ## Stop the containers
	U_ID=${UID} docker-compose stop

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) start

build: ## Rebuilds all the containers
	docker network create global-mysql-network || true
	docker network create global-rabbitmq-network || true
	docker network create global-postgresql-network || true
	cp -n docker-compose.yml.dist docker-compose.yml || true
	U_ID=${UID} docker-compose build

prepare: ## Runs backend commands
	$(MAKE) composer-install

server-start: ## starts the Symfony development server
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} symfony server:start -d

win-server-start: ## starts the Symfony development server
	U_ID=${UID} winpty docker exec -it --user ${UID} ${DOCKER_BE} symfony server:start -d

server-status: ## show status of the Symfony development server
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} symfony server:status

win-server-status: ## show status of the Symfony development server
	U_ID=${UID} winpty docker exec -it --user ${UID} ${DOCKER_BE} symfony server:status

server-stop: ## stop the Symfony development server
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} symfony server:stop

win-server-stop: ## stop the Symfony development server
	U_ID=${UID} winpty docker exec -it --user ${UID} ${DOCKER_BE} symfony server:stop

server-logs: ## Show Symfony logs in real time
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} symfony server:log

win-server-logs: ## Show Symfony logs in real time
	U_ID=${UID} winpty docker exec -it --user ${UID} ${DOCKER_BE} symfony server:log

# Backend commands
composer-install: ## Installs composer dependencies
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} composer install --no-interaction
# End backend commands

ssh-be: ## bash into the be container
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bash

ssh-be-root: ## bash into the be container with root user
	docker exec -it --user root ${DOCKER_BE} bash

ssh-postgres-root: ## bash into the postgres container with root user
	docker exec -it --user root ${DOCKER_POSTGRES} bash

ssh-mysql-root: ## bash into the mysql container with root user
	docker exec -it --user root ${DOCKER_MYSQL} bash

ssh-rabbitmq-root: ## bash into the rabbitmq container with root user
	docker exec -it --user root ${DOCKER_RABBIT} bash

code-style: ## Runs php-cs to fix code styling following Symfony rules
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} php-cs-fixer fix src --rules=@Symfony

containers-stats: ## Show containers stats
	docker stats ${DOCKER_BE} ${DOCKER_POSTGRES} ${DOCKER_MYSQL} ${DOCKER_RABBIT}

delete-project-containers: ## Delete project containers in Docker [command to delete all containers: docker rm -f $(docker ps -a -q)]
	docker rm -f ${DOCKER_BE} ${DOCKER_POSTGRES} ${DOCKER_MYSQL} ${DOCKER_RABBIT}

delete-all-volumes: ## Delete all volumes in Docker
	#docker volume rm $(docker volume ls -q)

delete-all-images: ## Delete all images in Docker
	#docker image rm -f $(docker image ls -a -q)
