-- Enriquecimiento de datos de Apex Gestoria (más realismo).
-- Idempotente: rehace clientes, nominas y facturas. NO toca usuarios (credenciales auditoría).
USE apexgestoria;

DELETE FROM clientes;
INSERT INTO clientes (nombre,nif,email,iban) VALUES
 ('Construccions Vidal SL','B12345678','info@vidalsl.cat','ES7620770024003102575766'),
 ('Perruqueria Anna','X1234567L','anna@perruqueria.cat','ES9121000418450200051332'),
 ('Tallers Puig SCP','J87654321','admin@tallerspuig.cat','ES7100302053091234567895'),
 ('Forn de Pa Sant Jordi SL','B61234567','gerencia@fornsantjordi.cat','ES2100302053091234567811'),
 ('Clinica Dental Somriu','B62345678','recepcio@somriu.cat','ES1500491800052710608901'),
 ('Transports Riera i Fills SL','B63456789','logistica@transportsriera.cat','ES6020802018413456789012'),
 ('Floristeria Camelia','39998877K','hola@floristeriacamelia.cat','ES3201822000123456789015'),
 ('Bar Restaurant El Racó','J64567890','reserves@elraco.cat','ES8800810123456789012345'),
 ('Assessoria Informatica Bitlab SL','B65678901','admin@bitlab.cat','ES4321000418401234567890'),
 ('Immobiliaria Mediterrani','B66789012','info@immomediterrani.cat','ES7600491500051234567892'),
 ('Centre de Fisioterapia Activa','40112233M','cita@fisioactiva.cat','ES9530350010987654321098'),
 ('Electricitat Giralt SL','B67890123','pressupostos@electrigiralt.cat','ES1020385001012345678903');

DELETE FROM nominas;
INSERT INTO nominas (empleado,dni,salario_bruto,iban) VALUES
 ('Joan Morales Pi','39123456A',2450.00,'ES1000492352082414205416'),
 ('Marta Soler Vidal','39654321B',2780.50,'ES8021000813610123456789'),
 ('Carles Roca Ferrer','40221144C',2150.00,'ES4420803045901234567812'),
 ('Nuria Camps Oller','40332255D',1980.75,'ES2900491800052710600001'),
 ('David Ruiz Mena','40443366E',3120.00,'ES7700810123450098761234'),
 ('Laia Bosch Pons','40554477F',2330.40,'ES1300751234560012345678'),
 ('Sergi Marti Vila','40665588G',2010.00,'ES6121000418999988887777'),
 ('Aina Torres Sala','40776699H',2890.20,'ES5000493526112233445566');

DROP TABLE IF EXISTS facturas;
CREATE TABLE facturas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  numero VARCHAR(20), cliente VARCHAR(100), fecha DATE,
  base DECIMAL(10,2), iva DECIMAL(10,2), total DECIMAL(10,2), estado VARCHAR(20)
);
INSERT INTO facturas (numero,cliente,fecha,base,iva,total,estado) VALUES
 ('F2026-001','Construccions Vidal SL','2026-01-12',1200.00,252.00,1452.00,'Pagada'),
 ('F2026-002','Tallers Puig SCP','2026-01-18',450.00,94.50,544.50,'Pagada'),
 ('F2026-003','Clinica Dental Somriu','2026-01-25',900.00,189.00,1089.00,'Pendent'),
 ('F2026-004','Forn de Pa Sant Jordi SL','2026-02-03',300.00,63.00,363.00,'Pagada'),
 ('F2026-005','Transports Riera i Fills SL','2026-02-10',1850.00,388.50,2238.50,'Pendent'),
 ('F2026-006','Bar Restaurant El Raco','2026-02-15',520.00,109.20,629.20,'Pagada'),
 ('F2026-007','Immobiliaria Mediterrani','2026-02-22',2400.00,504.00,2904.00,'Vençuda'),
 ('F2026-008','Floristeria Camelia','2026-03-01',180.00,37.80,217.80,'Pagada'),
 ('F2026-009','Assessoria Informatica Bitlab SL','2026-03-08',1100.00,231.00,1331.00,'Pendent'),
 ('F2026-010','Electricitat Giralt SL','2026-03-14',760.00,159.60,919.60,'Pagada'),
 ('F2026-011','Centre de Fisioterapia Activa','2026-03-20',640.00,134.40,774.40,'Pendent'),
 ('F2026-012','Immobiliaria Mediterrani','2026-03-27',2400.00,504.00,2904.00,'Pagada');
