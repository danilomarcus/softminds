-- DATA MODEL FOR SOFTWARE
-- BY DANILO MARCUS
-- EM 03/08/2021

-- CRIACAO DO BANCO DE DADOS
create database if not exists db_soft_minds;
use db_soft_minds;

-- TABELA DE USUARIOS e COLABORADORES
create table if not exists users(
	id int unsigned not null auto_increment,
	created_at datetime default CURRENT_TIMESTAMP,
	updated_at datetime NULL ON UPDATE CURRENT_TIMESTAMP,
	name varchar(255),
	email varchar(255) not null, -- unique field
	phone varchar(255) not null,
	password varchar(255) not null,
	document varchar(255) not null, -- CPF or CNPJ
	address_street_01 varchar(255),
	address_number_01 varchar(255),
	address_city_01 varchar(255),
	address_state_01 varchar(255),
	address_zip_code_01 varchar(255),
	address_street_02 varchar(255),
	address_number_02 varchar(255),
	address_city_02 varchar(255),
	address_state_02 varchar(255),
	address_zip_code_02 varchar(255),	
	forget varchar(255),
	portal_access varchar(255) DEFAULT '0', -- 0= nao   ; 1= sim
	is_supplier varchar(255) DEFAULT '0', -- 0= nao   ; 1= sim
	status_active varchar(255),  -- 0 inativo,1 ativo
	primary key(id),
	CONSTRAINT USER_MAIL_UK UNIQUE(email)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TABELA DE PRODUTOS
create table if not exists products(
	id int unsigned not null auto_increment,
	created_at datetime default CURRENT_TIMESTAMP,
	updated_at datetime NULL ON UPDATE CURRENT_TIMESTAMP,	
	name varchar(255),
	description varchar(255) not null,
	price double(8,2) not null,
	status_active varchar(255),  -- 0 inativo,1 ativo
	primary key(id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TABELA DE PEDIDOS
create table if not exists orders(
	id int unsigned not null auto_increment,
	created_at datetime default CURRENT_TIMESTAMP,
	updated_at datetime NULL ON UPDATE CURRENT_TIMESTAMP,
	deleted_at timestamp NULL DEFAULT NULL,
	id_supplier int unsigned not null, -- id do fornecedor
	id_user int unsigned not null, -- id do usuario que fez pedido
	details varchar(255) null, -- campo para observacoes do pedido
	total_amount double(8,2) null, -- valor total do pedido
	status_order varchar(255) default '0' ,  -- 0=open, 1=closed
	primary key(id),
	CONSTRAINT FK_ORDERS_SUPPLIER_ID foreign key(id_supplier) references users(id),
	CONSTRAINT FK_ORDERS_USER_ID foreign key(id_user) references users(id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TABELA DE PEDIDOS ITENS
create table if not exists orders_itens(
	id int unsigned not null auto_increment,
	created_at datetime default CURRENT_TIMESTAMP,
	updated_at datetime NULL ON UPDATE CURRENT_TIMESTAMP,
	id_order int unsigned not null, 	-- id do pedido
	id_product int unsigned not null, 	-- id do produto	
	price double(8,2) not null,			-- preco do item no pedido
	amount int not null,			-- qtde do produto no pedido
	primary key(id),
	CONSTRAINT FK_ORDERS_ITENS_ORDER_ID foreign key(id_order) references orders(id),
	CONSTRAINT FK_ORDERS_ITENS_PRODUCT_ID foreign key(id_product) references products(id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



