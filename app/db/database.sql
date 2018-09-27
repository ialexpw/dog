CREATE TABLE records (
	id integer PRIMARY KEY AUTOINCREMENT,
	response varchar,
	timestamp varchar,
	m_id varchar
);

CREATE TABLE users (
	id integer PRIMARY KEY AUTOINCREMENT,
	username varchar,
	password varchar,
	nti_email varchar,
	nti_phone varchar,
	active integer
);

CREATE TABLE monitors (
	id integer PRIMARY KEY AUTOINCREMENT,
	name varchar,
	description varchar,
	address varchar,
	location varchar,
	public integer,
	u_id integer
);

CREATE TABLE external (
	id integer PRIMARY KEY AUTOINCREMENT,
	code varchar,
	loc varchar,
	addr varchar,
	active integer
);