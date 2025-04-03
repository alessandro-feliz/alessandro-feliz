-- Database script

DROP TABLE IF EXISTS nao_transporte;
DROP TABLE IF EXISTS escala;
DROP TABLE IF EXISTS protocolo;
DROP TABLE IF EXISTS circulacao;
DROP TABLE IF EXISTS ventilacao;
DROP TABLE IF EXISTS rcp;
DROP TABLE IF EXISTS vitima_lesao;
DROP TABLE IF EXISTS lesao;
DROP TABLE IF EXISTS vitima_sintomas;
DROP TABLE IF EXISTS chamu;
DROP TABLE IF EXISTS avaliacao;
DROP TABLE IF EXISTS sintoma;
DROP TABLE IF EXISTS classe_sintoma;
DROP TABLE IF EXISTS vitima;
DROP TABLE IF EXISTS sexo;
DROP TABLE IF EXISTS ocorrencia;
DROP TABLE IF EXISTS estado_ocorrencia;
DROP TABLE IF EXISTS motivo_rejeicao;
DROP TABLE IF EXISTS verbete;
DROP TABLE IF EXISTS tipo_de_local;
DROP TABLE IF EXISTS utilizador;
DROP TABLE IF EXISTS perfil;
DROP TABLE IF EXISTS concelho;
DROP TABLE IF EXISTS distrito;

CREATE TABLE distrito (
   id				INT				NOT NULL,
   nome				VARCHAR(256) 	NOT NULL UNIQUE,
   PRIMARY KEY (id)
);

CREATE TABLE concelho (
   id				INT				NOT NULL,
   id_distrito 		INT				NOT NULL,
   nome				VARCHAR(256) 	NOT NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_distrito) 		REFERENCES distrito (id)
) ;

CREATE TABLE perfil (
   id 					INT 			NOT NULL AUTO_INCREMENT,
   nome					VARCHAR(256) 	NOT NULL,
   PRIMARY KEY (id)
);

INSERT INTO perfil VALUES (10, 'Administrador');
INSERT INTO perfil VALUES (20, 'Tripulante');
INSERT INTO perfil VALUES (30, 'Médico');

CREATE TABLE utilizador (
   id					INT 			NOT NULL AUTO_INCREMENT,
   id_perfil			INT 			NOT NULL,
   username 			VARCHAR(256) 	NOT NULL UNIQUE,
   password 			VARCHAR(256) 	NOT NULL,
   email 				VARCHAR(256) 	NOT NULL UNIQUE,
   nome 				VARCHAR(256) 	NOT NULL,
   codigo				VARCHAR(256) 	NOT NULL,
   ativo 				BOOLEAN			NOT NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_perfil) 			REFERENCES perfil (id)
);

INSERT INTO utilizador VALUES (NULL, 10, 'admin', 'admin', 'admin@sata.pt', 'Admin', 'admin', TRUE);
INSERT INTO utilizador VALUES (NULL, 20, 'tripulante1', 'tripulante1', 'tripulante1@sata.pt', 'Manuel Silva',		'1221000', TRUE);
INSERT INTO utilizador VALUES (NULL, 20, 'tripulante2', 'tripulante2', 'tripulante2@sata.pt', 'António Rodrigues', 	'1221001', TRUE);
INSERT INTO utilizador VALUES (NULL, 20, 'tripulante3', 'tripulante3', 'tripulante3@sata.pt', 'José Fernandes', 	'1221002', TRUE);
INSERT INTO utilizador VALUES (NULL, 20, 'tripulante4', 'tripulante4', 'tripulante4@sata.pt', 'João Gonçalves', 	'1221003', TRUE);
INSERT INTO utilizador VALUES (NULL, 20, 'tripulante5', 'tripulante5', 'tripulante5@sata.pt', 'Francisco Santos', 	'1221004', TRUE);
INSERT INTO utilizador VALUES (NULL, 20, 'tripulante6', 'tripulante6', 'tripulante6@sata.pt', 'Joaquim Pereira', 	'1221005', TRUE);
INSERT INTO utilizador VALUES (NULL, 20, 'tripulante7', 'tripulante7', 'tripulante7@sata.pt', 'Domingos Costa', 	'1221006', TRUE);
INSERT INTO utilizador VALUES (NULL, 20, 'tripulante8', 'tripulante8', 'tripulante8@sata.pt', 'Pedro Ferreira', 	'1221007', TRUE);
INSERT INTO utilizador VALUES (NULL, 20, 'tripulante9', 'tripulante9', 'tripulante9@sata.pt', 'Luís Gomes', 		'1221008', TRUE);
INSERT INTO utilizador VALUES (NULL, 30, 'medico1', 	'medico1', 	   'medico1@sata.pt', 	  'Carlos Martins', 	'1221009', TRUE);
INSERT INTO utilizador VALUES (NULL, 30, 'medico2', 	'medico2', 	   'medico2@sata.pt', 	  'Maria Sousa', 		'1221010', TRUE);
INSERT INTO utilizador VALUES (NULL, 30, 'medico3', 	'medico3', 	   'medico3@sata.pt', 	  'Ana Dias', 			'1221011', TRUE);
INSERT INTO utilizador VALUES (NULL, 30, 'medico4', 	'medico4', 	   'medico4@sata.pt', 	  'Isabel Oliveira', 	'1221012', TRUE);
INSERT INTO utilizador VALUES (NULL, 30, 'medico5', 	'medico5', 	   'medico5@sata.pt', 	  'Catarina Lopes', 	'1221013', TRUE);
INSERT INTO utilizador VALUES (NULL, 30, 'medico6', 	'medico6', 	   'medico6@sata.pt', 	  'Antónia Freitas', 	'1221014', TRUE);
INSERT INTO utilizador VALUES (NULL, 30, 'medico7', 	'medico7', 	   'medico7@sata.pt', 	  'Joana Francisco', 	'1221015', TRUE);
INSERT INTO utilizador VALUES (NULL, 30, 'medico8', 	'medico8', 	   'medico8@sata.pt', 	  'Francisca Nunes', 	'1221016', TRUE);
INSERT INTO utilizador VALUES (NULL, 30, 'medico9', 	'medico9', 	   'medico9@sata.pt', 	  'Rosa Ribeiro', 		'1221017', TRUE);

create table tipo_de_local(
   id 					INT 			NOT NULL,
   nome					VARCHAR(64)		NOT NULL,
   PRIMARY KEY (id)
);

INSERT INTO tipo_de_local VALUES (10, 'Residência');
INSERT INTO tipo_de_local VALUES (20, 'Trabalho');
INSERT INTO tipo_de_local VALUES (30, 'Via Pública');

CREATE TABLE estado_ocorrencia (
   id 					INT				NOT NULL,
   name					VARCHAR(64) 	NOT NULL UNIQUE,
   PRIMARY KEY (id)
);

INSERT INTO estado_ocorrencia VALUES (10, 'Pendente');
INSERT INTO estado_ocorrencia VALUES (20, 'Aceite');
INSERT INTO estado_ocorrencia VALUES (30, 'Finalizado');
INSERT INTO estado_ocorrencia VALUES (50, 'Rejeitado (ocupado - sem ambulancia)');
INSERT INTO estado_ocorrencia VALUES (60, 'Rejeitado (ocupado - sem tripulante)');
INSERT INTO estado_ocorrencia VALUES (70, 'Rejeitado (ignorado)');

create table ocorrencia(
   id 					INT 			NOT NULL AUTO_INCREMENT,
   nr_codu 				VARCHAR(64) 	NOT NULL UNIQUE,
   data 				DATE			NOT NULL,
   id_utilizador		INT 			NULL,
   id_estado_ocorrencia	INT				NOT NULL,
   descricao			VARCHAR(256)	NOT NULL,
   id_tipo_de_local		INT				NOT NULL,
   local				VARCHAR(256)	NOT NULL,
   id_concelho			INT				NULL,
   hora_caminho_local	TIME			NULL,
   hora_chegada_local	TIME			NULL,
   hora_caminho_hosp	TIME			NULL,
   hora_chegada_hosp	TIME			NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_utilizador) 			REFERENCES utilizador (id),
   FOREIGN KEY (id_estado_ocorrencia) 	REFERENCES estado_ocorrencia (id),
   FOREIGN KEY (id_tipo_de_local) 		REFERENCES tipo_de_local (id)
);

create table sexo(
   id 					INT 			NOT NULL,
   nome					VARCHAR(64)		NOT NULL,
   PRIMARY KEY (id)
);

INSERT INTO sexo VALUES (10, 'Masculino');
INSERT INTO sexo VALUES (20, 'Feminino');
INSERT INTO sexo VALUES (30, 'Outro');

create table vitima(
   id 					INT 			NOT NULL AUTO_INCREMENT,
   id_ocorrencia		INT				NOT NULL,
   nome					VARCHAR(256)	NULL,
   data_nascimento 		DATE			NULL,
   id_sexo				INT 			NULL,
   nr_sns				VARCHAR(64)		NULL,
   residencia			VARCHAR(256)	NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_ocorrencia) 			REFERENCES ocorrencia (id),
   FOREIGN KEY (id_sexo) 				REFERENCES sexo (id)
);

create table classe_sintoma(
   id 					INT 			NOT NULL,
   nome					VARCHAR(64)		NOT NULL UNIQUE,
   PRIMARY KEY (id)
);

INSERT INTO classe_sintoma VALUES (1, '');
INSERT INTO classe_sintoma VALUES (10, 'AVDS');
INSERT INTO classe_sintoma VALUES (20, 'ECG');
INSERT INTO classe_sintoma VALUES (30, 'Pele');
INSERT INTO classe_sintoma VALUES (40, 'Pupilas - diâmetro');
INSERT INTO classe_sintoma VALUES (50, 'Pupilas - reflexos');
INSERT INTO classe_sintoma VALUES (60, 'Pupilas - simetria');

create table sintoma(
   id 					INT 			NOT NULL,
   id_classe_sintoma	INT				NOT NULL,
   nome					VARCHAR(64)		NOT NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_classe_sintoma) 	REFERENCES classe_sintoma (id)
);

INSERT INTO sintoma VALUES (1, 1,  '');
-- Sintomas AVDS
INSERT INTO sintoma VALUES (100, 10,  'A');
INSERT INTO sintoma VALUES (101, 10,  'V');
INSERT INTO sintoma VALUES (102, 10,  'D');
INSERT INTO sintoma VALUES (103, 10,  'S');
-- Sintomas ECG
INSERT INTO sintoma VALUES (200, 20, 'ASS');
INSERT INTO sintoma VALUES (201, 20, 'AV1');
INSERT INTO sintoma VALUES (202, 20, 'AV2');
INSERT INTO sintoma VALUES (203, 20, 'AV3');
INSERT INTO sintoma VALUES (204, 20, 'BRD');
INSERT INTO sintoma VALUES (205, 20, 'BRE');
INSERT INTO sintoma VALUES (206, 20, 'ESV');
INSERT INTO sintoma VALUES (207, 20, 'EV');
INSERT INTO sintoma VALUES (208, 20, 'FA');
INSERT INTO sintoma VALUES (209, 20, 'FLA');
INSERT INTO sintoma VALUES (210, 20, 'FV');
INSERT INTO sintoma VALUES (211, 20, 'IST');
INSERT INTO sintoma VALUES (212, 20, 'RJ');
INSERT INTO sintoma VALUES (213, 20, 'RI');
INSERT INTO sintoma VALUES (214, 20, 'RS');
INSERT INTO sintoma VALUES (215, 20, 'SST');
INSERT INTO sintoma VALUES (216, 20, 'TSV');
INSERT INTO sintoma VALUES (217, 20, 'TV');
-- Sintomas Pele
INSERT INTO sintoma VALUES (300, 30, 'Normal');
INSERT INTO sintoma VALUES (301, 30, 'Pele pálida');
INSERT INTO sintoma VALUES (302, 30, 'Pele marmoreada');
INSERT INTO sintoma VALUES (303, 30, 'Cianose');
INSERT INTO sintoma VALUES (304, 30, 'Outro');
-- Sintomas Pupilas Diametro
INSERT INTO sintoma VALUES (400, 40, 'Normal');
INSERT INTO sintoma VALUES (401, 40, 'Em miose (contraído)');
INSERT INTO sintoma VALUES (402, 40, 'Em midríase (dilatado)');
-- Sintomas Pupilas Reflexos
INSERT INTO sintoma VALUES (410, 50, 'Conservados');
INSERT INTO sintoma VALUES (411, 50, 'Abolidos');
-- Sintomas Pupilas Reflexos
INSERT INTO sintoma VALUES (420, 60, 'Simetricas (isocóricas)');
INSERT INTO sintoma VALUES (421, 60, 'Assimétricas (anisocóricas)');

create table avaliacao (
   id 					INT 			NOT NULL AUTO_INCREMENT,
   id_ocorrencia		INT				NOT NULL,
   hora					TIME			NOT NULL,
   id_avds				INT				NOT NULL,
   vent_cpm				INT				NOT NULL,
   sat_o2				INT				NULL,
   sup_o2				INT				NULL,
   exp_co2				INT				NULL,
   pulso_bpm			INT				NOT NULL,
   id_ecg				INT				NULL,
   pa_sist				INT				NOT NULL,
   pa_diast				INT				NOT NULL,
   id_pele				INT				NOT NULL,
   temp					INT				NOT NULL,
   id_pupilas_diametro	INT				NOT NULL,
   id_pupilas_reflexos	INT				NOT NULL,
   id_pupilas_simetria	INT				NOT NULL,
   dor					INT				NULL,
   glicemia				INT				NOT NULL,
   news					INT				NOT NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_ocorrencia) 			REFERENCES ocorrencia (id),
   FOREIGN KEY (id_avds) 				REFERENCES sintoma (id),
   FOREIGN KEY (id_ecg) 				REFERENCES sintoma (id),
   FOREIGN KEY (id_pele) 				REFERENCES sintoma (id),
   FOREIGN KEY (id_pupilas_diametro) 	REFERENCES sintoma (id),
   FOREIGN KEY (id_pupilas_reflexos) 	REFERENCES sintoma (id),
   FOREIGN KEY (id_pupilas_simetria) 	REFERENCES sintoma (id)
);

create table chamu(
   id 					INT 			NOT NULL AUTO_INCREMENT,
   id_ocorrencia		INT				NOT NULL,
   circunstancias		VARCHAR(256)	NOT NULL,
   doencas				VARCHAR(256)	NULL,
   alergias				VARCHAR(256)	NULL,
   medicacao			VARCHAR(256)	NULL,
   hora_ult_ref			TIME			NULL,
   ult_ref				VARCHAR(256)	NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_ocorrencia) 			REFERENCES ocorrencia (id)
);

create table ocorrencia_sintomas(
   id_ocorrencia		INT				NOT NULL,
   id_sintoma			INT				NOT NULL,
   PRIMARY KEY (id_ocorrencia, id_sintoma),
   FOREIGN KEY (id_ocorrencia) 			REFERENCES ocorrencia (id),
   FOREIGN KEY (id_sintoma) 			REFERENCES sintoma (id)
);

create table lesao(
   id 					INT 			NOT NULL,
   simbolo				CHAR(1)			NOT NULL UNIQUE,
   nome					VARCHAR(64)		NOT NULL UNIQUE,
   PRIMARY KEY (id)
);

INSERT INTO lesao VALUES (10,  'F', 'Ferida');
INSERT INTO lesao VALUES (20,  '#', 'Fratura');
INSERT INTO lesao VALUES (30,  'C', 'Contusão');
INSERT INTO lesao VALUES (40,  'D', 'Dor');
INSERT INTO lesao VALUES (50,  'H', 'Hemorregia');
INSERT INTO lesao VALUES (60,  'Q', 'Queimadura');
INSERT INTO lesao VALUES (100, 'O', 'Outro');

create table vitima_lesao(
   id					INT 			NOT NULL AUTO_INCREMENT,
   id_ocorrencia	INT			NOT NULL,
   id_lesao			INT			NULL,
   x					INT			NULL,
   y					INT			NULL,
   l					INT			NULL,
   a					INT			NULL,
   descricao		TEXT			NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_ocorrencia) 		REFERENCES ocorrencia (id),
   FOREIGN KEY (id_lesao) 				REFERENCES lesao (id)
);

create table rcp(
   id 					INT 			NOT NULL,
   id_ocorrencia		INT				NOT NULL,
   presenciada 			BOOLEAN 		NOT NULL,
   sbv					TIME			NULL,
   sav					TIME			NULL,
   prim_ritmo_choque	BOOLEAN 		NULL,
   nr_choques			INT 			NULL,
   recuperado			TIME			NULL,
   suspenso				TIME			NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_ocorrencia) 			REFERENCES ocorrencia (id)
);

create table ventilacao(
   id 					INT 			NOT NULL,
   id_ocorrencia		INT				NOT NULL,
   desobstrucao 		BOOLEAN 		NULL,
   tubo_orofaringeo		BOOLEAN 		NULL,
   tubo_laringeo		BOOLEAN 		NULL,
   tubo_endrotraqueal	BOOLEAN 		NULL,
   masc_laringea		BOOLEAN 		NULL,
   crap					BOOLEAN 		NULL,
   vent_mecanica		BOOLEAN 		NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_ocorrencia) 			REFERENCES ocorrencia (id)
);

create table circulacao(
   id 					INT 			NOT NULL,
   id_ocorrencia		INT				NOT NULL,
   temperatura			BOOLEAN 		NULL,
   hemorregia			BOOLEAN 		NULL,
   penso				BOOLEAN 		NULL,
   torniquete			BOOLEAN 		NULL,
   cinto_pelvico		BOOLEAN 		NULL,
   acesso_venoso		BOOLEAN 		NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_ocorrencia)			REFERENCES ocorrencia (id)
);

create table protocolo(
   id 					INT 			NOT NULL,
   id_ocorrencia		INT				NOT NULL,
   imobilizacao			BOOLEAN 		NULL,
   vv_avc				BOOLEAN 		NULL,
   vv_coronaria			BOOLEAN 		NULL,
   vv_sepsis			BOOLEAN 		NULL,
   vv_trauma			BOOLEAN 		NULL,
   vv_pcr				BOOLEAN 		NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_ocorrencia) 			REFERENCES ocorrencia (id)
);

create table escala(
   id 					INT 			NOT NULL,
   id_ocorrencia		INT				NOT NULL,
   cincinatti			INT 			NULL,
   proacs				INT 			NULL,
   rts					INT 			NULL,
   mgap					INT 			NULL,
   race					INT 			NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_ocorrencia) 			REFERENCES ocorrencia (id)
);

create table nao_transporte(
   id 					INT 			NOT NULL,
   id_ocorrencia		INT				NOT NULL,
   abandonou			BOOLEAN			NULL,
   decisao_medica		BOOLEAN			NULL,
   morte				BOOLEAN			NULL,
   desativacao			BOOLEAN			NULL,
   recusa_proprio		BOOLEAN			NULL,
   recusa_representante	BOOLEAN			NULL,
   recusa_avaliacao		BOOLEAN			NULL,
   recusa_trtamento		BOOLEAN			NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (id_ocorrencia) 			REFERENCES ocorrencia (id)
);
