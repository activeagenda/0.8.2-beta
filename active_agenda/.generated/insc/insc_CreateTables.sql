CREATE TABLE `insc` (
   InsuranceConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   InsuranceConsiderationTitle varchar(128),
   InsuranceConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InsuranceConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `insc_l` (
   _RecordID int unsigned not null auto_increment,
   InsuranceConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   InsuranceConsiderationTitle varchar(128),
   InsuranceConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InsuranceConsiderationID
   )
) TYPE=InnoDB;
