# A program to
# 
# Project Leader Rodger Evans, 2011-06-01
# sunnycanuck@gmail.com
# Collaborators Voxel Soluciones
# http://www.voxelsoluciones.com
# info@voxelsoluciones.com
#
# Published under the Creative Commons Attribution-ShareAlike 2.5 Generic (CC BY-SA 2.5) licence
# http://creativecommons.org/licenses/by-sa/2.5/
#
# Publicado bajo la Licencia Creative Commons Atribucion-CompartirIgual 2.5 Mexico (CC BY-SA 2.5) 
# http://creativecommons.org/licenses/by-sa/2.5/mx/
#  

#!/usr/bin/python

import os
os.environ['PYTHON_EGG_CACHE'] = '/home/energiav/www/eggs_cache' # linea para establecer un directorio para los temporales del cache EGG para la biblioteca de MySQLdb

import MySQLdb
#import ephem
import math

# en Linux se tuvo que cambiar el path donde estaba el paquete ephem pues python no lo encontraba
import sys
sys.path = ["/usr/local/lib/python2.7/dist-packages/ephem"] + sys.path
import ephem

#Open database connection
#db = MySQLdb.connect("localhost","root","le3lo030","calculador")
#db = MySQLdb.connect("localhost","voxelsol_calculador","c4lcul4d0r!","voxelsol_calculador") #linea para conectarse en servidor de Voxel
db = MySQLdb.connect("localhost","energiav_calcula","c4lcul4d0r!","energiav_calculado") #linea para conectarse en servidor local de energiasim

#preparar un objeto de cursor utilizando el cursor() method
cursor = db.cursor()

A=0 #distancia arriba del mar

# para visibilidad de 23km 
a0st=0.4237-0.00821*(6-A)**2
a1st=0.5055+0.00595*(6.5-A)**2
kst=0.2711+0.01858*(2.5-A)**2

# para visabilidad de 5km
#a0st=0.2538-0.0063*(6-A)**2
#a1st=0.7678+0.0010*(6.5-A)**2
#kst=0.249+0.081*(2.5-A)**2

tTot=365*24 #numero total de muestras de tiempo (horas)
#tTot=24 #numero total de muestras de tiempo (horas)
dT=15 #tamano de intervalo de tiempo (en minutos)
num=round(tTot*60/dT) #numero de intervalos
num=int(num)
longitude = 0
latitude = 0

#id_terreno = '38' #id temporal del terreno a leer para calculos
id_terreno = sys.argv[1] #argumento enviado desde archivo de php con el tid=id del terreno

#aqui vamos a generar la tabla para el numero de terreno
cursor.execute("""DROP TABLE IF EXISTS ce_camino_solar_%st""" % (id_terreno))
sql = """CREATE TABLE ce_camino_solar_%st (
		 id INT PRIMARY KEY AUTO_INCREMENT,
		 tiempo TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		 az FLOAT(9,6),
		 alt FLOAT(9,6),
		 intcero INT,
		 intuno INT)""" % (id_terreno)
try:
	cursor.execute(sql)
except:
	print "Error: no se pudo crear la tabla"			 

# leer todos los datos de tabla ce_terreno que se utilizaran
sql = "SELECT id, latitude, longitude FROM ce_terreno WHERE id = %s" % id_terreno
try:
	cursor.execute(sql)
	numrows = int(cursor.rowcount)
	for i in range(numrows):
		row = cursor.fetchone()
		latitude = str(row[1])
		longitude = str(row[2])
		#latitude = '-116.646843'
		#longitude = '31.860884'

		#para imprimir resultado obtenido
		#print "Terreno %s latitude %s longitude %s \n" % (id_terreno, latitude, longitude)
except:
	print "Error: no se pudo leer la informacion"		

gatech = ephem.Observer()
#gatech.long, gatech.lat = '-116.646843', '31.860884'
gatech.lat, gatech.long = latitude, longitude
#print "latitude %s longitude %s PARA EPHEM \n" % (latitude, longitude)
d=gatech.date = '2011/1/1 8:00'   # 12 midnight WST (baja)

#comienza ciclo para generar datos y guardarlos en la tabla
for i in range(num):
	sun= ephem.Sun()
	sun.compute(gatech)

	vaz = float(sun.az)
	valt = float(sun.alt)
	vdate = gatech.date
	#para calcular intensidad
	if valt>0:
		trans=a0st+a1st*math.exp(-kst/(math.cos(math.pi/2-valt)))
		intensidad=1370*trans
	else:
		intensidad=0
	#intensidad=1 #esta linea es temporal para cuestiones de exprimentacion sabado 23 junio 2012	

	sql = "INSERT INTO ce_camino_solar_%st (tiempo, alt, az, intcero) VALUES ('%s','%f','%f','%d')" % (id_terreno,vdate,valt,vaz,intensidad)
	try:
		# Execute the SQL command
		cursor.execute(sql)
		# Commit your changes in the database
		db.commit()
	except:
		# Rollback in case there is an error
		db.rollback()

	gatech.date+=ephem.minute*dT
		
#disconnect from server
cursor.close()
db.close()