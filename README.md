# Travelog

## DB設計

### Article（ログテーブル）

| Column     | Type                           | Modifiers |
| ---------- | ------------------------------ | --------- |
| id         | bigint                         | not null  |
| title      | character varying(255)         | not null  |
| content    | text                           | not null  |
| start_date | date                           | not null  |
| end_date   | date                           | not null  |
| user_id    | bigint                         | not null  |
| created_at | timestamp(0) without time zone |           |
| updated_at | timestamp(0) without time zone |           |

### Photos（写真テーブル)

| Column      | Type                           | Modifiers |
| ----------- | ------------------------------ | --------- |
| id          | bigint                         | not null  |
| name        | character varying(255)         | not null  |
| storage_key | character varying(255)         | not null  |
| article_id  | bigint                         | not null  |
| created_at  | timestamp(0) without time zone |           |
| updated_at  | timestamp(0) without time zone |           |

### Usersテーブル

| Column            | Type                           | Modifiers |
| ----------------- | ------------------------------ | --------- |
| id                | bigint                         | not null  |
| name              | character varying(255)         | not null  |
| email             | character varying(255)         | not null  |
| profile           | text                           |           |
| email_verified_at | timestamp(0) without time zone |           |
| password          | character varying(255)         |           |
| remember_token    | character varying(100)         |           |
| created_at        | timestamp(0) without time zone |           |
| updated_at        | timestamp(0) without time zone |           |
