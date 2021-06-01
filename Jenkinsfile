pipeline {
    agent any
	stages {
		stage('Checkout') {
			steps {
				checkout scm
			}
		}
		stage('Deploy to production') {
			when { branch 'feature/deploy' }
			steps {
				script {
				   def date = new Date()
				   writeFile(file: 'info.json', text: "{\"release_date\": \"" + date + "\"}")
				}
				sh 'composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs --no-progress --no-suggest'
				sh 'yarn install'
				sh 'yarn build'
				sh 'tar -czf artifact.tar *'
				sh 'ansible-playbook ansible/deploy.yml'
			}
		}
	}
}
