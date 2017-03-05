CREATE TABLE `chmh` (
   ChemHandProcID int unsigned auto_increment not null,
   ChemicalID int,
   OrganizationID int,
   HandlingProcName varchar(128),
   ChemicalUseTypeID int,
   HandlingProcStatusID int,
   Responsibilities text,
   Verification text,
   Preparation text,
   Application text,
   PostApplication text,
   Precautions text,
   FirstAid text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemHandProcID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmh_l` (
   _RecordID int unsigned not null auto_increment,
   ChemHandProcID int unsigned  not null,
   ChemicalID int,
   OrganizationID int,
   HandlingProcName varchar(128),
   ChemicalUseTypeID int,
   HandlingProcStatusID int,
   Responsibilities text,
   Verification text,
   Preparation text,
   Application text,
   PostApplication text,
   Precautions text,
   FirstAid text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemHandProcID
   )
) TYPE=InnoDB;
