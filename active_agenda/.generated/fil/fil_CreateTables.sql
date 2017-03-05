CREATE TABLE `fil` (
   FileReqID int unsigned auto_increment not null,
   OrganizationID int,
   FileTypeID int unsigned not null,
   FileRetentionMethodID int,
   FileRetentionDesc text,
   FileRetentionPeriod int,
   FileRetentionUnitsID int,
   FileDispositionMethodID int,
   FileDispositionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FileReqID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `fil_l` (
   _RecordID int unsigned not null auto_increment,
   FileReqID int unsigned  not null,
   OrganizationID int,
   FileTypeID int unsigned not null,
   FileRetentionMethodID int,
   FileRetentionDesc text,
   FileRetentionPeriod int,
   FileRetentionUnitsID int,
   FileDispositionMethodID int,
   FileDispositionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FileReqID
   )
) TYPE=InnoDB;
