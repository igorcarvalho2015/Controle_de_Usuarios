
CREATE DATABASE TCC;
USE TCC;

CREATE TABLE Cargo (
		idCargo			INT 			NOT NULL auto_increment,
		strCargo		VARCHAR(30)		NOT NULL,
		strDescricao	VARCHAR(60)		NULL,
		MinSal			DECIMAL	(8,2)	NOT NULL,
		MaxSal			DECIMAL	(8,2)	NOT NULL,
		PRIMARY KEY (idCargo));

CREATE TABLE Funcionario (
	idFuncionario	INT 		        NOT NULL auto_increment,
	strNome 		VARCHAR(50)         NOT NULL,
	Matricula		INT(4) unsigned zerofill NOT NULL UNIQUE,
	CPF				BIGINT 		        NOT NULL UNIQUE,
	dtNascimento	DATE		        NOT NULL,
	Salario			DECIMAL(8,2)		NOT NULL,
	btAtivo			INT			        NOT NULL DEFAULT 1,
	id_Cargo		INT                 NOT NULL,
	id_Gerente		INT,
	PRIMARY KEY (idFuncionario),
	CONSTRAINT cargo_pk FOREIGN KEY (id_Cargo) REFERENCES Cargo (idCargo))ENGINE=InnoDB; 
	
ALTER TABLE Funcionario ADD CONSTRAINT valida_Ano CHECK (YEAR(dtNascimento) > 1900);
ALTER TABLE Funcionario ADD CONSTRAINT gerente_pk FOREIGN KEY (id_Gerente) REFERENCES Funcionario (idFuncionario);

CREATE TABLE Usuario (
	idUsuario		INT			    NOT NULL auto_increment,
	strLogin		VARCHAR(100)	NOT NULL UNIQUE,
	strSenha		VARCHAR(100)	NOT NULL,
	btAtivo			INT 		    NOT NULL,
	id_Func			INT             NOT NULL,
	PRIMARY KEY (idUsuario),
	CONSTRAINT func_pk FOREIGN KEY (id_Func) REFERENCES Funcionario (idFuncionario) ON DELETE RESTRICT ON UPDATE RESTRICT
	)ENGINE = InnoDB DEFAULT CHARACTER SET = latin1 COLLATE = latin1_swedish_ci;
	
CREATE TABLE Logs (
	idLog			INT 		NOT NULL auto_increment,
	acao 			VARCHAR(50) NOT NULL,
	dtacao			TIMESTAMP 	NOT NULL DEFAULT CURRENT_TIMESTAMP,
	id_Usuario		INT,
    PRIMARY KEY (idLog),
	FOREIGN KEY (id_Usuario) REFERENCES Usuario (idUsuario) ON DELETE SET NULL);
	
	-- ****************** PROCEDURES, VIEWS E TRIGGERS ******************

	
-- *** TRIGGER ***	
delimiter $$
CREATE TRIGGER tgAltStatus AFTER UPDATE ON Funcionario FOR EACH ROW
BEGIN
UPDATE usuario SET btAtivo = 0 WHERE id_Func IN (SELECT idFuncionario FROM funcionario WHERE btAtivo = 0);
END$$
DELIMITER ;


--  *** PROCEDURE ***

DELIMITER $$
CREATE PROCEDURE User_in (Login VARCHAR(50), Senha VARCHAR(50),Func INT)
BEGIN
INSERT INTO Usuario VALUES (NULL,AES_ENCRYPT(LOGIN,'UNICARIOCA'),MD5(SENHA),1,Func);
END$$
DELIMITER ;

-- *** VIEWS ***
CREATE VIEW RelEmpregAtivo AS
SELECT F.Matricula, F.strNome, C.strCargo FROM Funcionario F INNER JOIN Cargo C ON f.id_Cargo = C.idCargo
WHERE F.btAtivo = 1
ORDER BY F.Matricula;

CREATE VIEW RelAcoeUser AS
SELECT U.idUsuario, COALESCE(AES_DECRYPT(U.strLogin,'UNICARIOCA'), 'EXCLUIDO') AS strLogin, L.acao, L.dtacao FROM Logs L LEFT JOIN Usuario U  ON U.idUsuario = L.id_Usuario
ORDER BY U.idUsuario, L.dtacao DESC;

-- *** FUNCTION ***

DELIMITER $$
CREATE  FUNCTION valida_Sal(ID INT, SAL DECIMAL) RETURNS varchar(60) CHARSET utf8
BEGIN

SET @minS = (SELECT minsal FROM FUNCIONARIO F INNER JOIN CARGO C ON  F.id_Cargo = C.idCargo WHERE F.IDFUNCIONARIO = ID);
SET @maxS =(SELECT maxsal FROM FUNCIONARIO F INNER JOIN CARGO C ON  F.id_Cargo = C.idCargo WHERE F.IDFUNCIONARIO = ID);

if SAL >= @minS and SAL <= @maxS THEN
UPDATE FUNCIONARIO SET Salario= SAL where idFuncionario = ID;
SET @msg = (select "MUDANÇA DE SALARIO EFETUADA COM SUCESSO");
else
SET @msg = (SELECT "ERRO AO ALTERAR SALARIO");
end if;

RETURN @Msg;
END$$

DELIMITER ;


INSERT INTO cargo VALUES(1, 'ADM', 'ADMINISTRADOR', 1.00, 2.00);
INSERT INTO funcionario VALUES(1, 'TCC', '0001', 11111111111, '2000-01-01', 5.000, 1, 1, 1);
call User_in('tcc','tcc',1);