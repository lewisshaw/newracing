create database Racing;

use Racing;

CREATE TABLE Competitor (
    competitorId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    firstName varchar(50) NOT NULL,
    lastName varchar(50) NOT NULL,
    timestamp TIMESTAMP,
    PRIMARY KEY (competitorId)
) ENGINE=InnoDB;

CREATE TABLE BoatClass (
    boatClassId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    timestamp TIMESTAMP,
    PRIMARY KEY (boatClassId)
)ENGINE=InnoDB;

CREATE TABLE PyNumber (
    pyNumberId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    boatClassId INT UNSIGNED NOT NULL,
    pyNumber SMALLINT NOT NULL,
    active TINYINT(1) NOT NULL,
    timestamp TIMESTAMP,
    PRIMARY KEY (pyNumberId),
    FOREIGN KEY (boatClassId) REFERENCES BoatClass (boatClassId),
    KEY (active)
)ENGINE=InnoDB;

CREATE TABLE Series (
    seriesId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    seriesName VARCHAr(50) NOT NULL,
    startDate DATETIME NOT NULL,
    endDate DATETIME NOT NULL,
    timestamp TIMESTAMP,
    PRIMARY KEY (seriesId),
    KEY(startDate),
    KEY(endDate)
)ENGINE=InnoDB;

CREATE TABLE RaceType (
    raceTypeId TINYINT UNSIGNED NOT NULL,
    raceTypeHandle VARCHAR(20) NOT NULL,
    timestamp TIMESTAMP NOT NULL,
    PRIMARY KEY (raceTypeId),
    UNIQUE KEY (raceTypeHandle)
)ENGINE=InnoDB;

INSERT INTO RaceType (raceTypeId, raceTypeHandle)
     VALUES (1, 'HANDICAP'),
            (2, 'CLASS');

CREATE TABLE Race (
    raceId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    raceTypeId TINYINT UNSIGNED NOT NULL,
    seriesId INT UNSIGNED NOT NULL,
    name VARCHAR(50) NOT NULL,
    laps TINYINT NOT NULL,
    date DATETIME NOT NULL,
    timestamp TIMESTAMP,
    PRIMARY KEY (raceId),
    FOREIGN KEY (seriesId) REFERENCES Series (seriesId),
    FOREIGN KEY (raceTypeId) REFERENCES RaceType (raceTypeId),
    KEY(date)
)ENGINE=InnoDB;

CREATE TABLE Result (
    resultId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    raceId INT UNSIGNED NOT NULL,
    sailNumber INT NOT NULL,
    timestamp TIMESTAMP,
    PRIMARY KEY(resultId),
    FOREIGN KEY (raceId) REFERENCES Race (raceId),
    KEY (sailNumber)
)ENGINE=InnoDB;

CREATE TABLE ClassResult (
    resultId INT UNSIGNED NOT NULL,
    boatClassId INT UNSIGNED NOT NULL,
    position TINYINT NOT NULL,
    timestamp TIMESTAMP,
    PRIMARY KEY (resultId),
    FOREIGN KEY (resultId) REFERENCES Result (resultId),
    FOREIGN KEY (boatClassId) REFERENCES BoatClass (boatClassId)
)ENGINE=InnoDB;

CREATE TABLE HandicapResult (
    resultId INT UNSIGNED NOT NULL,
    pyNumberId INT UNSIGNED NOT NULL,
    time SMALLINT NOT NULL,
    laps TINYINT NOT NULL,
    timestamp TIMESTAMP,
    PRIMARY KEY (resultId),
    FOREIGN KEY (resultId) REFERENCES Result (resultId),
    FOREIGN KEY (pyNumberId) REFERENCES PyNumber (pyNumberId)
)ENGINE=InnoDB;

CREATE TABLE UnfinishedResultType (
    unfinishedTypeId TINYINT UNSIGNED NOT NULL,
    unfinishedTypeHandle VARCHAR(3) NOT NULL,
    timestamp TIMESTAMP,
    PRIMARY KEY (unfinishedTypeId),
    UNIQUE KEY (unfinishedTypeHandle)
)ENGINE=InnoDB;

INSERT INTO
    UnfinishedResultType (unfinishedTypeId, unfinishedTypeHandle)
VALUES
    (1, 'DNF'),
    (2, 'DNS');

CREATE TABLE UnfinishedResult (
    resultId INT UNSIGNED NOT NULL,
    boatClassId INT UNSIGNED NOT NULL,
    unfinishedTypeId TINYINT UNSIGNED NOT NULL,
    timestamp TIMESTAMP NOT NULL,
    PRIMARY KEY (resultId),
    FOREIGN KEY (resultId) REFERENCES Result(resultId),
    FOREIGN KEY (unfinishedTypeId) REFERENCES UnfinishedResultType (unfinishedTypeId)
)ENGINE=InnoDB;

CREATE TABLE CompetitorType (
    competitorTypeId TINYINT NOT NULL,
    description VARCHAR(100) NOT NULL,
    CompetitorTypeHandle VARCHAR(20) NOT NULL,
    PRIMARY KEY (competitorTypeId),
    UNIQUE KEY(competitorTypeHandle)
)ENGINE=InnoDB;

INSERT INTO CompetitorType (competitorTypeId, description, competitorTypeHandle)
     VALUES (1, 'helm', 'HELM'), (2, 'crew', 'CREW');

CREATE TABLE ResultCompetitor (
    resultCompetitorId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    resultId INT UNSIGNED NOT NULL,
    competitorId INT UNSIGNED NOT NULL,
    competitorTypeId TINYINT NOT NULL,
    PRIMARY KEY (resultCompetitorId),
    FOREIGN KEY (resultId) REFERENCES Result (resultId),
    FOREIGN KEY (competitorId) REFERENCES Competitor (competitorId),
    FOREIGN KEY (competitorTypeId) REFERENCES CompetitorType (competitorTypeId)
)ENGINE=InnoDB;

ALTER TABLE Racing.Race
ADD COLUMN isPublished tinyint(1) NOT NULL AFTER laps;
ALTER TABLE Racing.Race
ADD KEY (isPublished);

ALTER TABLE Racing.Race
MODIFY COLUMN isPublished tinyint(1) NOT NULL DEFAULT 0 AFTER laps;

use Racing;

alter table BoatClass add Column persons TINYINT NOT NULL AFTER name;

CREATE TABLE Racing.SeriesFile (
    leagueFileId INT unsigned NOT NULL auto_increment,
    seriesId INT unsigned NOT NULL,
    leagueFileName varchar(50) NOT NULL,
    timestamp TIMESTAMP,
    PRIMARY KEY (leagueFileId),
    FOREIGN KEY (seriesId) REFERENCES Racing.Series (seriesId)
)ENGINE=InnoDB;
