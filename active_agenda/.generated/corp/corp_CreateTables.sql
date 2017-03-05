CREATE TABLE `corp` (
   PracticeID int unsigned auto_increment not null,
   OrganizationID int,
   SituationID int unsigned not null,
   OrganizationDesc text,
   WorkRuleNo varchar(50),
   OccurNoID int,
   CorrActTypeID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PracticeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `corp_l` (
   _RecordID int unsigned not null auto_increment,
   PracticeID int unsigned  not null,
   OrganizationID int,
   SituationID int unsigned not null,
   OrganizationDesc text,
   WorkRuleNo varchar(50),
   OccurNoID int,
   CorrActTypeID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PracticeID
   )
) TYPE=InnoDB;
