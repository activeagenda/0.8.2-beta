CREATE TABLE `lpd` (
   LossProdServID int unsigned auto_increment not null,
   OrganizationID int,
   LossProdServDesc text,
   DesiredOutcome text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossProdServID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lpd_l` (
   _RecordID int unsigned not null auto_increment,
   LossProdServID int unsigned  not null,
   OrganizationID int,
   LossProdServDesc text,
   DesiredOutcome text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossProdServID
   )
) TYPE=InnoDB;
