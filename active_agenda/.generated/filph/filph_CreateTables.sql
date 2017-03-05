CREATE TABLE `filph` (
   FileRetentionID int unsigned auto_increment not null,
   FileReqID int unsigned not null,
   Original bool default 1,
   FilingOrganizationID int,
   FileName varchar(128),
   FileNumber varchar(20),
   FilingMethodID int,
   FilingMethodDesc text,
   FilingDispositionID int,
   FilingDispositionDesc text,
   PhysicalFileStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FileRetentionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `filph_l` (
   _RecordID int unsigned not null auto_increment,
   FileRetentionID int unsigned  not null,
   FileReqID int unsigned not null,
   Original bool default 1,
   FilingOrganizationID int,
   FileName varchar(128),
   FileNumber varchar(20),
   FilingMethodID int,
   FilingMethodDesc text,
   FilingDispositionID int,
   FilingDispositionDesc text,
   PhysicalFileStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FileRetentionID
   )
) TYPE=InnoDB;
