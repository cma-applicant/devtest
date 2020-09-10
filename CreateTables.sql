CREATE TABLE `Artwork` (
  `BatchId` varchar(32) NOT NULL,
  `AccessionNumber` varchar(50) NOT NULL,
  `Title` varchar(256) NOT NULL,
  `Tombstone` text NOT NULL,
  `Department` varchar(128) NOT NULL
);


CREATE TABLE `Creator` (
  `BatchId` varchar(32) NOT NULL,
  `Role` varchar(64) NOT NULL,
  `Description` varchar(256) NOT NULL,
  `ArtworkAccessionNumber` varchar(32) NOT NULL
);
