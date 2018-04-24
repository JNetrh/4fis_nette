/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     5.5.2017 18:04:52                            */
/*==============================================================*/


drop table if exists images;

drop table if exists galeryTags;

drop table if exists userrights;

drop table if exists newsTags;

drop table if exists galery;

drop table if exists tags;

drop table if exists jobsRequire;

drop table if exists jobs;

drop table if exists news;

drop table if exists rights;

drop table if exists users;



/*==============================================================*/
/* Table: users                                                 */
/*==============================================================*/
create table users
(
   id                   SERIAL,
   email                varchar(255) not null,
   name                 varchar(255),
   surname              varchar(255),
   password             varchar(255) not null,
   about                text,
   image                varchar(255),
   specialization       varchar(255),
   clubposition         varchar(255),
   member_display       BOOLEAN not null DEFAULT 0,
   primary key (id),
   unique (email)
) ENGINE=InnoDB CHARACTER SET utf8
;


/*==============================================================*/
/* Table: news                                                  */
/*==============================================================*/
create table news
(
   id                   SERIAL,
   caption              varchar(255),
   time                 varchar(255),
   content              TEXT,
   owner                BIGINT UNSIGNED,
   image                varchar(255),
   link                 varchar(255),
   note                 varchar(255),
   created              TIMESTAMP,
   publish              BOOLEAN not null DEFAULT 0,
   primary key (id),
   foreign key (owner) references users (id) on delete set null
) ENGINE=InnoDB CHARACTER SET utf8
;

/*==============================================================*/
/* Table: jobs                                                  */
/*==============================================================*/
create table jobs
(
   id                   SERIAL,
   caption              varchar(255),
   description          text,
   owner                BIGINT UNSIGNED,
   note                 text,
   link                 text,
   image                varchar(255),
   timestamp            TIMESTAMP,
   publish              BOOLEAN not null DEFAULT 0,
   primary key (id),
   foreign key (owner) references users (id) on delete set null
) ENGINE=InnoDB CHARACTER SET utf8
;


/*==============================================================*/
/* Table: rights                                                */
/*==============================================================*/
create table rights
(
   id             SERIAL,
   name           varchar(255),
   constraint unique_rights unique (name),
   primary key (id)
) ENGINE=InnoDB CHARACTER SET utf8
;


/*==============================================================*/
/* Table: userRights                                                */
/*==============================================================*/
create table userrights
(
   userId             BIGINT UNSIGNED not null,
   rightId            BIGINT UNSIGNED not null,
   primary key (userId, rightId),
   constraint FK_commonRights_usr foreign key (userId)
   references users (id) on DELETE CASCADE ,
   constraint FK_commonRights_rig foreign key (rightId)
   references rights (id) ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET utf8
;


/*==============================================================*/
/* Table: galery                                                */
/*==============================================================*/
create table galery
(
   id                   SERIAL,
   caption              varchar(255) not null,
   owner                BIGINT UNSIGNED,
   cover                varchar(255),
   link                 varchar(255),
   time                 varchar(255),
   directory            varchar(255) not null,
   primary key (id),
   foreign key (owner) references news (id) on delete set null
) ENGINE=InnoDB CHARACTER SET utf8
;

/*==============================================================*/
/* Table: images                                                */
/*==============================================================*/
create table images
(
   id                   SERIAL,
   category             varchar(255),
   alt                  varchar(255),
   image                varchar(255) not null,
   owner                BIGINT UNSIGNED,
   primary key (id),
   foreign key (owner) references galery (id) on delete CASCADE
) ENGINE=InnoDB CHARACTER SET utf8
;

/*==============================================================*/
/* Table: tags                                                */
/*==============================================================*/
create table tags
(
   id                   SERIAL,
   name                 varchar(255) not null,
   constraint unique_tags unique (name),
   primary key (id)
) ENGINE=InnoDB CHARACTER SET utf8
;

/*==============================================================*/
/* Table: galeryTags                                                */
/*==============================================================*/
create table galerytags
(
   galeryId             BIGINT UNSIGNED not null,
   tagId                BIGINT UNSIGNED not null,
   primary key (galeryId, tagId)
) ENGINE=InnoDB CHARACTER SET utf8
;

alter table galerytags
add constraint FK_commonTags_gal foreign key (galeryId)
references galery (id) ON DELETE CASCADE;
alter table galerytags
add constraint FK_commonTags_tag foreign key (tagId)
references tags (id) ON DELETE CASCADE;



/*==============================================================*/
/* Table: newsTags                                                */
/*==============================================================*/
create table newstags
(
   newsId               BIGINT UNSIGNED not null,
   tagId                BIGINT UNSIGNED not null,
   primary key (newsId, tagId)
) ENGINE=InnoDB CHARACTER SET utf8
;

alter table newstags
add constraint FK_newsTags_new foreign key (newsId)
references news (id) ON DELETE CASCADE;
alter table newstags
add constraint FK_newsTags_tag foreign key (tagId)
references tags (id) ON DELETE CASCADE;



/*==============================================================*/
/* Table: jobsRequire                                                 */
/*==============================================================*/
create table jobsrequire
(
   id                   SERIAL,
   owner                BIGINT UNSIGNED not null,
   type                 int not null DEFAULT 0,
   text                 text,
   primary key (id)
) ENGINE=InnoDB CHARACTER SET utf8
;
alter table jobsrequire
add constraint FK_jobsrequire foreign key (owner)
references jobs (id) ON DELETE CASCADE;



INSERT INTO `rights` (`id`, `name`) VALUES
  (1, 'admin'),
  (2, 'basic'),
  (3, 'clenove'),
  (4, 'aktuality'),
  (5, 'galerie'),
  (6, 'staze'),
  (7, 'spravaClenu'),
  (8, 'presigned');


INSERT INTO `users`(`email`, `password`) VALUES ("netj01@vse.cz", "$2y$10$4iP5iusxv7MAYDaB92moYuZdhEK.51V4j9mv7pSQbJnjP5NBG4BMa");
INSERT INTO `userrights`(`userId`, `rightId`) VALUES (1,1);
INSERT INTO `users`(`email`, `password`) VALUES ("example@example.cz", "$2y$10$4iP5iusxv7MAYDaB92moYuZdhEK.51V4j9mv7pSQbJnjP5NBG4BMa");
INSERT INTO `userrights`(`userId`, `rightId`) VALUES (2,1);

--
-- INSERT INTO `news`(`caption`, `time`, `content`, `autor`, `odkaz`, `note`, `created`) VALUES ("aktualita 1","2017-09-20 18:30","Máš už plány na 20. září? Zruš je! 4FIS ti totiž nabízí lepší zábavu ve formě beer pong turnaje ve Studentském klubu Celetná. Žádné zápisné, žádné registrace předem. Stačí přijít, zaplatit si pivo a (vy)hrát! První míček zamíří do kelímku už v 18:30. Tak nastuduj pravidla a finty, sežeň si parťáka do týmu a doraž.",null,"https://www.facebook.com/events/1403078973080470/?acontext=%7B%22action_history%22%3A%22[%7B%5C%22surface%5C%22%3A%5C%22messaging%5C%22%2C%5C%22mechanism%5C%22%3A%5C%22attachment%5C%22%2C%5C%22extra_data%5C%22%3A%7B%7D%7D]%22%7D%22%3E%3Cimg%20src=%22https","","2017-09-08 12:25:46");
-- insert into users (email, name, surname, password) values ("netj01@vse.cz", "Jakub", "Netrh", "hashjakoprase");
-- insert into jobs (caption, description, autor) values ("cap1", "popis", 3);
-- UPDATE users SET admin=1 WHERE id=1;
-- delete from users where id=1;
--
-- insert into jobs (caption, description, autor) values ("Tříměsíční stáž v IBM Česká republika", "Existuje mnoho variant s pasážemi Lorem Ipsum, nicméně valná většina trpí neduhy v podobě snahy o vtipný text či použití naprosto náhodných slov, což nevypadá zrovna uvěřitelně. Pokud plánujete použít pasáž z Lorem Ipsum, měli byste mít jistotu, že v textu nebude nic, co by jej mohlo narušovat. Všechny generátory Lorem Ipsum na internetu mají tendenci opakovat kusy textu podle potřeby, díky čemuž je tento", 5);
-- UPDATE news SET content = "Existuje mnoho variant s pasážemi Lorem Ipsum, nicméně valná většina trpí neduhy v podobě snahy o vtipný text či použití naprosto náhodných slov, což nevypadá zrovna uvěřitelně. Pokud plánujete použít pasáž z Lorem Ipsum, měli byste mít jistotu, že v textu nebude nic, co by jej mohlo narušovat. Všechny generátory Lorem Ipsum na internetu mají tendenci opakovat kusy textu podle potřeby, díky čemuž je tento" WHERE id = 38;

