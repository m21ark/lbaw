--------------DROP TABLES------------

DROP TABLE IF EXISTS "user" CASCADE;
DROP TABLE IF EXISTS "group" CASCADE;
DROP TABLE IF EXISTS post CASCADE;
DROP TABLE IF EXISTS "comment" CASCADE;
DROP TABLE IF EXISTS administrator CASCADE;
DROP TABLE IF EXISTS topic CASCADE;
DROP TABLE IF EXISTS user_report CASCADE;
DROP TABLE IF EXISTS "message" CASCADE;
DROP TABLE IF EXISTS "image" CASCADE;
DROP TABLE IF EXISTS group_join_request CASCADE;
DROP TABLE IF EXISTS friend_request CASCADE;
DROP TABLE IF EXISTS topics_interest_user CASCADE;
DROP TABLE IF EXISTS like_post CASCADE;
DROP TABLE IF EXISTS like_comment CASCADE;
DROP TABLE IF EXISTS user_mention CASCADE;
DROP TABLE IF EXISTS post_topic CASCADE;
DROP TABLE IF EXISTS group_themes CASCADE;
DROP TABLE IF EXISTS "owner" CASCADE;
DROP TABLE IF EXISTS "notification" CASCADE;

--------------DROP TYPES-------------

DROP TYPE IF EXISTS accept_st CASCADE;
DROP TYPE IF EXISTS notification_type CASCADE;

-----------------TYPES---------------
CREATE TYPE accept_st as ENUM ('Pendent', 'Accepted', 'Rejected');
CREATE TYPE notification_type as ENUM ('Like', 'Comment', 'FriendRequest', 'tag');
----------------Tables--------------
-- nota: mudar os id_post etc para simplesmente id

-- para dar insert  crypt('johnspassword', gen_salt('bf')) 
CREATE TABLE "user" (
    id SERIAL PRIMARY KEY,
    username TEXT NOT NULL CONSTRAINT username_uk UNIQUE,
    password TEXT NOT NULL,
    bio TEXT,
    email TEXT NOT NULL CONSTRAINT one_account_only UNIQUE,
    birthdate DATE NOT NULL,
    visibility BOOLEAN NOT NULL,
    photo TEXT NOT NULL DEFAULT 'df_usr_photo.png' -- especificar o dirétorio mais para a frente
);
-- n esquecer de adicionar os on cascade et..
CREATE TABLE "group" (
    id SERIAL PRIMARY KEY,
    "name" TEXT NOT NULL CONSTRAINT uk_group_name UNIQUE,
    "description" TEXT NOT NULL, 
    visibility BOOLEAN NOT NULL DEFAULT True
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    "text" TEXT NOT NULL,
    post_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_poster INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE, -- Parce razoável...se conta é eliminada ent eliminamos a publicação, acho que n entra na questão do anónimo
    id_group INTEGER REFERENCES "group"(id) ON UPDATE CASCADE ON DELETE CASCADE -- Se grupo ´r eliminado, remove-se os posts
);

CREATE TABLE "comment" (
    id SERIAL PRIMARY KEY,
    "text" TEXT NOT NULL, 
    post_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_post INTEGER NOT NULL REFERENCES post(id)  ON UPDATE CASCADE ON DELETE CASCADE, -- tira-se todos os comentários de posts que são eliminados 
    id_commenter INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE SET NULL, -- a parte de meter anónimo o user
    id_parent INTEGER REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE -- se pai é eliminado ent o filho tb é
);
-- meter limites nos textos das discription, post etc?

CREATE TABLE administrator (
    id SERIAL PRIMARY KEY,
    id_user INTEGER NOT NULL
);

CREATE TABLE topic (
    id SERIAL PRIMARY KEY,
    "topic" TEXT NOT NULL -- UK tenho de meter
);

CREATE TABLE user_report (
    id SERIAL PRIMARY KEY,
    report_date DATE NOT NULL,
    "description" TEXT,-- talvez meter not null
    decision_date DATE, 
    decision accept_st,
    id_reporter INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE SET NULL, -- garante que o report ainda esteja vivo
    id_admin INTEGER REFERENCES administrator(id) ON UPDATE CASCADE ON DELETE CASCADE, --- aqui se fosse null ele poderia voltar a ser visto
    id_comment INTEGER REFERENCES "comment"(id) ON UPDATE CASCADE ON DELETE CASCADE, -- CONSTRAINT comment_or_post CHECK (id_post is NULL), -- deve estar mal
    id_post INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE-- CONSTRAINT comment_or_post CHECK (id_comment is NULL), TRIGGER? ou até mudar a tabela
);

CREATE TABLE "message" (
    id_message text NOT NULL, 
    "date" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
    id_sender INTEGER  NOT NULL REFERENCES "user"(id)  ON UPDATE CASCADE ON DELETE CASCADE,
    id_receiver INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE CONSTRAINT not_same_user CHECK (id_sender <> id_receiver)
);

CREATE TABLE "image" (
    id SERIAL PRIMARY KEY, 
    "image" TEXT NOT NULL, 
    id_post INTEGER NOT NULL REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE group_join_request (
    id_user INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,  
    id_group INTEGER REFERENCES "group"(id) ON UPDATE CASCADE ON DELETE CASCADE, 
    "date" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    acceptance_status accept_st NOT NULL,
    PRIMARY KEY (id_user, id_group)
);

CREATE TABLE friend_request (
    id_user_sender INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE, 
    id_user_receiver INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    "date" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    acceptance_status accept_st NOT NULL,
    PRIMARY KEY (id_user_sender, id_user_receiver)
);

--- passando para o R12
CREATE TABLE topics_interest_user (
    id_user INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_topic INTEGER REFERENCES topic(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    PRIMARY KEY (id_user, id_topic)
);

CREATE TABLE like_post (
    id_user INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE SET NULL,
    id_post INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_post)
);

CREATE TABLE like_comment (
    id_user INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE SET NULL,
    id_comment INTEGER REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_comment)
);


CREATE TABLE user_mention ( 
    id_user INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE SET NULL,
    id_post INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_post)
);

CREATE TABLE post_topic (
    id_post INTEGER NOT NULL REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_topic INTEGER NOT NULL REFERENCES topic(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    PRIMARY KEY (id_post, id_topic)
);

CREATE TABLE group_themes(
    id_group INTEGER NOT NULL REFERENCES "group"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_topic INTEGER NOT NULL REFERENCES topic(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE "owner" ( 
    id_user INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE SET NULL, -- N se elimina o grupo ?
    id_group INTEGER REFERENCES "group"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_group)
);

CREATE TABLE "notification" ( 
    id SERIAL PRIMARY KEY,
    tipo notification_type NOT NULL, 
    id_user INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE, -- n vale a pensa ver a notificação 
    id_post INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE, 
    id_comment INTEGER REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_sender INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE 
);