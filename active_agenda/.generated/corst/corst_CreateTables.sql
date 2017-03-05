CREATE TABLE `corst` (
   CorrectiveSituationTypeID int unsigned auto_increment not null,
   PolicyOrganizationID int unsigned not null,
   CorrectiveSituationCategoryID int,
   CorrectiveSituationTitle varchar(128),
   CorrectiveSituationDescription text,
   WorkRuleNo varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CorrectiveSituationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `corst_l` (
   _RecordID int unsigned not null auto_increment,
   CorrectiveSituationTypeID int unsigned  not null,
   PolicyOrganizationID int unsigned not null,
   CorrectiveSituationCategoryID int,
   CorrectiveSituationTitle varchar(128),
   CorrectiveSituationDescription text,
   WorkRuleNo varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CorrectiveSituationTypeID
   )
) TYPE=InnoDB;
