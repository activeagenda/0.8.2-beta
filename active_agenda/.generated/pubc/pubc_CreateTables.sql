CREATE TABLE `pubc` (
   PublicityConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   PublicityConsiderationTitle varchar(128),
   PublicityConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PublicityConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pubc_l` (
   _RecordID int unsigned not null auto_increment,
   PublicityConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   PublicityConsiderationTitle varchar(128),
   PublicityConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PublicityConsiderationID
   )
) TYPE=InnoDB;
