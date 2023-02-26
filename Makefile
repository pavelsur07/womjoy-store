init: init-ci
init-ci: docker-down-clear \
	site-clear \
	docker-pull docker-build docker-up \
	site-init \
	site-assets-build
#	site-indexer-elastic

up: docker-up
down: docker-down
restart: down up
check: lint validate-schema site-analyze site-test
lint: site-lint
validate-schema: site-validate-schema

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull --include-deps

docker-build:
	docker-compose build --pull

site-update: site-composer-update site-yarn-upgrade

site-clear:
	docker run --rm -v ${PWD}/site:/app -w /app alpine sh -c 'rm -rf  .ready var/cache/* var/log/* var/test/*'

site-init: site-permissions site-composer-install site-assets-install \
	site-wait-db site-migrations \
 	site-fixtures site-ready

site-permissions:
	docker run --rm -v ${PWD}/site:/app -w /app alpine chmod 777 var/cache var/log var/test

site-composer-install:
	docker-compose run --rm site-php-cli composer install

site-composer-update:
	docker-compose run --rm site-php-cli composer update

site-oauth-keys:
	docker-compose run --rm site-php-cli mkdir -p var/oauth
	docker-compose run --rm site-php-cli openssl genrsa -out var/oauth/private.key 2048
	docker-compose run --rm site-php-cli openssl rsa -in var/oauth/private.key -pubout -out var/oauth/public.key
	docker-compose run --rm site-php-cli chmod 644 var/oauth/private.key var/oauth/public.key

site-assets-install:
	docker-compose run --rm site-node-cli yarn install

site-assets-build:
	docker-compose run --rm site-node-cli yarn build

site-yarn-upgrade:
	docker-compose run --rm site-node-cli yarn upgrade

site-wait-db:
	docker-compose run --rm site-php-cli wait-for-it site-postgres:5432 -t 30

site-migrations:
	docker-compose run --rm site-php-cli php bin/console doctrine:migrations:migrate --no-interaction

site-fixtures:
	docker-compose run --rm site-php-cli php bin/console doctrine:fixtures:load --no-interaction

site-indexer-elastic:
	docker-compose run --rm site-php-cli php bin/console elastic:inti-elastic
	docker-compose run --rm site-php-cli php bin/console elastic:products-indexer

site-ready:
	docker run --rm -v ${PWD}/site:/app --workdir=/app alpine touch .ready

site-check: site-validate-schema site-lint site-analyze site-test

site-validate-schema:
	docker-compose run --rm site-php-cli php bin/console doctrine:schema:validate

site-lint:
	docker-compose run --rm site-php-cli composer lint
	docker-compose run --rm site-php-cli composer php-cs-fixer fix -- --dry-run --diff

site-cs-fix:
	docker-compose run --rm site-php-cli composer php-cs-fixer fix

site-analyze:
	docker-compose run --rm site-php-cli composer psalm -- --no-diff

site-analyze-diff:
	docker-compose run --rm site-php-cli composer psalm

site-test:
	docker-compose run --rm site-php-cli composer test

site-test-coverage:
	docker-compose run --rm site-php-cli composer test-coverage

site-test-unit:
	docker-compose run --rm site-php-cli composer test run unit
	docker-compose run --rm site-php-cli composer test -- --testsuite=unit

site-test-unit-coverage:
	docker-compose run --rm site-php-cli composer test-coverage -- --testsuite=unit

site-test-functional:
	docker-compose run --rm site-php-cli composer test run functional
	#docker-compose run --rm site-php-cli composer test -- --testsuite=functional

site-test-functional-coverage:
	docker-compose run --rm site-php-cli composer test-coverage -- --testsuite=functional

site-yarn-lint:
	docker-compose run --rm site-node-cli yarn eslint
	docker-compose run --rm site-node-cli yarn stylelint

site-yarn-eslint-fix:
	docker-compose run --rm site-node-cli yarn eslint-fix

site-yarn-pretty:
	docker-compose run --rm site-node-cli yarn prettier

validate-jenkins:
	curl --user ${USER} -X POST -F "jenkinsfile=<Jenkinsfile" ${HOST}/pipeline-model-converter/validate

#------------------------------------ build ------------------------------------------------
staging-build: staging-build-site-php-cli

staging-build-site-php-cli:
		docker --log-level=debug build --pull --file=site/docker/staging/php-cli/Dockerfile --tag=${REGISTRY}/staging-site-php-cli:${IMAGE_TAG} site

try-staging-build:
	REGISTRY=localhost IMAGE_TAG=0 make staging-build-site-php-cli

build: build-site

build-site:
	docker --log-level=debug build --pull --file=site/docker/production/nginx/Dockerfile --tag=${REGISTRY}/site:${IMAGE_TAG} site
	docker --log-level=debug build --pull --file=site/docker/production/php-fpm/Dockerfile --tag=${REGISTRY}/site-php-fpm:${IMAGE_TAG} site
	docker --log-level=debug build --pull --file=site/docker/production/php-cli/Dockerfile --tag=${REGISTRY}/site-php-cli:${IMAGE_TAG} site

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make build

push: push-site

push-site:
	docker push ${REGISTRY}/site:${IMAGE_TAG}
	docker push ${REGISTRY}/site-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY}/site-php-cli:${IMAGE_TAG}

#---------------------  Deploy PROD ----------------------------------
deploy:
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'docker network create --driver=overlay traefik-public || true'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'rm -rf site_${BUILD_NUMBER}'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'mkdir site_${BUILD_NUMBER}'

	envsubst < docker-compose-production.yml > docker-compose-production-env.yml
	scp -o StrictHostKeyChecking=no -P ${PORT} docker-compose-production-env.yml deploy@${HOST}:site_${BUILD_NUMBER}/docker-compose.yml
	rm -f docker-compose-production-env.yml

	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker stack deploy --compose-file docker-compose.yml site --with-registry-auth --prune'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'rm -f site'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'ln -sr site_${BUILD_NUMBER} site'

deploy-clean:
	rm -f docker-compose-production-env.yml

rollback:
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker stack deploy --compose-file docker-compose.yml site --with-registry-auth --prune'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'rm -f site'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'ln -sr site_${BUILD_NUMBER} site'