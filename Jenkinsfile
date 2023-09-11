pipeline {
    agent {
        label 'agent1'
    }

    stages {

        stage('Construir Imagen Docker') {
            steps {
                script {
                    // Construir la imagen Docker utilizando el Dockerfile en tu repositorio
                    def dockerImage = docker.build('concursos-jenkins', '-f ci/Dockerfile .')
                }
            }
        }
    }
}

