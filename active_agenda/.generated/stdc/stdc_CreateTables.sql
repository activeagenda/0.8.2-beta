CREATE TABLE `stdc` (
   StandardsConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   StandardID int,
   StandardsConsiderationTitle varchar(128),
   StandardsConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      StandardsConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `stdc_l` (
   _RecordID int unsigned not null auto_increment,
   StandardsConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   StandardID int,
   StandardsConsiderationTitle varchar(128),
   StandardsConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      StandardsConsiderationID
   )
) TYPE=InnoDB;
