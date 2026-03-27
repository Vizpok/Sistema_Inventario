USE [Curso]
GO

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

/* =============================================
-Author:        JUAN MORALES OLAN
-Create date: 20260323
-Description:   De forma individual genera una tabla de logs, 
                en la que se irán registrando los sucesos de un sistema, 
                de igual forma, genera un trigger en tu tabla de usuarios 
                (puedes crear una tabla y base nuevas, o puedes usar una ya existente), 
                de modo que al momento de registrar un usuario, se genere un registro 
                en la tabla de logs que indique el cambio que sufrió la base de datos.

-Test:       
=============================================
UPDATE
=============================================
[DATE] [INITIALS] [COMMENTS]
YYYYMMDD NNNN CHANGES WERE MADE
YYYYMMDD NNNN CHANGES WERE MADE
YYYYMMDD NNNN CHANGES WERE MADE
===========================================*/

-- Tabla usuarios
CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY,
    nombre VARCHAR(100),
    correo VARCHAR(100),
    rol VARCHAR(50)
);
GO

-- 1. Query de creación de tabla de Logs
CREATE TABLE LOGS_USUARIOS (
    id_log INT IDENTITY(1,1) PRIMARY KEY,
    usuario_db VARCHAR(50),
    fecha DATETIME,
    accion VARCHAR(50),
    detalle VARCHAR(255)
);
GO

-- 2. Query de creación de trigger
CREATE OR ALTER TRIGGER TR_USUARIOS_LOG
ON usuarios
AFTER INSERT, UPDATE, DELETE
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @usuario_db VARCHAR(50) = SUSER_NAME();
    DECLARE @fecha DATETIME = GETDATE();

    IF EXISTS(SELECT 1 FROM inserted) AND EXISTS(SELECT 1 FROM deleted)
    BEGIN
        INSERT INTO LOGS_USUARIOS (usuario_db, fecha, accion, detalle)
        SELECT 
            @usuario_db, 
            @fecha, 
            'Modificado',
            CONCAT('Se ha modificado un usuario, datos nuevos: [', id_usuario, ', ', nombre, ', ', correo, ', ', rol, ']')
        FROM inserted;
    END

    ELSE IF EXISTS(SELECT 1 FROM inserted)
    BEGIN
        INSERT INTO LOGS_USUARIOS (usuario_db, fecha, accion, detalle)
        SELECT 
            @usuario_db, 
            @fecha, 
            'Registrado',
            CONCAT('Se ha registrado un usuario, datos: [', id_usuario, ', ', nombre, ', ', correo, ', ', rol, ']')
        FROM inserted;
    END

    ELSE IF EXISTS(SELECT 1 FROM deleted)
    BEGIN
        INSERT INTO LOGS_USUARIOS (usuario_db, fecha, accion, detalle)
        SELECT 
            @usuario_db, 
            @fecha, 
            'Eliminado',
            CONCAT('Se ha eliminado un usuario, datos: [', id_usuario, ', ', nombre, ', ', correo, ', ', rol, ']')
        FROM deleted;
    END
END;
GO

-- 3. Query de inserción de usuario
INSERT INTO usuarios (id_usuario, nombre, correo, rol) 
VALUES (1, 'Angel Jesus', 'fletescun@gmail.com', 'Administrador');
GO

UPDATE usuarios 
SET rol = 'Gerente', correo = 'fletescun@gmail.com' 
WHERE id_usuario = 1;
GO

DELETE FROM usuarios 
WHERE id_usuario = 1;
GO

-- 4. Query de consulta de logs
SELECT * FROM LOGS_USUARIOS ORDER BY fecha DESC;
GO