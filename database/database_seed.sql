DROP SCHEMA IF EXISTS lbaw2356 CASCADE;
CREATE SCHEMA lbaw2356;
SET search_path TO lbaw2356;

-----------------------------------------
-- Drop Tables and Types
-----------------------------------------

DROP TABLE IF EXISTS follow_notification CASCADE;
DROP TABLE IF EXISTS block_notification CASCADE;
DROP TABLE IF EXISTS undefined_topic_notification CASCADE;
DROP TABLE IF EXISTS removal_notification CASCADE;
DROP TABLE IF EXISTS edit_notification CASCADE;
DROP TABLE IF EXISTS upvote_notification CASCADE;
DROP TABLE IF EXISTS content_notification CASCADE;
DROP TABLE IF EXISTS comment_notification CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS member_report CASCADE;
DROP TABLE IF EXISTS content_report CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS vote CASCADE;
DROP TABLE IF EXISTS edit CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS follow_tag CASCADE;
DROP TABLE IF EXISTS tag_article CASCADE;
DROP TABLE IF EXISTS tag CASCADE;
DROP TABLE IF EXISTS article_image CASCADE;
DROP TABLE IF EXISTS article CASCADE;
DROP TABLE IF EXISTS content_item CASCADE;
DROP TABLE IF EXISTS topic_suggestion CASCADE;
DROP TABLE IF EXISTS topic CASCADE;
DROP TABLE IF EXISTS follow_member CASCADE;
DROP TABLE IF EXISTS appeal CASCADE;
DROP TABLE IF EXISTS member CASCADE;
DROP TABLE IF EXISTS profile_image CASCADE;
DROP TABLE IF EXISTS imdb_info CASCADE;

DROP TYPE IF EXISTS motives;
DROP TYPE IF EXISTS misconduct_types;
DROP TYPE IF EXISTS statuses;


-----------------------------------------
-- Drop Triggers and Functions
-----------------------------------------

DROP TRIGGER IF EXISTS data_anonymization ON member CASCADE;
DROP FUNCTION IF EXISTS data_anonymization();
DROP TRIGGER IF EXISTS delete_content_item ON content_item CASCADE;
DROP FUNCTION IF EXISTS delete_content_item();
DROP TRIGGER IF EXISTS before_permanent_delete_article ON article CASCADE;
DROP FUNCTION IF EXISTS before_permanent_delete_article();
DROP TRIGGER IF EXISTS after_permanent_delete_article ON article CASCADE;
DROP FUNCTION IF EXISTS after_permanent_delete_article();
DROP TRIGGER IF EXISTS before_delete_comment ON comment CASCADE;
DROP FUNCTION IF EXISTS before_delete_comment();
DROP TRIGGER IF EXISTS after_delete_comment ON comment CASCADE;
DROP FUNCTION IF EXISTS after_delete_comment();
DROP TRIGGER IF EXISTS ban_tag ON tag CASCADE;
DROP FUNCTION IF EXISTS ban_tag();
DROP TRIGGER IF EXISTS edit_content_notification ON edit CASCADE;
DROP FUNCTION IF EXISTS edit_content_notification();
DROP TRIGGER IF EXISTS remove_topic ON topic CASCADE;
DROP FUNCTION IF EXISTS remove_topic();
DROP TRIGGER IF EXISTS notify_undefined_topic ON article CASCADE;
DROP FUNCTION IF EXISTS notify_undefined_topic();
DROP FUNCTION IF EXISTS generate_undefined_topic_notification(author_id INT, article_id INT);
DROP TRIGGER IF EXISTS vote_for_own_content ON vote CASCADE;
DROP FUNCTION IF EXISTS vote_for_own_content();
DROP TRIGGER IF EXISTS news_article_tags ON tag_article CASCADE;
DROP FUNCTION IF EXISTS news_article_tags();
DROP TRIGGER IF EXISTS blocked_user_appeal ON appeal CASCADE;
DROP FUNCTION IF EXISTS blocked_user_appeal();
DROP TRIGGER IF EXISTS topic_control ON topic_suggestion CASCADE;
DROP FUNCTION IF EXISTS topic_control();
DROP TRIGGER IF EXISTS comment_date_validation ON comment CASCADE;
DROP FUNCTION IF EXISTS comment_date_validation();
DROP TRIGGER IF EXISTS comment_reply_validation ON comment CASCADE;
DROP FUNCTION IF EXISTS comment_reply_validation();
DROP TRIGGER IF EXISTS reply_to_comment_validation ON comment CASCADE;
DROP FUNCTION IF EXISTS reply_to_comment_validation();
DROP TRIGGER IF EXISTS report_self ON member_report CASCADE;
DROP FUNCTION IF EXISTS report_self();
DROP TRIGGER IF EXISTS report_self_content ON content_report CASCADE;
DROP FUNCTION IF EXISTS report_self_content();
DROP TRIGGER IF EXISTS generate_block_notification ON member CASCADE;
DROP FUNCTION IF EXISTS generate_block_notification();
DROP TRIGGER IF EXISTS generate_follow_notification ON follow_member CASCADE;
DROP FUNCTION IF EXISTS generate_follow_notification();
DROP TRIGGER IF EXISTS generate_comment_notification ON comment CASCADE;
DROP FUNCTION IF EXISTS generate_comment_notification();
DROP TRIGGER IF EXISTS update_content_item_academy_score ON vote CASCADE;
DROP FUNCTION IF EXISTS update_content_item_academy_score();
DROP TRIGGER IF EXISTS update_member_academy_score ON content_item CASCADE;
DROP FUNCTION IF EXISTS update_member_academy_score();
DROP TRIGGER IF EXISTS generate_removal_notification ON edit CASCADE;
DROP FUNCTION IF EXISTS generate_removal_notification();
DROP FUNCTION IF EXISTS content_item_search_update() CASCADE;
DROP TRIGGER IF EXISTS content_item_search_update ON content_item CASCADE;
DROP FUNCTION IF EXISTS article_search_update() CASCADE;
DROP TRIGGER IF EXISTS article_search_update ON article CASCADE;
DROP FUNCTION IF EXISTS member_search_update() CASCADE;
DROP TRIGGER IF EXISTS member_search_update ON member CASCADE;
DROP FUNCTION IF EXISTS email_lowercase() CASCADE;
DROP TRIGGER IF EXISTS email_lowercase ON member CASCADE;
DROP FUNCTION IF EXISTS tag_lowercase() CASCADE;
DROP TRIGGER IF EXISTS tag_lowercase ON tag CASCADE;
DROP FUNCTION IF EXISTS delete_appeals() CASCADE;
DROP TRIGGER IF EXISTS delete_appeals ON member CASCADE;


-----------------------------------------
-- Drop Indexes
-----------------------------------------

DROP INDEX IF EXISTS content_item_date;
DROP INDEX IF EXISTS content_item_academy_score;
DROP INDEX IF EXISTS article_topic;
DROP INDEX IF EXISTS content_item_search;
DROP INDEX IF EXISTS member_search;

-----------------------------------------
-- Drop Create Functions
-----------------------------------------

DROP FUNCTION IF EXISTS create_imdb_info(query_type VARCHAR(255), imdb_info_id VARCHAR(255));
DROP FUNCTION IF EXISTS create_profile_image(file_name VARCHAR(255));
DROP FUNCTION IF EXISTS create_member(email VARCHAR(255), password VARCHAR(255), username VARCHAR(255), first_name VARCHAR(255), last_name VARCHAR(255), biography TEXT, profile_image_id INTEGER);
DROP FUNCTION IF EXISTS create_admin(email VARCHAR(255), password VARCHAR(255), username VARCHAR(255), first_name VARCHAR(255), last_name VARCHAR(255), biography TEXT, profile_image_id INTEGER);
DROP FUNCTION IF EXISTS create_appeal(body VARCHAR(255), submitter_id INTEGER);
DROP FUNCTION IF EXISTS create_follow_member(follower_id INTEGER, followed_id INTEGER);
DROP FUNCTION IF EXISTS create_topic(name VARCHAR(255));
DROP FUNCTION IF EXISTS create_topic_suggestion(name VARCHAR(255), status statuses, suggester_id INTEGER);
DROP FUNCTION IF EXISTS create_content_item(body TEXT, author_id INTEGER);
DROP FUNCTION IF EXISTS create_article(title VARCHAR(255), body TEXT, author_id INTEGER, topic_id INTEGER, imdb_info_id INTEGER);
DROP FUNCTION IF EXISTS create_article_image(file_name VARCHAR(255), article_id INTEGER);
DROP FUNCTION IF EXISTS create_tag(name VARCHAR(255));
DROP FUNCTION IF EXISTS create_tag_article(tag_id INTEGER, article_id INTEGER);
DROP FUNCTION IF EXISTS create_follow_tag(tag_id INTEGER, member_id INTEGER);
DROP FUNCTION IF EXISTS create_comment(body TEXT, author_id INTEGER, is_reply BOOLEAN, article_id INTEGER, reply_id INTEGER);
DROP FUNCTION IF EXISTS create_edit(altered_field VARCHAR(255), old_value TEXT, new_value TEXT, content_item_id INTEGER, author_id INTEGER);
DROP FUNCTION IF EXISTS create_vote(member_id INTEGER, content_item_id INTEGER, weight INTEGER);
DROP FUNCTION IF EXISTS create_report(body TEXT, submitter_id INTEGER);
DROP FUNCTION IF EXISTS create_content_report(body TEXT, submitter_id INTEGER, motive motives, content_item_id INTEGER);
DROP FUNCTION IF EXISTS create_member_report(body TEXT, submitter_id INTEGER, misconduct misconduct_types, member_id INTEGER);
DROP FUNCTION IF EXISTS create_notification(notified_id INTEGER);
DROP FUNCTION IF EXISTS create_comment_notification(notified_id INTEGER, comment_id INTEGER);
DROP FUNCTION IF EXISTS create_content_notification(notified_id INTEGER, content_item_id INTEGER);
DROP FUNCTION IF EXISTS create_upvote_notification(notified_id INTEGER, content_item_id INTEGER, amount INTEGER);
DROP FUNCTION IF EXISTS create_edit_notification(notified_id INTEGER, content_item_id INTEGER);
DROP FUNCTION IF EXISTS create_removal_notification(notified_id INTEGER, content_item_id INTEGER);
DROP FUNCTION IF EXISTS create_undefined_topic_notification(notified_id INTEGER, content_item_id INTEGER);
DROP FUNCTION IF EXISTS create_block_notification(notified_id INTEGER);
DROP FUNCTION IF EXISTS create_follow_notification(notified_id INTEGER, follower_id INTEGER);


-----------------------------------------
-- Types
-----------------------------------------

CREATE TYPE motives AS ENUM ('Hateful', 'Spam', 'Violent', 'NSFW', 'Misinformation', 'Plagiarism', 'Other');
CREATE TYPE misconduct_types AS ENUM ('Hateful', 'Harassment', 'Spam', 'Impersonation', 'InnapropriateContent', 'Other');
CREATE TYPE statuses AS ENUM ('Pending', 'Accepted', 'Rejected');


-----------------------------------------
-- Tables
-----------------------------------------

CREATE TABLE imdb_info (
    id              SERIAL,
    query_type      VARCHAR(255)    NOT NULL,
    imdb_info_id         VARCHAR(255)    UNIQUE NOT NULL,
    CONSTRAINT imdb_info_pk PRIMARY KEY (id),
    CONSTRAINT imdb_info_imdb_info_id_unique UNIQUE (imdb_info_id)
);

CREATE TABLE profile_image (
    id              SERIAL,
    file_name       VARCHAR(255)    UNIQUE NOT NULL,
    CONSTRAINT profile_image_pk PRIMARY KEY (id),
    CONSTRAINT profile_image_file_name_unique UNIQUE (file_name)
);

CREATE TABLE member (
    id              SERIAL,
    email           VARCHAR(255)    UNIQUE NOT NULL,
    password        VARCHAR(255)    NOT NULL,
    username        VARCHAR(255)    UNIQUE NOT NULL,
    first_name      VARCHAR(255)    NOT NULL,
    last_name       VARCHAR(255)    NOT NULL,
    biography       TEXT,
    is_blocked      BOOLEAN         NOT NULL DEFAULT FALSE,
    is_admin        BOOLEAN         NOT NULL DEFAULT FALSE,
    is_deleted      BOOLEAN         NOT NULL DEFAULT FALSE,
    academy_score   INTEGER         NOT NULL DEFAULT 0,
    profile_image_id INTEGER       NOT NULL DEFAULT 0,
    remember_token  VARCHAR(255),
    google_id      VARCHAR(255)   UNIQUE,
    CONSTRAINT member_pk PRIMARY KEY (id),
    CONSTRAINT member_email_unique UNIQUE (email) ,
    CONSTRAINT member_username_unique UNIQUE (username),
    CONSTRAINT member_profile_image_fk FOREIGN KEY (profile_image_id) REFERENCES profile_image(id)
);

CREATE TABLE appeal (
    id              SERIAL,
    date_time       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    body            TEXT,
    submitter_id    INTEGER         NOT NULL,
    CONSTRAINT appeal_pk PRIMARY KEY (id),
    CONSTRAINT appeal_submitter_fk FOREIGN KEY (submitter_id) REFERENCES member(id)
);

CREATE TABLE follow_member (
    follower_id     INTEGER         NOT NULL,
    followed_id     INTEGER         NOT NULL,
    CONSTRAINT follow_pk PRIMARY KEY (follower_id, followed_id),
    CONSTRAINT follow_follower_fk FOREIGN KEY (follower_id) REFERENCES member(id) ON DELETE CASCADE,
    CONSTRAINT follow_followed_fk FOREIGN KEY (followed_id) REFERENCES member(id) ON DELETE CASCADE,
    CONSTRAINT follow_check CHECK (follower_id != followed_id)
);

CREATE TABLE topic (
    id              SERIAL,
    name            VARCHAR(255)    UNIQUE NOT NULL,
    CONSTRAINT topic_pk PRIMARY KEY (id),
    CONSTRAINT topic_name_unique UNIQUE (name)
);

CREATE TABLE topic_suggestion (
    id              SERIAL,
    name            VARCHAR(255)    UNIQUE NOT NULL,
    status          statuses        NOT NULL DEFAULT 'Pending',
    suggester_id     INTEGER         NOT NULL,
    CONSTRAINT topic_suggestion_pk PRIMARY KEY (id),
    CONSTRAINT topic_suggestion_name_unique UNIQUE (name),
    CONSTRAINT topic_suggester_fk FOREIGN KEY (suggester_id) REFERENCES member(id)
);

CREATE TABLE content_item (
    id              SERIAL,
    date_time       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    body            TEXT            NOT NULL,
    is_deleted      BOOLEAN         NOT NULL DEFAULT FALSE,
    academy_score   INTEGER         NOT NULL DEFAULT 0,
    author_id       INTEGER         NOT NULL,
    CONSTRAINT content_item_pk PRIMARY KEY (id),
    CONSTRAINT content_item_author_fk FOREIGN KEY (author_id) REFERENCES member(id)
);

CREATE TABLE article (
    id              INTEGER,
    title           VARCHAR(255)    NOT NULL,
    topic_id        INTEGER         NOT NULL,
    imdb_info_id    INTEGER,
    CONSTRAINT article_pk PRIMARY KEY (id),
    CONSTRAINT article_content_item_fk FOREIGN KEY (id) REFERENCES content_item(id) ON DELETE CASCADE,
    CONSTRAINT article_topic_fk FOREIGN KEY (topic_id) REFERENCES topic(id),
    CONSTRAINT article_imdb_info_fk FOREIGN KEY (imdb_info_id) REFERENCES imdb_info(id)
);

CREATE TABLE article_image (
    id              SERIAL,
    file_name       VARCHAR(255)    UNIQUE NOT NULL,
    article_id      INTEGER         NOT NULL,
    CONSTRAINT article_image_pk PRIMARY KEY (id),
    CONSTRAINT article_image_file_name_unique UNIQUE (file_name),
    CONSTRAINT article_image_article_fk FOREIGN KEY (article_id) REFERENCES article(id)
);

CREATE TABLE tag (
    id              SERIAL,
    name            VARCHAR(255)    UNIQUE NOT NULL,
    is_banned       BOOLEAN         NOT NULL DEFAULT FALSE,
    CONSTRAINT tag_pk PRIMARY KEY (id),
    CONSTRAINT tag_name_unique UNIQUE (name)
);

CREATE TABLE tag_article (
    tag_id          INTEGER         NOT NULL,
    article_id      INTEGER         NOT NULL,
    CONSTRAINT tag_article_pk PRIMARY KEY (tag_id, article_id),
    CONSTRAINT tag_article_tag_fk FOREIGN KEY (tag_id) REFERENCES tag(id) ON DELETE CASCADE,
    CONSTRAINT tag_article_article_fk FOREIGN KEY (article_id) REFERENCES article(id) ON DELETE CASCADE
);

CREATE TABLE follow_tag (
    tag_id     INTEGER         NOT NULL,
    member_id     INTEGER         NOT NULL,
    CONSTRAINT follow_tag_pk PRIMARY KEY (tag_id, member_id),
    CONSTRAINT follow_tag_tag_fk FOREIGN KEY (tag_id) REFERENCES tag(id) ON DELETE CASCADE,
    CONSTRAINT follow_tag_member_fk FOREIGN KEY (member_id) REFERENCES member(id) ON DELETE CASCADE
);

CREATE TABLE comment (
    id              INTEGER,
    is_reply        BOOLEAN         NOT NULL,
    article_id      INTEGER         NOT NULL,
    reply_id        INTEGER,
    CONSTRAINT comment_pk PRIMARY KEY (id),
    CONSTRAINT comment_content_item_fk FOREIGN KEY (id) REFERENCES content_item(id) ON DELETE CASCADE,
    CONSTRAINT comment_article_fk FOREIGN KEY (article_id) REFERENCES article(id),
    CONSTRAINT comment_reply_fk FOREIGN KEY (reply_id) REFERENCES comment(id),
    CONSTRAINT comment_reply_check CHECK (is_reply = TRUE AND reply_id IS NOT NULL OR is_reply = FALSE AND reply_id IS NULL)
);

CREATE TABLE edit (
    id              SERIAL,
    altered_field   VARCHAR(255)    NOT NULL,
    old_value       TEXT            NOT NULL,
    new_value       TEXT            NOT NULL,
    content_item_id INTEGER         NOT NULL,
    author_id       INTEGER         NOT NULL,
    CONSTRAINT edit_pk PRIMARY KEY (id),
    CONSTRAINT edit_content_item_fk FOREIGN KEY (content_item_id) REFERENCES content_item(id) ON DELETE CASCADE,
    CONSTRAINT edit_author_fk FOREIGN KEY (author_id) REFERENCES member(id)
);

CREATE TABLE vote (
    member_id       INTEGER         NOT NULL,
    content_item_id INTEGER         NOT NULL,
    weight          INTEGER         NOT NULL CHECK (weight = 1 OR weight = -1),
    CONSTRAINT vote_pk PRIMARY KEY (member_id, content_item_id),
    CONSTRAINT vote_member_fk FOREIGN KEY (member_id) REFERENCES member(id),
    CONSTRAINT vote_content_item_fk FOREIGN KEY (content_item_id) REFERENCES content_item(id)
);

CREATE TABLE report (
    id              SERIAL,
    date_time       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    body            TEXT,
    submitter_id    INTEGER         NOT NULL,
    CONSTRAINT report_pk PRIMARY KEY (id),
    CONSTRAINT report_submitter_fk FOREIGN KEY (submitter_id) REFERENCES member(id)
);

CREATE TABLE content_report (
    id              INTEGER,
    motive          motives         NOT NULL,
    content_item_id INTEGER         NOT NULL,
    CONSTRAINT content_report_pk PRIMARY KEY (id),
    CONSTRAINT content_report_report_fk FOREIGN KEY (id) REFERENCES report(id) ON DELETE CASCADE,
    CONSTRAINT content_report_content_item_fk FOREIGN KEY (content_item_id) REFERENCES content_item(id) ON DELETE CASCADE
);

CREATE TABLE member_report (
    id              INTEGER,
    misconduct      misconduct_types NOT NULL, 
    member_id       INTEGER         NOT NULL,
    CONSTRAINT member_report_pk PRIMARY KEY (id),
    CONSTRAINT member_report_report_fk FOREIGN KEY (id) REFERENCES report(id) ON DELETE CASCADE,
    CONSTRAINT member_report_member_fk FOREIGN KEY (member_id) REFERENCES member(id)
);

CREATE TABLE notification (
    id              SERIAL,
    was_read        BOOLEAN         NOT NULL DEFAULT FALSE,
    date_time       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    notified_id     INTEGER         NOT NULL,
    CONSTRAINT notification_pk PRIMARY KEY (id),
    CONSTRAINT notification_notified_fk FOREIGN KEY (notified_id) REFERENCES member(id) ON DELETE CASCADE
);

CREATE TABLE comment_notification (
    id              INTEGER,
    comment_id      INTEGER         NOT NULL,
    CONSTRAINT comment_notification_pk PRIMARY KEY (id),
    CONSTRAINT comment_notification_notification_fk FOREIGN KEY (id) REFERENCES notification(id) ON DELETE CASCADE,
    CONSTRAINT comment_notification_comment_fk FOREIGN KEY (comment_id) REFERENCES comment(id) ON DELETE CASCADE
);

CREATE TABLE content_notification (
    id              INTEGER,
    content_item_id INTEGER         NOT NULL,
    CONSTRAINT content_notification_pk PRIMARY KEY (id),
    CONSTRAINT content_notification_notification_fk FOREIGN KEY (id) REFERENCES notification(id) ON DELETE CASCADE,
    CONSTRAINT content_notification_content_item_fk FOREIGN KEY (content_item_id) REFERENCES content_item(id) ON DELETE CASCADE
);

CREATE TABLE upvote_notification (
    id              INTEGER,
    amount          INTEGER         NOT NULL,
    CONSTRAINT upvote_notification_pk PRIMARY KEY (id),
    CONSTRAINT upvote_notification_content_notification_fk FOREIGN KEY (id) REFERENCES content_notification(id) ON DELETE CASCADE,
    CONSTRAINT upvote_notification_amount_check CHECK (amount > 0)
);

CREATE TABLE edit_notification (
    id              INTEGER,
    CONSTRAINT edit_notification_pk PRIMARY KEY (id),
    CONSTRAINT edit_notification_content_notification_fk FOREIGN KEY (id) REFERENCES content_notification(id) ON DELETE CASCADE
);

CREATE TABLE removal_notification (
    id              INTEGER,
    CONSTRAINT removal_notification_pk PRIMARY KEY (id),
    CONSTRAINT removal_notification_content_notification_fk FOREIGN KEY (id) REFERENCES content_notification(id) ON DELETE CASCADE
);

CREATE TABLE undefined_topic_notification (
    id              INTEGER,
    CONSTRAINT undefined_topic_notification_pk PRIMARY KEY (id),
    CONSTRAINT undefined_topic_notification_content_notification_fk FOREIGN KEY (id) REFERENCES content_notification(id) ON DELETE CASCADE
);

CREATE TABLE block_notification (
    id              INTEGER,
    CONSTRAINT block_notification_pk PRIMARY KEY (id),
    CONSTRAINT block_notification_notification_fk FOREIGN KEY (id) REFERENCES notification(id) ON DELETE CASCADE
);

CREATE TABLE follow_notification (
    id              INTEGER,
    follower_id     INTEGER         NOT NULL,
    CONSTRAINT follow_notification_pk PRIMARY KEY (id),
    CONSTRAINT follow_notification_notification_fk FOREIGN KEY (id) REFERENCES notification(id) ON DELETE CASCADE,
    CONSTRAINT follow_notification_follower_fk FOREIGN KEY (follower_id) REFERENCES member(id)
);


-----------------------------------------
-- Triggers and Functions
-----------------------------------------

CREATE FUNCTION data_anonymization() 
RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE member SET
        email = CONCAT('deleted', OLD.id),
        username = CONCAT('deleted', OLD.id),
        password = '',
        first_name = 'Deleted',
        last_name = 'User',
        biography = '',
        profile_image_id = 0,
        google_id = NULL
    WHERE id = OLD.id;

        DELETE FROM profile_image WHERE id != 0 AND id = OLD.profile_image_id;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER data_anonymization 
    AFTER UPDATE OF is_deleted ON member
    FOR EACH ROW
    WHEN (OLD.is_deleted = false AND NEW.is_deleted = true)
    EXECUTE PROCEDURE data_anonymization();


CREATE FUNCTION delete_content_item()
RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (OLD.id IN (SELECT id FROM article)) THEN
        UPDATE content_item AS ct
        SET is_deleted = TRUE
        FROM comment AS c
        WHERE c.article_id = OLD.id AND c.id = ct.id AND c.is_reply = FALSE;
    ELSIF (OLD.id IN (SELECT id FROM comment)) THEN
        IF ((SELECT is_reply FROM comment WHERE comment.id = OLD.id) = FALSE) THEN
            UPDATE content_item AS ct
            SET is_deleted = TRUE
            FROM comment AS c
            WHERE c.reply_id = OLD.id AND c.id = ct.id AND c.is_reply = TRUE;
        END IF;
    END IF;

    UPDATE member 
    SET academy_score = member.academy_score - OLD.academy_score
    WHERE member.id = OLD.author_id;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER delete_content_item
    BEFORE UPDATE OF is_deleted ON content_item
    FOR EACH ROW
    WHEN (NEW.is_deleted = TRUE AND OLD.is_deleted = FALSE)
    EXECUTE PROCEDURE delete_content_item();

CREATE FUNCTION before_permanent_delete_article()
RETURNS TRIGGER AS
$BODY$
BEGIN
    DELETE FROM vote
    WHERE vote.content_item_id = OLD.id;

    DELETE FROM comment
    WHERE comment.article_id = OLD.id AND comment.is_reply = FALSE;

    DELETE FROM article_image
    WHERE article_image.article_id = OLD.id;

    RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER before_permanent_delete_article 
    BEFORE DELETE ON article
    FOR EACH ROW
    EXECUTE PROCEDURE before_permanent_delete_article();


CREATE FUNCTION after_permanent_delete_article()
RETURNS TRIGGER AS
$BODY$
BEGIN
    DELETE FROM content_item
    WHERE content_item.id = OLD.id;

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER after_permanent_delete_article
    AFTER DELETE ON article
    FOR EACH ROW
    EXECUTE PROCEDURE after_permanent_delete_article();


CREATE FUNCTION before_delete_comment()
RETURNS TRIGGER AS
$BODY$
BEGIN
    DELETE FROM vote
    WHERE vote.content_item_id = OLD.id;

    IF (OLD.is_reply = FALSE) THEN
        DELETE FROM comment
        WHERE comment.reply_id = OLD.id AND comment.is_reply = TRUE;
    END IF;

    RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER before_delete_comment 
    BEFORE DELETE ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE before_delete_comment();


CREATE FUNCTION after_delete_comment()
RETURNS TRIGGER AS
$BODY$
BEGIN
    DELETE FROM content_item
    WHERE content_item.id = OLD.id;

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER after_delete_comment
    AFTER DELETE ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE after_delete_comment();


CREATE FUNCTION ban_tag()
RETURNS TRIGGER AS
$BODY$
BEGIN
    DELETE FROM tag_article
    WHERE tag_article.tag_id = OLD.id;

    DELETE FROM follow_tag
    WHERE follow_tag.tag_id = OLD.id;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER ban_tag 
    BEFORE UPDATE OF is_banned ON tag
    FOR EACH ROW
    WHEN (OLD.is_banned = false AND NEW.is_banned = true)
    EXECUTE PROCEDURE ban_tag();


CREATE FUNCTION edit_content_notification()
RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
BEGIN
    IF (NEW.author_id = (SELECT author_id FROM content_item WHERE content_item.id = NEW.content_item_id)) THEN
        RETURN NULL;
    END IF;

    INSERT INTO notification (notified_id)
    VALUES ((SELECT author_id FROM content_item WHERE content_item.id = NEW.content_item_id))
    RETURNING id INTO notification_id;

    INSERT INTO content_notification (id, content_item_id)
    VALUES (notification_id, NEW.content_item_id);
    
    INSERT INTO edit_notification (id)
    VALUES (notification_id);

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER edit_content_notification 
    AFTER INSERT ON edit
    FOR EACH ROW
    WHEN (NEW.altered_field != 'is_deleted')
    EXECUTE FUNCTION edit_content_notification(); 


CREATE FUNCTION remove_topic()
RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE article 
    SET topic_id = 0
    WHERE topic_id = OLD.id;

    RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER remove_topic 
    BEFORE DELETE ON topic
    FOR EACH ROW
    EXECUTE PROCEDURE remove_topic();


CREATE FUNCTION generate_undefined_topic_notification(author_id INT, article_id INT)
RETURNS VOID AS
$BODY$
DECLARE
    notification_id INTEGER;
BEGIN
    INSERT INTO notification (notified_id)
    VALUES (author_id)
    RETURNING id INTO notification_id;

    INSERT INTO content_notification (id, content_item_id)
    VALUES (notification_id, article_id);

    INSERT INTO undefined_topic_notification (id)
    VALUES (notification_id);

    RETURN;
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION notify_undefined_topic()
RETURNS TRIGGER AS
$BODY$
DECLARE
    author_id INTEGER;
BEGIN
    SELECT content_item.author_id, content_item.id
    FROM content_item
    WHERE content_item.id = OLD.id
    INTO author_id;

    EXECUTE generate_undefined_topic_notification(author_id, OLD.id);

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER notify_undefined_topic 
    AFTER UPDATE OF topic_id ON article
    FOR EACH ROW
    WHEN (OLD.topic_id != 0 AND NEW.topic_id = 0)
    EXECUTE FUNCTION notify_undefined_topic();


CREATE FUNCTION vote_for_own_content()
RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.member_id = (SELECT author_id FROM content_item WHERE content_item.id = NEW.content_item_id)) THEN
        RAISE EXCEPTION 'Members cannot vote on their own content';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER vote_for_own_content
    BEFORE INSERT ON vote
    FOR EACH ROW
    EXECUTE FUNCTION vote_for_own_content();


CREATE FUNCTION news_article_tags()
RETURNS TRIGGER AS
$BODY$
BEGIN
    IF ((6 < (SELECT COUNT(*) FROM tag_article WHERE tag_article.article_id = NEW.article_id))) THEN 
        RAISE EXCEPTION 'Cannot add more than 6 tags to a news article';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER news_article_tags
    BEFORE INSERT ON tag_article
    FOR EACH ROW
    EXECUTE PROCEDURE news_article_tags();


CREATE FUNCTION blocked_user_appeal()
RETURNS TRIGGER AS
$BODY$
BEGIN
    IF ((SELECT COUNT(*) FROM appeal WHERE appeal.submitter_id = NEW.submitter_id) > 0) THEN
        RAISE EXCEPTION 'Cannot appeal more than one time';
    END IF;

    IF ((SELECT is_blocked FROM member WHERE member.id = NEW.submitter_id) = false) THEN
        RAISE EXCEPTION 'Cannot appeal if not blocked';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER blocked_user_appeal
    BEFORE INSERT ON appeal
    FOR EACH ROW
    EXECUTE PROCEDURE blocked_user_appeal();


CREATE FUNCTION topic_control()
RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO TOPIC (name)
    VALUES (NEW.name);

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER topic_control
    AFTER UPDATE OF status ON topic_suggestion
    FOR EACH ROW
    WHEN (OLD.status = 'Pending' AND NEW.status = 'Accepted')
    EXECUTE PROCEDURE topic_control();


CREATE FUNCTION comment_date_validation()
RETURNS TRIGGER AS
$BODY$
BEGIN
    IF ((SELECT date_time FROM content_item WHERE content_item.id = NEW.id) < (SELECT date_time FROM content_item WHERE content_item.id = NEW.article_id)) THEN
        RAISE EXCEPTION 'Comment date cannot precede article date';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER comment_date_validation
    BEFORE INSERT ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE comment_date_validation();


CREATE FUNCTION comment_reply_validation()
RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (
        NEW.is_reply = TRUE
        AND 
        (SELECT date_time FROM content_item WHERE content_item.id = NEW.reply_id)
        > 
        (SELECT date_time FROM content_item WHERE content_item.id = NEW.id)
    ) THEN
        RAISE EXCEPTION 'Reply date cannot precede comment date';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER comment_reply_validation
    BEFORE INSERT ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE comment_reply_validation();


CREATE FUNCTION reply_to_comment_validation()
RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (
        NEW.is_reply = TRUE
        AND 
        (SELECT is_reply FROM comment WHERE comment.id = NEW.reply_id) = TRUE
    ) THEN
        RAISE EXCEPTION 'Cannot reply to a reply';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER reply_to_comment_validation
    BEFORE INSERT ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE reply_to_comment_validation();


CREATE FUNCTION report_self()
RETURNS TRIGGER AS
$BODY$
BEGIN
    IF ((SELECT submitter_id FROM report WHERE report.id = NEW.id) = NEW.member_id) THEN
        RAISE EXCEPTION 'A member is not allowed to report themselves';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER report_self
    BEFORE INSERT ON member_report
    FOR EACH ROW
    EXECUTE PROCEDURE report_self();


CREATE FUNCTION report_self_content()
RETURNS TRIGGER AS
$BODY$
BEGIN
    IF ((SELECT submitter_id FROM report WHERE report.id = NEW.id) = (SELECT author_id FROM content_item WHERE content_item.id = NEW.content_item_id)) THEN
        RAISE EXCEPTION 'A member is not allowed to report their own content';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER report_self_content
    BEFORE INSERT ON content_report
    FOR EACH ROW
    EXECUTE PROCEDURE report_self_content();


CREATE FUNCTION generate_block_notification()
RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
BEGIN
    INSERT INTO notification (notified_id)
    VALUES (NEW.id)
    RETURNING id INTO notification_id;

    INSERT INTO block_notification (id)
    VALUES (notification_id);

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER generate_block_notification
    AFTER UPDATE OF is_blocked ON member
    FOR EACH ROW
    WHEN (OLD.is_blocked = false AND NEW.is_blocked = true)
    EXECUTE PROCEDURE generate_block_notification();


CREATE FUNCTION generate_follow_notification()
RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
BEGIN
    INSERT INTO notification (notified_id)
    VALUES (NEW.followed_id)
    RETURNING id INTO notification_id;

    INSERT INTO follow_notification (id, follower_id)
    VALUES (notification_id, NEW.follower_id);

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER generate_follow_notification
    AFTER INSERT ON follow_member
    FOR EACH ROW
    EXECUTE PROCEDURE generate_follow_notification();


CREATE FUNCTION generate_comment_notification()
RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
BEGIN
    IF ((SELECT author_id FROM content_item WHERE content_item.id = NEW.id) = (SELECT author_id FROM content_item WHERE content_item.id = NEW.article_id)) THEN
        RETURN NULL;
    END IF;

    INSERT INTO notification (notified_id)
    VALUES ((SELECT author_id FROM content_item WHERE content_item.id = NEW.article_id))
    RETURNING id INTO notification_id;

    INSERT INTO comment_notification (id, comment_id)
    VALUES (notification_id, NEW.id);

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER generate_comment_notification
    AFTER INSERT ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE generate_comment_notification();


CREATE FUNCTION update_content_item_academy_score()
RETURNS TRIGGER AS
$BODY$
DECLARE
    total_score INTEGER;
    vote_print vote;
    count INTEGER;
BEGIN
    IF (TG_OP = 'INSERT' OR TG_OP = 'UPDATE') THEN
        SELECT SUM(weight) INTO total_score
        FROM vote
        WHERE vote.content_item_id = NEW.content_item_id;
    ELSIF (TG_OP = 'DELETE') THEN
        SELECT SUM(weight) INTO total_score
        FROM vote
        WHERE content_item_id = OLD.content_item_id;
    END IF;

    UPDATE content_item
    SET academy_score = COALESCE(total_score, 0)
    WHERE content_item.id = COALESCE(NEW.content_item_id, OLD.content_item_id);

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_content_item_academy_score
    AFTER INSERT OR DELETE OR UPDATE ON vote
    FOR EACH ROW
    EXECUTE PROCEDURE update_content_item_academy_score();


CREATE FUNCTION update_member_academy_score()
RETURNS TRIGGER AS
$BODY$
DECLARE
    total_score INT;
BEGIN
    SELECT SUM(academy_score) INTO total_score
    FROM content_item
    WHERE author_id = NEW.author_id AND is_deleted = FALSE;

    UPDATE member
    SET academy_score = total_score
    WHERE member.id = NEW.author_id;

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_member_academy_score
    AFTER UPDATE OF academy_score ON content_item
    FOR EACH ROW
    EXECUTE PROCEDURE update_member_academy_score();

CREATE FUNCTION generate_removal_notification()
RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
BEGIN

    IF (NEW.author_id = (SELECT author_id FROM content_item WHERE content_item.id = NEW.content_item_id)) THEN
        RETURN NULL;
    END IF;

    INSERT INTO notification (notified_id)
    VALUES ((SELECT author_id FROM content_item WHERE content_item.id = NEW.content_item_id))
    RETURNING id INTO notification_id;

    INSERT INTO content_notification (id, content_item_id)
    VALUES (notification_id, NEW.content_item_id);

    INSERT INTO removal_notification (id)
    VALUES (notification_id);

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
    
CREATE TRIGGER generate_removal_notification
    AFTER INSERT ON edit
    FOR EACH ROW
    WHEN (NEW.altered_field = 'is_deleted' AND NEW.old_value = 'FALSE' AND NEW.new_value = 'TRUE')
    EXECUTE PROCEDURE generate_removal_notification();

CREATE FUNCTION email_lowercase()
RETURNS TRIGGER AS
$BODY$
BEGIN
    NEW.email = LOWER(NEW.email);	
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER email_lowercase
    BEFORE INSERT OR UPDATE ON member
    FOR EACH ROW
    EXECUTE PROCEDURE email_lowercase();


CREATE FUNCTION tag_lowercase()
RETURNS TRIGGER AS
$BODY$
BEGIN
    NEW.name = LOWER(NEW.name);
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tag_lowercase
    BEFORE INSERT OR UPDATE ON tag
    FOR EACH ROW
    EXECUTE PROCEDURE tag_lowercase();


-----------------------------------------
-- Indexes
-----------------------------------------

-- Performance Indexes

CREATE INDEX content_item_date ON content_item USING btree (date_time);
CREATE INDEX content_item_academy_score ON content_item USING btree (academy_score);
CREATE INDEX article_topic ON article USING hash (topic_id);


-- Full-text Search Indexes

ALTER TABLE content_item
ADD COLUMN tsvectors tsvector;

CREATE FUNCTION content_item_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        IF (NEW.id IN (SELECT id FROM article)) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', (SELECT title FROM article WHERE article.id = NEW.id)), 'A') ||
                setweight(to_tsvector('english', NEW.body), 'B')
            );
        ELSE
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.body), 'B')
            );
        END IF;
 END IF;
 
 IF TG_OP = 'UPDATE' THEN
        IF (NEW.id IN (SELECT id FROM article)) THEN
            IF (NEW.body <> OLD.body) THEN
                NEW.tsvectors = (
                    setweight(to_tsvector('english', (SELECT title FROM article WHERE article.id = NEW.id)), 'A') ||
                    setweight(to_tsvector('english', NEW.body), 'B')
                );
            END IF;
        ELSE
            IF (NEW.body <> OLD.body) THEN
                NEW.tsvectors = (
                    setweight(to_tsvector('english', NEW.body), 'B')
                );
            END IF;
        END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER content_item_search_update
BEFORE INSERT OR UPDATE ON content_item
FOR EACH ROW
EXECUTE PROCEDURE content_item_search_update();


CREATE FUNCTION article_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        IF (NEW.id IN (SELECT id FROM content_item)) THEN
            UPDATE content_item
            SET tsvectors = (
                setweight(to_tsvector('english', NEW.title), 'A') ||
                setweight(to_tsvector('english', (SELECT body FROM content_item WHERE content_item.id = NEW.id)), 'B')
            )
            WHERE content_item.id = NEW.id;
        END IF;
 END IF;

 IF TG_OP = 'UPDATE' THEN
        IF (NEW.id IN (SELECT id FROM content_item)) THEN
            IF (NEW.title <> OLD.title) THEN
                UPDATE content_item
                SET tsvectors = (
                    setweight(to_tsvector('english', NEW.title), 'A') ||
                    setweight(to_tsvector('english', (SELECT body FROM content_item WHERE content_item.id = NEW.id)), 'B')
                )
                WHERE content_item.id = NEW.id;
            END IF;
        END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER article_search_update
BEFORE INSERT OR UPDATE ON article
FOR EACH ROW
EXECUTE PROCEDURE article_search_update();


CREATE INDEX content_item_search ON content_item USING GIST (tsvectors);

ALTER TABLE member 
ADD COLUMN tsvectors tsvector;

CREATE FUNCTION member_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.username), 'A') ||
            setweight(to_tsvector('english', NEW.first_name), 'B') ||
            setweight(to_tsvector('english', NEW.last_name), 'B')
        );
 END IF;
 
 IF TG_OP = 'UPDATE' THEN
        IF (NEW.username <> OLD.username) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.username), 'A') ||
                setweight(to_tsvector('english', NEW.first_name), 'B') ||
                setweight(to_tsvector('english', NEW.last_name), 'B')
            );
        END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER member_search_update
BEFORE INSERT OR UPDATE ON member
FOR EACH ROW
EXECUTE PROCEDURE member_search_update();


CREATE FUNCTION delete_appeals() RETURNS TRIGGER AS $$
BEGIN
    DELETE FROM appeal
    WHERE appeal.submitter_id = OLD.id;

    RETURN NULL;
END $$
LANGUAGE plpgsql;


CREATE TRIGGER delete_appeals
AFTER UPDATE OF is_blocked ON member
FOR EACH ROW
WHEN (OLD.is_blocked = TRUE AND NEW.is_blocked = FALSE)
EXECUTE PROCEDURE delete_appeals();

CREATE INDEX member_search ON member USING GIN (tsvectors);


CREATE FUNCTION create_imdb_info(query_type VARCHAR(255), imdb_info_id VARCHAR(255))
RETURNS VOID AS
$BODY$
BEGIN
    INSERT INTO imdb_info (query_type, imdb_info_id)
    VALUES (query_type, imdb_info_id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_profile_image(file_name VARCHAR(255))
RETURNS VOID AS
$BODY$
BEGIN
    INSERT INTO profile_image (file_name)
    VALUES (file_name);
END
$BODY$
LANGUAGE plpgsql;


CREATE FUNCTION create_member(email VARCHAR(255), password VARCHAR(255), username VARCHAR(255), first_name VARCHAR(255), last_name VARCHAR(255), biography TEXT, profile_image_id INTEGER)
RETURNS INTEGER AS
$BODY$
DECLARE
    member_id INTEGER;
BEGIN
    INSERT INTO member (email, password, username, first_name, last_name, biography, profile_image_id)
    VALUES (email, password, username, first_name, last_name, biography, profile_image_id)
    RETURNING id INTO member_id;

    RETURN member_id;
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_admin(email VARCHAR(255), password VARCHAR(255), username VARCHAR(255), first_name VARCHAR(255), last_name VARCHAR(255), biography TEXT, profile_image_id INTEGER)
RETURNS VOID AS
$BODY$
DEClARE
    member_id INTEGER;
BEGIN
    member_id := create_member(email, password, username, first_name, last_name, biography, profile_image_id);
    UPDATE member
    SET is_admin = TRUE
    WHERE id = member_id;
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_appeal(body VARCHAR(255), submitter_id INTEGER)
RETURNS VOID AS
$BODY$
BEGIN
    INSERT INTO appeal (body, submitter_id)
    VALUES (body, submitter_id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_follow_member(follower_id INTEGER, followed_id INTEGER)
RETURNS VOID AS
$BODY$
BEGIN
    INSERT INTO follow_member (follower_id, followed_id)
    VALUES (follower_id, followed_id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_topic(name VARCHAR(255))
RETURNS VOID AS
$BODY$
BEGIN
    INSERT INTO topic (name)
    VALUES (name);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_topic_suggestion(name VARCHAR(255), status statuses, suggester_id INTEGER)
RETURNS VOID AS
$BODY$
BEGIN
    INSERT INTO topic_suggestion (name, status, suggester_id)
    VALUES (name, status, suggester_id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_content_item(body TEXT, author_id INTEGER)
RETURNS INTEGER AS
$BODY$
DECLARE
    content_item_id INTEGER;    
BEGIN
    INSERT INTO content_item (body, author_id)
    VALUES (body, author_id)
    RETURNING id INTO content_item_id;

    RETURN content_item_id;
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_article(title VARCHAR(255), body TEXT, author_id INTEGER, topic_id INTEGER, imdb_info_id INTEGER)
RETURNS VOID AS
$BODY$
DECLARE
    id INTEGER;
BEGIN
    id := create_content_item(body, author_id);

    INSERT INTO article (id, title, topic_id, imdb_info_id)
    VALUES (id, title, topic_id, imdb_info_id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_article_image(file_name VARCHAR(255), article_id INTEGER)
RETURNS VOID AS
$BODY$
BEGIN
    INSERT INTO article_image (file_name, article_id)
    VALUES (file_name, article_id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_tag(name VARCHAR(255))
RETURNS VOID AS
$BODY$
BEGIN
    INSERT INTO tag (name)
    VALUES (name);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_tag_article(tag_id INTEGER, article_id INTEGER)
RETURNS VOID AS
$BODY$
BEGIN
    INSERT INTO tag_article (tag_id, article_id)
    VALUES (tag_id, article_id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_follow_tag(tag_id INTEGER, member_id INTEGER)
RETURNS VOID AS
$BODY$
BEGIN
    INSERT INTO follow_tag (tag_id, member_id)
    VALUES (tag_id, member_id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_comment(body TEXT, author_id INTEGER, is_reply BOOLEAN, article_id INTEGER, reply_id INTEGER)
RETURNS VOID AS
$BODY$
DECLARE
    id INTEGER;
BEGIN
    id := create_content_item(body, author_id);
    INSERT INTO comment (id, is_reply, article_id, reply_id)
    VALUES (id, is_reply, article_id, reply_id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_edit(altered_field VARCHAR(255), old_value TEXT, new_value TEXT, content_item_id INTEGER, author_id INTEGER)
RETURNS VOID AS
$BODY$
BEGIN
    INSERT INTO edit (altered_field, old_value, new_value, content_item_id, author_id)
    VALUES (altered_field, old_value, new_value, content_item_id, author_id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_vote(member_id INTEGER, content_item_id INTEGER, weight INTEGER)
RETURNS VOID AS
$BODY$
BEGIN
    INSERT INTO vote (member_id, content_item_id, weight)
    VALUES (member_id, content_item_id, weight);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_report(body TEXT, submitter_id INTEGER)
RETURNS INTEGER AS
$BODY$
DECLARE
    report_id INTEGER;
BEGIN
    INSERT INTO report (body, submitter_id)
    VALUES (body, submitter_id)
    RETURNING id INTO report_id;

    RETURN report_id;
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_content_report(body TEXT, submitter_id INTEGER, motive motives, content_item_id INTEGER)
RETURNS VOID AS
$BODY$
DECLARE
    id INTEGER;
BEGIN
    id := create_report(body, submitter_id);
    INSERT INTO content_report (id, motive, content_item_id)
    VALUES (id, motive, content_item_id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_member_report(body TEXT, submitter_id INTEGER, misconduct misconduct_types, member_id INTEGER)
RETURNS VOID AS
$BODY$
DECLARE 
    id INTEGER;
BEGIN
    id := create_report(body, submitter_id);
    INSERT INTO member_report (id, misconduct, member_id)
    VALUES (id, misconduct, member_id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_notification(notified_id INTEGER)
RETURNS INTEGER AS
$BODY$
DECLARE
    notification_id INTEGER;
BEGIN
    INSERT INTO notification (notified_id)
    VALUES (notified_id)
    RETURNING id INTO notification_id;

    RETURN notification_id;
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_comment_notification(notified_id INTEGER, comment_id INTEGER)
RETURNS VOID AS
$BODY$
DECLARE
    id INTEGER;
BEGIN
    id := create_notification(comment_id);
    INSERT INTO comment_notification (id, comment_id)
    VALUES (id, comment_id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_content_notification(notified_id INTEGER, content_item_id INTEGER)
RETURNS INTEGER AS
$BODY$
DECLARE
    id INTEGER;
BEGIN
    id := create_notification(notified_id);
    INSERT INTO content_notification (id, content_item_id)
    VALUES (id, content_item_id);

    RETURN id;
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_upvote_notification(notified_id INTEGER, content_item_id INTEGER, amount INTEGER)
RETURNS VOID AS
$BODY$
DECLARE
    id INTEGER;
BEGIN
    id := create_content_notification(notified_id, content_item_id);
    INSERT INTO upvote_notification (id, amount)
    VALUES (id, amount);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_edit_notification(notified_id INTEGER, content_item_id INTEGER)
RETURNS VOID AS
$BODY$
DECLARE
    id INTEGER;
BEGIN
    id := create_content_notification(notified_id, content_item_id);
    INSERT INTO edit_notification (id)
    VALUES (id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_removal_notification(notified_id INTEGER, content_item_id INTEGER)
RETURNS VOID AS
$BODY$
DECLARE
    id INTEGER;
BEGIN
    id := create_content_notification(notified_id, content_item_id);
    INSERT INTO removal_notification (id)
    VALUES (id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_undefined_topic_notification(notified_id INTEGER, content_item_id INTEGER)
RETURNS VOID AS
$BODY$
DECLARE
    id INTEGER;
BEGIN
    id := create_content_notification(notified_id, content_item_id);
    INSERT INTO undefined_topic_notification (id)
    VALUES (id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_block_notification(notified_id INTEGER)
RETURNS VOID AS
$BODY$
DECLARE
    id INTEGER;
BEGIN
    id := create_notification(notified_id);
    INSERT INTO block_notification (id)
    VALUES (id);
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION create_follow_notification(notified_id INTEGER, follower_id INTEGER)
RETURNS VOID AS
$BODY$
DECLARE
    id INTEGER;
BEGIN
    id := create_notification(notified_id);
    INSERT INTO follow_notification (id, follower_id)
    VALUES (id, follower_id);
END
$BODY$
LANGUAGE plpgsql;

SELECT create_imdb_info('movie', 'tt0111161');
SELECT create_imdb_info('movie', 'tt0068646');
SELECT create_imdb_info('name', 'tt0071562');

INSERT INTO profile_image (id, file_name) VALUES (0, 'default.png');
SELECT create_profile_image('harveyspecter.png');
SELECT create_profile_image('donnapaulsen.png');
SELECT create_profile_image('louislitt.png');
SELECT create_profile_image('jessicapearson.png');
SELECT create_profile_image('dannascott.png');

SELECT create_admin('harvey.specter@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'harveyspecter', 'Harvey', 'Specter', 'When you are backed against the wall, break the goddamn thing down.', 1);
SELECT create_member('mike.ross@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'mikeross', 'Mike', 'Ross', 'The Rules Dictate That You Must Be Precise As The Law Is A Precise Endeavor.', 0);
SELECT create_member('donna.paulsen@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'donnapaulsen', 'Donna', 'Paulsen', 'I am too busy being a badass and worrying about my hair', 2);
SELECT create_member('louis.litt@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'louislitt', 'Louis', 'Litt', 'Mud and cats... mud and cats...', 3);
SELECT create_admin('jessica.pearson@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'jessicapearson', 'Jessica', 'Pearson', 'I deal with children on a daily basis.', 4);
SELECT create_member('harold.jakowski@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'haroldjakowski', 'Harold', 'Jakowski', NULL, 0);
SELECT create_member('rachel.zane@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'rachelzane', 'Rachel', 'Zane', 'Law school is for boring people. We make history.', 0);
SELECT create_member('alex.williams@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'alexwilliams', 'Alex', 'Williams', 'Winners don''t make excuses when the other side plays the game.', 0);
SELECT create_member('daniel.hardman@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'danielhardman', 'Daniel', 'Hardman', 'Power is like real estate. It''s all about location, location, location. And your location is hell.', 0);
SELECT create_member('sheila.sazs@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'sheilasazs', 'Sheila', 'Sazs', 'Sometimes the good ones, they like a little bit of bad.', 0);
SELECT create_member('benjamin.cahill@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'benjamincahill', 'Benjamin', 'Cahill', 'You don''t send a puppy to clean up its own mess.', 0);
SELECT create_member('gretchen.bodinski@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'gretchenbodinski', 'Gretchen', 'Bodinski', 'I don''t need to explain myself to you. And I won''t.', 0);
SELECT create_member('logan.sanders@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'logansanders', 'Logan', 'Sanders', 'Get on the train, or get off the tracks.', 0);
SELECT create_member('samantha.wheeler@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'samanthawheeler', 'Samantha', 'Wheeler', 'Sometimes you have to get dirty to get what you want.', 0);
SELECT create_member('karen.cahill@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'karencahill', 'Karen', 'Cahill', 'I''m not interested in great. I want to know who its daddy is.', 0);
SELECT create_member('travis.tanner@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'travistanner', 'Travis', 'Tanner', 'I could run this place better than you. And in heels.', 0);
SELECT create_member('dannas.scott@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'danascott', 'Danna', 'Scott', 'Sometimes the law works for you, and sometimes against you. But you always want it to be on your side.', 5);
SELECT create_member('stefan.kaczmarek@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'stefankaczmarek', 'Stefan', 'Kaczmarek', 'Don''t raise your voice, improve your argument.', 0);
SELECT create_member('raymond.cohen@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'raymondcohen', 'Raymond', 'Cohen', 'Win a no-win situation by rewriting the rules.', 0);
SELECT create_member('gina.torres@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'ginatorres', 'Gina', 'Torres', 'Success is a war, not a battle.', 0);
SELECT create_member('robert.zane@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'robertzane', 'Robert', 'Zane', 'The best way to gain someone''s trust is to give trust.', 0);
SELECT create_member('jenny.griffith@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'jennygriffith', 'Jenny', 'Griffith', 'Winners are people who are willing to lose.', 0);
SELECT create_member('oliver.grady@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'olivergrady', 'Oliver', 'Grady', 'The law isn''t about being fair, it''s about winning.', 0);
SELECT create_member('henry.gerrard@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'henrygerrard', 'Henry', 'Gerrard', 'If you want to gain something, you have to take something.', 0);
SELECT create_member('derrick.wells@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'derrickwells', 'Derrick', 'Wells', 'People don''t remember what you say; they remember how you make them feel.', 0);
SELECT create_member('helen.preston@popcornpost.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'helenpreston', 'Helen', 'Preston', 'In the legal world, reputation is everything.', 0);

SELECT create_follow_member(1, 2);
SELECT create_follow_member(1, 3);
SELECT create_follow_member(1, 5);
SELECT create_follow_member(1, 8);
SELECT create_follow_member(1, 10);
SELECT create_follow_member(1, 11);
SELECT create_follow_member(1, 12);
SELECT create_follow_member(1, 15);
SELECT create_follow_member(1, 18);
SELECT create_follow_member(1, 20);
SELECT create_follow_member(2, 1);
SELECT create_follow_member(2, 3);
SELECT create_follow_member(2, 5);
SELECT create_follow_member(2, 7);
SELECT create_follow_member(2, 9);
SELECT create_follow_member(2, 11);
SELECT create_follow_member(2, 13);
SELECT create_follow_member(2, 15);
SELECT create_follow_member(2, 17);
SELECT create_follow_member(2, 19);
SELECT create_follow_member(3, 1);
SELECT create_follow_member(3, 2);
SELECT create_follow_member(3, 4);
SELECT create_follow_member(3, 6);
SELECT create_follow_member(3, 8);
SELECT create_follow_member(3, 10);
SELECT create_follow_member(3, 12);
SELECT create_follow_member(3, 14);
SELECT create_follow_member(3, 16);
SELECT create_follow_member(3, 18);
SELECT create_follow_member(4, 1);
SELECT create_follow_member(4, 2);
SELECT create_follow_member(4, 3);
SELECT create_follow_member(4, 5);
SELECT create_follow_member(4, 7);
SELECT create_follow_member(4, 9);
SELECT create_follow_member(4, 11);
SELECT create_follow_member(4, 13);
SELECT create_follow_member(4, 15);
SELECT create_follow_member(4, 17);
SELECT create_follow_member(5, 1);
SELECT create_follow_member(5, 2);
SELECT create_follow_member(5, 3);
SELECT create_follow_member(5, 4);
SELECT create_follow_member(5, 6);
SELECT create_follow_member(5, 8);
SELECT create_follow_member(5, 10);
SELECT create_follow_member(5, 12);
SELECT create_follow_member(5, 14);
SELECT create_follow_member(5, 16);
SELECT create_follow_member(6, 2);
SELECT create_follow_member(6, 3);
SELECT create_follow_member(6, 4);
SELECT create_follow_member(6, 7);
SELECT create_follow_member(6, 9);
SELECT create_follow_member(6, 11);
SELECT create_follow_member(6, 13);
SELECT create_follow_member(6, 15);
SELECT create_follow_member(6, 17);
SELECT create_follow_member(6, 19);
SELECT create_follow_member(7, 1);
SELECT create_follow_member(7, 2);
SELECT create_follow_member(7, 3);
SELECT create_follow_member(7, 4);
SELECT create_follow_member(7, 6);
SELECT create_follow_member(7, 8);
SELECT create_follow_member(7, 10);
SELECT create_follow_member(7, 12);
SELECT create_follow_member(7, 14);
SELECT create_follow_member(7, 16);
SELECT create_follow_member(8, 1);
SELECT create_follow_member(8, 2);
SELECT create_follow_member(8, 3);
SELECT create_follow_member(8, 4);
SELECT create_follow_member(8, 6);
SELECT create_follow_member(8, 7);
SELECT create_follow_member(8, 9);
SELECT create_follow_member(8, 11);
SELECT create_follow_member(8, 13);
SELECT create_follow_member(8, 15);
SELECT create_follow_member(9, 1);
SELECT create_follow_member(9, 2);
SELECT create_follow_member(9, 3);
SELECT create_follow_member(9, 4);
SELECT create_follow_member(9, 6);
SELECT create_follow_member(9, 8);
SELECT create_follow_member(9, 10);
SELECT create_follow_member(9, 12);
SELECT create_follow_member(9, 14);
SELECT create_follow_member(9, 16);
SELECT create_follow_member(10, 1);
SELECT create_follow_member(10, 2);
SELECT create_follow_member(10, 3);
SELECT create_follow_member(10, 4);
SELECT create_follow_member(10, 6);
SELECT create_follow_member(10, 8);
SELECT create_follow_member(10, 9);
SELECT create_follow_member(10, 11);
SELECT create_follow_member(10, 13);
SELECT create_follow_member(10, 15);
SELECT create_follow_member(11, 1);
SELECT create_follow_member(11, 2);
SELECT create_follow_member(11, 3);
SELECT create_follow_member(11, 4);
SELECT create_follow_member(11, 6);
SELECT create_follow_member(11, 8);
SELECT create_follow_member(11, 10);
SELECT create_follow_member(11, 12);
SELECT create_follow_member(11, 14);
SELECT create_follow_member(11, 16);
SELECT create_follow_member(12, 1);
SELECT create_follow_member(12, 2);
SELECT create_follow_member(12, 3);
SELECT create_follow_member(12, 4);
SELECT create_follow_member(12, 6);
SELECT create_follow_member(12, 8);
SELECT create_follow_member(12, 10);
SELECT create_follow_member(12, 11);
SELECT create_follow_member(12, 13);
SELECT create_follow_member(12, 15);
SELECT create_follow_member(13, 1);
SELECT create_follow_member(13, 2);
SELECT create_follow_member(13, 3);
SELECT create_follow_member(13, 4);
SELECT create_follow_member(13, 6);
SELECT create_follow_member(13, 8);
SELECT create_follow_member(13, 10);
SELECT create_follow_member(13, 11);
SELECT create_follow_member(13, 12);
SELECT create_follow_member(13, 16);
SELECT create_follow_member(14, 1);
SELECT create_follow_member(14, 2);
SELECT create_follow_member(14, 3);
SELECT create_follow_member(14, 4);
SELECT create_follow_member(14, 6);
SELECT create_follow_member(14, 8);
SELECT create_follow_member(14, 10);
SELECT create_follow_member(14, 11);
SELECT create_follow_member(14, 13);
SELECT create_follow_member(14, 15);
SELECT create_follow_member(15, 1);
SELECT create_follow_member(15, 2);
SELECT create_follow_member(15, 3);
SELECT create_follow_member(15, 4);
SELECT create_follow_member(15, 6);
SELECT create_follow_member(15, 8);
SELECT create_follow_member(15, 10);
SELECT create_follow_member(15, 11);
SELECT create_follow_member(15, 13);
SELECT create_follow_member(15, 14);
SELECT create_follow_member(16, 1);
SELECT create_follow_member(16, 2);
SELECT create_follow_member(16, 3);
SELECT create_follow_member(16, 4);
SELECT create_follow_member(16, 6);
SELECT create_follow_member(16, 8);
SELECT create_follow_member(16, 10);
SELECT create_follow_member(16, 11);
SELECT create_follow_member(16, 13);
SELECT create_follow_member(16, 15);
SELECT create_follow_member(17, 1);
SELECT create_follow_member(17, 2);
SELECT create_follow_member(17, 3);
SELECT create_follow_member(17, 4);
SELECT create_follow_member(17, 6);
SELECT create_follow_member(17, 8);
SELECT create_follow_member(17, 10);
SELECT create_follow_member(17, 11);
SELECT create_follow_member(17, 13);
SELECT create_follow_member(17, 15);
SELECT create_follow_member(18, 1);
SELECT create_follow_member(18, 2);
SELECT create_follow_member(18, 3);
SELECT create_follow_member(18, 4);
SELECT create_follow_member(18, 6);
SELECT create_follow_member(18, 8);
SELECT create_follow_member(18, 10);
SELECT create_follow_member(18, 11);
SELECT create_follow_member(18, 13);
SELECT create_follow_member(18, 15);
SELECT create_follow_member(19, 1);
SELECT create_follow_member(19, 2);
SELECT create_follow_member(19, 3);
SELECT create_follow_member(19, 4);
SELECT create_follow_member(19, 6);
SELECT create_follow_member(19, 8);
SELECT create_follow_member(19, 10);
SELECT create_follow_member(19, 11);
SELECT create_follow_member(19, 13);
SELECT create_follow_member(19, 15);
SELECT create_follow_member(20, 1);
SELECT create_follow_member(20, 2);
SELECT create_follow_member(20, 3);
SELECT create_follow_member(20, 4);
SELECT create_follow_member(20, 6);
SELECT create_follow_member(20, 8);
SELECT create_follow_member(20, 10);
SELECT create_follow_member(20, 11);
SELECT create_follow_member(20, 13);
SELECT create_follow_member(20, 15);
SELECT create_follow_member(21, 1);
SELECT create_follow_member(21, 2);
SELECT create_follow_member(21, 3);
SELECT create_follow_member(21, 4);
SELECT create_follow_member(21, 6);
SELECT create_follow_member(21, 8);
SELECT create_follow_member(21, 10);
SELECT create_follow_member(21, 11);
SELECT create_follow_member(21, 13);
SELECT create_follow_member(21, 15);
SELECT create_follow_member(22, 1);
SELECT create_follow_member(22, 2);
SELECT create_follow_member(22, 3);
SELECT create_follow_member(22, 4);
SELECT create_follow_member(22, 6);
SELECT create_follow_member(22, 8);
SELECT create_follow_member(22, 10);
SELECT create_follow_member(22, 11);
SELECT create_follow_member(22, 13);
SELECT create_follow_member(22, 15);
SELECT create_follow_member(23, 1);
SELECT create_follow_member(23, 2);
SELECT create_follow_member(23, 3);
SELECT create_follow_member(23, 4);
SELECT create_follow_member(23, 6);
SELECT create_follow_member(23, 8);
SELECT create_follow_member(23, 10);
SELECT create_follow_member(23, 11);
SELECT create_follow_member(23, 13);
SELECT create_follow_member(23, 15);
SELECT create_follow_member(24, 1);
SELECT create_follow_member(24, 2);
SELECT create_follow_member(24, 3);
SELECT create_follow_member(24, 4);
SELECT create_follow_member(24, 6);
SELECT create_follow_member(24, 8);
SELECT create_follow_member(24, 10);
SELECT create_follow_member(24, 11);
SELECT create_follow_member(24, 13);
SELECT create_follow_member(24, 15);
SELECT create_follow_member(25, 1);
SELECT create_follow_member(25, 2);
SELECT create_follow_member(25, 3);
SELECT create_follow_member(25, 4);
SELECT create_follow_member(25, 6);
SELECT create_follow_member(25, 8);
SELECT create_follow_member(25, 10);
SELECT create_follow_member(25, 11);
SELECT create_follow_member(25, 13);
SELECT create_follow_member(25, 15);


INSERT INTO topic (id, name) VALUES (0, 'Undefined');
SELECT create_topic('Review');
SELECT create_topic('Leaks');
SELECT create_topic('Interview');
SELECT create_topic('Analysis');
SELECT create_topic('Announcement');

SELECT create_topic_suggestion('Awards', 'Pending', 1);
SELECT create_topic_suggestion('Rumors', 'Pending', 3);
SELECT create_topic_suggestion('Drama', 'Rejected', 4);
SELECT create_topic_suggestion('Artistry', 'Pending', 5);
SELECT create_topic_suggestion('Narrative', 'Pending', 7);
SELECT create_topic_suggestion('Craftsmanship', 'Pending', 10);
SELECT create_topic_suggestion('Aesthetics', 'Pending', 15);
SELECT create_topic_suggestion('Masterpiece', 'Pending', 20);
UPDATE topic_suggestion SET status = 'Accepted' WHERE id = 2;

SELECT create_article('The Godfather: A Cinematic Masterpiece that Suits Every Taste', 'Francis Ford Coppola''s ''The Godfather'' is an undeniable masterpiece that transcends time. Released in 1972, this cinematic gem continues to captivate audiences worldwide. Marlon Brando''s iconic portrayal of Don Vito Corleone, along with Al Pacino, James Caan, and Robert Duvall''s stellar performances, has left an indelible mark on the history of cinema. The film''s narrative intricacy, compelling characters, and a hauntingly beautiful score by Nino Rota make it an immersive experience. ''The Godfather'' doesn''t just tell a story; it immerses you in the complex world of the Italian-American mafia. From its unforgettable opening scene to the iconic ''offer you can''t refuse,'' the film''s dialogue and cinematography are a work of art. With a flawless blend of crime, family, and morality, ''The Godfather'' is not just a film; it''s a cinematic journey through the dark alleys of power and loyalty. A must-see for cinephiles and a timeless classic.', 1, 1, 1);
SELECT create_article('Defending the Silver Screen: Hollywood''s Legal Dramas', 'In the world of legal dramas, Hollywood has always been a courtroom where gripping stories are tried and tested. From Atticus Finch''s unwavering moral compass in To Kill a Mockingbird to the charismatic maneuvers of Daniel Kaffee in A Few Good Men, the silver screen has presented us with a gallery of memorable attorneys. But it''s not just about the lawyers; it''s about the riveting cases they take on, the intense cross-examinations, and the moral dilemmas that keep us on the edge of our seats. So, let''s embark on a cinematic journey through the hallowed halls of justice. We''ll explore some of the most iconic legal thrillers, dissect the courtroom strategies, and maybe even find a lesson or two in the fine art of persuasion. As Harvey Specter would say, It''s not about the law; it''s about the game.', 1, 5, NULL);
SELECT create_article('Donna Paulsen unveils Donna Paulsen''s show - Suits', 'Donna Paulsen, the sharp-witted and stylish legal powerhouse known for her role in ''Suits,'' has graciously decided to reveal some exclusive behind-the-scenes insights into the beloved legal drama series that captivated audiences worldwide. ''Suits,'' the hit TV show that aired for nine gripping seasons, showcased Donna Paulsen''s character, played by Sarah Rafferty, as a central figure in the high-stakes world of Manhattan law firms. Now, Donna steps off the screen and into our world to share anecdotes, trivia, and personal experiences from her time on set. In this revealing article, Donna discusses the camaraderie among the cast, the meticulous attention to detail in recreating a high-powered law firm atmosphere, and some of the unforgettable moments that made ''Suits'' an iconic show. Donna''s stories, peppered with her characteristic charm and wit, provide fans with an exclusive look into the making of ''Suits.'' Discover the inspiration behind the characters, the challenges faced during filming, and how this legal drama became a fan-favorite. If you''re a die-hard ''Suits'' fan or simply intrigued by the inner workings of the entertainment industry, Donna Paulsen''s insights into the world of ''Suits'' are a must-read. Get ready to step behind the curtain and explore the magic that brought ''Suits'' to life on your screens.', 3, 2, 2);
SELECT create_article('Louis Litt''s Favorite Movie: A Lesson in Tenacity', 'Hey, it''s me, Louis Litt! The man with the charm, the style, and the legal prowess that makes the world spin. But today, I''m not here to talk about my legal victories. I want to take a moment to share with you my all-time favorite movie, a film that embodies the spirit of never giving up and fighting for what you believe in. The movie that holds a special place in my heart is none other than "Rocky"! Directed by John G. Avildsen and starring Sylvester Stallone, this cinematic masterpiece is a tale of resilience, determination, and the unyielding human spirit. "Rocky" is not just a boxing movie; it''s a story of an underdog who defied all odds. Just like me in the world of law, Rocky Balboa rose from obscurity to take on the heavyweight champion of the world, Apollo Creed. The parallels are uncanny. Rocky, a small-time boxer from Philadelphia, goes the distance with Creed and doesn''t give up, no matter how tough the fight gets. The character of Rocky Balboa represents the essence of tenacity, and that''s something I can truly appreciate. In the courtroom, I''ve faced numerous challenges and adversaries, but I always stand my ground and fight for justice. Just like Rocky, I''m not one to back down from a challenge. The film also showcases the importance of mentorship, as Rocky''s relationship with his trainer, Mickey, is a source of inspiration. It reminds me of the guidance I received from Harvey Specter.  Stay strong, keep pushing forward, and never forget, it''s not about how hard you hit; it''s about how hard you can get hit and keep moving forward. That''s how winning is done. - Louis Litt', 4, 4, NULL);
SELECT create_article('Cinematic Marvels: Exploring the Uncharted Realms of Superhero Films', 'The world of cinema has witnessed a remarkable evolution with the rise of superhero films, taking audiences on thrilling journeys through uncharted realms and dimensions. From the iconic Marvel Cinematic Universe to the brooding atmosphere of DC Comics adaptations, superhero movies have become a cultural phenomenon. This article delves into the cinematic marvels that have redefined the landscape of modern storytelling. The fusion of cutting-edge special effects, compelling narratives, and charismatic performances has elevated superhero films to unprecedented heights. Join us as we navigate through the exhilarating exploits of beloved characters, the intricacies of interconnected story arcs, and the visual spectacle that accompanies every superhero showdown. Whether you''re a die-hard fan or a casual viewer, the impact of superhero cinema on the film industry is undeniable. As we explore these uncharted realms, we''ll uncover the magic that brings these larger-than-life characters to the silver screen, leaving an indelible mark on the hearts of audiences around the globe.', 6, 3, NULL);
SELECT create_article('The Cinematic Odyssey: A Decade of Cinematic Brilliance', 'As we stand on the precipice of a new era, let''s take a retrospective journey through the cinematic landscape of the past decade. The 2010s were a kaleidoscope of cinematic brilliance, marked by groundbreaking storytelling, visual splendor, and performances that etched themselves into the annals of film history. From the inception of new cinematic universes to the revival of classic franchises, the past ten years have been a cinematic odyssey that captivated audiences worldwide. Let''s delve into the transformative narratives that challenged the norms, the filmmakers who dared to push the boundaries, and the actors who brought these stories to life. From the hauntingly beautiful landscapes of interstellar space to the gritty streets of neo-noir cities, each frame was a brushstroke on the canvas of our collective imagination. Directors like Christopher Nolan, Denis Villeneuve, and Greta Gerwig crafted narratives that transcended genres, leaving an indelible impact on the way we perceive cinema. Actors such as Joaquin Phoenix, Frances McDormand, and Chadwick Boseman breathed life into characters that will be remembered for generations. This article serves as a celebration of the artistry that unfolded on the silver screen, an homage to the storytellers who dared to dream, and a testament to the enduring power of cinema to shape our perceptions and emotions. So, join us as we traverse through the cinematic odyssey of the 2010s, a decade that will be remembered as a golden age of storytelling and visual splendor.', 9, 4, NULL);
SELECT create_article('The Art of Cinematography: Painting with Light and Shadow', 'Cinematography, often hailed as the art of visual storytelling, is a nuanced dance between light and shadow. From the chiaroscuro of film noir to the ethereal glow of fantasy realms, cinematographers wield light as their brush and the camera as their canvas. Each frame is a meticulously composed painting, where the interplay of light and shadow creates a visual symphony that resonates with the audience. The magic happens behind the lens, where cinematographers craft the mood, atmosphere, and emotion of a scene through their masterful control of light. It''s a delicate balance, a dance between illumination and obscurity, where every frame tells a story within the story. The use of practical effects, lens choices, and camera movements adds layers to this visual narrative, transporting the audience into the heart of the filmmaker''s vision. As we explore the enchanting world of cinematography, we discover the unsung heroes who work tirelessly to capture moments that linger in our memories. Join us on this journey into the art of painting with light and shadow, where every frame is a brushstroke on the canvas of cinema.', 23, 2, NULL);
SELECT create_article('Behind the Scenes: Crafting Unforgettable Movie Scores', 'In the realm of cinema, the power of music is undeniable. Movie scores, with their ability to evoke emotions and enhance storytelling, play a pivotal role in creating memorable cinematic experiences. Composers, often unsung heroes, work behind the scenes to craft melodies that resonate with audiences long after the credits roll. From the iconic themes of John Williams to the haunting compositions of Hans Zimmer, movie scores transcend the screen and become timeless pieces of art. This article delves into the fascinating world of film composition, exploring the creative process, the collaboration between composers and directors, and the impact of music on our cinematic journey. Join us as we unravel the magic behind the scores that elevate movies from mere visuals to immersive, emotional journeys. Discover the stories behind the melodies that linger in our hearts and minds, enhancing the magic of cinema one note at a time.', 23, 1, NULL);
SELECT create_article('The Art of Cinematic Storytelling: Beyond the Silver Screen', 'Cinema is not just a medium; it''s a storyteller, weaving narratives that captivate and resonate with audiences across the globe. Beyond the silver screen, the art of cinematic storytelling extends its influence into our lives, shaping perspectives and sparking conversations. This article explores the profound impact of storytelling in cinema, examining how filmmakers use visuals, dialogue, and pacing to create compelling narratives. From the timeless classics to the contemporary masterpieces, we''ll delve into the techniques that make stories unforgettable. Join us on a journey through the realms of plot twists, character development, and the subtle nuances that make each cinematic experience unique. As we unravel the threads of storytelling, we''ll discover the artistry that goes beyond the mere act of watching a film. Cinema, at its core, is a mirror reflecting the human experience, and through the art of storytelling, it leaves an indelible mark on our collective consciousness.', 13, 2, NULL);
SELECT create_article('An Exclusive Interview with Harvey Specter: Mastering the Art of Legal Drama', 'In a candid and exclusive interview, we sit down with the legal genius himself, Harvey Specter, to unravel the mysteries behind the captivating world of legal drama. Known for his razor-sharp wit, impeccable style, and an uncanny ability to win cases, Harvey shares insights into his approach to the courtroom, his iconic one-liners, and the challenges of balancing power and morality. From his early days as a young associate to becoming a name synonymous with success, Harvey takes us on a journey through the highs and lows of his illustrious career. We delve into the nuances of legal strategy, the importance of mentorship, and the art of negotiation. Beyond the courtroom, Harvey opens up about the personal philosophies that drive him, the impact of relationships on his professional life, and the moments that define him as a lawyer. This exclusive interview provides a rare glimpse into the mind of Harvey Specter, offering fans and aspiring attorneys alike a chance to learn from the best in the business.', 18, 3, NULL);
SELECT create_article('The Cinematic Evolution: From Black and White to 4K Brilliance', 'As we journey through the annals of cinematic history, the evolution of film technology becomes a fascinating narrative of innovation and visual splendor. From the humble beginnings of black and white silent films to the mesmerizing world of 4K resolution, each era has contributed to the immersive experience we enjoy today. This article explores the technological milestones that have shaped the landscape of cinema, from the invention of the first motion picture camera by the Lumière brothers to the advent of Technicolor and the transition to digital cinematography. We''ll delve into the transformative impact of special effects, the rise of CGI, and the continuous quest for higher resolutions. Join us on this cinematic odyssey as we celebrate the magic of storytelling through the lens of ever-evolving technology. As filmmakers embrace new possibilities, audiences are treated to a visual feast that transcends the boundaries of imagination. The journey from monochrome to high-definition is not just a technical progression; it''s a testament to the enduring power of cinema to capture our hearts and minds.', 23, 4, NULL);
SELECT create_article('Unveiling the Enigma: Exploring Christopher Nolan''s Cinematic Puzzle', 'Christopher Nolan, a maestro of modern filmmaking, has consistently delivered mind-bending narratives that challenge and captivate audiences. From the intricacies of "Inception" to the time-twisting narrative of "Interstellar," Nolan''s films are more than cinematic experiences; they are puzzles waiting to be solved. This article takes you on a journey through the enigmatic storytelling of Christopher Nolan, unraveling the layers of his narrative techniques and the thematic threads that bind his filmography. Explore the concept of time dilation, the use of practical effects, and the recurring motifs that define Nolan''s unique cinematic fingerprint. As we dissect the intricacies of his films, from "Memento" to "Tenet," join us in deciphering the puzzles within puzzles that have become a hallmark of Nolan''s work. Whether you''re a seasoned Nolan enthusiast or a newcomer to his cinematic universe, prepare to be immersed in the labyrinthine world of a director who continues to push the boundaries of storytelling in cinema.', 13, 2, NULL);
SELECT create_article('Behind the Lens: A Cinematographer''s Art in Shaping Visual Narratives', 'In the realm of filmmaking, cinematography stands as a silent yet powerful storyteller. The lens captures emotions, frames narratives, and transforms scripts into visual masterpieces. This article delves into the artistry of cinematographers, exploring their pivotal role in shaping the visual language of cinema. From the iconic framing of Gregg Toland in "Citizen Kane" to the mesmerizing long takes of Emmanuel Lubezki in "Birdman," we unravel the techniques that define the work of these unsung heroes behind the lens. Discover the magic of lighting, composition, and camera movement as we celebrate the cinematic moments that have left an indelible mark on the history of film. Interviews with renowned cinematographers provide insights into their creative processes, challenges faced on set, and the delicate balance between technical precision and artistic expression. Join us on a visual journey through the eyes of the cinematographer, where each frame tells a story, and the play of light and shadow creates a symphony of visual poetry.', 23, 2, NULL);

SELECT create_article_image('article_image_1.jpg', 1);
SELECT create_article_image('article_image_2.jpg', 1);
SELECT create_article_image('article_image_3.jpg', 1);
SELECT create_article_image('article_image_4.jpg', 3);
SELECT create_article_image('article_image_5.jpg', 3);
SELECT create_article_image('article_image_6.jpg', 4);

SELECT create_tag('godfather');
SELECT create_tag('suits');
SELECT create_tag('donna_paulsen'); 
SELECT create_tag('cinematography');
SELECT create_tag('filmmaking');
SELECT create_tag('movie_classics');
SELECT create_tag('legal_drama');
SELECT create_tag('superhero_films');
SELECT create_tag('marvel');
SELECT create_tag('dc_comics');
SELECT create_tag('cinematic_brilliance');
SELECT create_tag('film_history');
SELECT create_tag('storytelling');
SELECT create_tag('interviews');
SELECT create_tag('movie_scores');
SELECT create_tag('technology_in_cinema');
SELECT create_tag('christopher_nolan');
SELECT create_tag('behind_the_scenes');
SELECT create_tag('cinematic_evolution');
SELECT create_tag('film_technology');
SELECT create_tag('visual_narratives');
SELECT create_tag('movie_directors');
SELECT create_tag('hollywood');

SELECT create_tag_article(1, 1);
SELECT create_tag_article(1, 2);
SELECT create_tag_article(1, 7);
SELECT create_tag_article(2, 1);
SELECT create_tag_article(2, 3);
SELECT create_tag_article(2, 4);
SELECT create_tag_article(3, 1);
SELECT create_tag_article(3, 3);
SELECT create_tag_article(3, 9);
SELECT create_tag_article(4, 4);
SELECT create_tag_article(5, 6);
SELECT create_tag_article(5, 8);
SELECT create_tag_article(5, 10);
SELECT create_tag_article(5, 13);
SELECT create_tag_article(6, 5);
SELECT create_tag_article(6, 8);
SELECT create_tag_article(7, 5);
SELECT create_tag_article(7, 6);
SELECT create_tag_article(8, 1);
SELECT create_tag_article(8, 6);
SELECT create_tag_article(9, 6);
SELECT create_tag_article(10, 7);
SELECT create_tag_article(10, 10);
SELECT create_tag_article(11, 7);
SELECT create_tag_article(11, 8);
SELECT create_tag_article(12, 2);
SELECT create_tag_article(12, 5);
SELECT create_tag_article(12, 9);
SELECT create_tag_article(12, 13);
SELECT create_tag_article(13, 2);
SELECT create_tag_article(13, 8);
SELECT create_tag_article(13, 10);
SELECT create_tag_article(13, 12);
SELECT create_tag_article(13, 13);
SELECT create_tag_article(14, 1);
SELECT create_tag_article(14, 5);
SELECT create_tag_article(14, 9);
SELECT create_tag_article(14, 10);
SELECT create_tag_article(18, 11);
SELECT create_tag_article(23, 7);
SELECT create_tag_article(23, 9);

SELECT create_follow_tag(1, 1);
SELECT create_follow_tag(2, 1);
SELECT create_follow_tag(3, 1);
SELECT create_follow_tag(4, 1);
SELECT create_follow_tag(5, 1);
SELECT create_follow_tag(1, 2);
SELECT create_follow_tag(2, 2);
SELECT create_follow_tag(3, 2);
SELECT create_follow_tag(4, 2);
SELECT create_follow_tag(5, 2);
SELECT create_follow_tag(6, 2);
SELECT create_follow_tag(1, 3);
SELECT create_follow_tag(2, 3);
SELECT create_follow_tag(3, 3);
SELECT create_follow_tag(4, 3);
SELECT create_follow_tag(5, 3);
SELECT create_follow_tag(6, 3);
SELECT create_follow_tag(7, 3);
SELECT create_follow_tag(2, 4);
SELECT create_follow_tag(3, 4);
SELECT create_follow_tag(4, 4);
SELECT create_follow_tag(5, 4);
SELECT create_follow_tag(6, 4);
SELECT create_follow_tag(7, 4);
SELECT create_follow_tag(8, 4);
SELECT create_follow_tag(3, 5);
SELECT create_follow_tag(4, 5);
SELECT create_follow_tag(5, 5);
SELECT create_follow_tag(6, 5);
SELECT create_follow_tag(7, 5);
SELECT create_follow_tag(8, 5);
SELECT create_follow_tag(9, 5);
SELECT create_follow_tag(4, 6);
SELECT create_follow_tag(5, 6);
SELECT create_follow_tag(6, 6);
SELECT create_follow_tag(7, 6);
SELECT create_follow_tag(8, 6);
SELECT create_follow_tag(9, 6);
SELECT create_follow_tag(5, 7);
SELECT create_follow_tag(6, 7);
SELECT create_follow_tag(7, 7);
SELECT create_follow_tag(8, 7);
SELECT create_follow_tag(9, 7);
SELECT create_follow_tag(10, 7);
SELECT create_follow_tag(6, 8);
SELECT create_follow_tag(7, 8);
SELECT create_follow_tag(8, 8);
SELECT create_follow_tag(9, 8);
SELECT create_follow_tag(10, 8);
SELECT create_follow_tag(11, 8);
SELECT create_follow_tag(7, 9);
SELECT create_follow_tag(8, 9);
SELECT create_follow_tag(9, 9);
SELECT create_follow_tag(10, 9);
SELECT create_follow_tag(11, 9);
SELECT create_follow_tag(12, 9);
SELECT create_follow_tag(8, 10);
SELECT create_follow_tag(9, 10);
SELECT create_follow_tag(10, 10);
SELECT create_follow_tag(11, 10);
SELECT create_follow_tag(12, 10);
SELECT create_follow_tag(13, 10);
SELECT create_follow_tag(9, 11);
SELECT create_follow_tag(10, 11);
SELECT create_follow_tag(11, 11);
SELECT create_follow_tag(12, 11);
SELECT create_follow_tag(13, 11);
SELECT create_follow_tag(14, 11);
SELECT create_follow_tag(10, 12);
SELECT create_follow_tag(11, 12);
SELECT create_follow_tag(12, 12);
SELECT create_follow_tag(13, 12);
SELECT create_follow_tag(14, 12);
SELECT create_follow_tag(15, 12);
SELECT create_follow_tag(11, 13);
SELECT create_follow_tag(12, 13);
SELECT create_follow_tag(13, 13);
SELECT create_follow_tag(14, 13);
SELECT create_follow_tag(15, 13);
SELECT create_follow_tag(16, 13);
SELECT create_follow_tag(12, 14);
SELECT create_follow_tag(13, 14);
SELECT create_follow_tag(14, 14);
SELECT create_follow_tag(15, 14);
SELECT create_follow_tag(16, 14);
SELECT create_follow_tag(17, 14);
SELECT create_follow_tag(13, 15);
SELECT create_follow_tag(14, 15);
SELECT create_follow_tag(15, 15);
SELECT create_follow_tag(16, 15);
SELECT create_follow_tag(17, 15);
SELECT create_follow_tag(18, 15);
SELECT create_follow_tag(14, 16);
SELECT create_follow_tag(15, 16);
SELECT create_follow_tag(16, 16);
SELECT create_follow_tag(17, 16);
SELECT create_follow_tag(18, 16);
SELECT create_follow_tag(19, 16);
SELECT create_follow_tag(15, 17);
SELECT create_follow_tag(16, 17);
SELECT create_follow_tag(17, 17);
SELECT create_follow_tag(18, 17);
SELECT create_follow_tag(19, 17);
SELECT create_follow_tag(20, 17);
SELECT create_follow_tag(16, 18);
SELECT create_follow_tag(17, 18);
SELECT create_follow_tag(18, 18);
SELECT create_follow_tag(19, 18);
SELECT create_follow_tag(20, 18);
SELECT create_follow_tag(21, 18);
SELECT create_follow_tag(17, 19);
SELECT create_follow_tag(18, 19);
SELECT create_follow_tag(19, 19);
SELECT create_follow_tag(20, 19);
SELECT create_follow_tag(21, 19);
SELECT create_follow_tag(22, 19);
SELECT create_follow_tag(18, 20);
SELECT create_follow_tag(19, 20);
SELECT create_follow_tag(20, 20);
SELECT create_follow_tag(21, 20);
SELECT create_follow_tag(22, 20);
SELECT create_follow_tag(23, 20);
SELECT create_follow_tag(19, 21);
SELECT create_follow_tag(20, 21);
SELECT create_follow_tag(21, 21);
SELECT create_follow_tag(22, 21);
SELECT create_follow_tag(20, 22);
SELECT create_follow_tag(21, 22);
SELECT create_follow_tag(22, 22);

SELECT create_comment('I love this movie! It is a masterpiece!', 2, FALSE, 1, NULL);
SELECT create_comment('I have seen better...', 4, TRUE, 1, 14);
SELECT create_comment('I have read better articles', 4, FALSE, 1, NULL);
SELECT create_comment('Louis... cut it out.', 3, TRUE, 1, 14);
SELECT create_comment('Fantastic work, I wish more people shared this view.', 5, FALSE, 2, NULL);
SELECT create_comment('I do share this view as well.', 2, TRUE, 2, 18);
SELECT create_comment('Only you to write an article about yourself.', 1, FALSE, 3, NULL);
SELECT create_comment('Wow... just, wow...', 2, TRUE, 3, 20);
SELECT create_comment('Wonderful read!', 4, FALSE, 3, NULL);

SELECT create_edit('title', 'Donna Paulsen unveils the Donna Paulsen ', 'Donna Paulsen Uncovers Exclusive Behind-the-Scenes Secrets of ''Suits''', 3, 5);
UPDATE article SET title = 'Donna Paulsen Uncovers Exclusive Behind-the-Scenes Secrets of ''Suits''' WHERE id = 3;

UPDATE article SET topic_id = 0 WHERE id = 3;

SELECT create_edit('body', 'I love this movie! It is a masterpiece!', 'I love this movie!', 5, 2);
UPDATE content_item SET body = 'I love this movie!' WHERE id = 5;

SELECT create_vote(1, 7, 1);
SELECT create_vote(1, 9, 1);
SELECT create_vote(1, 11, 1);
SELECT create_vote(1, 13, 1);
SELECT create_vote(1, 14, -1);
SELECT create_vote(2, 4, 1);
SELECT create_vote(2, 7, -1);
SELECT create_vote(2, 10, -1);
SELECT create_vote(2, 11, 1);
SELECT create_vote(2, 12, -1);
SELECT create_vote(3, 2, -1);
SELECT create_vote(3, 5, 1);
SELECT create_vote(3, 8, 1);
SELECT create_vote(3, 11, 1);
SELECT create_vote(3, 13, 1);
SELECT create_vote(4, 1, 1);
SELECT create_vote(4, 3, 1);
SELECT create_vote(4, 6, -1);
SELECT create_vote(4, 9, -1);
SELECT create_vote(4, 12, 1);
SELECT create_vote(5, 2, -1);
SELECT create_vote(5, 5, 1);
SELECT create_vote(5, 8, -1);
SELECT create_vote(5, 10, -1);
SELECT create_vote(5, 13, 1);
SELECT create_vote(6, 1, 1);
SELECT create_vote(6, 4, 1);
SELECT create_vote(6, 7, -1);
SELECT create_vote(6, 10, 1);
SELECT create_vote(6, 12, -1);
SELECT create_vote(7, 2, -1);
SELECT create_vote(7, 4, 1);
SELECT create_vote(7, 7, -1);
SELECT create_vote(7, 10, 1);
SELECT create_vote(7, 13, 1);
SELECT create_vote(8, 1, 1);
SELECT create_vote(8, 3, 1);
SELECT create_vote(8, 5, 1);
SELECT create_vote(8, 8, -1);
SELECT create_vote(8, 11, 1);
SELECT create_vote(9, 2, -1);
SELECT create_vote(9, 4, 1);
SELECT create_vote(9, 9, -1);
SELECT create_vote(9, 12, 1);
SELECT create_vote(10, 1, 1);
SELECT create_vote(10, 4, -1);
SELECT create_vote(10, 7, -1);
SELECT create_vote(10, 10, 1);
SELECT create_vote(10, 12, 1);
SELECT create_vote(11, 2, -1);
SELECT create_vote(11, 5, -1);
SELECT create_vote(11, 8, -1);
SELECT create_vote(11, 11, 1);
SELECT create_vote(11, 13, 1);
SELECT create_vote(12, 1, 1);
SELECT create_vote(12, 3, 1);
SELECT create_vote(12, 6, -1);
SELECT create_vote(12, 9, -1);
SELECT create_vote(12, 12, 1);
SELECT create_vote(13, 2, 1);
SELECT create_vote(13, 5, 1);
SELECT create_vote(13, 8, -1);
SELECT create_vote(13, 11, 1);
SELECT create_vote(13, 13, 1);
SELECT create_vote(14, 1, 1);
SELECT create_vote(14, 3, 1);
SELECT create_vote(14, 6, -1);
SELECT create_vote(14, 9, -1);
SELECT create_vote(14, 12, 1);
SELECT create_vote(15, 2, -1);
SELECT create_vote(15, 4, 1);
SELECT create_vote(15, 7, -1);
SELECT create_vote(15, 10, 1);
SELECT create_vote(15, 13, 1);
SELECT create_vote(16, 1, 1);
SELECT create_vote(16, 3, 1);
SELECT create_vote(16, 6, -1);
SELECT create_vote(16, 9, -1);
SELECT create_vote(16, 12, 1);
SELECT create_vote(17, 2, -1);
SELECT create_vote(17, 5, 1);
SELECT create_vote(17, 8, -1);
SELECT create_vote(17, 11, 1);
SELECT create_vote(17, 13, 1);
SELECT create_vote(18, 1, 1);
SELECT create_vote(18, 4, 1);
SELECT create_vote(18, 7, -1);
SELECT create_vote(18, 13, 1);
SELECT create_vote(19, 2, -1);
SELECT create_vote(19, 5, 1);
SELECT create_vote(19, 8, -1);
SELECT create_vote(19, 11, 1);
SELECT create_vote(19, 13, 1);
SELECT create_vote(20, 1, 1);
SELECT create_vote(20, 3, 1);
SELECT create_vote(20, 6, -1);
SELECT create_vote(20, 9, -1);
SELECT create_vote(20, 12, 1);
SELECT create_vote(21, 2, -1);
SELECT create_vote(21, 5, 1);
SELECT create_vote(21, 8, -1);
SELECT create_vote(21, 11, 1);
SELECT create_vote(21, 13, 1);
SELECT create_vote(22, 1, 1);
SELECT create_vote(22, 4, 1);
SELECT create_vote(22, 7, -1);
SELECT create_vote(22, 10, 1);
SELECT create_vote(22, 13, 1);
SELECT create_vote(24, 1, 1);
SELECT create_vote(24, 4, 1);
SELECT create_vote(24, 7, -1);
SELECT create_vote(24, 10, 1);
SELECT create_vote(24, 13, 1);
SELECT create_vote(25, 2, -1);
SELECT create_vote(25, 5, 1);
SELECT create_vote(25, 8, -1);
SELECT create_vote(25, 11, 1);
SELECT create_vote(25, 13, 1);

SELECT create_upvote_notification(3, 3, 5);

SELECT create_content_report(NULL, 1, 'Misinformation', 4);

SELECT create_member_report('This member is a disgrace to the legal profession.', 4, 'InnapropriateContent', 1);

UPDATE content_item SET is_deleted = TRUE WHERE id = 4;
SELECT create_edit('is_deleted', 'FALSE', 'TRUE', 4, 5);

UPDATE member SET is_blocked = TRUE WHERE id = 4;

SELECT create_appeal('I did not do anything wrong, I demand to be unblocked!', 4);

DROP TABLE IF EXISTS password_reset_tokens CASCADE;

CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    used BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);