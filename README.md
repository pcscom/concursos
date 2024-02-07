
# Aspirantes

El objetivo de este proyecto es orientar a los aspirantes de concursos en el proceso de inscripción y seguimiento.
Esta desarrollado en PHP y HTML, con Bootstrap 5.

## Instalación

Aplica la documentación de primeros pasos de la página oficial de Yii2.

https://www.yiiframework.com/doc/guide/2.0/en/start-installation

Las dependencias están descriptas en el archivo composer.json.
Ejecutando el siguiente comando en la raíz del proyecto, se descargan los paquetes necesarios.

```php composer update```


Si el usuario no tiene permisos sobre los archivos donde se encuentra el proyecto, será necesario asignarle permisos de administrador.

### Configuración

El archivo /config/db.php contiene la configuración necesaria para la integración con la base de datos (MySQL).

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1:3306;dbname=demoDbName',
    'username' => 'demoUser',
    'password' => 'demoPassword',
    'charset' => 'utf8',
];


# Aspirantes

El objetivo de este proyecto es orientar a los aspirantes de concursos en el proceso de inscripción y seguimiento.
Esta desarrollado en PHP y HTML, con Bootstrap 5.

## Instalación

Aplica la documentación de primeros pasos de la página oficial de Yii2.

https://www.yiiframework.com/doc/guide/2.0/en/start-installation

Las dependencias están descriptas en el archivo composer.json.

### Configuración

El archivo /config/db.php contiene la configuración necesaria para la integración con la base de datos (MySQL).

``
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host={ip}:{puerto};dbname={Nombre de la instancia}',
    'username' => {usuario},
    'password' => {password},
    'charset' => 'utf8',
];
``

En caso de querer usar la url completa en lugar de relativa, en el archivo /config/web.php se puede reemplazar 

``'enablePrettyUrl' => true`` por ``'enablePrettyUrl' => false ``

### Autenticación y Usuarios

Se instaló el componente yii2-usuario de 2amigos para el manejo de usuarios. 

https://github.com/2amigos/yii2-usuario

Los archivos de configuración, vistas, modelos y controladores estan en /vendor/2amigos/yii2-usuario/src/User/
