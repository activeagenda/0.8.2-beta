CREATE TABLE `doca` (
   DocumentAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   DocumentID int unsigned not null,
   MannerAssociated text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `doca_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   DocumentID int unsigned not null,
   MannerAssociated text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentAssocID
   )
) TYPE=InnoDB;
