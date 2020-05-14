# create this table first because of foreign key constraints
CREATE TABLE Location (
    Id int AUTO_INCREMENT,
    IPFrom int,
    IPTo int,
    CountryISOCode varchar(2),
    Country varchar(2),
    State varchar(128),
    City varchar(2),
    Latitude decimal(18, 6), # max 6 ints to the right of the decimal!
    Longitude decimal(18, 6),

    PRIMARY KEY (Id)
);

CREATE TABLE IPAddress (
    Id int AUTO_INCREMENT,
    Network varchar(255) NOT NULL,
    GeonameId int,
    ContinentCode varchar(2),
    ContinentName varchar(128),
    CountryISOCode varchar(2),
    CountryName varchar(128),
    isAnonymousProxy boolean DEFAULT 0,
    isSatelliteProvider boolean DEFAULT 0,
    LocationId int, # each IP belongs to a location many ips to one location!

    FOREIGN KEY (LocationId) REFERENCES Location(Id), # allows for joins, showing information about the location

    PRIMARY KEY (Id)
);