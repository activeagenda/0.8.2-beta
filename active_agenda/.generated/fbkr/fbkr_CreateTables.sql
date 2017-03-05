CREATE TABLE `fbkr` (
   FeedbackResponseID int unsigned auto_increment not null,
   FeedbackID int unsigned not null,
   Response text,
   ImpactID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FeedbackResponseID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `fbkr_l` (
   _RecordID int unsigned not null auto_increment,
   FeedbackResponseID int unsigned  not null,
   FeedbackID int unsigned not null,
   Response text,
   ImpactID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FeedbackResponseID
   )
) TYPE=InnoDB;
