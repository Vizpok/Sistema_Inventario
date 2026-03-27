USE BASE_PRUEBA
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE OR ALTER PROCEDURE [dbo].[PA_LOGIN] (@USUARIO VARCHAR(100), @CONTRASENA VARCHAR(100), @TOKEN VARCHAR(64) OUT) AS
BEGIN
    SET NOCOUNT ON
    DECLARE @IDUSUARIO INT = 0, @CONTRASENA_SISTEMA VARCHAR(100), @INTENTOS INT = 0, @ESTATUS BIT = 0
    SELECT @USUARIO=ISNULL(@USUARIO,''), @CONTRASENA=ISNULL(@CONTRASENA,'')

    IF '' IN(@USUARIO, @CONTRASENA)
    BEGIN
        SELECT 'Faltan datos' AS MSG, 200 AS RESULT
    END
    ELSE
    BEGIN
        IF NOT EXISTS(SELECT TOP 1 1 FROM USUARIOS WITH(NOLOCK) WHERE @USUARIO IN(USUARIO, CORREO))
        BEGIN
            SELECT 'Usuario no encontrado' AS MSG, 404 AS RESULT
        END
        ELSE
        BEGIN
            SET @CONTRASENA=HASHBYTES('SHA2_256', CONVERT(VARBINARY(256), ISNULL(@CONTRASENA, '')))
            
            SELECT 
                @IDUSUARIO = IDUSUARIO, 
                @CONTRASENA_SISTEMA = CONTRASENA, 
                @INTENTOS = INTENTOS, 
                @ESTATUS = ESTATUS 
            FROM USUARIOS WITH(NOLOCK) 
            WHERE @USUARIO IN(USUARIO, CORREO)

            IF @ESTATUS = 0
            BEGIN
                SELECT 'Inactivo' AS MSG, 200 AS RESULT
            END
            ELSE
            BEGIN
                IF @CONTRASENA != @CONTRASENA_SISTEMA
                BEGIN
                    UPDATE USUARIOS
                    SET
                        INTENTOS = @INTENTOS + 1,
                        ESTATUS = IIF(@INTENTOS = 4, 0, ESTATUS)
                    WHERE IDUSUARIO = @IDUSUARIO
                    
                    SELECT 'Contraseña incorrecta' AS MSG, 200 AS RESULT
                END
                ELSE
                BEGIN
                    SET @TOKEN = CONVERT(VARCHAR(64), NEWID())

                    UPDATE USUARIOS SET INTENTOS = 0 WHERE IDUSUARIO = @IDUSUARIO

                    INSERT INTO SESIONES(TKSESION, IDUSUARIO)
                    VALUES(@TOKEN, @IDUSUARIO)

                    SELECT 'OK' AS MSG, 200 AS RESULT, @TOKEN AS TOKEN
                END
            END
        END
    END
    SET NOCOUNT OFF
END
GO
