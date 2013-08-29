SimuladorEnergia
================

Una sistema de programación para simular el consumo de energia basado en recibos de CFE, y los effectos de instalaciones de sistemas de generación alterna como fotovoltaico y eólico.

Instrucciones de Instalación para el Calculador Energético

Requerimientos:
Apache 2.0
PHP 5.3
Python 2.7
MySQL 5


Copiar directorio completo de archivos del calculador al directorio del servidor Web
Crear una base de datos en MySQL
Importar el archivo .sql proporcionado para crear las tablas mínimas necesarias
Instalar setuptools para poder instalar paquetes en Python más fácil
Se necesita tener el MySQL original de mysql.com instalado para que reconozca el easy_install http://stackoverflow.com/questions/1293484/easiest-way-to-activate-php-and-mysql-on-mac-os-10-6-snow-leopard
Con easy_install instalar las bibliotecas pyephem http://rhodesmill.org/pyephem/
Con easy_install instalar las bibliotecas de MySQL que python ocupa: sudo easy_install MySQL-python según http://qor72.blogspot.com/2009/04/mysql-os-x-and-easyinstall-mysql-python.html
Hay que exportar una variable llamada DYLD_LIBRARY_PATH con valor de /usr/local/mysql/lib según http://stackoverflow.com/questions/4559699/python-mysqldb-and-library-not-loaded-libmysqlclient-16-dylib
Estas instrucciones son algo específicas para MacOSX
Para Linux estas observaciones:
Para poder instala pyephem en Linux se corre el pip pero para poderlo compilar hay que tener instalado el python-dev package
El ephem marcaba un error al correr que no encontraba bibliotecas, entonces en esta página están las instrucciones para poder encimar el path a la hora de ejecutar el archiv, esto solo pasó en mi instalación de Linux Ubuntu http://docs.webfaction.com/software/python.html#importerror

Configuración:
Abrir el archivo caminoSolar.py
En la línea 19 modificar el directorio el cache de Python para que apunte a donde está ubicado el directorio eggs_cache y darle permisos de escritura:
os.environ['PYTHON_EGG_CACHE'] = '/home/energiav/www/eggs_cache' # linea para establecer un directorio para los temporales del cache EGG para la biblioteca de MySQLdb
En la línea 27, se tiene que modificar el path  donde está el paquete de pyephem, esto puede que solo sea necesario en Linux:
# en Linux se tuvo que cambiar el path donde estaba el paquete ephem pues python no lo encontraba
import sys
sys.path = ["/usr/local/lib/python2.7/dist-packages/ephem"] + sys.path
En la línea 33 cambiar los datos de conexión a la base de datos de MySQL por lo del servidor en donde se está instalando
db = MySQLdb.connect("localhost","usuario","contraseña","basededatos") #linea para conectarse al servidor local de energiasim





