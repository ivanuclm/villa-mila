<?php

return [
    'owner_email' => env('VILLA_OWNER_EMAIL'),
    'owner_name' => env('VILLA_OWNER_NAME', 'Milagros'),
    'owner_phone' => env('VILLA_OWNER_PHONE', '+34 600 000 000'),
    'owner_whatsapp' => env('VILLA_OWNER_WHATSAPP'),

    'check_in_time' => env('VILLA_CHECK_IN_TIME', 'a partir de las 16:00'),
    'check_out_time' => env('VILLA_CHECK_OUT_TIME', 'antes de las 12:00'),

    'payment' => [
        'bank_account' => env('VILLA_BANK_ACCOUNT', 'ES00 0000 0000 0000 0000 0000'),
        'account_name' => env('VILLA_BANK_ACCOUNT_HOLDER', 'Milagros – Villa Mila'),
        'instructions' => env('VILLA_PAYMENT_NOTES', 'Indica en el concepto tu nombre y fechas y envía el comprobante del pago.'),
    ],

    'house_rules_url' => env('VILLA_HOUSE_RULES_URL'),
    'guidebook_url' => env('VILLA_GUIDEBOOK_URL'),
    'contract_url' => env('VILLA_CONTRACT_URL'),

    'contract_template' => <<<'CONTRACT'
CONTRATO DE ALQUILER TURÍSTICO DE CORTA DURACIÓN
CASA RURAL VILLA MILA de VALDEGANGA  ( ALBACETE )

Calle Olmo 39 / Calle Abadía 55  / Valdeganga / Albacete / España  C. P .02150
La Casa Rural Villa Mila es un ALOJAMIENTO .
En Valdeganga a ………de……………………………..de …………….

De una parte el PROPIETARIO………………………………………………………………………;
mayor de edad , con domicilio a efecto de notificaciones en C/ Zamora nº 4 7º izq. B
02001 Albacete ; con D N I  ……………………….

Por otra parte  : DATOS DEL QUE RESERVA EN SU NOMBRE Y EN REPRESENTACIÓN DE LOS VIAJEROS :
NOMBRE
PRIMER APELLIDO
SEGUNDO APELLIDO
TIPO DE DOCUMENTO IDENTIFICATIVO……………………Nº………………………………
FECHA Y PAÍS DE NACIMIENTO………………………………………………………………..
DOMICILIO HABITUAL  C/…………………………………………………………………………..
Nº…………  CIUDAD…………………………………..PROVINCIA ( STATE )
PAÍS………………………………………….

A EFECTOS DE FACTURACIÓN
DOMICILIO
POBLACIÓN
PROVINCIA
PAÍS DE FACTURACIÓN : ESPAÑA .
CÓDIGO POSTAL
CÓDIGO DE RESERVA ( Fecha de la reserva )………………………………….
Será proporcionado por el propietario y será el mismo para todos los viajeros del grupo y 
coincidirá con la fecha de pago de la RESERVA-FIANZA .

LAS NORMAS DE VILLA MILA DE VALDEGANGA SE BASAN EN LAS LEYES Y REALES DECRETOS :
                      LAU 4/2013 de 4 de junio .
                      LSC 4/2015 de 30 de marzo
                      RC 88 /2018 de 29 de noviembre.
                      RC 933/ 2021 de 26 de octubre
                      RC 1312 /2024 de 23 de diciembre .
                   
CAPACIDAD : / 8 / ocho . Camas supletorias : 3 y 1 cuna .Total 11 / once . 
Se prohíbe acceder  a todo el recinto y en todo momento a personas y mascotas que no figuren en este contrato ; el propietario se verá obligado a informar a las autoridades .
En caso de recibir invitados , incluidos menores , que no figuren en el registro ,ni el propietario ,ni el seguro se responsabilizan de los daños que pudieran sufrir,ni de los que infrinjan .La cuantía de los  daños a la propiedad se especificará por factura aparte de la estipulada en el contrato .

VILLA MILA de Valdeganga ,Albacete es un ALOJAMIENTO NO COMPARTIDO , DE USO EXCLUSIVO.

MÍNIMO de NOCHES que se RESERVAN 2 DOS NOCHES .
MÍNIMO de VIAJEROS que RESERVAN 8 OCHO ,incluidos los niños , más una cuna gratuita . Si vienen menos de ocho viajeros ,pagan por ocho .
No se alquila por habitaciones .
MÁXIMO DE VIAJEROS INCLUIDOS LOS NIÑOS ,EXCEPTO LOS BEBÉS DE CUNA .
11 ONCE .
Todos los viajeros pagan el mismo precio .
Todos los niños menores de tres años y bebés que duerman en cuna o con los padres no contabilizarán como cama ocupada ,pero tendrán que registrarse .
Deben traer su propia ropa de cuna .

EL PROPIETARIO ESTÁ OBLIGADO A REGISTRAR LOS DATOS IDENTIFICATIVOS DE TODOS LOS VIAJEROS INCLUSO NIÑOS Y BEBÉS .

MASCOTAS : SOLO SE ADMITE UN PERRO DE ASISTENCIA CON SU DOCUMENTACIÓN . Debe traer su propia cama y sus platos para comer y beber.
Hará sus necesidades fuera del alojamiento , en el campo ,cumpliendo las normas de convivencia ,limpiar. No se pueden bañar en la piscina .Los daños que infrinjan al alojamiento correrán a cargo del dueño . 
 
EL QUE RESERVA Y REPRESENTA A TODOS LOS VIAJEROS declara la veracidad de todos los datos del contrato y asume toda responsabilidad administrativa o sancionadora que pudiera derivarse del incumplimiento de esta cláusula o de este contrato.

RESERVA Y FIANZA

Reserva inmediata : 2 DÍAS antes de la fecha de entrada . (Mirar en el calendario la disponibilidad ) ENTRE LA SALIDA DE VIAJEROS ANTERIORES Y VUESTRA ENTRADA HAY QUE DEJAR DOS DÍAS. 
Reserva de hasta  6 MESES antes de la fecha de entrada .
Reserva previo pago de 250 € , que también servirán de FIANZA , NO LIMITATIVA , para responder de posibles daños o uso indebido del  recinto e incumplimiento del contrato .
Si no hay daños se devolverán en el plazo de 3 días después de la salida.
En caso de daños  no cubiertos por el seguro o incumplimiento del contrato , se especificarán por factura aparte de la estipulada en el contrato, teniendo en cuenta la cuantía de la fianza,en el plazo de 14 días posteriores a la salida .
 
PERIODO RESERVADO DE NOCHES Y DÍAS QUE SE ALQUILAN
Desde el día……. de…………………de…………..a las 12 h AM, hasta el día………. de…………………..de …………….a las 12 h AM . SON ………..NOCHES .
PRECIO…………………€ . SE PAGARÁ el día de la entrada según acuerden las partes .

Villa Mila de Valdeganga Albacete tiene Seguro de Responsabilidad Civil
Queda prohibido al viajero ceder o subarrendar el alojamiento.

POLÍTICA DE CANCELACIÓN
Si se cancela en el plazo de 48 horas después de la reserva , se devolverá el total de la reserva .
Si se cancela entre 48 horas después de la reserva y 15 días antes de la entrada , se devuelve el 50% .
Si se cancela con 14 días antes de la entrada no se devuelve la cuantía de la reserva .
Si se cancela por situaciones de fuerza mayor , debidamente documentadas , se devuelve el total de la reserva .
Si el propietario se ve obligado a cancelar la reserva por razones de fuerza mayor,devolverá el total de la reserva ,intentará encontrar otro alojamiento similar u ofrecer otras fechas a los viajeros .

LA ENTRADA
Dos días antes de la entrada el propietario mandará a los viajeros un enlace para rellenarlo con sus datos y automáticamente se enviará al Ministerio del Interior. Recibirán un mensaje diciendo que se ha enviado correctamente .
Las llaves se entregarán tras el pago total del importe de la estancia.
Serán recibidos personalmente. El alojamiento dispone de caja para llaves con contraseña . 
El horario de entrada puede ser flexible .En este caso , el representante de los viajeros debe contactar con el propietario para acordar una modificación .
Adelantar la entrada  y /o retrasar la salida puede suponer un coste extra ,depende de la disponibilidad y hay que comunicarlo al propietario con 24 h. de antelación.


REGISTRO DOCUMENTAL DE  LAS PERSONAS FÍSICAS O JURÍDICAS
LISTADO DE VIAJEROS 

Nombre
Primer apellido
Segundo apellido
Fecha de nacimiento……………..Indicar si es menor , parentesco……………………….
Documento identificativo . Tipo……………………………………Nº……………………………
Nº de soporte ………………………………….Sexo…………………………………
País de nacimiento……………………………..
Código de reserva……………………………………

Nombre 
Primer apellido
Segundo apellido
Fecha de nacimiento………… .Indicar si es menor ,parentesco……………..
Documento identificativo.Tipo……………………………………..Nº………………………………
Nº soporte……………………………………….Sexo…………………………..
País de nacimiento……………………………….
Código de reserva……………………………………..



Nombre
Primer apellido
Segundo apellido
Fecha de nacimiento……………. Indicar si es menor ,parentesco ………………………..
Documento identificativo. Tipo……………………………………Nº………………………
Nº soporte…………………………………………Sexo………………………….
País de nacimiento……………………………
Código de reserva………………………………

Nombre
Primer apellido
Segundo apellido
Fecha de nacimiento……………… Indicar si es menor ,  parentesco…………………...
Documento identificativo . Tipo…………………………………….Nº……………………….
Nº de soporte………………………………………..Sexo……………………………
País de nacimiento………………………………
Código de reserva……………………………….

Nombre
Primer apellido
Segundo apellido
Fecha de nacimiento……………… Indicar si es menor ,  parentesco ……………………..
Documento identificativo . Tipo……………………………………Nº…………………………
Nº de soporte………………………………………Sexo…………………………..
País de nacimiento……………………………………..
Código de reserva………………………….

Nombre
Primer apellido
Segundo apellido
Fecha de nacimiento…………………… Indicar si es menor,   parentesco…………………...
Documento identificativo . Tipo………………………. Nº ...………………………….
Nº de soporte ... …………………………………….. Sexo …………………………..
País de nacimiento……………………………………….
Código de reserva……………………………..

Nombre
Primer apellido ………………………………
Segundo apellido………………………………
Fecha de nacimiento…………………… .Indicar si es menor ,  parentesco………………….. .
Documento identificativo. Tipo………………………...Nº………………………
Nº de soporte ……………………………………… Sexo…………………………
País de nacimiento…………………………………………
Código de reserva……………………………….



Nombre
Primer apellido……………………………………..
Segundo apellido………………………………….
Fecha de nacimiento…………………… .Indicar si es menor , parentesco …………………….
Documento identificativo .Tipo…………………………Nº…………………………..
Nº de soporte …………………………………………Sexo……………………………
País de nacimiento ………………………………………..
Código de reserva …………………………………

Nombre
Primer apellido ……………………………………
Segundo apellido ………………………………….
Fecha de nacimiento……………………...Indicar si es menor ,parentesco ………………….
Documento identificativo .Tipo…………………………Nº………………………………
Nº de soporte ………………………………………..Sexo…………………………….
País de nacimiento …………………………………………
Código de reserva ………………………………………

Nombre
Primer apellido …………………………………………
Segundo apellido …………………………………….
Fecha de nacimiento …………………... .Indicar si es menor  ,parentesco………………..
Documento identificativo .Tipo………………………..Nº……………………………….
Nº de soporte………………………………………..Sexo………………………………. 
País de nacimiento ……………………………………..
Código de reserva……………………………………………


 
Nombre
Primer apellido………………………………………………
Segundo apellido…………………………………………….
Fecha de nacimiento……………………… Indicar si es menor ,parentesco……………..
Documento identificativo .Tipo………………………..Nº……………………………………
Nº de soporte………………………………………Sexo………………………………………
País de nacimiento…………………………………..
Código de reserva……………………………………

Nombre
Primer apellido……………………………………………….
Segundo apellido………………………………………………..
Fecha de nacimiento……………………… Indicar si es menor ,parentesco……………
Documento identificativo .Tipo………………………Nº……………………………………
Nº de soporte………………………………………..Sexo…………………………………
País de nacimiento…………………………………
Código de reserva…………………………………….


Nombre
Primer apellido: ...…………………………………………………
Segundo apellido…………………………………………………..
Fecha de nacimiento……………………….. Indicar si es menor ,parentesco………….
Documento identificativo. Tipo………………………..Nº…………………………………..
Nº de soporte………………………………………….Sexo…………………………………
País de nacimiento……………………………………..
Código de reserva……………………………………………

Nombre
Primer apellido………………………………………………….
Segundo apellido………………………………………………..
Fecha de nacimiento……………………… Indicar si es menor ,parentesco……………
Documento identificativo .Tipo………………………Nº……………………………………..
Nº de soporte…………………………………………Sexo……………………………..
País de nacimiento……………………………………..
Código de reserva……………………………………….


DOCUMENTACIÓN DEL PERRO DE ASISTENCIA 
Nº de inscripción en el registro . Vinculación con su dueño . Nombre del dueño .
Debe llevar chip .
Debe presentar la cartilla de vacunación al día .
Si sufre daños o produce daños al alojamiento o a terceros serán imputables a su dueño y este exime de toda responsabilidad al alojamiento y al propietario . La fianza , no limitativa ,será tenida en cuenta para subsanar dichos daños. 
 


LA SALIDA


Este contrato  quedará automáticamente resuelto ,sin necesidad de aviso previo ,en el plazo y hora señalados .
Retrasar la salida puede suponer un coste extra y es necesario comunicarse con el propietario con 24 h. de antelación ,pues los plazos están sujetos a disponibilidad .
Si el viajero abandona el alojamiento antes deberá comunicarlo al propietario y no tendrá derecho al reembolso del importe pagado .
Al finalizar la estancia ,el viajero deberá devolver las llaves SIN DEMORA  al propietario o dejarlas en el lugar previamente acordado.
Se considera FALTA GRAVE no devolver las llaves y no salir del recinto alojativo cuando expire el periodo del contrato.
Si no cumpliesen esta claúsula el viajero deberá abonar una cantidad igual al TRIPLE del precio diario contratado por cada día adicional hasta obtener el propietario la libre posesión
independientemente del pago de la reserva - fianza .



NORMAS GENERALES DE LA CASA

RESPETAR EL ENTORNO FÍSICO tanto del interior como del exterior del recinto.
EVITAR INCENDIOS : 
       LA BARBACOA solo se usa con carbón .La casa provee de una bolsa de carbón gratuita. No puede encenderse si el viento sopla a 20 km /h o más .
Se proporcionarán cartones para evitar manchar el suelo adyacente a la barbacoa y debajo del paellero móvil y evitar accidentes .
       FUMAR y VAPEAR está prohibido dentro de la casa .Se puede fumar en el porche y en lugares lejos del monte .Obligatorio el uso de ceniceros . Prohibido tirar colillas fuera de los ceniceros.
       PROHIBIDO USAR BENGALAS ,PETARDOS Y COHETES .
       PROHIBIDO TIRAR AL MONTE ningún tipo de suciedad que altere el entorno .
       RECICLAR .Habrá bolsas de basura de repuesto .
RESPETAR EL ENTORNO ACÚSTICO : HORARIO DE DESCANSO DE 00:00 A 
8 AM . Moderar el volumen de la música y los ruidos .
PROHIBIDO FIESTAS ESTRIDENTES Y COMPORTAMIENTOS que alteren la tranquilidad del entorno y molesten a los vecinos,(pueden llamar a las autoridades)  .
Los grupos que alquilan deben tener una media de 25 años .
Los padres o tutores se hacen responsables de sus hijos.
El perro es responsabilidad de su dueño.
Dentro de la casa no se pueden usar pelotas y balones .
Dentro  del recinto no se pueden usar patines ,patinetes, patinetes eléctricos .El sistema eléctrico de la casa no permite recargar vehículos eléctricos .
Prohibido usar confetis en todo el recinto .Limpiarlos ocupa tiempo y perjudica la disponibilidad del alojamiento.
No se admiten grupos para despedidas de soltero/a . 
Se prohíben fiestas que puedan ocasionar daños para el alojamiento .
No se permite mover muebles , ni realizar modificaciones de su disposición original .
Prohibido subirse a los muebles ,sentarse sobre ellos y en las camas con la ropa mojada .
Prohibido dejar ropa mojada encima de los muebles y camas .
.


LA PISCINA es desbordante y automática .Se rellena de agua automáticamente .En uno de los lados tiene un pozo de compensación con una tapa amarilla a ras del suelo que no se debe pisar .Depura de forma automática sin peligro para los bañistas .Tiene un flotador con cloro de refuerzo .
Está al servicio de los viajeros desde finales de mayo hasta la primera semana de octubre ,
si el tiempo lo permite.
No está vallada.
No tirarse de cabeza a la piscina.
A veces en el periodo de frío puede estar tapada con una cubierta que no deben pisar personas y perros .
Ducharse en la ducha solar al entrar y al salir.
Prohibido usar cristal ,vidrio,chapas …cerca y dentro de la piscina.
No tirar nada dentro de la piscina.
No poner encima de los gresites de la piscina pies de sombrilla, sillas,objetos metálicos que puedan deteriorar el gresite.
Recoger los pelos de la ducha solar para que no se lode y se derrame el agua en la piscina.
Prohibido que se bañe el perro .

BAÑOS
Hay dos baños completos y un aseo .
Se ponen dos o tres rollos de papel higiénico en cada baño .Tienen extractores.
Habrá jabón de manos ,gel , champú , repuesto de bolsa de basura y un secador de pelo.
Las toallas están encima de las camas. No se usan para desmaquillarse (trae tus propios algodones ) y no se usan para la piscina, hay otras para esa función . Habrá toallas adicionales en los armarios o cajones de los dormitorios .
No colgar toallas en la mampara ni en la barra de las cortinas de la bañera ,hay perchas para ello. Hay tendederos interior y exterior para tender las toallas y dejarlas a la salida .
Evitar atascos : en el WC solo papel higiénico ,no de cocina, compresas y algodones al cubo de basura.Recoger los pelos de la ducha y bañera.
Se proporcionará kit de limpieza para casos de necesidad.
Deben traer sus propias compresas .

COCINA
Totalmente equipada con electrodomésticos básicos y menaje suficiente .
Usar  hules para proteger las mesas. En la casa hay hules.
No golpear los cajones del frigo con las bolsas de hielo.
Usar los salvamanteles para proteger del calor.
 En un armario de la cocina  hay un botiquín completo y revisado antes de cada estancia.
No contiene productos que puedan suponer daños ,solo los básicos recomendados por ley.
 
NO DERROCHAR ENERGÍA : calefacción ,aire acondicionado y electricidad . 
NO DERROCHAR AGUA .
A LA SALIDA SACAR LA BASURA A LOS CONTENEDORES DE RECICLAJE .Están a 30 m de la casa .

DESCRIPCIÓN DEL ALOJAMIENTO

Calefacción en toda la casa ,aire acondicionado ,extractores en los baños ,mosquiteras bloqueadores de puertas en los dormitorios para mayor intimidad (cuidado niños) , luces de emergencia y señalización de salidas de emergencia homologadas por arquitecto ,extintores 
rejas de protección para niños en la escalera,(parte de arriba) y puertas de salida .
Wifi gratuito en toda la casa . La contraseña se encuentra junto al router en el salón de arriba. 
Hay juegos de mesa, ajedrez y diana .

DOS PLANTAS
Orientación : E, S, W.

. ABAJO : aparcamiento para dos coches .En la calle hay aparcamientos gratuitos a quince m. , jardín ,piscina privada desbordante de temporada de 8m por 4m con ducha solar, barbacoa,paellero móvil ,ambos con su menaje apropiado ,cenador , solarium .
Gran mirador al monte, al valle y al río Júcar ,pila de piedra y fuente .
Salón comedor con puerta persiana 32,5 m2 , abierto al jardín ,ventilador de techo, estufa de pared con mando y zona de lavadora.
Cocina . Con vistas al jardín y a la piscina .
Pasillos.
Tres dormitorios dobles con camas de 90cm con vistas a la piscina ,totalmente equipados,
uno de ellos con salida directa al jardín.
Un dormitorio de matrimonio con cama de 150cm con vistas al jardín y a la piscina,vestidor,ventilador de pie , cuna .Cuarto de baño completo con ducha con asiento y bidé.
Cuarto de baño completo con bañera ,ducha ,bidé y bañera para bebés (solicitar) .
Plancha ,secador de pelo .
Escalera con barandilla abatible para  seguridad de los niños en la parte de arriba .

ARRIBA : distribuidor ,salón comedor de 44 m2 con sofá cama de 150 cm , con vistas al jardín ,a la piscina ,al monte y al pueblo ; con gran  terraza de 33 m2 con vistas a todo el espacio ,aseo con ventana ,ESTUDIO PRIVADO con aire acondicionado inverter , que funciona como quinto dormitorio con 2 camas supletorias de 90 cm ,despacho para trabajar, cocina totalmente equipada ,mesa para comer y patio . Puede estar abierto o cerrado ,por ahorro energético .Se abre por petición de los viajeros y supone un coste igual al precio de 2 personas , como si estuviera ocupado .Siempre se abre si vienen 10 viajeros sin coste alguno  No se alquila aislado del resto del alojamiento .
  Recibidor con puerta de reja para seguridad de los niños ,terraza de 16m2 con escalera 
que baja al jardín ,a la piscina y a la calle
 Hay otra cama de 90cm supletoria que se puede ubicar donde los viajeros elijan y supone un coste igual al precio por persona y noche .
En el salón hay una chimenea que de momento no se puede encender hasta que no tenga puerta . No se puede cocinar ni asar en la chimenea .
Se informará a los viajeros cuando esté en uso.
La casa tiene dos entradas.

SITUADA en la montaña  que acoge el valle del río Júcar ,a diez minutos andando de la plaza . A 25 km de Albacete.
En la casa pueden entrar servicios sanitarios siempre que en el aparcamiento del alojamiento no haya coches.
En la casa encontrarán información sobre el pueblo ,actividades ,tiendas , urgencias , todo tipo de servicios : nºs de  teléfono ,pedidos , ocio,restaurantes ,pubs ,servicio de taxi,horarios de autobús , servicio de limpieza . Tendrán folletos y enlaces . 

VILLA MILA DE VALDEGANGA  solo ofrece alojamiento, información , atención y  ayuda las 24 h.
El resto de los servicios correrán a cargo de los viajeros .

Si detectan alguna avería o sucede algún imprevisto contactar cuanto antes con el propietario . El propietario intentará solucionar el problema en el mínimo tiempo posible y llamar a los servicios técnicos correspondientes, que valorarán la avería  e intentarán solucionar.
Los viajeros eximen de toda responsabilidad  a la propiedad de las averías y fallos en los suministros que se pudieran originar por causas ajenas a esta y que fueran solo imputables a las empresas suministradoras o a fenómenos atmosféricos .
El propietario no se hace responsable de los accidentes que pudieran sufrir los viajeros.
En la casa existen hojas de reclamaciones.
A la salida de los viajeros el propietario revisa el alojamiento y el inventario de los enseres del mismo ,haciendo fotos si encuentra desperfectos o daños .

Los viajeros declaran conocer el estado de conservación y las características del alojamiento y se obligan a conservarlo en perfecto estado durante el plazo de alquiler libremente pactado entre ambas partes.

Ambas partes se someten expresamente a los tribunales competentes de Albacete para solucionar cualquier desavenencia o disputa que surgiera derivada de este contrato.
Y para que conste y surta los efectos oportunos firman los comparecientes el presente contrato que consta de cinco folios a dos caras firmados en duplicado por las dos partes 
en todos y cada uno de los folios.

NOMBRE COMPLETO Y FIRMA REPRESENTANTE Y RESPONSABLE DE LOS VIAJEROS




FIRMA DEL PROPIETARIO O REPRESENTANTE.




El propietario se reserva el derecho de modificar este contrato por cambios legales ,mejoras u otros asuntos que pudieran surgir ,antes de ser firmado y para otras contrataciones .
Este alojamiento cumple con la normativa vigente sobre la confidencialidad y privacidad de los datos personales de todos y cada uno de los viajeros .

REFERENCIA CATASTRAL  4732001XJ1343S0001YF
CÓDIGO SEGURO DE VERIFICACIÓN MUNICIPAL  D4AA ACQC 34Y7 CHDH H43M 
Nº DE REGISTRO DE EMPRESAS Y ESTABLECIMIENTOS TURÍSTICOS DE CLM  
            02012120657   Casa Rural  tres estrellas verdes .
NRA Nº DE REGISTRO ÚNICO DE ARRENDAMIENTO OFICIAL 
            ESFCTU0000020040007853470000000000000TURO1-02050 / 18095
Nº DE LICENCIA CCAA TURO1 - 0250 / 1809 .


FORMA DE PAGO 
                    Efectivo
                   Transferencia bancaria inmediata  / banca online .
Se paga a la llegada y se entregan las llaves .







.
CONTRACT,
];
