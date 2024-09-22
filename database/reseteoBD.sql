-- Deshabilitar las restricciones de claves foráneas temporalmente
SET FOREIGN_KEY_CHECKS = 0;

-- Limpiar datos de tablas transaccionales

TRUNCATE TABLE compra;
TRUNCATE TABLE detalle_compra;
TRUNCATE TABLE detalle_venta;
TRUNCATE TABLE efectivocaja;
TRUNCATE TABLE entrada_salida;
TRUNCATE TABLE movimiento_producto;
TRUNCATE TABLE venta;

-- Eliminar datos de la tabla usuario excepto el administrador
DELETE FROM usuario WHERE rol_id != 1;

-- Limpiar otros datos que no sean parametrizados
TRUNCATE TABLE cliente;
TRUNCATE TABLE proveedor;
TRUNCATE TABLE producto;

-- Habilitar las restricciones de claves foráneas
SET FOREIGN_KEY_CHECKS = 1;
