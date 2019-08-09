create table evento
(
  evento_id        int auto_increment
    primary key,
  nome             varchar(255) null,
  data_inicio      date         null,
  data_termino     date         null,
  descricao        text         null,
  data_prorrogacao date         null,
  evento_inicio    date         null,
  evento_termino   date         null,
  inativo          int          null
)
  engine = InnoDB
  charset = utf8mb4;

create table atividade
(
  atividade_id     int auto_increment
    primary key,
  evento_id        int          not null,
  titulo           varchar(255) null,
  responsavel      varchar(255) null,
  carga_horaria    int(3)       null,
  datahora_inicio  datetime     null,
  datahora_termino datetime     null,
  local            varchar(255) null,
  quantidade_vaga  int(4)       null,
  tipo             varchar(255) null,
  inativo          int          null,
  constraint atividade_ibfk_1
  foreign key (evento_id) references evento (evento_id)
    on update cascade
    on delete cascade
)
  engine = InnoDB
  charset = utf8mb4;

create index evento_id
  on atividade (evento_id);

create table usuario
(
  usuario_id      int auto_increment
    primary key,
  nome            varchar(255)       not null,
  cpf             varchar(14)        not null,
  senha           varchar(100)       not null,
  email           varchar(255)       not null,
  data_nascimento date               not null,
  telefone        varchar(20)        not null,
  endereco        varchar(255)       not null,
  bairro          varchar(100)       not null,
  estado          varchar(2)         not null,
  cidade          varchar(100)       not null,
  cep             varchar(10)        not null,
  nacionalidade   varchar(50)        not null,
  ocupacao        varchar(100)       not null,
  admin           int(1) default '0' not null
)
  engine = InnoDB
  charset = utf8mb4;

create table presenca
(
  atividade_id int    not null,
  usuario_id   int    not null,
  presenca     int(1) null,
  primary key (atividade_id, usuario_id),
  constraint presenca_ibfk_1
  foreign key (atividade_id) references atividade (atividade_id)
    on update cascade,
  constraint presenca_ibfk_2
  foreign key (usuario_id) references usuario (usuario_id)
    on update cascade
)
  engine = InnoDB
  charset = utf8mb4;

create index presenca_ibfk_2
  on presenca (usuario_id);
