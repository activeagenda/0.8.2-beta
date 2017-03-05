CREATE TABLE `fbk` (
   FeedbackID int unsigned auto_increment not null,
   OrganizationID int,
   Anonymous bool default 0,
   FeedbackTypeID int,
   FeedbackTitle text,
   FeedbackProvided text,
   FeedbackStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FeedbackID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `fbk_l` (
   _RecordID int unsigned not null auto_increment,
   FeedbackID int unsigned  not null,
   OrganizationID int,
   Anonymous bool default 0,
   FeedbackTypeID int,
   FeedbackTitle text,
   FeedbackProvided text,
   FeedbackStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FeedbackID
   )
) TYPE=InnoDB;
