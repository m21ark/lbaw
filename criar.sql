-----------------TYPES---------------
CREATE TYPE accept_st as ENUM ('Pendent', 'Accepted', 'Rejected');

----------------Tables--------------

-- nota: mudar os id_post etc para simplesmente id

-- para dar insert  crypt('johnspassword', gen_salt('bf')) 
CREATE TABLE auth_user (
    id SERIAL PRIMARY KEY,
    username TEXT NOT NULL CONSTRAINT username_uk UNIQUE,
    password TEXT NOT NULL CONSTRAINT password_len CHECK (LENGTH(password) > 8),
    bio TEXT,
    email TEXT NOT NULL CONSTRAINT one_account_only UNIQUE,
    birthdate DATE NOT NULL,
    visibility BOOLEAN NOT NULL,
    photo TEXT NOT NULL DEFAULT 'df_usr_photo.png'
);
-- n esquecer de adicionar os on cascade et..
CREATE TABLE "group" (
    id SERIAL PRIMARY KEY,
    "name" TEXT NOT NULL CONSTRAINT uk_group_name UNIQUE,
    "description" TEXT NOT NULL, 
    visibility BOOLEAN NOT NULL --default TRUE
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    "text" TEXT NOT NULL,
    post_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_poster INTEGER REFERENCES auth_user(id),
    id_group INTEGER REFERENCES "group"(id)
);

CREATE TABLE "comment" (
    id SERIAL PRIMARY KEY,
    "text" TEXT NOT NULL, 
    post_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_post INTEGER NOT NULL REFERENCES post(id), 
    id_commenter INTEGER NOT NULL REFERENCES auth_user(id),
    id_original INTEGER REFERENCES comment(id)
);
-- meter limites nos textos das discription post etc

CREATE TABLE administrator (
    id SERIAL PRIMARY KEY,
    id_user INTEGER NOT NULL
);

CREATE TABLE topic_tag (
    id SERIAL PRIMARY KEY,
    "topic" TEXT NOT NULL -- UK tenho de meter
);

CREATE TABLE user_report (
    id SERIAL PRIMARY KEY,
    report_date DATE NOT NULL,
    "description" TEXT,-- talvez meter not null
    decision_date DATE, 
    decision INTEGER,
    id_reporter INTEGER NOT NULL REFERENCES auth_user(id), -- ver o que acontece se um dos dois é apagado
    id_reported INTEGER NOT NULL REFERENCES auth_user(id), -- CONSTRAINT not_same_user CHECK (id_reported <> id_reporter), TRIGGER TB ? ou sera que da assim
    id_admin INTEGER REFERENCES administrator(id),
    id_comment INTEGER REFERENCES "comment"(id), -- CONSTRAINT comment_or_post CHECK (id_post is NULL), -- deve estar mal
    id_post INTEGER REFERENCES post(id) -- CONSTRAINT comment_or_post CHECK (id_comment is NULL), TRIGGER? ou até mudar a tabela
);

CREATE TABLE "message" (
    id_message text NOT NULL, 
    "date" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, --mesma questão de em cima
    id_sender INTEGER  NOT NULL REFERENCES auth_user(id),
    id_receiver INTEGER NOT NULL REFERENCES auth_user(id) CONSTRAINT not_same_user CHECK (id_sender <> id_receiver)
);

CREATE TABLE "image" (
    id SERIAL PRIMARY KEY, 
    "image" TEXT NOT NULL, 
    id_post INTEGER NOT NULL REFERENCES post(id)
);

CREATE TABLE group_join_request (
    id_user INTEGER REFERENCES auth_user,  
    id_group INTEGER REFERENCES "group"(id), 
    "date" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    acceptance_status accept_st NOT NULL,
    PRIMARY KEY (id_user, id_group)
);

CREATE TABLE friend_request (
    id_user_sender INTEGER REFERENCES auth_user, 
    id_user_receiver INTEGER REFERENCES auth_user,
    "date" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    acceptance_status accept_st NOT NULL,
    PRIMARY KEY (id_user_sender, id_user_receiver)
);

--- passando para o R12
CREATE TABLE topics_interest_user (
    id_user INTEGER REFERENCES auth_user(id),
    id_topic INTEGER REFERENCES topic_tag(id),
    PRIMARY KEY (id_user, id_topic)
);

CREATE TABLE like_post (
    id_user INTEGER REFERENCES auth_user(id),
    id_post INTEGER REFERENCES post(id),
    PRIMARY KEY (id_user, id_post)
);

CREATE TABLE like_comment (
    id_user INTEGER REFERENCES auth_user(id),
    id_comment INTEGER REFERENCES comment(id),
    PRIMARY KEY (id_user, id_comment)
);


CREATE TABLE user_mention ( -- dps nesta tabela pode ser util fazer uma api para fazer isto por ajax
    id_user INTEGER REFERENCES auth_user(id),
    id_post INTEGER REFERENCES post(id),
    PRIMARY KEY (id_user, id_post)
);

CREATE TABLE post_topic (
    id_post INTEGER NOT NULL REFERENCES post(id),
    id_topic INTEGER NOT NULL REFERENCES topic_tag(id),
    PRIMARY KEY (id_post, id_topic)
);

CREATE TABLE "owner" ( 
    id_user INTEGER REFERENCES auth_user(id),
    id_group INTEGER REFERENCES "group"(id),
    PRIMARY KEY (id_user, id_group)
);
