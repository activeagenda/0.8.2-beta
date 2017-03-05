CREATE TABLE `medc` (
   MedicalConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   MedicalConsiderationTitle varchar(128),
   MedicalConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MedicalConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medc_l` (
   _RecordID int unsigned not null auto_increment,
   MedicalConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   MedicalConsiderationTitle varchar(128),
   MedicalConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MedicalConsiderationID
   )
) TYPE=InnoDB;
