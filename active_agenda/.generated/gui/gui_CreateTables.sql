CREATE TABLE `gui` (
   GuidanceOrganizationID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      GuidanceOrganizationID
   ),
   INDEX gui_Related (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `gui_l` (
   _RecordID int unsigned not null auto_increment,
   GuidanceOrganizationID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      GuidanceOrganizationID
   ),
   INDEX gui_l_Related (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;
