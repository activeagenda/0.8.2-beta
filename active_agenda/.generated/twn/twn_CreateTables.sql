CREATE TABLE `twn` (
   TownHallQuestionID int unsigned auto_increment not null,
   OrganizationID int,
   QuestionTypeID int,
   QuestionAsked varchar(128),
   QuestionDetails text,
   QuestionStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TownHallQuestionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `twn_l` (
   _RecordID int unsigned not null auto_increment,
   TownHallQuestionID int unsigned  not null,
   OrganizationID int,
   QuestionTypeID int,
   QuestionAsked varchar(128),
   QuestionDetails text,
   QuestionStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TownHallQuestionID
   )
) TYPE=InnoDB;
