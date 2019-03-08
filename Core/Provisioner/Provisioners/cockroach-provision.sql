CREATE TABLE minds.comments (
  uuid UUID NOT NULL DEFAULT gen_random_uuid(),
  legacy_guid INT NULL,
  parent_uuid UUID NULL,
  owner_guid INT NULL,
  entity_guid INT NULL,
  created_timestamp TIMESTAMP NULL DEFAULT now():::TIMESTAMP,
  notification_type STRING(20) NULL,
  data JSONB NULL,
  CONSTRAINT "primary" PRIMARY KEY (uuid ASC),
  INDEX legacy_guid_idx (legacy_guid ASC),
  FAMILY "primary" (uuid, legacy_guid, parent_uuid, owner_guid, entity_guid, created_timestamp, notification_type, data)
);

CREATE TABLE minds.entities (
  uuid UUID NOT NULL DEFAULT gen_random_uuid(),
  legacy_guid INT NULL,
  owner_guid INT NULL,
  entity_guid INT NULL,
  created_timestamp TIMESTAMP NULL DEFAULT now():::TIMESTAMP,
  CONSTRAINT "primary" PRIMARY KEY (uuid ASC),
  INDEX legacy_guid_idx (legacy_guid ASC),
  FAMILY "primary" (uuid, legacy_guid, owner_guid, entity_guid, created_timestamp)
);

CREATE TABLE minds.entity_hashtags (
  guid INT NOT NULL,
  hashtag STRING NOT NULL,
  CONSTRAINT "primary" PRIMARY KEY (guid ASC, hashtag ASC),
  INDEX entity_hashtags_hashtag_idx (hashtag ASC),
  INDEX entity_hashtags_hashtag_guid_idx (hashtag ASC, guid ASC),
  FAMILY "primary" (guid, hashtag)
);

CREATE TABLE minds.helpdesk_categories (
  uuid UUID NOT NULL DEFAULT gen_random_uuid(),
  title STRING(100) NOT NULL,
  parent UUID NULL,
  branch STRING NULL,
  CONSTRAINT "primary" PRIMARY KEY (uuid ASC),
  FAMILY "primary" (uuid, title, parent, branch)
);

CREATE TABLE minds.helpdesk_faq (
  uuid UUID NOT NULL DEFAULT gen_random_uuid(),
  question STRING NULL,
  answer STRING NULL,
  category_uuid UUID NULL,
  CONSTRAINT "primary" PRIMARY KEY (uuid ASC),
  CONSTRAINT fk_category_uuid_ref_helpdesk_categories FOREIGN KEY (category_uuid) REFERENCES helpdesk_categories (uuid),
  INDEX helpdesk_faq_auto_index_fk_category_uuid_ref_helpdesk_categories (category_uuid ASC),
  FAMILY "primary" (uuid, question, answer, category_uuid)
);

CREATE TABLE minds.helpdesk_votes (
  question_uuid UUID NOT NULL,
  user_guid STRING(18) NOT NULL,
  direction STRING NOT NULL,
  CONSTRAINT "primary" PRIMARY KEY (question_uuid ASC, user_guid ASC, direction ASC),
  FAMILY "primary" (question_uuid, user_guid, direction)
);

CREATE TABLE minds.hidden_hashtags (
  hashtag STRING NOT NULL,
  hidden_since TIMESTAMP NOT NULL DEFAULT now(),
  admin_guid INT NOT NULL,
  CONSTRAINT "primary" PRIMARY KEY (hashtag ASC),
  FAMILY "primary" (hashtag, hidden_since, admin_guid)
);

CREATE TABLE minds.notification_batches (
  user_guid INT NOT NULL,
  batch_id STRING NOT NULL,
  CONSTRAINT "primary" PRIMARY KEY (user_guid ASC, batch_id ASC),
  INDEX notification_batches_batch_id_idx (batch_id ASC),
  FAMILY "primary" (user_guid, batch_id)
);

CREATE TABLE minds.notifications (
  uuid UUID NOT NULL DEFAULT gen_random_uuid(),
  to_guid INT NOT NULL,
  from_guid INT NULL,
  created_timestamp TIMESTAMP NULL DEFAULT now():::TIMESTAMP,
  read_timestamp TIMESTAMP NULL,
  notification_type STRING NOT NULL,
  data JSONB NULL,
  entity_guid STRING NULL,
  batch_id STRING NULL,
  CONSTRAINT "primary" PRIMARY KEY (to_guid ASC, notification_type ASC, uuid DESC),
  INDEX notifications_redux_created_timestamp_idx (created_timestamp DESC),
  INDEX notifications_redux_batch_id_idx (batch_id ASC) STORING (from_guid, entity_guid, created_timestamp, read_timestamp, data),
  FAMILY "primary" (uuid, to_guid, from_guid, created_timestamp, read_timestamp, notification_type, data, entity_guid, batch_id)
);

CREATE TABLE minds.suggested (
  type STRING NOT NULL,
  guid INT NOT NULL,
  rating INT NULL,
  score INT NULL,
  lastsynced TIMESTAMP NULL,
  CONSTRAINT "primary" PRIMARY KEY (type ASC, guid ASC),
  INDEX suggested_lastsynced_score_rating_idx (lastsynced DESC, score DESC, rating DESC),
  INDEX suggested_lastsynced_score_idx (lastsynced DESC, score DESC),
  INDEX suggested_redux_rating_idx (rating DESC) STORING (lastsynced, score),
  FAMILY "primary" (type, guid, rating, score, lastsynced)
);

CREATE TABLE minds.suggested_tags (
  guid INT NOT NULL,
  rating INT NULL,
  type STRING NULL,
  score INT NULL,
  lastsynced TIMESTAMP NULL,
  hashtags STRING[] NULL,
  CONSTRAINT "primary" PRIMARY KEY (guid ASC),
  FAMILY "primary" (guid, rating, type, score, lastsynced, hashtags)
);
