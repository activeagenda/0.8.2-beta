CREATE TABLE `para` (
   PartnershipAuditID int unsigned auto_increment not null,
   PartnershipID int unsigned not null,
   AuditingOrganizationID int,
   AuditScope text,
   AuditPurpose text,
   ImprovementOpportunity text,
   SharingOpportunity text,
   SharedExpectationsGoal float,
   LocalExpectationsGoal float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PartnershipAuditID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `para_l` (
   _RecordID int unsigned not null auto_increment,
   PartnershipAuditID int unsigned  not null,
   PartnershipID int unsigned not null,
   AuditingOrganizationID int,
   AuditScope text,
   AuditPurpose text,
   ImprovementOpportunity text,
   SharingOpportunity text,
   SharedExpectationsGoal float,
   LocalExpectationsGoal float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PartnershipAuditID
   )
) TYPE=InnoDB;
