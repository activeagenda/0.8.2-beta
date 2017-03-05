CREATE TABLE `att` (
   AttachmentID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FileName varchar(128),
   Description varchar(128),
   FileSize float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AttachmentID
   ),
   INDEX att_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `att_l` (
   _RecordID int unsigned not null auto_increment,
   AttachmentID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FileName varchar(128),
   Description varchar(128),
   FileSize float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AttachmentID
   ),
   INDEX att_l_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;
