SQL code

CREATE TABLE `Kasutajad` (
    kasutaja_id INT AUTO_INCREMENT PRIMARY KEY,
    kasutajanimi VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    salasõna VARCHAR(255) NOT NULL,
    loodud DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `sõndmused` (
    sõndmus_id INT AUTO_INCREMENT PRIMARY KEY,
    kasutaja_id INT,
    pealkiri VARCHAR(255) NOT NULL,
    kirjeldus TEXT NOT NULL,
    algus_aeg DATETIME NOT NULL,
    lõpp_aeg DATETIME NOT NULL,
    loodud DATETIME DEFAULT CURRENT_TIMESTAMP,
    uuendatud DATETIME ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kasutaja_id) REFERENCES Kasutajad(kasutaja_id) ON DELETE CASCADE
);

CREATE TABLE `meeldetuletused` (
    meeldetuletus_id INT AUTO_INCREMENT PRIMARY KEY,
    sündmus_id INT,
    meeldetuletuse_aeg DATETIME NOT NULL,
    FOREIGN KEY (sündmus_id) REFERENCES Sündmused(sündmus_id) ON DELETE CASCADE
);
