--
-- for SQLITE
--

CREATE TABLE mail_queue
(
    mail_id    INTEGER PRIMARY KEY AUTOINCREMENT,
    que_id     TEXT,
    status     VARCHAR(16) NOT NULL,
    mail_to    TEXT        NOT NULL,
    mail_from  TEXT        NOT NULL,
    reply_to   TEXT,
    cc         TEXT,
    bcc        TEXT,
    options    TEXT,
    subject    TEXT        NOT NULL,
    body_text  TEXT,
    body_html  TEXT,
    created_at DATETIME NOT NULL,
    send_at    DATETIME
);