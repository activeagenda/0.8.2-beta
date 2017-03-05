CREATE TABLE `agrc` (
   AgreementConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   AgreementConsiderationTitle varchar(128),
   AgreementConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AgreementConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `agrc_l` (
   _RecordID int unsigned not null auto_increment,
   AgreementConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   AgreementConsiderationTitle varchar(128),
   AgreementConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AgreementConsiderationID
   )
) TYPE=InnoDB;
