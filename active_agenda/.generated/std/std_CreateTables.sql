CREATE TABLE `std` (
   StandardID int unsigned auto_increment not null,
   OrganizationID int,
   CountryID int unsigned not null,
   StandardLevelID int,
   StandardsOrganizationID int,
   StandardsOrganizationAbbreviation varchar(25),
   StandardTitle varchar(128),
   StandardCode varchar(128),
   StandardSection varchar(128),
   StandardDescription text,
   StandardURL varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      StandardID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `std_l` (
   _RecordID int unsigned not null auto_increment,
   StandardID int unsigned  not null,
   OrganizationID int,
   CountryID int unsigned not null,
   StandardLevelID int,
   StandardsOrganizationID int,
   StandardsOrganizationAbbreviation varchar(25),
   StandardTitle varchar(128),
   StandardCode varchar(128),
   StandardSection varchar(128),
   StandardDescription text,
   StandardURL varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      StandardID
   )
) TYPE=InnoDB;
