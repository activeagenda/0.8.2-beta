CREATE TABLE `corcp` (
   CorrectivePracticeID int unsigned auto_increment not null,
   CorrectiveSituationTypeID int unsigned not null,
   OccurNoID int,
   CorrectivePracticeCategoryID int,
   CorrectivePracticeDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CorrectivePracticeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `corcp_l` (
   _RecordID int unsigned not null auto_increment,
   CorrectivePracticeID int unsigned  not null,
   CorrectiveSituationTypeID int unsigned not null,
   OccurNoID int,
   CorrectivePracticeCategoryID int,
   CorrectivePracticeDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CorrectivePracticeID
   )
) TYPE=InnoDB;
