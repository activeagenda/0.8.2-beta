CREATE TABLE `bpc` (
   BestPracticeID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   BestPracticeCategoryID int,
   BestPracticeTitle varchar(128),
   BestPracticeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BestPracticeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bpc_l` (
   _RecordID int unsigned not null auto_increment,
   BestPracticeID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   BestPracticeCategoryID int,
   BestPracticeTitle varchar(128),
   BestPracticeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BestPracticeID
   )
) TYPE=InnoDB;
