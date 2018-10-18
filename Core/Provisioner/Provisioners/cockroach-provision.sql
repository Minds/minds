CREATE TABLE minds.suggested (
  guid INT PRIMARY KEY,
  rating INT,
  type VARCHAR(15),
  score INT
);

CREATE TABLE minds.user_hashtags (
  guid INT,
  hashtag STRING,
  PRIMARY KEY (guid, hashtag)
);

CREATE TABLE minds.entity_hashtags (
  guid INT,
  hashtag STRING,
  PRIMARY KEY (guid, hashtag)
);

CREATE TABLE minds.hidden_hashtags (
  hashtag STRING NOT NULL,
  hidden_since TIMESTAMP NOT NULL DEFAULT now(),
  admin_guid INT NOT NULL,
  PRIMARY KEY (hashtag)
)