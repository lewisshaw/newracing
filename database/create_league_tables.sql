CREATE TABLE Racing.SeriesFile (
    leagueFileId INT unsigned NOT NULL auto_increment,
    seriesId INT unsigned NOT NULL,
    leagueFileName varchar(50) NOT NULL,
    timestamp TIMESTAMP,
    PRIMARY KEY (leagueFileId),
    FOREIGN KEY (seriesId) REFERENCES Racing.Series (seriesId)
)ENGINE=InnoDB;
