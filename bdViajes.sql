CREATE DATABASE bdviajes; 

CREATE TABLE empresa(
    idempresa bigint AUTO_INCREMENT,
    enombre varchar(150),
    edireccion varchar(150),
    PRIMARY KEY (idempresa)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE responsable (
    rnumeroempleado bigint AUTO_INCREMENT,
    rnumerolicencia bigint,
	rnombre varchar(150), 
    rapellido  varchar(150), 
    PRIMARY KEY (rnumeroempleado)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;;
	
CREATE TABLE viaje (
    idviaje bigint AUTO_INCREMENT,
	vdestino varchar(150),
    vcantmaxpasajeros int,
	idempresa bigint,
    rnumeroempleado bigint,
    vimporte float,
    tipoAsiento varchar(150), /* semicama o cama */
    idayvuelta varchar(150), /* si no */
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa (idempresa),
	FOREIGN KEY (rnumeroempleado) REFERENCES responsable (rnumeroempleado)
    ON UPDATE CASCADE
    ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;
	
CREATE TABLE pasajero (
    rdocumento varchar(15),
    pnombre varchar(150), 
    papellido varchar(150), 
	ptelefono int, 
	idviaje bigint,
    PRIMARY KEY (rdocumento),
	FOREIGN KEY (idviaje) REFERENCES viaje (idviaje)	
    )ENGINE=InnoDB DEFAULT CHARSET=utf8; 

/* Datos para probar */
 
INSERT INTO empresa(enombre, edireccion)
VALUES ('Via Bariloche', 'Teodoro Planas 2900');

INSERT INTO responsable(rnumerolicencia, rnombre, rapellido)
VALUES (43152353, 'Pedro', 'Garcia'),
(42145235, 'Enrique', 'Gomez'),
(45423523, 'Lautaro', 'Mostoles'),
(41235353, 'Bruno', 'Estrada'),
(40253523, 'Francisco', 'Fuentes');

INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta)
VALUES ('ushuaia', 10, 1, 1, 50000, 'cama', 'no'),
('bariloche', 20, 1, 2, 10200, 'semicama', 'no'),
('posadas', 5, 1, 3, 30000, 'cama', 'si'),
('buenos aires', 4, 1, 4, 20000, 'semicama', 'si'),
('mendoza', 6, 1, 5, 15000, 'cama', 'si');

INSERT INTO pasajero(rdocumento, pnombre, papellido, ptelefono, idviaje)
VALUES (93253534, 'Isaac', 'Esparza', 4124345, 1),
(45235385, 'Manuela', 'Montalvo', 8725663, 1),
(95125353, 'Abigail', 'Torrez', 4804064, 1),
(43253523, 'Ximena', 'Gurule', 9448043, 1),
(41245235, 'Luciana', 'Betancourt', 5555057, 2),
(95425353, 'Andres', 'Verduzco', 8587715, 2),
(45235353, 'Valeria', 'Romo', 4954011, 2),
(40424535, 'Luis', 'Longoria', 0283625, 3),
(93412543, 'Abigail', 'Huerta', 4785764, 3),
(43123535, 'Francisco', 'Calderon', 0205195, 3),
(41241243, 'Lorenzo', 'Yanez', 4464108, 3),
(92412453, 'Christopher', 'Toro', 5593414, 4),
(44523535, 'Lautaro', 'Portillo', 5441046, 4),
(41235523, 'Constanza', 'Valdez', 8483710, 4),
(94241244, 'Juan Jose', 'Sanchez', 5497404, 4);


