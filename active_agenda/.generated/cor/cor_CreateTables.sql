CREATE TABLE `cor` (
   CorrActID int unsigned auto_increment not null,
   CorrectiveSituationTypeID int unsigned not null,
   OrganizationID int unsigned not null,
   SituationDesc text,
   OccurNoID int,
   CorrectivePracticeCategoryID int,
   CorrectivePracticeDescription text,
   CorrActDesc text,
   CorrActStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CorrActID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cor_l` (
   _RecordID int unsigned not null auto_increment,
   CorrActID int unsigned  not null,
   CorrectiveSituationTypeID int unsigned not null,
   OrganizationID int unsigned not null,
   SituationDesc text,
   OccurNoID int,
   CorrectivePracticeCategoryID int,
   CorrectivePracticeDescription text,
   CorrActDesc text,
   CorrActStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CorrActID
   )
) TYPE=InnoDB;
