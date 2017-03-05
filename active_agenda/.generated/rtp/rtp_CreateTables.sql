CREATE TABLE `rtp` (
   RecommendationID int unsigned auto_increment not null,
   CauseID int,
   RecToPreventID int,
   RecToPreventTitle varchar(128),
   RecToPreventDesc text,
   RecommendationStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RecommendationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rtp_l` (
   _RecordID int unsigned not null auto_increment,
   RecommendationID int unsigned  not null,
   CauseID int,
   RecToPreventID int,
   RecToPreventTitle varchar(128),
   RecToPreventDesc text,
   RecommendationStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RecommendationID
   )
) TYPE=InnoDB;
