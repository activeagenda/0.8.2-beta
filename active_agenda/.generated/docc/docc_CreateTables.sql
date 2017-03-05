CREATE TABLE `docc` (
   DocumentConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   DocumentConsiderationTitle varchar(128),
   DocumentConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `docc_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   DocumentConsiderationTitle varchar(128),
   DocumentConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentConsiderationID
   )
) TYPE=InnoDB;
