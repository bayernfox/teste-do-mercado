CREATE DATABASE IF NOT EXISTS db_valemobi;
USE db_valemobi;

CREATE TABLE IF NOT EXISTS tb_tipo_mercadoria (
	id_tipo_mercadoria INT AUTO_INCREMENT NOT NULL,
    nm_tipo_mercadoria VARCHAR(64) NOT NULL,
    PRIMARY KEY (id_tipo_mercadoria)
);

INSERT INTO tb_tipo_mercadoria (nm_tipo_mercadoria) VALUES
	 ("Automotivo")
	,("Bebês")
	,("Brinquedos")
	,("Cama, Mesa e Banho")
	,("Câmeras e Filmadoras")
	,("Celulares e Telefones")
	,("Decoração")
	,("Eletrodomésticos")
	,("Esporte e Lazer")
	,("Ferramentas")
	,("Games")
	,("Informática")
	,("Livros")
	,("Malas")
	,("Moda")
	,("Móveis")
	,("Portáteis")
	,("Relógios")
	,("Saúde e Beleza")
	,("Tablets")
	,("TVs e Acessórios")
	,("Utilidades Domésticas")
;

CREATE TABLE IF NOT EXISTS tb_mercadoria (
	id_mercadoria INT AUTO_INCREMENT NOT NULL,
    id_tipo_mercadoria INT NOT NULL,
    nm_mercadoria VARCHAR(64) NOT NULL,
    quantidade SMALLINT NOT NULL,
    preco REAL NOT NULL,
    PRIMARY KEY (id_mercadoria),
    FOREIGN KEY (id_tipo_mercadoria) REFERENCES tb_tipo_mercadoria (id_tipo_mercadoria)
);

CREATE TABLE IF NOT EXISTS tb_negociacao (
	id_negociacao INT AUTO_INCREMENT NOT NULL,
    id_mercadoria INT NOT NULL,
    quantidade INT NOT NULL,
    vl_total REAL NOT NULL,
    PRIMARY KEY (id_negociacao),
    FOREIGN KEY (id_mercadoria) REFERENCES tb_mercadoria (id_mercadoria)
);
