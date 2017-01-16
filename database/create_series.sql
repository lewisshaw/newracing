CREATE TABLE Racing.SeriesUpdate (
    seriesUpdateId int unsigned NOT NULL auto_increment,
    seriesId int unsigned NOT NULL,
    isComplete tinyint(1) NOT NULL default 0,
    timestamp TIMESTAMP,
    PRIMARY KEY(seriesUpdateId),
    FOREIGN KEY (seriesId) REFERENCES Racing.Series(seriesId)
)ENGINE=InnoDB;

CREATE TABLE Racing.SeriesResult (
    seriesResultId int unsigned NOT NULL auto_increment,
    seriesId int unsigned NOT NULL,
    competitorId int unsigned NOT NULL,
    raceId int unsigned NOT NULL,
    sailNumber int NOT NULL,
    position SMALLINT NOT NULL,
    timestamp TIMESTAMP,
    PRIMARY KEY (seriesResultId),
    FOREIGN KEY (seriesId) REFERENCES Racing.Series(seriesId),
    FOREIGN KEY (competitorId) REFERENCES Racing.Competitor(competitorId),
    FOREIGN KEY (raceId) REFERENCES Racing.Race (raceId)

)Engine=InnoDB;
