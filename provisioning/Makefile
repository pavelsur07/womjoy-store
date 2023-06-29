site-ru:
	ansible-playbook -i hosts_ru.yml site.yml

site-com:
	ansible-playbook -i hosts_com.yml site.yml

site:
	ansible-playbook -i hosts.yml site.yml

upgrade-ru:
	ansible-playbook -i hosts_ru.yml upgrade.yml

upgrade-com:
	ansible-playbook -i hosts_com.yml upgrade.yml

upgrade-ru:
	ansible-playbook -i hosts_ru.yml upgrade.yml

authorize-com:
	ansible-playbook -i hosts_com.yml authorize.yml

authorize:
	ansible-playbook -i hosts.yml authorize.yml

authorize:
	ansible-playbook -i hosts.yml authorize.yml

generate-deploy-key:
	ssh-keygen -q -t rsa -N '' -f files/deploy_rsa

authorize-deploy:
	ansible-playbook -i hosts.yml authorize-deploy.yml

docker-login:
	ansible-playbook -i hosts.yml docker-login.yml
