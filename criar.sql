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
DROP TABLE IF EXISTS group_topic CASCADE;
DROP TABLE IF EXISTS "owner" CASCADE;
DROP TABLE IF EXISTS "notification" CASCADE;

--------------DROP TYPES-------------

DROP TYPE IF EXISTS accept_st CASCADE;
DROP TYPE IF EXISTS notification_type CASCADE;

-----------------TYPES---------------
CREATE TYPE accept_st as ENUM ('Pendent', 'Accepted', 'Rejected');
CREATE TYPE notification_type as ENUM ('Like', 'Comment', 'FriendRequest', 'UserMention');


--------------Trigger----------------
DROP TRIGGER IF EXISTS post_like_notification ON like_post; 
DROP TRIGGER IF EXISTS comment_like_notification ON like_comment;
DROP TRIGGER IF EXISTS comment_notification ON comment;
DROP TRIGGER IF EXISTS friend_request_notification ON friend_request;
DROP TRIGGER IF EXISTS user_mention_notification ON user_mention;
DROP TRIGGER IF EXISTS block_add_like_comment ON like_comment;
DROP TRIGGER IF EXISTS block_update_like_comment ON like_comment;
DROP TRIGGER IF EXISTS block_add_like ON like_post;
DROP TRIGGER IF EXISTS block_update_like ON like_post; 
DROP TRIGGER IF EXISTS blocked_user_no_message ON "message"; 
DROP TRIGGER IF EXISTS blocked_user_no_comment ON "comment"; 
DROP TRIGGER IF EXISTS group_owner_succession ON "user";
DROP TRIGGER IF EXISTS group_owner_leaving ON "owner"; 
DROP TRIGGER IF EXISTS user_publishing_on_group ON post;
DROP TRIGGER IF EXISTS user_report_cascade_decision ON user_report; 
DROP TRIGGER IF EXISTS user_topic_limit ON topics_interest_user;
DROP TRIGGER IF EXISTS group_topic_limit ON group_topic;
DROP TRIGGER IF EXISTS private_user_messaging_privacy ON "message";

--------------Functions--------------
DROP FUNCTION IF EXISTS post_like_notification();
DROP FUNCTION IF EXISTS comment_like_notification();
DROP FUNCTION IF EXISTS comment_notification();
DROP FUNCTION IF EXISTS friend_request_notification();
DROP FUNCTION IF EXISTS user_mention_notification();
DROP FUNCTION IF EXISTS blocked_user_no_social_interaction();
DROP FUNCTION IF EXISTS blocked_user_no_message();
DROP FUNCTION IF EXISTS blocked_user_no_comment();
DROP FUNCTION IF EXISTS group_owner_succession();
DROP FUNCTION IF EXISTS group_owner_leaving();
DROP FUNCTION IF EXISTS user_publishing_on_group();
DROP FUNCTION IF EXISTS user_report_cascade_decision();
DROP FUNCTION IF EXISTS user_search_update();
DROP FUNCTION IF EXISTS group_search_update();
DROP FUNCTION IF EXISTS post_search_update();
DROP FUNCTION IF EXISTS comment_search_update();
DROP FUNCTION IF EXISTS user_topic_limit();
DROP FUNCTION IF EXISTS group_topic_limit();
DROP FUNCTION IF EXISTS private_user_messaging_privacy();


----------------Indexes-------------

DROP INDEX IF EXISTS index_post_user;
DROP INDEX IF EXISTS index_post_date;
DROP INDEX IF EXISTS index_comment;
DROP INDEX IF EXISTS index_message;
DROP INDEX IF EXISTS index_notification;

----------------Tables--------------

CREATE TABLE "user" (
    id SERIAL PRIMARY KEY,
    username TEXT NOT NULL CONSTRAINT username_uk UNIQUE,
    password TEXT NOT NULL,
    bio TEXT,
    email TEXT NOT NULL CONSTRAINT one_account_only UNIQUE,
    birthdate DATE NOT NULL CONSTRAINT user_birthdate_check CHECK (date_part('year', AGE(birthdate)) >= 16),
    visibility BOOLEAN NOT NULL DEFAULT True,
    photo TEXT NOT NULL DEFAULT 'user/user.png', 
    ban_date DATE DEFAULT NULL
);

CREATE TABLE "group" (
    id SERIAL PRIMARY KEY,
    "name" TEXT NOT NULL CONSTRAINT uk_group_name UNIQUE,
    photo TEXT NOT NULL DEFAULT 'group/group_default.png',
    "description" TEXT NOT NULL, 
    visibility BOOLEAN NOT NULL DEFAULT True
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    "text" TEXT NOT NULL,
    post_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_poster INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE, 
    id_group INTEGER REFERENCES "group"(id) ON UPDATE CASCADE ON DELETE CASCADE 
);

CREATE TABLE "comment" (
    id SERIAL PRIMARY KEY,
    "text" TEXT NOT NULL, 
    post_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_post INTEGER NOT NULL REFERENCES post(id)  ON UPDATE CASCADE ON DELETE CASCADE,
    id_commenter INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE, 
    id_parent INTEGER REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE 
);

CREATE TABLE administrator (
    id SERIAL PRIMARY KEY,
    id_user INTEGER NOT NULL
);

CREATE TABLE topic (
    id SERIAL PRIMARY KEY,
    "topic" TEXT NOT NULL CONSTRAINT uk_topic_name UNIQUE
);

CREATE TABLE user_report (
    id SERIAL PRIMARY KEY,
    report_date DATE NOT NULL,
    "description" TEXT,
    decision_date DATE, 
    decision accept_st,
    id_reporter INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_admin INTEGER REFERENCES administrator(id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_comment INTEGER REFERENCES "comment"(id) ON UPDATE CASCADE ON DELETE CASCADE, 
    id_post INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "message" (
    id SERIAL PRIMARY KEY, 
    "text" TEXT NOT NULL,
    "date" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
    seen BOOLEAN,
    id_sender INTEGER  NOT NULL REFERENCES "user"(id)  ON UPDATE CASCADE ON DELETE CASCADE,
    id_receiver INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE CONSTRAINT not_same_user CHECK (id_sender <> id_receiver)
);

CREATE TABLE "image" (
    id SERIAL PRIMARY KEY, 
    "path" TEXT NOT NULL, 
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

CREATE TABLE topics_interest_user (
    id_user INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_topic INTEGER REFERENCES topic(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    PRIMARY KEY (id_user, id_topic)
);

CREATE TABLE like_post (
    id_user INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_post INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_post)
);

CREATE TABLE like_comment (
    id_user INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_comment INTEGER REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_comment)
);


CREATE TABLE user_mention ( 
    id_user INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_post INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_post)
);

CREATE TABLE post_topic (
    id_post INTEGER NOT NULL REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_topic INTEGER NOT NULL REFERENCES topic(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    PRIMARY KEY (id_post, id_topic)
);

CREATE TABLE group_topic(
    id_group INTEGER NOT NULL REFERENCES "group"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_topic INTEGER NOT NULL REFERENCES topic(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE "owner" ( 
    id_user INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE, 
    id_group INTEGER REFERENCES "group"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_group)
);

CREATE TABLE "notification" ( 
    id SERIAL PRIMARY KEY,
    tipo notification_type NOT NULL,
    seen BOOLEAN NOT NULL DEFAULT False, 
    id_user INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE, 
    id_post INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE, 
    id_comment INTEGER REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_sender INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
 notification_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


-- TRIGGER01
-- A Like on a post must have a corresponding notification

CREATE FUNCTION post_like_notification() RETURNS TRIGGER AS
$BODY$
BEGIN

    INSERT INTO "notification"(tipo,seen,id_user,id_post,id_comment,id_sender) 
    values ('Like', False, (SELECT id_poster FROM post WHERE id = NEW.id_post), NEW.id_post, NULL, NULL);

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER post_like_notification
    AFTER INSERT ON like_post
    FOR EACH ROW
    EXECUTE PROCEDURE post_like_notification();


-- TRIGGER02
-- A Like on a comment or a reply must have a corresponding notification. Notifying the poster and, in case of a reply, the commenter

CREATE FUNCTION comment_like_notification() RETURNS TRIGGER AS 
$BODY$ 
BEGIN
    INSERT INTO "notification"(tipo, seen, id_user, id_post, id_comment, id_sender)
    values (
            'Like',
            False,
            (
                SELECT DISTINCT id_poster
                FROM like_comment
                    JOIN "comment" ON (comment.id = like_comment.id_comment)
                    JOIN post ON (comment.id_post = post.id)
                WHERE comment.id = NEW.id_comment
            ),
            NULL,
            NEW.id_comment,
            NULL
        );
    IF EXISTS(
        (
            SELECT DISTINCT id_parent
            FROM like_comment
                JOIN "comment" ON (comment.id = like_comment.id_comment)
            WHERE comment.id = id_comment
        )
    ) THEN
    INSERT INTO "notification"(tipo, seen, id_user, id_post, id_comment, id_sender)
    values (
            'Like',
            False,
            (
                SELECT DISTINCT id_commenter
                FROM like_comment
                    JOIN "comment" ON (comment.id = like_comment.id_comment)
                WHERE comment.id = NEW.id_comment
            ),
            NULL,
            NEW.id_comment,
            NULL
        );
    END IF;
    RETURN NEW;
END 
$BODY$ 
LANGUAGE plpgsql;

CREATE TRIGGER comment_like_notification
    AFTER INSERT ON like_comment
    FOR EACH ROW
    EXECUTE PROCEDURE comment_like_notification();



-- TRIGGER03
-- A comment on a post or a reply to a comment must have a corresponding notification. Notifying the poster and, in case of a reply, the commenter

CREATE FUNCTION comment_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO "notification"(tipo, seen, id_user, id_post, id_comment, id_sender)
    values (
            'Comment',
            False,
            (
                SELECT DISTINCT id_poster
                FROM comment
                    JOIN post ON (post.id = comment.id_post)
                WHERE (post.id = New.id_post)
            ),
            NULL,
            NEW.id,
            NULL
        );
  
    IF (NEW.id_parent is not NULL) THEN
    INSERT INTO "notification"(tipo, seen, id_user, id_post, id_comment, id_sender)
    values (
            'Comment',
            False,
            (
                SELECT id_commenter
                FROM comment
                WHERE (id = New.id_parent)
            ),
            NULL,
            NEW.id,
            NULL
        );
    END IF;
    RETURN NEW;
END
$BODY$ 
LANGUAGE plpgsql;

CREATE TRIGGER comment_notification
    AFTER INSERT ON "comment"
    FOR EACH ROW
    EXECUTE PROCEDURE comment_notification();


-- TRIGGER04
-- Friend Request notification. Notifying the receiver of the request

CREATE FUNCTION friend_request_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO "notification"(tipo,seen,id_user,id_post,id_comment,id_sender) 
    values ('FriendRequest', False, NEW.id_user_receiver, NULL, NULL, New.id_user_sender); 
    
    RETURN NEW; 
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER friend_request_notification
    AFTER INSERT ON friend_request
    FOR EACH ROW
    EXECUTE PROCEDURE friend_request_notification();


-- TRIGGER05
-- User mention on post notification

CREATE FUNCTION user_mention_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO "notification"(tipo,seen,id_user,id_post,id_comment,id_sender) 
    values ('UserMention', False, NEW.id_user, NEW.id_post, NULL, NULL); 
    
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER user_mention_notification
    AFTER INSERT ON user_mention
    FOR EACH ROW
    EXECUTE PROCEDURE user_mention_notification();


-- TRIGGER06
-- Blocked user can’t have network social interactions (making likes on comments and posts).

CREATE FUNCTION blocked_user_no_social_interaction() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT id FROM "user" WHERE id = NEW.id_user AND ban_date > current_date) THEN 
        RAISE EXCEPTION 'The user is blocked and cannot make this action';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER block_add_like_comment
    BEFORE INSERT ON like_comment
    FOR EACH ROW
    EXECUTE PROCEDURE blocked_user_no_social_interaction();

CREATE TRIGGER block_update_like_comment
    BEFORE UPDATE ON like_comment
    FOR EACH ROW
    EXECUTE PROCEDURE blocked_user_no_social_interaction();

CREATE TRIGGER block_add_like
    BEFORE INSERT ON like_post
    FOR EACH ROW
    EXECUTE PROCEDURE blocked_user_no_social_interaction();

CREATE TRIGGER block_update_like
    BEFORE UPDATE ON like_post
    FOR EACH ROW
    EXECUTE PROCEDURE blocked_user_no_social_interaction();

-- TRIGGER07
-- Blocked user can’t send messages

CREATE FUNCTION blocked_user_no_message() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT id FROM "user" WHERE id = NEW.id_sender AND ban_date > current_date) THEN 
        RAISE EXCEPTION 'The user is blocked and cannot make this action ';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER blocked_user_no_message
    BEFORE INSERT ON "message"
    FOR EACH ROW
    EXECUTE PROCEDURE blocked_user_no_message();

-- TRIGGER08
-- Blocked user can’t make comments

CREATE FUNCTION blocked_user_no_comment() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT id FROM "user" WHERE id = NEW.id_commenter AND ban_date > current_date) THEN 
        RAISE EXCEPTION 'The user is blocked and cannot make this action ';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER blocked_user_no_comment
    BEFORE INSERT ON "comment"
    FOR EACH ROW
    EXECUTE PROCEDURE blocked_user_no_comment();


-- TRIGGER09
-- Group Owner succession should be resolved before deleting an user.

CREATE FUNCTION group_owner_succession() RETURNS TRIGGER AS 
$BODY$ 
BEGIN
    IF EXISTS (
        SELECT id
        FROM (
                SELECT id_group,
                    count(*) as ct
                FROM "owner"
                GROUP BY id_group
            ) as T
            JOIN "owner" on (T.id_group = "owner".id_group)
            JOIN "user" on ("owner".id_user = "user".id)
        WHERE ct < 2
    ) THEN RAISE EXCEPTION 'User must garantee group succession.';
    END IF;
    RETURN OLD;
END 
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER group_owner_succession
    BEFORE DELETE ON "user"
    FOR EACH ROW
    EXECUTE PROCEDURE group_owner_succession();


-- TRIGGER10
-- A group owner leaving a group ownership must guarantee succession

CREATE FUNCTION group_owner_leaving() RETURNS TRIGGER AS 
$BODY$
BEGIN
    IF EXISTS (
        SELECT id_group
        FROM "owner"
        GROUP BY id_group
        HAVING OLD.id_group = id_group
            AND COUNT(id_user) < 2
    ) THEN RAISE EXCEPTION 'User must garantee group succession.';
    END IF;
    RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER group_owner_leaving
    BEFORE DELETE ON "owner"
    FOR EACH ROW
    EXECUTE PROCEDURE group_owner_leaving();


-- TRIGGER11
-- Posting on groups are restricted to members of the group

CREATE FUNCTION user_publishing_on_group() RETURNS TRIGGER AS 
$BODY$
BEGIN 
    IF (NEW.id_group is not NULL) THEN IF NOT EXISTS (
        SELECT *
        FROM group_join_request
        WHERE id_group = NEW.id_group
            AND id_user = NEW.id_poster
            AND acceptance_status = 'Accepted'
    ) THEN RAISE EXCEPTION 'User does not belong to the group';
    END IF;
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER user_publishing_on_group
    BEFORE INSERT ON post
    FOR EACH ROW
    EXECUTE PROCEDURE user_publishing_on_group();


-- TRIGGER12
-- The report decision must be cascaded to other similar reports to save admin time

CREATE FUNCTION user_report_cascade_decision() RETURNS TRIGGER AS 
$BODY$
BEGIN
    IF (NEW.id_admin is not NULL) THEN
    UPDATE user_report
    SET id_admin = NEW.id_admin,
        decision_date = NEW.decision_date,
        decision = NEW.decision
    WHERE (
            id_comment = NEW.id_comment
            OR id_post = NEW.id_post
        )
        AND id_reporter <> NEW.id_reporter
        AND id_admin is NULL;
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER user_report_cascade_decision
    AFTER UPDATE ON user_report
    FOR EACH ROW
    EXECUTE PROCEDURE user_report_cascade_decision();


-- TRIGGER13
-- User topics of interest are limit to 3 topics

CREATE FUNCTION user_topic_limit() RETURNS TRIGGER AS 
$BODY$
BEGIN 
    IF EXISTS (
        (
            SELECT id_user
            FROM topics_interest_user
            GROUP BY id_user
            HAVING id_user = New.id_user
                AND COUNT(*) = 3
        )
    ) THEN RAISE EXCEPTION 'User cannot add another topic of interest';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


-- TRIGGER14 
-- Group topics are limit to 3 topics

CREATE TRIGGER user_topic_limit
    BEFORE INSERT ON topics_interest_user
    FOR EACH ROW
    EXECUTE PROCEDURE user_topic_limit();

CREATE FUNCTION group_topic_limit() RETURNS TRIGGER AS 
$BODY$ 
BEGIN
    IF EXISTS (
        (
            SELECT id_group
            FROM group_topic
            GROUP BY id_group
            HAVING id_group = New.id_group
                AND COUNT(*) = 3
        )
    ) THEN RAISE EXCEPTION 'Group cannot add another topic of interest';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER group_topic_limit
    BEFORE INSERT ON group_topic
    FOR EACH ROW
    EXECUTE PROCEDURE group_topic_limit();

-- TRIGGER15
-- Only friends can send messages to private accounts

CREATE FUNCTION private_user_messaging_privacy() RETURNS TRIGGER AS 
$BODY$ 
BEGIN
    IF(
        (
            SELECT visibility
            FROM "user"
            WHERE id = NEW.id_receiver
        ) = False
    ) THEN IF NOT EXISTS (
        (
            SELECT
            FROM friend_request
            WHERE (
                    id_user_receiver = NEW.id_receiver
                    AND id_user_sender = NEW.id_sender
                )
                OR (
                    id_user_sender = NEW.id_receiver
                    AND id_user_receiver = New.id_sender
                )
                AND acceptance_status = 'Accepted'
        )
    ) THEN RAISE EXCEPTION 'The users are not friends';
    END IF;
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER private_user_messaging_privacy
    BEFORE INSERT ON "message"
    FOR EACH ROW
    EXECUTE PROCEDURE private_user_messaging_privacy();



ALTER TABLE "user"
ADD COLUMN tsvectors TSVECTOR; 

CREATE FUNCTION user_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.username), 'A') ||
            setweight(to_tsvector('english', NEW.bio), 'B')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.username <> OLD.username OR NEW.bio <> OLD.bio) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.username), 'A') ||
                setweight(to_tsvector('english', NEW.bio), 'B')
            );
        END IF;
    END IF;    
    RETURN NEW;
END $$
LANGUAGE plpgsql;


CREATE TRIGGER user_search_update
    BEFORE INSERT OR UPDATE ON "user"
    FOR EACH ROW
    EXECUTE PROCEDURE user_search_update();

CREATE INDEX user_search_fts ON "user" USING GIN (tsvectors);


ALTER TABLE "group"
ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION group_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', NEW.description), 'B')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR NEW.description <> OLD.description) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.name), 'A') ||
                setweight(to_tsvector('english', NEW.description), 'B')
            );
        END IF;
    END IF;    
    RETURN NEW;
END $$
LANGUAGE plpgsql;


CREATE TRIGGER group_search_update
    BEFORE INSERT OR UPDATE ON "group"
    FOR EACH ROW 
    EXECUTE PROCEDURE group_search_update();

CREATE INDEX group_search_fts ON "group" USING GIN (tsvectors);


ALTER TABLE "post"
ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION post_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (setweight(to_tsvector('english', NEW.text), 'A'));
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.text <> OLD.text) THEN
            NEW.tsvectors = (setweight(to_tsvector('english', NEW.text), 'A'));
        END IF;
    END IF;    
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER post_search_update
    BEFORE INSERT OR UPDATE ON "post"
    FOR EACH ROW 
    EXECUTE PROCEDURE post_search_update();

CREATE INDEX post_search_fts ON "post" USING GIN (tsvectors);



ALTER TABLE "comment"
ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION comment_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (setweight(to_tsvector('english', NEW.text), 'C'));
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.text <> OLD.text) THEN
            NEW.tsvectors = (setweight(to_tsvector('english', NEW.text), 'C'));
        END IF;
    END IF;    
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER comment_search_update
    BEFORE INSERT OR UPDATE ON "comment"
    FOR EACH ROW 
    EXECUTE PROCEDURE comment_search_update();

CREATE INDEX comment_search_fts ON "comment" USING GIN (tsvectors);


CREATE INDEX index_post_user ON post USING hash (id_poster);
CREATE INDEX index_post_date ON post USING btree (post_date);
CREATE INDEX index_comment ON comment USING hash (id_post);
CREATE INDEX index_message ON message USING hash (id_receiver);
CREATE INDEX index_notification ON notification USING hash (id_user);