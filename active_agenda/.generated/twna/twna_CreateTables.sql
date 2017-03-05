CREATE TABLE `twna` (
   TownHallAnswerID int unsigned auto_increment not null,
   TownHallQuestionID int unsigned not null,
   AnswertoQuestion text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TownHallAnswerID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `twna_l` (
   _RecordID int unsigned not null auto_increment,
   TownHallAnswerID int unsigned  not null,
   TownHallQuestionID int unsigned not null,
   AnswertoQuestion text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TownHallAnswerID
   )
) TYPE=InnoDB;
