Aprendices

Integrantes:
* Francisco Natanael Ortiz Martínez - natafrank.cucei@gmail.com
* Miguel Ángel López Cervantes - miguelfrost78@gmail.com
* Jordan Alberto Alvarez Macías - jordalbert23@gmail.com

Hosting: http://aprendices.url.ph/

Facebook:
https://www.facebook.com/natafrank.cucei
https://www.facebook.com/MigueLopezC
https://www.facebook.com/jordanalberto.alvarezmacias

Twitter:
https://twitter.com/natafrank - @natafrank
https://twitter.com/MigueLpzC - @MigueLpzC


==========

Repository for web class CUCEI.

# Inventario vehicular e inspecciones

## Descripción general
Se requiere llevar control de inspecciones vehiculares cuantos estos ingresan al taller.
La inspección se lleva por medio de un checklist en el cual se evalua el status del vehiculo así como daños que este pudiera presentar.
Al finalizar la inspección se define el lugar al cual será asignado el vehículo.
El vehículo puede cambiar su ubicación durante su estancia en el taller y se requiere llevar control de esto indicando siempre quién ha realizado la acción, el motivo y en que fecha y hora ha sucedido el evento.
Para dar salida al vehículo, es necesario realizar nuevamente la inspección y el sistema deberá indicar si hay cambios en su estatus de entrada contra su estatus de salida.

## Vehículos
De los vehiculos se mantiene un número de identificación llamado VIN el cual es leído con un escaner a traves de una tablet o dispositivo móvil.
Al leer el VIN, el sistema reconoce la marca, el modelo, color, caracteristicas, etc.

## Inventario
Este será llenado desde una tableta o cualquier dispositivo móvil cumpliendo con un formato amigable para el usuario donde pueda seleccionar los campos preferentemente sin tener que escribir.
Se debe evaluar:
Kilometraje
Cantidad de combustible
Golpes (se define pieza y severidad)

## Ubicación
El taller tiene ubicaciones definidas por el administrador tales como taller, horno, patio etc.
Cada uno de estos puede o no tener además ubicaciones en forma de tablero, ejemplo, en patio se definen ubicaciones como A1, C4, etc.
